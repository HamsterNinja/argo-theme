import 'babel-polyfill';
import Vue from 'vue';
import Vuex from 'vuex';
import {mapState, mapGetters} from 'vuex';
Vue.use(Vuex);
Vue.use(mapState);
Vue.use(mapGetters);
import store from './store';

$('.btn-hamburger').click(function () {
    $('.mobile-menu').addClass('active');
});
$('.btn-hamburger.active').click(function () {
    $('.mobile-menu').removeClass('active');
});


$('.activity-block-slick').slick({
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 3,
    arrows: false,
    dots: true,
    responsive: [{
            breakpoint: 950,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2,
                infinite: true,
                arrows: false,
                dots: true
            }
        }, {
            breakpoint: 640,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                infinite: true,
                arrows: false,
                dots: true
            }
        }
    ]
});


$('.popular-slick').slick({
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 3,
    arrows: false,
    dots: true,
    responsive: [{
            breakpoint: 950,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2,
                infinite: true,
                arrows: false,
                dots: true
            }
        }, {
            breakpoint: 640,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                infinite: true,
                arrows: false,
                dots: true
            }
        }
    ]
});

$('.menu-slick').not('.slick-initialized').slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
});

$('.choice-button').click(function (event) {
    event.preventDefault();
    $('.choice-button').removeClass('active');
    $(this).addClass('active');

    var id = $(this).attr('data-id');
    if (id) {
        $('.single_menu-tabs-content:visible').fadeOut(0, function () {
            $('.single_menu-tabs').find('#' + id).fadeIn('slow', function () {
                $('.menu-slick').slick('reinit');
            });
        });
    }
});

import MaskedInput from 'vue-masked-input';

import numeral from 'numeral';
numeral.register('locale', 'ru', {
    delimiters: {
        thousands: ' ',
        decimal: ','
    },
    abbreviations: {
        thousand: 'тыс.',
        million: 'млн.',
        billion: 'млрд.',
        trillion: 'трлн.'
    },
    ordinal: function () {
        return '.';
    },
    currency: {
        symbol: 'руб.'
    }
});

numeral.locale('ru');


Vue.filter("formatNumber", function (value) {
    return numeral(value).format(); 
});

const app = new Vue({
    el: "#app",
    store,
    delimiters: ["((", "))"],
    data: {},
    components: {
        'masked-input': MaskedInput,
    },
    computed: {},
    methods: {
        async addCart(ID) {
            this.adding = true;
            let formProduct = new FormData();
            formProduct.append('action', 'add_one_product');
            formProduct.append('product_id', ID);
            formProduct.append('quantity', store.state.productCount ?  store.state.productCount : 1);
                        
            let fetchData = {
                method: "POST",
                body: formProduct
            };
            let response = await fetch(wc_add_to_cart_params.ajax_url, fetchData);
            let jsonResponse = await response.json();
            if (jsonResponse.error != 'undefined' && jsonResponse.error) {
                console.log(jsonResponse.error);
            } else if (jsonResponse.success) {
                location = SITEDATA.url + "/cart/";
            }
            this.adding = false;
        },
        
    },
});