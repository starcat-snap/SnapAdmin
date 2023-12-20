const { Criteria } = SnapAdmin.Data;

/**
 * @package content
 */
// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
export default function createMediaDefaultFolderService() {
    const cache = {};
    const repository = SnapAdmin.Service('repositoryFactory').create('media_default_folder');

    return {
        getDefaultFolderId: (entityName) => {
            // Directly return cache value if exist
            if (cache[entityName]) {
                return cache[entityName];
            }

            const criteria = new Criteria(1, 1);
            criteria.addAssociation('folder');
            criteria.addFilter(
                Criteria.equals('entity', entityName),
            );

            cache[entityName] = repository.search(criteria, SnapAdmin.Context.api)
                .then((data) => {
                    return data.first().folder.id;
                })
                .catch(() => {
                    return null;
                });

            return cache[entityName];
        },
    };
}
