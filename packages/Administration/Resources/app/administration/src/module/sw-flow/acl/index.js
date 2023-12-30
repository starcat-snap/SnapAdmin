SnapAdmin.Service('privileges')
    .addPrivilegeMappingEntry({
        category: 'permissions',
        parent: 'settings',
        key: 'flow',
        roles: {
            viewer: {
                privileges: [
                    'flow:read',
                    'flow_sequence:read',
                    'rule:read',
                    'mail_template:read',
                    'mail_template_type:read',
                    'document_type:read',
                    'state_machine:read',
                    'state_machine_state:read',
                    'tag:read',
                ],
                dependencies: [],
            },
            editor: {
                privileges: [
                    'flow:update',
                    'flow_sequence:update',
                    'flow_sequence:create',
                    'flow_sequence:delete',
                    'tag:create',
                    SnapAdmin.Service('privileges').getPrivileges('rule.creator'),
                    SnapAdmin.Service('privileges').getPrivileges('mail_templates.creator'),
                ],
                dependencies: [
                    'flow.viewer',
                ],
            },
            creator: {
                privileges: [
                    'flow:create',
                ],
                dependencies: [
                    'flow.viewer',
                    'flow.editor',
                ],
            },
            deleter: {
                privileges: [
                    'flow:delete',
                ],
                dependencies: [
                    'flow.viewer',
                ],
            },
        },
    });
