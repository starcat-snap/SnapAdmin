/**
 * @package admin
 */

import type Vue from 'vue';
import Entity, { assignSetterMethod } from '@snap-admin/admin-extension-sdk/es/data/_internals/Entity';

assignSetterMethod((draft, property, value) => {
    // @ts-expect-error
    SnapAdmin.Application.view.setReactive(draft as Vue, property, value);
});

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
export default Entity;
