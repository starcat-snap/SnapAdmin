/* eslint-disable max-len, sw-deprecation-rules/private-feature-declarations */
SnapAdmin.Component.register('sw-scheduled-task-index', () => import('./page/sw-scheduled-task-index'));

const {Module} = SnapAdmin;

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
Module.register('sw-scheduled-task', {
    type: 'core',
    name: 'media',
    title: 'sw-scheduled-task.general.mainMenuItemGeneral',
    description: 'sw-scheduled-task.general.descriptionTextModule',
    version: '1.0.0',
    targetVersion: '1.0.0',
    icon: 'regular-image',
    entity: 'scheduled-task',
    routes: {
        index: {
            components: {
                default: 'sw-scheduled-task-index',
            },
            path: 'index',
            meta: {
                privilege: 'scheduled-task.viewer',
            },
        },
    },

    navigation: [{
        id: 'sw-scheduled-task',
        label: 'sw-scheduled-task.general.mainMenuItemGeneral',
        icon: 'regular-image',
        path: 'sw.scheduled.task.index',
        position: 1000,
        parent: 'sw-system',
        privilege: 'scheduled-task.viewer',
    }],
});
