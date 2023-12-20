import initLanguageService from 'src/app/init-post/language.init';

describe('src/app/init-post/language.init.ts', () => {
    it('should init the language service', () => {
        const mock = jest.fn(() => null);
        SnapAdmin.Application.$container.resetProviders();

        SnapAdmin.Service().register('languageAutoFetchingService', mock);

        initLanguageService();

        // middleware should not be executed yet
        expect(mock).not.toHaveBeenCalled();

        // access repositoryFactory to trigger the middleware
        SnapAdmin.Application.getContainer('service').repositoryFactory.create('product');

        // middleware should be executed now
        expect(mock).toHaveBeenCalled();
    });
});
