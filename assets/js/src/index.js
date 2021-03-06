import "babel-polyfill";
import Vue from "vue";
import Vuex from "vuex";
import { mapState, mapGetters, mapMutations } from "vuex";
Vue.use(Vuex);
Vue.use(mapState);
Vue.use(mapGetters);
Vue.use(mapMutations);
import store from "./store";

import contactsMap from "./contacts-map";

document.addEventListener("DOMContentLoaded", () => {
    contactsMap.init();

    //TODO: переписать
    $(".btn-hamburger").click(function() {
        $(".mobile-menu").addClass("active");
    });
    $(".btn-hamburger.active").click(function() {
        $(".mobile-menu").removeClass("active");
    });

    $(".activity-block-slick").slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 3,
        arrows: false,
        dots: true,
        responsive: [
            {
                breakpoint: 950,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    infinite: true,
                    arrows: false,
                    dots: true,
                },
            },
            {
                breakpoint: 640,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    infinite: true,
                    arrows: false,
                    dots: true,
                },
            },
        ],
    });

    $(".popular-slick").slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 3,
        arrows: true,
        dots: true,
        responsive: [
            {
                breakpoint: 1350,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    arrows: false,
                    dots: true,
                },
            },
            {
                breakpoint: 950,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    infinite: true,
                    arrows: false,
                    dots: true,
                },
            },
            {
                breakpoint: 640,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    infinite: true,
                    arrows: false,
                    dots: true,
                },
            },
        ],
    });

    $(".menu-slick").slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        speed: 500,
        fade: true,
        cssEase: "linear",
    });

    $(".choice-button").click(function(event) {
        event.preventDefault();
        $(".choice-button").removeClass("active");
        $(this).addClass("active");

        var id = $(this).attr("data-id");
        if (id) {
            $(".single_menu-tabs-content:visible").removeClass("visible");
            $(".single_menu-tabs")
                .find("#" + id)
                .addClass("visible");
        }
    });
});

import Vuelidate from "vuelidate";
Vue.use(Vuelidate);

import MapHall from "./components/MapHall.vue";
Vue.component("map-hall", MapHall);

import MapHall2 from "./components/MapHall2.vue";
Vue.component("map-hall-2", MapHall2);

import RegistrationForm from "./components/RegistrationForm.vue";
Vue.component("registration-form", RegistrationForm);

import ChangePassword from "./components/ChangePassword.vue";
Vue.component("change-password", ChangePassword);

import PageLoginForm from "./components/PageLoginForm.vue";
Vue.component("page-login-form", PageLoginForm);

import callbackModal from "./components/callbackModal.vue";
Vue.component("callback-modal", callbackModal);

import Reservation from "./components/Reservation.vue";
Vue.component("reservation", Reservation);

import QuantityButton from "./components/QuantityButton.vue";
Vue.component("quantity-button", QuantityButton);

import Tabs from "./components/Tabs.vue";
Vue.component("tabs", Tabs);

import Tab from "./components/Tab.vue";
Vue.component("tab", Tab);

import TabsTable from "./components/TabsTable.vue";
Vue.component("tabs-table", TabsTable);

import TabTable from "./components/TabTable.vue";
Vue.component("tab-table", TabTable);

import MaskedInput from "vue-masked-input";

import numeral from "numeral";
numeral.register("locale", "ru", {
    delimiters: {
        thousands: " ",
        decimal: ",",
    },
    abbreviations: {
        thousand: "тыс.",
        million: "млн.",
        billion: "млрд.",
        trillion: "трлн.",
    },
    ordinal: function() {
        return ".";
    },
    currency: {
        symbol: "руб.",
    },
});

numeral.locale("ru");

