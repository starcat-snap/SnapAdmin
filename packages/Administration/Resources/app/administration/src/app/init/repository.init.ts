/**
 * @package admin
 */

const RepositoryFactory = SnapAdmin.Classes._private.RepositoryFactory;
const { EntityHydrator, ChangesetGenerator, EntityFactory } = SnapAdmin.Data;
const ErrorResolverError = SnapAdmin.Data.ErrorResolver;

const customEntityTypes = [{
    name: 'custom_entity_detail',
    icon: 'regular-image-text',
    // ToDo NEXT-22655 - Re-implement, when custom_entity_list page is available
    // }, {
    //     name: 'custom_entity_list',
    //     icon: 'regular-list',
}];

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
export default function initializeRepositoryFactory(container: InitContainer) {
    const httpClient = container.httpClient;
    const factoryContainer = SnapAdmin.Application.getContainer('factory');
    const serviceContainer = SnapAdmin.Application.getContainer('service');

    return httpClient.get('_info/entity-schema.json', {
        headers: {
            Authorization: `Bearer ${serviceContainer.loginService.getToken()}`,
        },
    }).then(({ data }) => {
        const entityDefinitionFactory = factoryContainer.entityDefinition;
        const customEntityDefinitionService = serviceContainer.customEntityDefinitionService;

        // eslint-disable-next-line @typescript-eslint/no-unsafe-argument
        Object.entries(data).forEach(([key, value]) => {
            entityDefinitionFactory.add(key, value);

            if (key.startsWith('custom_entity_') || key.startsWith('ce_')) {
                // @ts-expect-error - value is defined
                customEntityDefinitionService.addDefinition(value);
            }
        });

        const hydrator = new EntityHydrator();
        const changesetGenerator = new ChangesetGenerator();
        const entityFactory = new EntityFactory();
        const errorResolver = new ErrorResolverError();

        SnapAdmin.Application.addServiceProvider('repositoryFactory', () => {
            return new RepositoryFactory(
                hydrator,
                changesetGenerator,
                entityFactory,
                httpClient,
                errorResolver,
            );
        });
        SnapAdmin.Application.addServiceProvider('entityHydrator', () => {
            return hydrator;
        });
        SnapAdmin.Application.addServiceProvider('entityFactory', () => {
            return entityFactory;
        });
    });
}
