import Vue  from "vue";

import Transitions from 'vue2-transitions';
Vue.use(Transitions);

import Vuelidate from 'vuelidate';
Vue.use(Vuelidate);

import {
	Tabs,
	Tab
} from 'vue-tabs-component';
Vue.component('tabs', Tabs);
Vue.component('tab', Tab);

import ToggleButton from 'vue-js-toggle-button';
Vue.use(ToggleButton);

import VueMomentLib from "vue-moment-lib";
Vue.use(VueMomentLib);

import {
	ClientTable,
	Event
} from 'vue-tables-2';

const VueScrollTo = require('vue-scrollto');
Vue.use(VueScrollTo);

Vue.use(ClientTable, {
	texts: {
		count: "Mostrando {from} a {to} de {count} registros totales|{count} registros|Un registro",
		first: 'Primero',
		last: 'Último',
		filter: "Filtro: ",
		filterPlaceholder: "Buscar",
		limit: "Registros:",
		page: "Página:",
		noResults: "Registros no encontrados",
		filterBy: "Filtrar por {column}",
		loading: 'Cargando...',
		defaultOption: 'Seleccionar {column}',
		columns: 'Columnas'
	},
}, false, 'bootstrap3', 'default');

import VueCurrencyFilter from 'vue-currency-filter';

import BlockUI from 'vue-blockui'
Vue.use(BlockUI)

Vue.use(VueCurrencyFilter, {
	symbol: '$',
	thousandsSeparator: '.',
	fractionCount: 2,
	fractionSeparator: ',',
	symbolPosition: 'front',
	symbolSpacing: true
});

import VTooltip from 'v-tooltip';
Vue.use(VTooltip);

import CKEditor from 'ckeditor4-vue';
Vue.use( CKEditor );

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

import VueDragTree from 'vue-drag-tree';
import 'vue-drag-tree/dist/vue-drag-tree.min.css';
Vue.use(VueDragTree);

import VueSlideoutPanel from 'vue2-slideout-panel';
Vue.use(VueSlideoutPanel);

import VueCurrencyInput from 'vue-currency-input'
Vue.use(VueCurrencyInput)

import { VueQueryPlugin } from '@tanstack/vue-query';
Vue.use(VueQueryPlugin)
