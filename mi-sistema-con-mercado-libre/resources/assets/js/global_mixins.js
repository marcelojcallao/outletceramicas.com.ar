import Vue from 'vue';

import auth from './mixins/auth';
Vue.mixin(auth);

import sleep_mixin from './mixins/sleep-mixin';
Vue.mixin(sleep_mixin);

import toast_mixin from './mixins/toast-mixin';
Vue.mixin(toast_mixin);