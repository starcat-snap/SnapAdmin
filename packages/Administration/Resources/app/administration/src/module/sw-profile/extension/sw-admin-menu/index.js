/**
 * @package services-settings
 */
import template from './sw-admin-menu.html.twig';

const { Component } = SnapAdmin;

Component.override('sw-admin-menu', {
    template,
    inject: ['acl'],

});
