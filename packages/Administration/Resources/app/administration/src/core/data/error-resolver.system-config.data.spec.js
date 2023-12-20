import ErrorResolverSystemConfig from 'src/core/data/error-resolver.system-config.data';
import SnapAdminError from 'src/core/data/SnapAdminError';
import mock from './mocks/error-resolver.system-config.mock.json';

const errorResolverSystemConfig = new ErrorResolverSystemConfig();

describe('src/core/data/error-resolver.system-config.data.ts', () => {
    it('should handleWriteErrors throw error', () => {
        const fn = () => errorResolverSystemConfig.handleWriteErrors(null);
        expect(fn).toThrow(Error);
    });

    it('should handleWriteErrors has system error', () => {
        errorResolverSystemConfig.handleWriteErrors(mock.errorWithSystemError);

        const countSystemError = SnapAdmin.State.getters['error/countSystemError']();

        expect(countSystemError).toBe(1);
    });

    it('should handleWriteErrors has api error', () => {
        errorResolverSystemConfig.handleWriteErrors(mock.apiErrors);

        const result = SnapAdmin.State.getters['error/getSystemConfigApiError'](ErrorResolverSystemConfig.ENTITY_NAME, null, 'dummy.key');

        expect(result).toBeInstanceOf(SnapAdminError);
    });

    it('should handleWriteErrors has api error with translations', () => {
        errorResolverSystemConfig.handleWriteErrors(mock.apiErrorsWithTranslation);

        const result = SnapAdmin.State.getters['error/getSystemConfigApiError'](ErrorResolverSystemConfig.ENTITY_NAME, null, 'dummy.key');

        expect(result).toEqual({});
    });

    it('should cleanWriteErrors need clean all api errors', () => {
        errorResolverSystemConfig.handleWriteErrors(mock.apiErrors);

        errorResolverSystemConfig.cleanWriteErrors();

        const result = SnapAdmin.State.getters['error/getAllApiErrors']();

        expect(result).toEqual([]);
    });
});
