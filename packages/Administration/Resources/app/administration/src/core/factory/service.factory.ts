/**
 * @package admin
 */

type ServiceObject = {
    get: <SN extends keyof ServiceContainer>(serviceName: SN) => ServiceContainer[SN],
    list: () => (keyof ServiceContainer)[],
    register: <SN extends keyof ServiceContainer>(serviceName: SN, service: ServiceContainer[SN]) => void,
    registerMiddleware: typeof SnapAdmin.Application.addServiceProviderMiddleware,
    registerDecorator: typeof SnapAdmin.Application.addServiceProviderDecorator,
}

/**
 * Return the ServiceObject (SnapAdmin.Service().myService)
 * or direct access the services (SnapAdmin.Service('myService')
 */
function serviceAccessor<SN extends keyof ServiceContainer>(serviceName: SN): ServiceContainer[SN]
function serviceAccessor(): ServiceObject
function serviceAccessor<SN extends keyof ServiceContainer>(serviceName?: SN): ServiceContainer[SN] | ServiceObject {
    if (serviceName) {
        // eslint-disable-next-line @typescript-eslint/no-unsafe-return
        return SnapAdmin.Application.getContainer('service')[serviceName];
    }

    const serviceObject: ServiceObject = {
        // eslint-disable-next-line @typescript-eslint/no-unsafe-return
        get: (name) => SnapAdmin.Application.getContainer('service')[name],
        list: () => SnapAdmin.Application.getContainer('service').$list(),
        register: (name, service) => SnapAdmin.Application.addServiceProvider(name, service),
        registerMiddleware: (...args) => SnapAdmin.Application.addServiceProviderMiddleware(...args),
        registerDecorator: (...args) => SnapAdmin.Application.addServiceProviderDecorator(...args),
    };

    return serviceObject;
}

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
export default serviceAccessor;
