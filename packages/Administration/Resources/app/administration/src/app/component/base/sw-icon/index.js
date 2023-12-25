/**
 * This file is not linted by ESLint because it cannot be parsed by ESLint because of the dynamic import
 * with dynamic import value.
 */
import template from './sw-icon.html.twig';
import './sw-icon.scss';

// Prefetch specific icons to avoid loading them asynchronously to improve performance


const { Component } = SnapAdmin;

/**
 * @package admin
 *
 * @private
 * @description Renders an icon from the icon library.
 * @status ready
 * @example-type static
 * @component-example
 * <div>
 *     <sw-icon name="circle-download" color="#1abc9c"></sw-icon>
 *     <sw-icon name="regular-storefront" color="#3498db"></sw-icon>
 *     <sw-icon name="regular-eye-slash" color="#9b59b6"></sw-icon>
 *     <sw-icon name="regular-fingerprint" color="#f39c12"></sw-icon>
 *     <sw-icon name="regular-tools-alt" color="#d35400"></sw-icon>
 *     <sw-icon name="regular-user" color="#c0392b"></sw-icon>
 *     <sw-icon name="circle" color="#fc427b"></sw-icon>
 *     <sw-icon name="regular-bell" color="#f1c40f"></sw-icon>
 * </div>
 */
Component.register('sw-icon', {
    template,

    inject: [
        'feature',
    ],

    props: {
        name: {
            type: String,
            required: true,
        },
        color: {
            type: String,
            required: false,
            default: null,
        },
        small: {
            type: Boolean,
            required: false,
            default: false,
        },
        large: {
            type: Boolean,
            required: false,
            default: false,
        },
        size: {
            type: String,
            required: false,
            default: null,
        },
        decorative: {
            type: Boolean,
            required: false,
            default: false,
        },
    },

    data() {
        return {
            iconSvgData: '',
        };
    },

    computed: {
        iconName() {
            return `icons-${this.name}`;
        },

        classes() {
            return [
                `icon--${this.name}`,
                {
                    'sw-icon--small': this.small,
                    'sw-icon--large': this.large,
                },
            ];
        },

        styles() {
            let size = this.size;

            if (!Number.isNaN(parseFloat(size)) && !Number.isNaN(size - 0)) {
                size = `${size}px`;
            }

            return {
                color: this.color,
                width: size,
                height: size,
            };
        },
    },

    beforeMount() {
        this.iconSvgData = `<svg id="meteor-icon-kit__${this.name}"></svg>`
    },

    watch: {
        name: {
            handler(newName) {
                if (!newName) {
                    return;
                }
                this.loadIconSvgData(newName);
            },
            immediate: true,
        },
    },

    methods: {
        loadIconSvgData(iconFullName) {
            // iconFullName = 'home';
            return  import(`@tabler/icons/./${iconFullName}.svg`).then((iconSvgData) => {
                if (iconSvgData.default) {
                    this.iconSvgData = iconSvgData.default;
                } else {
                    // note this only happens if the import exists but does not export a default
                    console.error(`The SVG file for the icon name ${iconFullName} could not be found and loaded.`);
                    this.iconSvgData = '';
                }
            });
        },
    },
});
