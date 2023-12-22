import { computed, inject, ref } from 'vue';
import template from './sw-user-list.html.twig';
import './sw-user-list.scss';

const { Mixin } = SnapAdmin;
/**
 * @package admin
 */
// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
export default {
    template,
    inject: [
        'acl',
    ],
    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('listing'),
    ],
    setup() {
        const total = ref(0);
        const isLoading = ref(false);
        const users = ref(null);
        const sortDirection = ref('DESC');

        const getUserColumns = () => {
            return [{
                property: 'userName',
            }];
        };

        const userColumns = computed(() => {
            return getUserColumns();
        });
        const repositoryFactory = inject('repositoryFactory');
        const userRepository = computed(() => {
            return repositoryFactory.create('user');
        });

        return {
            total,
            isLoading,
            users,
            userColumns,
            userRepository,
            sortDirection,
        };
    },
};
