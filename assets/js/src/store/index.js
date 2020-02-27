import Vue from 'vue';
import Vuex from 'vuex';
// import createPersistedState from 'vuex-persistedstate';
Vue.use(Vuex);

const set = key => (state, val) => {
    state[key] = val
}

const store = new Vuex.Store({
    state: {
        searchString: SITEDATA.search_query,
        catalogCategory: SITEDATA.category_slug,
        cartSubtotal: parseFloat(SITEDATA.cart_subtotal),
        currentUser: SITEDATA.user_data,
        productCount: 1,
        pageNum: 1,
        showLoader: false,
        loadingProducts: false,
        product: {},
        products: [],
        orders: [],
    },
    getters: {
        getProductCount: state => state.productCount,
        orders: state => state.orders,
    },
    mutations: {
        updateProductCount: set('productCount'),
        updateCartSubtotal: set('cartSubtotal'),
        updateOrders: set('orders'),
    },
    actions: {
        async fetchOrders({commit}) {
            const orders = await fetch(`${SITEDATA.url}/wp-json/amadreh/v1/get-orders`)
            let data = await orders.json()
            commit('updateOrders', data.data.orders)
        },
    },
});

export default store;