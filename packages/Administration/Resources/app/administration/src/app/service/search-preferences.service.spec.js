/**
 * @package system-settings
 */
import SearchPreferencesService from 'src/app/service/search-preferences.service';

describe('searchPreferencesService', () => {
    it('is registered correctly', () => {
        let searchPreferencesService = new SearchPreferencesService({
            userConfigRepository: SnapAdmin.Service('repositoryFactory').create('user_config'),
        });
        searchPreferencesService = {
            createUserSearchPreferences: jest.fn(),
            getDefaultSearchPreferences: jest.fn(),
            getUserSearchPreferences: jest.fn(),
            processSearchPreferences: jest.fn(),
            processSearchPreferencesFields: jest.fn(),
        };

        expect(searchPreferencesService).toEqual(expect.objectContaining({
            createUserSearchPreferences: searchPreferencesService.createUserSearchPreferences,
            getDefaultSearchPreferences: searchPreferencesService.getDefaultSearchPreferences,
            getUserSearchPreferences: searchPreferencesService.getUserSearchPreferences,
            processSearchPreferences: searchPreferencesService.processSearchPreferences,
            processSearchPreferencesFields: searchPreferencesService.processSearchPreferencesFields,
        }));
    });
});
