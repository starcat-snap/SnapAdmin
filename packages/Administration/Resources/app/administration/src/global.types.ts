/**
 * @package admin
 */

/* eslint-disable @typescript-eslint/no-explicit-any */
/* eslint-disable import/no-named-default */
import type { Decorator, default as Bottle } from 'bottlejs';
import type { SnapAdminClass } from 'src/core/snap-admin';
// trick to make it an "external module" to support global type extension

// base methods for subContainer
// Export for modules and plugins to extend the service definitions
// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
export interface SubContainer<ContainerName extends string> {
    $decorator(name: string | Decorator, func?: Decorator): this;

    $register(Obj: Bottle.IRegisterableObject): this;

    $list(): (keyof Bottle.IContainer[ContainerName])[];
}

// declare global types
declare global {
    /**
     * "any" type which looks more awful in the code so that spot easier
     * the places where we need to fix the TS types
     */
    type $TSFixMe = any;
    type $TSFixMeFunction = (...args: any[]) => any;

    /**
     * Dangerous "unknown" types which are specific enough but do not provide type safety.
     * You should avoid using these.
     */
    type $TSDangerUnknownObject = { [key: string | symbol]: unknown };
    /**
     * Make the SnapAdmin object globally available
     */
    const SnapAdmin: SnapAdminClass;

    interface Window {
        SnapAdmin: SnapAdminClass;
        _features_: {
            [featureName: string]: boolean
        };
        processingInactivityLogout?: boolean;
    }

    const _features_: {
        [featureName: string]: boolean
    };

    /**
     * Define global container for the bottle.js containers
     */
        // eslint-disable-next-line @typescript-eslint/no-empty-interface
    interface ServiceContainer extends SubContainer<'service'> {
    }

    // eslint-disable-next-line @typescript-eslint/no-empty-interface
    interface InitContainer extends SubContainer<'init'> {
    }

    interface FactoryContainer extends SubContainer<'factory'> {
    }
}

/**
 * Link global bottle.js container to the bottle.js container interface
 */
declare module 'bottlejs' { // Use the same module name as the import string
    type IContainerChildren = 'factory' | 'service' | 'init';

    interface IContainer {
        factory: FactoryContainer,
        service: ServiceContainer,
        init: InitContainer,
    }
}
