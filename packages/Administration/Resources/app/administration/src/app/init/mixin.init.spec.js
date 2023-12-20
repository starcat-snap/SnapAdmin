import createAppMixin from 'src/app/init/mixin.init';

describe('src/app/init/mixin.init.js', () => {
    it('should register all app mixins', () => {
        createAppMixin();

        expect(SnapAdmin.Mixin.getByName('sw-form-field')).toBeDefined();
        expect(SnapAdmin.Mixin.getByName('generic-condition')).toBeDefined();
        expect(SnapAdmin.Mixin.getByName('listing')).toBeDefined();
        expect(SnapAdmin.Mixin.getByName('notification')).toBeDefined();
        expect(SnapAdmin.Mixin.getByName('placeholder')).toBeDefined();
        expect(SnapAdmin.Mixin.getByName('position')).toBeDefined();
        expect(SnapAdmin.Mixin.getByName('remove-api-error')).toBeDefined();
        expect(SnapAdmin.Mixin.getByName('ruleContainer')).toBeDefined();
        expect(SnapAdmin.Mixin.getByName('salutation')).toBeDefined();
        expect(SnapAdmin.Mixin.getByName('sw-inline-snippet')).toBeDefined();
        expect(SnapAdmin.Mixin.getByName('user-settings')).toBeDefined();
        expect(SnapAdmin.Mixin.getByName('validation')).toBeDefined();
    });
});
