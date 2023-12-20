/**
 * @package admin
 *
 * @private
 * @module core/factory/context
 * @param {Object} context
 * @type factory
 */
export default function createContext(context = {}) {
    const Defaults = SnapAdmin.Defaults;
    const isDevMode = (process.env.NODE_ENV !== 'production');
    const installationPath = getInstallationPath(context, isDevMode);
    const apiPath = `${installationPath}/api`;

    const languageId = localStorage.getItem('sw-admin-current-language') || Defaults.systemLanguageId;

    // set initial context
    SnapAdmin.State.commit('context/setApiInstallationPath', installationPath);
    SnapAdmin.State.commit('context/setApiApiPath', apiPath);
    SnapAdmin.State.commit('context/setApiApiResourcePath', `${apiPath}`);
    SnapAdmin.State.commit('context/setApiAssetsPath', getAssetsPath(context.assetPath, isDevMode));
    SnapAdmin.State.commit('context/setApiLanguageId', languageId);
    SnapAdmin.State.commit('context/setApiInheritance', false);

    if (isDevMode) {
        SnapAdmin.State.commit('context/setApiSystemLanguageId', Defaults.systemLanguageId);
        SnapAdmin.State.commit('context/setApiLiveVersionId', Defaults.versionId);
    }

    // assign unknown context information
    Object.entries(context).forEach(([key, value]) => {
        SnapAdmin.State.commit('context/addApiValue', { key, value });
    });

    return SnapAdmin.Context.api;
}

/**
 * Provides the installation path of the application. The path provides the scheme, host and sub directory.
 *
 * @param {Object} context
 * @param {Boolean} isDevMode
 * @returns {string}
 */
function getInstallationPath(context, isDevMode) {
    if (isDevMode) {
        return '';
    }

    let fullPath = '';
    if (context.schemeAndHttpHost?.length) {
        fullPath = `${context.schemeAndHttpHost}${context.basePath}`;
    }

    return fullPath;
}

/**
 * Provides the path to the assets directory.
 *
 * @param {String} installationPath
 * @param {Boolean} isDevMode
 * @returns {string}
 */
function getAssetsPath(installationPath, isDevMode) {
    if (isDevMode) {
        return '/bundles/';
    }

    return `${installationPath}/bundles/`;
}
