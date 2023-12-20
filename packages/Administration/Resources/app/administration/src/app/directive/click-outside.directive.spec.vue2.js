/**
 * @package admin
 */
describe('directives/click-outside', () => {
    it('should register the directive', () => {
        expect(SnapAdmin.Directive.getByName('click-outside')).toBeDefined();
    });
});
