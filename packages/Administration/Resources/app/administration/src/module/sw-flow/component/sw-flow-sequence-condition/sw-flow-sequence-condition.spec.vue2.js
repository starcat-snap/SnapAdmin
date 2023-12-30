import { createLocalVue, shallowMount } from '@vue/test-utils_v2';
import swFlowSequenceCondition from 'src/module/sw-flow/component/sw-flow-sequence-condition';
import 'src/app/component/form/select/base/sw-single-select';
import 'src/app/component/form/select/entity/sw-entity-single-select';
import 'src/app/component/form/select/base/sw-select-base';
import 'src/app/component/form/field-base/sw-block-field';
import 'src/app/component/form/field-base/sw-base-field';
import 'src/app/component/form/select/base/sw-select-result-list';
import 'src/app/component/form/select/base/sw-select-result';

import EntityCollection from 'src/core/data/entity-collection.data';

import Vuex from 'vuex_v2';
import flowState from 'src/module/sw-flow/state/flow.state';

SnapAdmin.Component.register('sw-flow-sequence-condition', swFlowSequenceCondition);

const sequenceFixture = {
    id: '1',
    actionName: null,
    ruleId: '',
    parentId: null,
    position: 1,
    displayGroup: 1,
    config: {},
};

const sequencesFixture = [
    {
        ...sequenceFixture,
        ruleId: '1111',
    },
    {
        ...sequenceFixture,
        id: '2',
        parentId: '1',
        trueCase: true,
    },
    {
        ...sequenceFixture,
        id: '3',
        parentId: '1',
        trueCase: false,
    },
];

function getSequencesCollection(collection = []) {
    return new EntityCollection(
        '/flow_sequence',
        'flow_sequence',
        null,
        { isSnapAdminContext: true },
        collection,
        collection.length,
        null,
    );
}

SnapAdmin.Service().register('shopwareDiscountCampaignService', () => {
    return { isDiscountCampaignActive: jest.fn(() => false) };
});

async function createWrapper(propsData = {}) {
    const localVue = createLocalVue();
    localVue.use(Vuex);

    return shallowMount(await SnapAdmin.Component.build('sw-flow-sequence-condition'), {
        stubs: {
            'sw-icon': {
                template: '<div class="sw-icon" v-on="$listeners"></div>',
            },
            'sw-context-button': true,
            'sw-context-menu-item': {
                template: `
                    <div class="sw-context-menu-item" v-on="$listeners">
                      <slot></slot>
                    </div>
                `,
            },
            'sw-entity-single-select': await SnapAdmin.Component.build('sw-entity-single-select'),
            'sw-single-select': await SnapAdmin.Component.build('sw-single-select'),
            'sw-select-base': await SnapAdmin.Component.build('sw-select-base'),
            'sw-block-field': await SnapAdmin.Component.build('sw-block-field'),
            'sw-base-field': await SnapAdmin.Component.build('sw-base-field'),
            'sw-select-result': await SnapAdmin.Component.build('sw-select-result'),
            'sw-select-result-list': await SnapAdmin.Component.build('sw-select-result-list'),
            'sw-popover': true,
            'sw-loader': true,
            'sw-field-error': true,
            'sw-label': true,
            'sw-flow-rule-modal': true,
        },
        propsData: {
            sequence: sequenceFixture,
            ...propsData,
        },
        provide: {
            flowBuilderService: {
                getActionModalName: () => {},
            },
            repositoryFactory: {
                create: () => {
                    return {
                        search: jest.fn(() => {
                            return Promise.resolve([
                                {
                                    id: 'someRestrictedRule',
                                    name: 'All customers',
                                },
                                {
                                    id: 'allCustomersRule',
                                    name: 'Restricted rule',
                                },
                            ]);
                        }),
                        get: (id) => Promise.resolve({
                            id,
                            name: 'Rule name',
                        }),
                        create: () => { return {}; },
                    };
                },
            },
        },
    });
}

