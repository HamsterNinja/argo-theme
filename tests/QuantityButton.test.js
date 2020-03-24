import 'babel-polyfill';
import { mount, shallowMount, createLocalVue } from '@vue/test-utils';

import Vuex from 'vuex'

const localVue = createLocalVue()
localVue.use(Vuex)

import Vue from 'vue';
import QuantityButton from '@/components/QuantityButton.vue'

describe('QuantityButton', () => {
    global.SITEDATA = {}
    let store
    let actions
    let mutations
    
    beforeEach(() => {
        mutations = {
            updateProductCount: jest.fn(),
            updateCartSubtotal: jest.fn()
        };
        store = new Vuex.Store({
            state: {},
            actions,
            mutations
        });
    })

    test('является экземпляром Vue', () => {
        const wrapper = mount(QuantityButton)
        expect(wrapper.isVueInstance()).toBeTruthy()
    })

    test('Кнопка увеличить', () => {
        const wrapper = shallowMount(QuantityButton, {
            store,
            localVue,
        });
        wrapper.find('.increase-button').trigger('click')
        expect(wrapper.vm.countComponent).toBe(2)
    })

    test('Кнопка уменьшить', () => {
        const wrapper = shallowMount(QuantityButton, {
            store,
            localVue,
            propsData: {
                count: 10
            }
        });
        wrapper.find('.decrease-button').trigger('click')
        expect(wrapper.vm.countComponent).toBe(9)
    })

    test('Печать в input числа', () => {
        const wrapper = shallowMount(QuantityButton, {
            store,
            localVue,
        });
        let input = wrapper.find('input')
        input.element.value = '15'
        input.trigger('change')
        expect(wrapper.vm.countComponent).toBe(15)
    })

    test('Отбросить отрицательные значение в input', () => {
        const wrapper = shallowMount(QuantityButton, {
            store,
            localVue,
        });
        let input = wrapper.find('input')
        input.element.value = '-15'
        input.trigger('change')
        expect(wrapper.vm.countComponent).toBe(15)
    })

    test('Не число в input', () => {
        const wrapper = shallowMount(QuantityButton, {
            store,
            localVue,
        });
        let input = wrapper.find('input')
        input.element.value = 'Not number'
        input.trigger('change')
        expect(wrapper.vm.countComponent).toBe(1)
    })

 })