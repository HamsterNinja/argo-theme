import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate';
Vue.use(Vuex);

const set = key => (state, val) => {
    state[key] = val
}

const store = new Vuex.Store({
    state: {
        productCount: 1,
        catalogCategory: SITEDATA.category_slug,
        cartSubtotal: parseFloat(SITEDATA.cart_subtotal),
        pageNum: 1,
        showLoader: false,
        loadingProducts: false,
        product: {},
        products: [],
        searchString: SITEDATA.search_query,
    },
    getters: {
        getProductCount: state => state.productCount,
    },
    mutations: {
        updateProductCount: set('productCount'),
        updateCartSubtotal: set('cartSubtotal'),
    },
    actions: {
    },
});

export default store;