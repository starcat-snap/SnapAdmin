import type RuleConditionService from '../service/rule-condition.service';

const { Application } = SnapAdmin;

/**
 * @package business-ops
 */
Application.addServiceProviderDecorator('ruleConditionDataProviderService', (ruleConditionService: RuleConditionService) => {
    return ruleConditionService;
});
