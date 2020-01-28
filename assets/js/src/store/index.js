import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

const set = key => (state, val) => {
    state[key] = val
}

const store = new Vuex.Store({
    state: {
        productCount: 1,
    },
    getters: {
        getProductCount: state => state.productCount,
    },
    mutations: {
        updateProductCount: set('productCount'),
    },
    actions: {},
});

export default store;