describe('src/module/sw-flow/component/sw-flow-sequence-condition', () => {
    beforeAll(() => {
        SnapAdmin.Service().register('ruleConditionDataProviderService', () => {
            return {
                getRestrictedRules: () => Promise.resolve(['someRestrictedRule']),
            };
        });

        SnapAdmin.State.registerModule('swFlowState', {
            ...flowState,
            state: {
                flow: {
                    eventName: '',
                    sequences: getSequencesCollection([{ ...sequenceFixture }]),
                },
                invalidSequences: [],
                restrictedRules: [],
            },
        });
    });

    it('should show help element if sequence is a first created root sequence', async () => {
        const wrapper = await createWrapper();

        const helpElement = wrapper.find('.sw-flow-sequence-condition__explains');
        expect(helpElement.exists()).toBeTruthy();

        const trueArrow = wrapper.find('.sw-flow-sequence-condition__true-arrow');
        expect(trueArrow.exists()).toBeFalsy();

        const falseArrow = wrapper.find('.sw-flow-sequence-condition__false-arrow');
        expect(falseArrow.exists()).toBeFalsy();
    });

    it('should create 2 true/false children selectors if sequence is root sequence which contains a rule', async () => {
        let sequencesState = SnapAdmin.State.getters['swFlowState/sequences'];
        expect(sequencesState).toHaveLength(1);

        const wrapper = await createWrapper({
            sequence: {
                ...sequenceFixture,
                ruleId: '1111',
                rule: {
                    name: 'Rule name',
                    id: '1111',
                },
            },
        });

        const helpElement = wrapper.find('.sw-flow-sequence-condition__explains');
        expect(helpElement.exists()).toBeFalsy();

        const trueArrow = wrapper.find('.sw-flow-sequence-condition__true-arrow');
        expect(trueArrow.exists()).toBeTruthy();

        const falseArrow = wrapper.find('.sw-flow-sequence-condition__false-arrow');
        expect(falseArrow.exists()).toBeTruthy();

        // Flow sequences add 2 new selectors
        sequencesState = SnapAdmin.State.getters['swFlowState/sequences'];
        expect(sequencesState).toHaveLength(3);

        // Show context button
        const trueAction = wrapper.find('.sw-flow-sequence-condition__true-action');
        expect(trueAction.exists()).toBeTruthy();

        const falseAction = wrapper.find('.sw-flow-sequence-condition__false-action');
        expect(falseAction.exists()).toBeTruthy();

        const falseArrowIcon = wrapper.find('.sw-icon[name="regular-chevron-down-s"]');
        expect(falseArrowIcon.exists()).toBeFalsy();

        const trueArrowIcon = wrapper.find('.sw-icon[name="regular-chevron-right-s"]');
        expect(trueArrowIcon.exists()).toBeFalsy();
    });

    it('should show arrow icon if sequence has trueBlock or falseBlock', async () => {
        const wrapper = await createWrapper({
            sequence: {
                ...sequenceFixture,
                ruleId: '1111',
                trueBlock: {
                    2: {
                        ...sequencesFixture[1],
                    },
                },
                falseBlock: {
                    3: {
                        ...sequencesFixture[2],
                    },
                },
                rule: {
                    name: 'Rule name',
                    id: '1111',
                },
            },
        });

        // Show context button
        const trueAction = wrapper.find('.sw-flow-sequence-condition__true-action');
        expect(trueAction.exists()).toBeFalsy();

        const falseAction = wrapper.find('.sw-flow-sequence-condition__false-action');
        expect(falseAction.exists()).toBeFalsy();

        const falseArrowIcon = wrapper.find('.sw-icon[name="regular-chevron-down-s"]');
        expect(falseArrowIcon.exists()).toBeTruthy();

        const trueArrowIcon = wrapper.find('.sw-icon[name="regular-chevron-right-s"]');
        expect(trueArrowIcon.exists()).toBeTruthy();
    });

    it('should able to add new trueBlock or falseBlock', async () => {
        SnapAdmin.State.commit(
            'swFlowState/setSequences',
            getSequencesCollection([{
                ...sequenceFixture,
                ruleId: '1111',
                rule: {
                    name: 'Rule name',
                    id: '1111',
                },
            }]),
        );

        let sequencesState = SnapAdmin.State.getters['swFlowState/sequences'];
        expect(sequencesState).toHaveLength(1);

        const wrapper = await createWrapper({
            sequence: {
                ...sequenceFixture,
                parentId: '4',
                ruleId: '1111',
                rule: {
                    name: 'Rule name',
                    id: '1111',
                },
            },
        });

        // Show context button
        const conditionTrueBlock = wrapper.findAll('.sw-flow-sequence-condition__true-action .sw-context-menu-item');
        await conditionTrueBlock.at(0).trigger('click');

        sequencesState = SnapAdmin.State.getters['swFlowState/sequences'];
        expect(sequencesState).toHaveLength(2);

        const actionFalseBlock = wrapper.findAll('.sw-flow-sequence-condition__false-action .sw-context-menu-item');
        await actionFalseBlock.at(1).trigger('click');

        sequencesState = SnapAdmin.State.getters['swFlowState/sequences'];
        expect(sequencesState).toHaveLength(3);
    });

    it('should set error for single select if action name is empty', async () => {
        SnapAdmin.State.commit('swFlowState/setInvalidSequences', ['1']);

        const wrapper = await createWrapper();
        await wrapper.setProps({
            sequence: {
                ...sequenceFixture,
            },
        });

        const actionSelection = wrapper.find('.sw-flow-sequence-condition__selection-rule');
        expect(actionSelection.attributes('error')).toBeTruthy();
    });

    it('should remove error for after select an action name', async () => {
        SnapAdmin.State.commit(
            'swFlowState/setSequences',
            getSequencesCollection([{ ...sequenceFixture }]),
        );
        SnapAdmin.State.commit('swFlowState/setInvalidSequences', ['1']);

        let invalidSequences = SnapAdmin.State.get('swFlowState').invalidSequences;
        expect(invalidSequences).toEqual(['1']);

        const wrapper = await createWrapper();
        await wrapper.setProps({
            sequence: {
                ...sequenceFixture,
            },
        });

        const actionSelection = wrapper.find('.sw-flow-sequence-condition__selection-rule');
        expect(actionSelection.attributes('error')).toBeTruthy();

        const selectElement = wrapper.find('.sw-select__selection');

        await selectElement.trigger('click');
        await flushPromises();

        const ruleOptionInSelect = wrapper.find('.sw-select-option--1');
        await ruleOptionInSelect.trigger('click');

        invalidSequences = SnapAdmin.State.get('swFlowState').invalidSequences;
        expect(invalidSequences).toEqual([]);
    });

    it('should able to remove a condition and its children', async () => {
        SnapAdmin.State.commit('swFlowState/setSequences', getSequencesCollection(sequencesFixture));

        const wrapper = await createWrapper({
            sequence: {
                ...sequenceFixture,
                ruleId: '1111',
                rule: {
                    name: 'Rule name',
                    id: '1111',
                },
                trueBlock: {
                    2: {
                        ...sequencesFixture[1],
                        _isNew: true,
                    },
                },
                falseBlock: {
                    3: {
                        ...sequencesFixture[2],
                        _isNew: true,
                    },
                },
            },
        });

        let sequencesState = SnapAdmin.State.getters['swFlowState/sequences'];
        expect(sequencesState).toHaveLength(3);


        const deleteRule = wrapper.findAll('.sw-flow-sequence-condition__delete-condition');
        await deleteRule.trigger('click');

        sequencesState = SnapAdmin.State.getters['swFlowState/sequences'];
        expect(sequencesState).toHaveLength(0);
    });

    it('should be able to change the rule', async () => {
        const sequence = {
            ...sequenceFixture,
            ruleId: '1111',
            rule: {
                name: 'Restricted rule',
                id: '1111',
            },
        };

        SnapAdmin.State.commit(
            'swFlowState/setSequences',
            getSequencesCollection([{ ...sequence }]),
        );

        const wrapper = await createWrapper({
            sequence,
        });

        const editButton = wrapper.find('.sw-flow-sequence-condition__rule-change');
        await editButton.trigger('click');
        await flushPromises();

        const selectElement = wrapper.find('.sw-select__selection');

        await selectElement.trigger('click');
        await flushPromises();

        const ruleOptionInSelect = wrapper.find('.sw-select-option--1');
        await ruleOptionInSelect.trigger('click');

        const sequencesState = SnapAdmin.State.getters['swFlowState/sequences'];
        expect(sequencesState[0]).toEqual({
            ...sequence,
            ruleId: 'allCustomersRule',
            rule: {
                ...sequence.rule,
                id: 'allCustomersRule',
            },
        });
    });

    it('should able to delete rule', async () => {
        const sequence = {
            ...sequenceFixture,
            ruleId: '1111',
            rule: {
                name: 'Rule name',
                id: '1111',
            },
        };

        SnapAdmin.State.commit(
            'swFlowState/setSequences',
            getSequencesCollection([{ ...sequence }]),
        );

        const wrapper = await createWrapper({
            sequence,
        });

        const editButton = wrapper.find('.sw-flow-sequence-condition__rule-delete');
        await editButton.trigger('click');

        const sequencesState = SnapAdmin.State.getters['swFlowState/sequences'];
        expect(sequencesState[0]).toEqual({
            ...sequence,
            rule: null,
            ruleId: '',
        });
    });

    it('should able to disable add buttons', async () => {
        const wrapper = await createWrapper({
            sequence: {
                ...sequenceFixture,
                ruleId: '1111',
                rule: {
                    name: 'Rule name',
                    id: '1111',
                },
            },
        });

        const components = [
            '.sw-flow-sequence-condition__context-button',
            '.sw-flow-sequence-condition__rule-context-button',
            '.sw-flow-sequence-condition__add-false-action',
            '.sw-flow-sequence-condition__add-false-condition',
            '.sw-flow-sequence-condition__add-true-action',
            '.sw-flow-sequence-condition__add-true-condition',
        ];

        components.forEach(component => {
            expect(wrapper.find(component).attributes().disabled).toBeFalsy();
        });

        await wrapper.setProps({
            disabled: true,
        });

        components.forEach(component => {
            expect(wrapper.find(component).attributes().disabled).toBeTruthy();
        });
    });

    it('should show rule modal when click on create new rule option', async () => {
        const sequence = {
            ...sequenceFixture,
            ruleId: '',
        };

        SnapAdmin.State.commit(
            'swFlowState/setSequences',
            getSequencesCollection([{ ...sequence }]),
        );
        const wrapper = await createWrapper({
            sequence,
        });

        let createRuleModal = wrapper.find('sw-flow-rule-modal-stub');
        expect(createRuleModal.exists()).toBeFalsy();

        const selectElement = wrapper.find('.sw-select__selection');
        await selectElement.trigger('click');

        const createRuleButton = wrapper.find('.sw-select-result__create-new-rule');
        await createRuleButton.trigger('click');

        createRuleModal = wrapper.find('sw-flow-rule-modal-stub');
        expect(createRuleModal.exists()).toBeTruthy();
    });

    it('should show rule modal when click on edit rule option', async () => {
        const sequence = {
            ...sequenceFixture,
            ruleId: '1111',
            rule: {
                name: 'Rule name',
                id: '1111',
            },
        };

        SnapAdmin.State.commit(
            'swFlowState/setSequences',
            getSequencesCollection([{ ...sequence }]),
        );

        const wrapper = await createWrapper({
            sequence,
        });

        let ruleModal = wrapper.find('sw-flow-rule-modal-stub');
        expect(ruleModal.exists()).toBeFalsy();

        const editButton = wrapper.find('.sw-flow-sequence-condition__rule-edit');
        await editButton.trigger('click');

        ruleModal = wrapper.find('sw-flow-rule-modal-stub');
        expect(ruleModal.exists()).toBeTruthy();
        expect(ruleModal.attributes()['rule-id']).toBe('1111');
    });

    it('should disable the rule if it is restricted', async () => {
        const wrapper = await createWrapper();
        await SnapAdmin.State.dispatch('swFlowState/setRestrictedRules', 'someRestrictedRule');

        const selectElement = wrapper.find('.sw-select__selection');

        await selectElement.trigger('click');
        await flushPromises();

        const disabledRule = wrapper.find('.sw-select-result-list ul:nth-of-type(2) li');

        expect(disabledRule.classes()).toContain('is--disabled');
    });

    it('should not disable the rule if it is restricted', async () => {
        const wrapper = await createWrapper();

        const selectElement = wrapper.find('.sw-select__selection');

        await selectElement.trigger('click');
        await flushPromises();

        const disabledRule = wrapper.find('.sw-select-result-list ul:nth-of-type(2) li:nth-of-type(2)');

        expect(disabledRule.classes()).not.toContain('is--disabled');
    });
});
