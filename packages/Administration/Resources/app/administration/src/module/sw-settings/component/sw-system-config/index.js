/**
 * @package services-settings
 */
import ErrorResolverSystemConfig from 'src/core/data/error-resolver.system-config.data';
import template from './sw-system-config.html.twig';
import './sw-system-config.scss';

const { Mixin } = SnapAdmin;
const { object, string: { kebabCase } } = SnapAdmin.Utils;
const { mapSystemConfigErrors } = SnapAdmin.Component.getComponentHelper();

/**
 * Component which automatically renders all fields for a given system_config schema. It allows the user to edit these
 * configuration values.
 *
 * N.B: This component handles the data completely independently, therefore you need to trigger the saving of
 *      data manually with a $ref. Due to the fact that the data is stored inside this component, destroying
 *      the component could lead to unsaved changes. One primary case for this could be if it will be used
 *      inside tabs. Because if the user changes the tab content then this component gets destroyed and therefore
 *      also the corresponding data.
 */

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
export default {
    template,

    inject: ['systemConfigApiService'],

    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('sw-inline-snippet'),
    ],

    props: {
        domain: {
            required: true,
            type: String,
        },
    },

    data() {
        return {
            isLoading: false,
            config: {},
            actualConfigData: {},
            hasCssFields: false,
        };
    },

    computed: {

        typesWithMapInheritanceSupport() {
            return [
                'text',
                'textarea',
                'url',
                'password',
                'int',
                'float',
                'bool',
                'checkbox',
                'colorpicker',
            ];
        },
    },

    watch: {
        actualConfigData: {
            handler() {
                this.emitConfig();
            },
            deep: true,
        },

        isLoading(value) {
            this.$emit('loading-changed', value);
        },
    },

    created() {
        this.createdComponent();
    },

    methods: {
        getFieldError(fieldName) {
            return mapSystemConfigErrors(ErrorResolverSystemConfig.ENTITY_NAME, null, fieldName);
        },

        async createdComponent() {
            this.isLoading = true;
            try {
                await this.readConfig();
                await this.readAll();
            } catch (error) {
                if (error?.response?.data?.errors) {
                    this.createErrorNotification(error.response.data.errors);
                }
            } finally {
                this.isLoading = false;
            }
        },

        async readConfig() {
            this.config = await this.systemConfigApiService.getConfig(this.domain);
            this.config.every((card) => {
                return card?.elements.every((field) => {
                    if (field?.config?.css) {
                        this.hasCssFields = true;
                        return false;
                    }
                    return true;
                });
            });
        },

        readAll() {
            this.isLoading = true;
            if (this.actualConfigData.hasOwnProperty(null)) {
                this.isLoading = false;
                return Promise.resolve();
            }

            return this.loadSystemConfig();
        },

        async loadSystemConfig() {
            this.isLoading = true;

            try {
                const values = await this.systemConfigApiService.getValues(this.domain);

                this.$set(this.actualConfigData, null, values);
            } finally {
                this.isLoading = false;
            }
        },

        saveAll() {
            this.isLoading = true;
            return this.systemConfigApiService
                .batchSave(this.actualConfigData)
                .finally(() => {
                    this.isLoading = false;
                });
        },

        createErrorNotification(errors) {
            let message = `<div>${this.$tc(
                'sw-config-form-renderer.configLoadErrorMessage',
                errors.length,
            )}</div><ul>`;

            errors.forEach((error) => {
                message = `${message}<li>${error.detail}</li>`;
            });
            message += '</ul>';

            this.createNotificationError({
                message: message,
                autoClose: false,
            });
        },

        hasMapInheritanceSupport(element) {
            const componentName = element.config ? element.config.componentName : undefined;

            if (componentName === 'sw-switch-field' || componentName === 'sw-snippet-field') {
                return true;
            }

            return this.typesWithMapInheritanceSupport.includes(element.type);
        },

        getElementBind(element, mapInheritance) {
            const bind = object.deepCopyObject(element);

            if (!this.hasMapInheritanceSupport(element)) {
                delete bind.config.label;
                delete bind.config.helpText;
            } else {
                bind.mapInheritance = mapInheritance;
            }

            // Add select properties
            if (['single-select', 'multi-select'].includes(bind.type)) {
                bind.config.labelProperty = 'name';
                bind.config.valueProperty = 'id';
            }

            if (element.type === 'text-editor') {
                bind.config.componentName = 'sw-text-editor';
            }

            if (bind.config.css && bind.config.helpText === undefined) {
                bind.config.helpText = this.$tc('sw-settings.system-config.scssHelpText') + element.config.css;
            }

            return bind;
        },

        getInheritWrapperBind(element) {
            if (this.hasMapInheritanceSupport(element)) {
                return {};
            }

            return {
                label: this.getInlineSnippet(element.config.label),
                helpText: this.getInlineSnippet(element.config.helpText),
            };
        },

        getInheritedValue(element) {
            const value = this.actualConfigData.null[element.name];

            if (value) {
                return value;
            }

            if (element.config?.componentName) {
                const componentName = element.config.componentName;

                if (componentName === 'sw-switch-field') {
                    return false;
                }
            }

            switch (element.type) {
                case 'date':
                case 'datetime':
                case 'single-select':
                case 'colorpicker':
                case 'password':
                case 'url':
                case 'text':
                case 'textarea':
                case 'text-editor': {
                    return '';
                }

                case 'multi-select': {
                    return [];
                }

                case 'checkbox':
                case 'bool': {
                    return false;
                }

                case 'float':
                case 'int': {
                    return 0;
                }

                default: {
                    return null;
                }
            }
        },

        emitConfig() {
            this.$emit('config-changed', this.actualConfigData[null]);
        },

        kebabCase(value) {
            return kebabCase(value);
        },
    },
};
