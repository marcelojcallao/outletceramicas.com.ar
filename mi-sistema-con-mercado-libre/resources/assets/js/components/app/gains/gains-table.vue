<template>
	<div>
		<v-client-table
			ref="gainsTable"
			:columns="columns"
			:data="GainsListGetter"
			:options="options"
		>
		<template slot="id" slot-scope="props">
			<button @click="childRowOpen(props.row.id)">Ver detalle</button>
		</template>
		</v-client-table>
		<div class="text-center">
			<paginate
				:page-count="(GainsPaginationGetter) ? GainsPaginationGetter.last_page : 1"
				:click-handler="loadData"
				:prev-text="'Ant.'"
				:next-text="'Sig.'"
				:container-class="'pagination'"
			>
			</paginate>
		</div>
	</div>
</template>

<script>
import Paginate from 'vuejs-paginate';
import { mapGetters } from "vuex";
import Cost from "./table-components/cost.vue";
import Earning from "./table-components/earning.vue";
import Index from "./table-components/index.vue";
import Neto from "./table-components/neto.vue";
import Quantity from "./table-components/quantity.vue";
import SalePrice from "./table-components/sale-price.vue";
import gainsMixin from '../../../mixins/Gains/gains-mixin';
import ChildRow from './table-components/childRow.vue';

export default {

	name: "GainsTable",

	components: { Paginate },

	mixins: [gainsMixin],

	data() {
		return {
			columns: [
				"id",
				"number",
				"product_name",
				"code",
				"sale_price",
				"quantity",
				"cost",
				"neto",
				"earning",
			],
			options: {
				filterable: false,
				showChildRowToggler: false,
				orderBy: { column: false },
				sortable: [],
				uniqueKey: "id",
				perPage: 31,
				pagination: { dropdown: false },
				headings: {
					id: "ch",
					number: "#",
					product_name: "Producto",
					code: "Código",
					sale_price: "Precio de venta",
					quantity: "Cantidad vendida",
					cost: "Costo",
					neto: "Total ventas",
					earning: "Ganancia",
				},
				columnsClasses: {
					id: "col-md-1 text-center",
					number: "col-md-1 text-center",
					product_name: "col-md-3 text-left",
					code: "col-md-2 text-left",
					sale_price: "col-md-1 text-right",
					quantity: "col-md-1 text-right",
					cost: "col-md-1 text-right",
					neto: "col-md-1 text-right",
					earning: "col-md-1 text-right",
				},
				templates: {
					number: Index,
					sale_price: SalePrice,
					quantity: Quantity,
					cost: Cost,
					neto: Neto,
					earning: Earning,
				},
				childRow: ChildRow
			},
			pageCount: 1,
		};
  	},

	computed: {
		...mapGetters([
			"GainsFromDateGetter",
			"GainsToDateGetter",
			"GainsListGetter",
			"ListProductsGetter",
			"GainsPaginationGetter"
		]),
	},

	methods: {

		childRowOpen(id){
			console.log("🚀 ~ file: gains-table.vue:121 ~ childRowOpen ~ id:", id)
			this.$refs.gainsTable.toggleChildRow(id);
		}
	}
};
</script>

<style  scoped>
.VueTables__child-row-toggler {
        width: 16px;
        height: 16px;
        line-height: 16px;
        display: block;
        margin: auto;
        text-align: center;
    }

    .VueTables__child-row-toggler--closed::before {
       /* font-family: FontAwesome; 
        content: "\f067";
        color :green; */
        content:  "+";
        font-size: 150%
    }

    .VueTables__child-row-toggler--open::before {
        /* font-family: FontAwesome;
        content: "\f111";
        color : red; */
        content: "-";
        font-size: 150%
    }
</style>
