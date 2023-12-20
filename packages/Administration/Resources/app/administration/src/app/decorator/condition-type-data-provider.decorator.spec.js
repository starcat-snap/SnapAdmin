import 'src/app/decorator/condition-type-data-provider.decorator';
import RuleConditionService from 'src/app/service/rule-condition.service';

describe('entity-collection.data.ts', () => {
    beforeAll(async () => {
        SnapAdmin.Service().register('ruleConditionDataProviderService', () => {
            return new RuleConditionService();
        });
    });

    it('should register conditions with correct scope', async () => {
        const condition = SnapAdmin.Service('ruleConditionDataProviderService').getByType('language');

        expect(condition).toBeDefined();
        expect(condition.scopes).toEqual(['global']);
    });

    it('should add app script conditions', async () => {
        SnapAdmin.Service('ruleConditionDataProviderService').addScriptConditions([
            {
                id: 'bar',
                name: 'foo',
                group: 'misc',
                config: {},
            },
        ]);

        const condition = SnapAdmin.Service('ruleConditionDataProviderService').getByType('bar');

        expect(condition.component).toBe('sw-condition-script');
        expect(condition.type).toBe('scriptRule');
        expect(condition.label).toBe('foo');
    });
});
