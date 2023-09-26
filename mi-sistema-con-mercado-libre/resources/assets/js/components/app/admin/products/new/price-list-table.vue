<template>
    <div style="min-height:300px">
        <div class="price-base">
            <p>Lista de precios</p>
            <div class="costo">
				<p>Aumentar valor del beneficio (%)</p>
				<input
					id="costo-step"
                    type="text"
                    class="form-control"
                    @focus="$event.target.select()"
                    v-model="benefit_step"
				>
					<button class="btn btn-outline-warning btn-icon sq-32" type="button" @click="upBenefit">
						<span class="icon icon-plus"></span>
					</button>
					<button class="btn btn-outline-warning btn-icon sq-32" type="button" @click="downBenefit">
						<span class="icon icon-minus"></span>
					</button>
                <p>Aumentar valor del costo (%)</p>
                <input
					id="costo-step"
                    type="text"
                    class="form-control"
                    @focus="$event.target.select()"
                    v-model="costo_step"
				>
					<button class="btn btn-outline-primary btn-icon sq-32" type="button" @click="upCosto">
						<span class="icon icon-plus"></span>
					</button>
					<button class="btn btn-outline-primary btn-icon sq-32" type="button" @click="downCosto">
						<span class="icon icon-minus"></span>
					</button>


                <p>Precio Base</p>
                <input
                    type="text"
                    class="form-control"
                    @focus="$event.target.select()"
                    v-model="price_base">
            </div>
        </div>
        <table class="table table-hover table-bordered" >
            <thead>
                <tr>
                    <th class="text-center" width="5%">#</th>
                    <th class="text-center" width="10%">Habilitar para este producto</th>
                    <th class="text-center" width="45%">Lista de precio</th>
                    <th class="text-center" width="10%">Beneficio (%)</th>
                    <th class="text-center" width="10%">Costo</th>
                    <th class="text-center" width="10%">Importe a publicar</th>
                    <th class="text-center" width="10%">Ganancia</th>
                </tr>
            </thead>
            <tbody>
                <price_list_row v-for="(price_list, index) in PriceProductGetter" :key="index" :index="index" :price_list="price_list" >

                </price_list_row>
            </tbody>
        </table>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import collect from 'collect.js';
import price_list_row from './price-list-row';
import sleep_mixin from './../../../../../mixins/sleep-mixin';
    export default {

        components : {price_list_row},

        mixins : [sleep_mixin],

		data(){
			return {
				costo_step: 10,
				increment_percentage_costo: 0,
				benefit_step: 10,
				increment_percentage_benefit: 0,
				clonePriceList: null
			}
		},

		methods: {

			applyCosto(percentage){

				this.ClonePriceListsGetter.forEach((price_list, index) => {

					const importe = price_list.price * parseFloat(percentage /100) + price_list.price

					const payload = {
						index : index,
						price_list_id : price_list.id,
						price : importe.toFixed(2)
					}

					this.$store.commit('NEW_PRODUCT_SET_PRICE', payload);
				});
			},

			upCosto(){

				this.increment_percentage_costo = parseFloat(this.costo_step) + parseFloat(this.increment_percentage_costo);

				this.applyCosto(this.increment_percentage_costo);

			},

			downCosto(){

				this.increment_percentage_costo = parseFloat(this.increment_percentage_costo) - parseFloat(this.costo_step);

				this.applyCosto(this.increment_percentage_costo);

			},

			applyBenefit(percentage){

				this.ClonePriceListsGetter.forEach((price_list, index) => {

					const value = price_list.benefit * parseFloat(percentage /100) + price_list.benefit

					const payload = {
						index : index,
						price_list_id : price_list.id,
						benefit : value.toFixed(2)
					}
					this.$store.commit('NEW_PRODUCT_SET_PRICE_BY_BENEFIT', payload);
				});
			},

			upBenefit(){

				this.increment_percentage_benefit = parseFloat(this.increment_percentage_benefit) + parseFloat(this.benefit_step);

				this.applyBenefit(this.increment_percentage_benefit);

			},

			downBenefit(){

				this.increment_percentage_benefit = parseFloat(this.increment_percentage_benefit) - parseFloat(this.benefit_step);

				this.applyBenefit(this.increment_percentage_benefit);

			},
		},

		async mounted(){

			await this.sleep(2000);

			const clonePriceList = JSON.parse(JSON.stringify(this.PriceProductGetter));
			this.$store.commit('SET_CLONE_PRICE_LISTS', clonePriceList);
		},

        computed : {
            ...mapGetters([
                'ProductGetter',
                'PriceProductGetter',
				'ClonePriceListsGetter'
            ]),

            price_base : {
                get(){
                    return this.ProductGetter.price_base;
                },
                set(value){
                    this.$store.commit('NEW_PRODUCT_SET_PRICE_BASE', value);
                }
            }
        },

    }
</script>

<style scoped>
    table caption{
        font-weight: bold;
        font-size: 1.5rem;
        color: rgba(0,0,0,.5);
    }
    .price-base {
        display: flex;
        justify-content: space-between;
    }
	.costo{
		width: 60%;
        display: flex;
        justify-content: flex-end;
    }
	.costo input {
		width: 8rem;
		text-align: right;
	}
	.costo p {
		padding-top: .8rem;
		margin-right: 1rem;
		margin-left: 1rem;
	}
	.costo #costo-step {
		width: 4.5rem;
		text-align: center;
	}
</style>
