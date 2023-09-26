<template>
	<div >
		<h4 v-if="Movements" class="text-left">Movimiento de Stock</h4>
		<div v-if="Movements" id="stock--movements">
			<table>
				<thead>
					<tr class="sticky">
						<th>#</th>
						<th>Movimiento</th>
						<th>Fecha</th>
						<th>Detalle</th>
						<th>Stock</th>
						<th>Usuario</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(movement, index) in Movements" :key="index">
						<td>{{ index + 1 }}</td>
						<td>{{ movement.name }}</td>
						<td>{{ movement.date }}</td>
						<td>{{ Movement(movement) }}</td>
						<td>{{ movement.actual_stock }}</td>
						<td>{{ movement.user }}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</template>

<script>
	import { Event } from 'vue-tables-2';
	export default {

		props: {
			data: {
				type: Object,
				required: true
			},
		},

		data(){
			return {
				movements: null
			}
		},

		methods: {

			Movement(movement){

				let text = 'Cantidad';

				if(movement.name === 'AGREGA STOCK'){
					text = 'Ingresan'
				}
				if(movement.name === 'DESCUENTA STOCK'){
					text = 'Cantidad vendida'
				}

				if (movement.data.hasOwnProperty('mts_totales')) {
					return `${text}: ${movement.data.cantidad} - Mts. por unidad: ${movement.data.mts_by_unity.toFixed(2)} - Mts. totales: ${movement.data.mts_totales.toFixed(2)}`
				}

				return `${text}: ${movement.data.cantidad}`;
			}
		},

		computed: {

			Movements(){
				return this.movements;
			},

		},

		async beforeMount(){

			const { data } = await this.$store.dispatch('getStockMovements', this.data.id);

			if ( data ) {
				this.movements = data
			}
		},

		mounted(){

			Event.$emit('open_product_child_row', 'pp');

			console.log("🚀 ~ file: ChildRow.vue:11 ~ mounted ~ mounted:", this.data)

		}
	}
</script>

<style lang="scss" scoped>
	#stock--movements{

		padding: 1rem 3rem;
		padding-top: 0;
		max-height: 31rem;
		overflow-y: auto;
	}
	#stock--movements table{
		width: 100%;
	}
	#stock--movements table thead tr th {
		background-color: darkolivegreen;
		color: beige;
		font-weight: bold;
		height: 34px;
	}
	#stock--movements table thead tr th:nth-child(1){
		width: 5%;
	}
	#stock--movements table thead tr th:nth-child(2),
	#stock--movements table thead tr th:nth-child(4){
		width: 25%;
	}
	#stock--movements table thead tr th:nth-child(3){
		width: 15%;
	}
	#stock--movements table thead tr th:nth-child(5){
		width: 10%;
	}
	#stock--movements table thead tr th:nth-child(5){
		width: 20%;
	}

	#stock--movements table tbody tr {
		height: 25px;
	}
	#stock--movements table tbody tr td:nth-child(4){
		text-align: left;
	}
	#stock--movements table tbody tr td:nth-child(5){
		text-align: right;
	}

	#stock--movements table tbody tr td:nth-child(1),
	#stock--movements table tbody tr td:nth-child(3){
		text-align: center;
	}
	#stock--movements table tbody tr td:nth-child(2){
		text-align: left;
	}
	#stock--movements table tbody tr {
		padding: 10px 3px;
	}
	#stock--movements .sticky {
		position: sticky;
		top: 0;
	}

</style>
