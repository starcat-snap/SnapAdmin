/**
 * @package admin
 */

import { mount } from '@vue/test-utils';
import shortcutPlugin from 'src/app/plugin/shortcut.plugin';
import 'src/app/component/form/sw-text-field';
import 'src/app/component/form/field-base/sw-contextual-field';
import 'src/app/component/form/field-base/sw-block-field';
import 'src/app/component/form/field-base/sw-base-field';
import 'src/app/component/form/sw-colorpicker';
import 'src/app/component/form/sw-compact-colorpicker';
import 'src/app/component/form/sw-switch-field';
import 'src/app/component/form/sw-checkbox-field';
import 'src/app/component/base/sw-container';
import 'src/app/component/base/sw-button';

SnapAdmin.Utils.debounce = function debounce(fn) {
    return function execFunction(...args) {
        fn.apply(this, args);
    };
};

const createWrapper = async (componentOverride = {}) => {
    const baseComponent = {
        name: 'base-component',
        template: '<div></div>',
        ...componentOverride,
    };

    const element = document.createElement('div');
    if (document.body) {
        document.body.appendChild(element);
    }

    return mount(baseComponent, {
        attachTo: element,
        global: {
            plugins: [shortcutPlugin],
        },
    });
};

function defineJsdomProperties() {
    // 'Implement' innerText in JSDOM: https://github.com/jsdom/jsdom/issues/1245
    Object.defineProperty(global.Element.prototype, 'innerText', {
        get() {
            return this.textContent;
        },
    });

    // 'Implement' isContentEditable in JSDOM: https://github.com/jsdom/jsdom/issues/1670
    Object.defineProperty(global.Element.prototype, 'isContentEditable', {
        get() {
            return this.getAttribute('contenteditable');
        },
    });
}

describe('app/plugins/shortcut.plugin', () => {
    let wrapper;

    it('should test with a Vue.js component', async () => {
        wrapper = await createWrapper();

        expect(wrapper.vm).toBeTruthy();
    });

    it('String: should call the onSave method', async () => {
        const onSaveMock = jest.fn();

        wrapper = await createWrapper({
            shortcuts: {
                s: 'onSave',
            },
            methods: {
                onSave() {
                    onSaveMock();
                },
            },
        });

        expect(onSaveMock).not.toHaveBeenCalled();

        await wrapper.trigger('keydown', {
            key: 's',
        });
        await wrapper.trigger('keydown', {
            key: 'CTRL',
        });

        expect(onSaveMock).toHaveBeenCalledWith();
    });

    it('Object with boolean active: should call the onSave method', async () => {
        const onSaveMock = jest.fn();

        wrapper = await createWrapper({
            shortcuts: {
                s: {
                    active: true,
                    method: 'onSave',
                },
            },
            methods: {
                onSave() {
                    onSaveMock();
                },
            },
        });

        expect(onSaveMock).not.toHaveBeenCalled();

        await wrapper.trigger('keydown', {
            key: 's',
        });

        expect(onSaveMock).toHaveBeenCalledWith();
    });

    it('Object with boolean active: should NOT call the onSave method', async () => {
        const onSaveMock = jest.fn();

        wrapper = await createWrapper({
            shortcuts: {
                s: {
                    active: false,
                    method: 'onSave',
                },
            },
            methods: {
                onSave() {
                    onSaveMock();
                },
            },
        });

        expect(onSaveMock).not.toHaveBeenCalled();

        await wrapper.trigger('keydown', {
            key: 's',
        });

        expect(onSaveMock).not.toHaveBeenCalledWith();
    });

    it('Object with function active: should call the onSave method', async () => {
        const onSaveMock = jest.fn();

        wrapper = await createWrapper({
            shortcuts: {
                s: {
                    active() {
                        return true;
                    },
                    method: 'onSave',
                },
            },
            methods: {
                onSave() {
                    onSaveMock();
                },
            },
        });

        expect(onSaveMock).not.toHaveBeenCalled();

        await wrapper.trigger('keydown', {
            key: 's',
        });

        expect(onSaveMock).toHaveBeenCalledWith();
    });

    it('Object with function active: should NOT call the onSave method', async () => {
        const onSaveMock = jest.fn();

        wrapper = await createWrapper({
            shortcuts: {
                s: {
                    active() {
                        return false;
                    },
                    method: 'onSave',
                },
            },
            methods: {
                onSave() {
                    onSaveMock();
                },
            },
        });

        expect(onSaveMock).not.toHaveBeenCalled();

        await wrapper.trigger('keydown', {
            key: 's',
        });

        expect(onSaveMock).not.toHaveBeenCalledWith();
    });

    it('Object with function active which access the vue instance: should call the onSave method', async () => {
        const onSaveMock = jest.fn();

        wrapper = await createWrapper({
            shortcuts: {
                s: {
                    active() {
                        return this.activeValue;
                    },
                    method: 'onSave',
                },
            },
            computed: {
                activeValue() {
                    return true;
                },
            },
            methods: {
                onSave() {
                    onSaveMock();
                },
            },
        });

        expect(onSaveMock).not.toHaveBeenCalled();

        await wrapper.trigger('keydown', {
            key: 's',
        });

        expect(onSaveMock).toHaveBeenCalledWith();
    });

    it('Object with function active which access the vue instance: should NOT call the onSave method', async () => {
        const onSaveMock = jest.fn();

        wrapper = await createWrapper({
            shortcuts: {
                s: {
                    active() {
                        return this.activeValue;
                    },
                    method: 'onSave',
                },
            },
            computed: {
                activeValue() {
                    return false;
                },
            },
            methods: {
                onSave() {
                    onSaveMock();
                },
            },
        });

        expect(onSaveMock).not.toHaveBeenCalled();

        await wrapper.trigger('keydown', {
            key: 's',
        });

        expect(onSaveMock).not.toHaveBeenCalledWith();
    });

    it('Object with function: function should be executed for each shortcut press', async () => {
        const onSaveMock = jest.fn();
        let shouldExecute = true;

        wrapper = await createWrapper({
            shortcuts: {
                s: {
                    active() {
                        return shouldExecute;
                    },
                    method: 'onSave',
                },
            },
            methods: {
                onSave() {
                    onSaveMock();
                },
            },
        });

        // shortcut should be executed
        expect(onSaveMock).not.toHaveBeenCalled();

        await wrapper.trigger('keydown', {
            key: 's',
        });

        expect(onSaveMock).toHaveBeenCalledWith();

        // change value dynamically
        onSaveMock.mockReset();
        shouldExecute = false;

        expect(onSaveMock).not.toHaveBeenCalled();

        await wrapper.trigger('keydown', {
            key: 's',
        });

        // shortcut should not be executed
        expect(onSaveMock).not.toHaveBeenCalledWith();
    });
});
