import './sw-admin-updates-info.scss';
import template from './sw-admin-updates-info.html.twig';

const { Component } = SnapAdmin;

/**
 * @package services-settings
 * @private
 */
Component.register('sw-settings-shopware-updates-info', {
    template,

    props: {
        changelog: {
            type: String,
            required: true,
        },
        isLoading: {
            type: Boolean,
        },
    },
});
