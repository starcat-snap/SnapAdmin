/**
 * @package admin
 */

let isInitialized = false;

/**
 * @private
 */
export default function LanguageAutoFetchingService() {
    if (isInitialized) return;
    isInitialized = true;

    // initial loading of the language
    loadLanguage(SnapAdmin.Context.api.languageId);

    // load the language Entity
    async function loadLanguage(newLanguageId) {
        const languageRepository = SnapAdmin.Service('repositoryFactory').create('language');
        const newLanguage = await languageRepository.get(newLanguageId, {
            ...SnapAdmin.Context.api,
            inheritance: true,
        });

        SnapAdmin.State.commit('context/setApiLanguage', newLanguage);
    }

    // watch for changes of the languageId
    SnapAdmin.State.watch(state => state.context.api.languageId, (newValue, oldValue) => {
        if (newValue === oldValue) return;

        loadLanguage(newValue);
    });
}
