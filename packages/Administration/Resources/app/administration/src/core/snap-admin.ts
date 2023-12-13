/**
 * @package admin
 *
 * SnapAdmin End Developer API
 * @module SnapAdmin
 * @ignore
 */
import Bottle from 'bottlejs';
import Feature from 'src/core/feature';
import ApplicationBootstrapper from 'src/core/application';

/** Initialize feature flags at the beginning */
if (window.hasOwnProperty('_features_')) {
    Feature.init(_features_);
}


// strict mode was set to false because it was defined wrong previously
Bottle.config = { strict: false };
const container = new Bottle();

const application = new ApplicationBootstrapper(container);

class SnapAdminClass {
    public Application = application;

}

const SnapAdminInstance = new SnapAdminClass();

window.SnapAdmin = SnapAdminInstance;

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
export default SnapAdminInstance;

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
export { SnapAdminClass };
