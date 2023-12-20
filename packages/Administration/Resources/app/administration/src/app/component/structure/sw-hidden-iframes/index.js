import { MAIN_HIDDEN } from '@snap-admin/admin-extension-sdk/es/location';
import template from './sw-hidden-iframes.html.twig';

const { Component } = SnapAdmin;

/**
 * @package admin
 *
 * @private
 */
Component.register('sw-hidden-iframes', {
    template,

    computed: {
        extensions() {
            return SnapAdmin.State.getters['extensions/privilegedExtensions'];
        },

        MAIN_HIDDEN() {
            return MAIN_HIDDEN;
        },
    },
});
