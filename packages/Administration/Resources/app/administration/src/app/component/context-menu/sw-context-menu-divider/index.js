import template from './sw-context-menu-divider.html.twig';
import './sw-context-menu-divider.scss';

const { Component } = SnapAdmin;

/**
 * @package admin
 *
 * @private
 */
Component.register('sw-context-menu-divider', {
    template,
});
