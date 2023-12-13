import type Bottle from 'bottlejs';

/**
 * @package admin
 *
 * @module core/application
 */

interface bundlesSinglePluginResponse {
    css?: string | string[],
    js?: string | string[],
    html?: string,
    baseUrl?: null | string,
    type?: 'plugin',
    version?: string,
    active?: boolean,
}

interface bundlesPluginResponse {
    [key: string]: bundlesSinglePluginResponse
}

/**
 * @private
 *
 * The application bootstrapper bootstraps the application and registers the necessary
 * and optional parts of the application in a shared DI container which provides you
 * with an easy-to-use way to add new services as well as decoration these services.
 *
 * The bootstrapper provides you with the ability to register middleware for all or specific
 * services too.
 */
class ApplicationBootstrapper {
    public $container: Bottle;

    /**
     * Provides the necessary class properties for the class to work probably
     */
    constructor(container: Bottle) {
        // eslint-disable-next-line @typescript-eslint/no-empty-function
        const noop = (): void => {
        };
        this.$container = container;

        // Create an empty DI container for the core initializers & services, so we can separate the core initializers
        // and the providers
        this.$container.service('service', noop);
        this.$container.service('init', noop);
        this.$container.service('factory', noop);
    }

    /**
     * Starts the bootstrapping process of the application.
     */
    start(config = {}) {

    }
}

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
export default ApplicationBootstrapper;
