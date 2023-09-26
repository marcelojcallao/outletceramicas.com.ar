
require('./bootstrap_client');

window.Vue = require('vue');

import Vue from 'vue';

import VueCurrencyFilter from 'vue-currency-filter';

Vue.use(VueCurrencyFilter, {
	symbol: '$',
	thousandsSeparator: '.',
	fractionCount: 2,
	fractionSeparator: ',',
	symbolPosition: 'front',
	symbolSpacing: true
});

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)

Vue.component('categories', require('./new-template/categories').default);
Vue.component('categories_accordion', require('./new-template/categories-accordion').default);
Vue.component('info_search_quantity', require('./new-template/info_search_quantity').default);
Vue.component('mobile_menu', require('./new-template/mobile_menu').default);
Vue.component('navigator', require('./new-template/navigator').default);
Vue.component('products', require('./new-template/products').default);
Vue.component('search_product', require('./new-template/search-product').default);
Vue.component('single_product', require('./new-template/single_product').default);
Vue.component('sort_products', require('./new-template/sort_products').default);
Vue.component('top_products', require('./new-template/top_products').default);
Vue.component('map_location', require('./new-template/map-Location').default);
Vue.component('contact_form', require('./new-template/contact-form').default);

import store from "./vuex/client-store";

const app = new Vue({
	el: '#page',
	store,

	mounted(){
	}
});