Vue.filter("formatNumber", function(value) {
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
        user: SITEDATA.user_data,
        errors: [],
        cart: {},
        checkout: {
            submitted: false,
            submitStatus: "",
            first_name: "",
            phone: "",
            email: "",
            address: "",
            city: "",
            street: "",
            house: "",
            apartment: "",
            intercom: "",
            porch: "",
            floor: "",
            comment: "",
            payment: "cod",
        },
    },
    validations: {
        checkout: {
            phone: {
                required,
            },
            city: {
                required,
            },
            street: {
                required,
            },
            house: {
                required,
            },
            email: {
                required,
                email,
            },
        },
    },
    components: {
        "masked-input": MaskedInput,
    },
    computed: {
        ...mapState(["pageNum", "cartSubtotal"]),
        delivery: {
            get() {
                return this.$store.state.delivery;
            },
            set(value) {
                this.$store.commit("updateDelivery", value);
            },
        },
        cartTotal() {
            let subtototal = this.cartSubtotal
            subtototal = subtototal + this.shippingPrice
            return subtototal
        },
        shippingPrice() {
            let shippingPrice = 0;
            this.areas.forEach(area => {
                if(area.name === this.checkout.city){
                    shippingPrice = area.price
                }
                area.subareas.forEach(subarea => {
                    if(subarea.name === this.checkout.city){
                        shippingPrice = subarea.price
                    }
                })
            });
            return parseInt(shippingPrice)
        },
        areas() {
            return this.$store.getters["areas"]
        },
    },
    async mounted() {

        this.checkout.first_name = this.user && this.user.first_name? this.user.first_name: '';
        this.checkout.phone =this.user && this.user.phone ? this.user.phone : '';
        this.checkout.city = this.user && this.user.city ? this.user.city : '';
        this.checkout.street = this.user && this.user.street ? this.user.street: '';
        this.checkout.house = this.user && this.user.house ? this.user.house : '';
        this.checkout.email = this.user && this.user.email ? this.user.email : '';
        this.checkout.apartment = this.user && this.user.apartment ? this.user.apartment : '';
        this.checkout.intercom = this.user && this.user.intercom ? this.user.intercom : '';
        this.checkout.porch = this.user && this.user.porch ? this.user.porch : '';
        this.checkout.floor = this.user && this.user.floor ? this.user.floor : '';

        if (this.$store.getters["areas"].length === 0) {
            await this.$store.dispatch("fetchAreas");
        }
    },
    methods: {
        async addCart(ID) {
            // TODO: убрать jquery
            let targetButton = event.target;
            var cart = $(".main-header-cart");
            let imgtodrag = $(event.target)
                .parents(".popular-item")
                .find(".popular-item-img img")
                .eq(0);
            if (imgtodrag.length !== 0) {
                var imgclone = imgtodrag
                    .clone()
                    .offset({
                        top: imgtodrag.offset().top,
                        left: imgtodrag.offset().left,
                    })
                    .css({
                        opacity: "0.5",
                        position: "absolute",
                        height: "150px",
                        width: "150px",
                        "z-index": "100",
                    })
                    .appendTo($("body"))
                    .animate(
                        {
                            top: cart.offset().top + 10,
                            left: cart.offset().left + 10,
                            width: 75,
                            height: 75,
                        },
                        1000
                    );

                imgclone.animate(
                    {
                        width: 0,
                        height: 0,
                    },
                    function() {
                        $(this).detach();
                    }
                );
            }

            this.adding = true;
            let formProduct = new FormData();
            formProduct.append("action", "add_one_product");
            formProduct.append("product_id", ID);
            formProduct.append(
                "quantity",
                store.state.productCount ? store.state.productCount : 1
            );

            let fetchData = {
                method: "POST",
                body: formProduct,
            };
            let response = await fetch(
                wc_add_to_cart_params.ajax_url,
                fetchData
            );
            let jsonResponse = await response.json();
            if (jsonResponse.error != "undefined" && jsonResponse.error) {
                console.log(jsonResponse.error);
            } else if (jsonResponse.success) {
                // location = SITEDATA.url + "/cart/";
            }
            if (jsonResponse.fragments) {
                // TODO: переписать
                Array.from(jsonResponse.fragments).forEach((element) => {
                    element.classList.add("updating");
                });

                $.each(jsonResponse.fragments, function(key, value) {
                    $(key).replaceWith(value);
                    $(key)
                        .stop(true)
                        .css("opacity", "1");
                });
            }
            this.adding = false;
        },

        async orderProducts() {
            // TODO: валидация без popup
            this.errors = [];

            if (!this.checkout.first_name) {
                this.errors.push("Требуется указать имя.");
            }

            if (!this.checkout.phone) {
                this.errors.push("Укажите номер телефона.");
            } else if (!this.validRussianPhone(this.checkout.phone)) {
                this.errors.push("Укажите корректный номер телефона.");
            }

            if (this.delivery == "courier") {
                if (!this.checkout.city) {
                    this.errors.push("Требуется указать город.");
                }
                if (!this.checkout.street) {
                    this.errors.push("Требуется указать адрес.");
                }

                if (!this.checkout.house) {
                    this.errors.push("Требуется указать дом.");
                }
            }

            setTimeout(() => {
                this.errors = [];
            }, 4000);

            if (!this.errors.length) {
                let bodyFormData = new FormData();
                bodyFormData.append("payment_method", this.checkout.payment);
                bodyFormData.append("first_name", this.checkout.first_name);
                bodyFormData.append("email", this.checkout.email);
                bodyFormData.append("phone", this.checkout.phone);
                bodyFormData.append("address", this.checkout.address);
                bodyFormData.append("city", this.checkout.city);
                bodyFormData.append("street", this.checkout.street);
                bodyFormData.append("house", this.checkout.house);
                bodyFormData.append("apartment", this.checkout.apartment);
                bodyFormData.append("intercom", this.checkout.intercom);
                bodyFormData.append("porch", this.checkout.porch);
                bodyFormData.append("floor", this.checkout.floor);
                bodyFormData.append("comment", this.checkout.comment);
                bodyFormData.append("payment", this.checkout.payment);
                bodyFormData.append("delivery", this.delivery);

                let fetchData = {
                    method: "POST",
                    body: bodyFormData,
                };

                this.checkout.submitStatus = "PENDING";
                let response = await fetch(
                    `${SITEDATA.ajax_url}?action=create_order`,
                    fetchData
                );
                let jsonResponse = await response.json();
                if (jsonResponse.data.result == "fail") {
                    this.checkout.submitStatus = "ERROR";
                    setTimeout(() => {
                        this.checkout.submitStatus = "";
                    }, 1000);
                    this.showModal("modal-window--error-checkout");
                } else if (jsonResponse.data.result == "success") {
                    app.clearOrderForm();
                    // this.showModal("modal-window--thanks");
                    this.checkout.submitStatus = "SUCCESS";
                    setTimeout(() => {
                        this.checkout.submitStatus = "";
                    }, 1000);
                    document.location = jsonResponse.data.redirect;
                }
            }
        },

        onCloseErrors() {
            this.errors = [];
        },

        clearOrderForm() {
            this.checkout.first_name = "";
            this.checkout.email = "";
            this.checkout.phone = "";
            this.checkout.address = "";
            this.checkout.city = "";
            this.checkout.street = "";
            this.checkout.house = "";
            this.checkout.apartment = "";
            this.checkout.intercom = "";
            this.checkout.porch = "";
            this.checkout.floor = "";
            this.checkout.comment = "";
            this.checkout.comment = "";
        },

        validEmail: function(email) {
            const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },

        validRussianPhone: function(phone) {
            const re = /^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/;
            return re.test(phone);
        },
    },
});
$(document).ready(function() {
    $(".photo-gallery_content-inner").masonry({
        // options
        itemSelector: ".gallery-item",
        percentPosition: true,
        columnWidth: ".gallery-item",
    });
});
