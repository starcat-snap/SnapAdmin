/**
 * @package admin
 */

import { defineComponent } from 'vue';

/**
 * @private
 */
export default SnapAdmin.Mixin.register('sw-inline-snippet', defineComponent({
    computed: {
        swInlineSnippetLocale(): string {
            return SnapAdmin.State.get('session').currentLocale as unknown as string;
        },

        swInlineSnippetFallbackLocale(): string {
            return SnapAdmin.Context.app.fallbackLocale as unknown as string;
        },
    },

    methods: {
        getInlineSnippet(value: {
            [key: string]: string;
        }) {
            if (SnapAdmin.Utils.types.isEmpty(value)) {
                return '';
            }
            if (value[this.swInlineSnippetLocale]) {
                return value[this.swInlineSnippetLocale];
            }
            if (value[this.swInlineSnippetFallbackLocale]) {
                return value[this.swInlineSnippetFallbackLocale];
            }
            if (SnapAdmin.Utils.types.isObject(value)) {
                const locale = Object.keys(value).find((key) => {
                    return value[key] !== '';
                });

                if (locale !== undefined) {
                    return value[locale];
                }
            }

            return value;
        },
    },
}));
