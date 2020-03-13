import 'babel-polyfill';
import Vue from 'vue';
import Vuex from 'vuex';
import {mapState, mapGetters} from 'vuex';
Vue.use(Vuex);
Vue.use(mapState);
Vue.use(mapGetters);
import store from './store';

import contactsMap from './contacts-map';

document.addEventListener('DOMContentLoaded', () => {
    contactsMap.init();


    //TODO: переписать
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
});

import Vuelidate from 'vuelidate';
Vue.use(Vuelidate);

import MapHall from './components/MapHall.vue';
Vue.component('map-hall', MapHall);

import RegistrationForm from './components/RegistrationForm.vue';
Vue.component('registration-form', RegistrationForm);

import EditAccount from './components/EditAccount.vue';
Vue.component('edit-account-form', EditAccount);

import ChangePassword from './components/ChangePassword.vue';
Vue.component('change-password', ChangePassword);

import PageLoginForm from './components/PageLoginForm.vue';
Vue.component('page-login-form', PageLoginForm);

import callbackModal from './components/callbackModal.vue';
Vue.component('callback-modal', callbackModal);

import Reservation from './components/Reservation.vue';
Vue.component('reservation', Reservation);

import QuantityButton from './components/QuantityButton.vue';
Vue.component('quantity-button', QuantityButton);

import Tabs from './components/Tabs.vue';
Vue.component('tabs', Tabs);

import Tab from './components/Tab.vue';
Vue.component('tab', Tab);

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

import { required, email, minLength } from "vuelidate/lib/validators";
import { modal } from "./components/mixins/modal";

const app = new Vue({
    el: "#app",
    store,
    delimiters: ["((", "))"],
    mixins: [modal],
    data: {
        errors: [],
        cart:{
            delivery: 'courier',
        },
        checkout: {
            submitted: false,
            submitStatus: '',
            first_name: '',
            phone: '',
            email: '',
            address: '',
            city: '',
            street: '',
            house: '',
            apartment: '',
            intercom: '',
            porch: '',
            floor: '',
            comment: '',
            payment: 'bank_card_payment',
        }
    },
    validations: {
        checkout: {
            phone: {
                required
            },
            city: {
                required
            },
            street: {
                required
            },
            house: {
                required
            }   
        }
    },
    components: {
        'masked-input': MaskedInput,
    },
    computed: {
        ...mapState([
            'pageNum',
            'cartSubtotal',
        ]),
        cartTotal: function () {
            let subtototal = this.cartSubtotal;
            subtototal = subtototal + this.shippingPrice;
            return subtototal;
        },
        shippingPrice: function(){
            let shippingPrice = 0;
            return shippingPrice;
        },
    },
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
                // location = SITEDATA.url + "/cart/";
            }
            if ( jsonResponse.fragments ) {
                // TODO: переписать
                Array.from(jsonResponse.fragments).forEach(element => {
                    element.classList.add('updating');
                });

                $.each( jsonResponse.fragments, function( key, value ) {
                    $( key ).replaceWith( value );
                    $( key ).stop( true ).css( 'opacity', '1' );
                });
            }
            this.adding = false;
        },

        async orderProducts() {
            // TODO: валидация без popup
            this.errors = [];
            if (!this.checkout.first_name) {
                this.errors.push('Требуется указать имя.');
            }

            if (!this.checkout.phone) {
                this.errors.push("Укажите номер телефона.");
            } else if (!this.validRussianPhone(this.checkout.phone)) {
                this.errors.push("Укажите корректный номер телефона.");
            }

            if (!this.checkout.city) {
                this.errors.push('Требуется указать город.');
            }
            if (!this.checkout.street) {
                this.errors.push('Требуется указать адрес.');
            }

            if (!this.checkout.house) {
                this.errors.push('Требуется указать дом.');
            }

            setTimeout(()=>{
                this.errors = [];
            }, 4000);

            if (!this.errors.length) {
                let bodyFormData = new FormData();
                // bodyFormData.append('payment_method', this.checkout.payment);
                // TODO: добавить методы оплаты 
                bodyFormData.append('payment_method', 'cod');
                bodyFormData.append('billing_first_name', this.checkout.first_name);
                bodyFormData.append('billing_email', this.checkout.email);
                bodyFormData.append('billing_phone', this.checkout.phone);
                bodyFormData.append('billing_city', this.checkout.city);
                bodyFormData.append('billing_street', this.checkout.street);
                bodyFormData.append('billing_house', this.checkout.house);
                
                let fetchData = {
                    method: "POST",
                    body: bodyFormData
                };
                let response = await fetch(`${SITEDATA.ajax_url}?action=create_order`, fetchData);
                let jsonResponse = await response.json();
                if (jsonResponse.data.result == 'fail') {
                    console.log(jsonResponse);
                    this.showModal("modal-window--error-checkout");
                } else if(jsonResponse.data.result == 'success'){
                    app.clearOrderForm();
                    this.showModal("modal-window--thanks");
                    document.location = jsonResponse.data.redirect;
                }
            }

        },

        onCloseErrors() {
            this.errors = []
        },

        clearOrderForm() {
            this.checkout.first_name = '';
            this.checkout.email = '';
            this.checkout.phone = '';
            this.checkout.address = '';
            this.checkout.city = '';
            this.checkout.street = '';
            this.checkout.house = '';
            this.checkout.apartment = '';
            this.checkout.intercom = '';
            this.checkout.porch = '';
            this.checkout.floor = '';
            this.checkout.comment = '';
            this.checkout.comment = '';
        },
        
        validEmail: function (email) {
            const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },

        validRussianPhone: function (phone) {
            const re = /^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/;
            return re.test(phone);
        },
        
    },
});