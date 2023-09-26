<template>
   <tr>
        <td class="text-center">{{index + 1 }}</td>
        <td class="text-center"><input type="checkbox" v-model="enabledPriceListToProduct" /></td>
        <td>{{price_list.name }}</td>
        <td class="text-center">
            <input
                :disabled="! enabledPriceListToProduct"
                class="form-control"
                type="text"
                v-model="benefit"
                @focus="$event.target.select()"
            >
        </td>
        <td class="text-center">
            <input
                :disabled="! enabledPriceListToProduct"
                class="form-control"
                type="text"
                v-model="costo"
                @focus="$event.target.select()"
            >
        </td>
        <td class="text-right">
            {{Importe | currency }}
        </td>
        <td class="text-right">
            {{Ganancia | currency }}
        </td>
    </tr>
</template>

<script>
import {mapGetters} from 'vuex';
export default {

    props : ['index', 'price_list'],

    data(){
        return {
            importe : 0,
            enabledPriceList : false
        }
    },

    computed : {

        ...mapGetters([
            'PriceProductGetter'
        ]),

        costo : {
            get(){
                if (this.PriceProductGetter[this.index]) {

                    return this.PriceProductGetter[this.index].price;
                }
            },

            set(value){

                const payload = {
                    index : this.index,
                    price_list_id : this.price_list.id,
                    price : value
                }
                this.$store.commit('NEW_PRODUCT_SET_PRICE', payload);
            }
        },
        benefit : {
            get(){
                if (this.PriceProductGetter[this.index]) {

                    return this.PriceProductGetter[this.index].benefit;
                }
            },

            set(value){

                const payload = {
                    index : this.index,
                    price_list_id : this.price_list.id,
                    benefit : value
                }
                this.$store.commit('NEW_PRODUCT_SET_PRICE_BY_BENEFIT', payload);
            }
        },

        enabledPriceListToProduct : {
            get(){
                if (this.PriceProductGetter[this.index]) {

                    return this.PriceProductGetter[this.index].enabledPriceListToProduct;
                }
            },
            set(value){

                const payload = {
                    index : this.index,
                    price_list_id : this.price_list.id,
                    enabledPriceListToProduct : value
                }
                this.$store.commit('NEW_PRODUCT_SET_ENABLE_PRICE_LIST', payload);
            }
        },

        Importe(){

            if (this.PriceProductGetter[this.index] && this.PriceProductGetter[this.index].import) {
                return this.PriceProductGetter[this.index].import;
            }

            return 0;
        },

        Ganancia(){
            //price en este caso es el costo

            if (this.PriceProductGetter[this.index] && this.PriceProductGetter[this.index].price) {
                return this.PriceProductGetter[this.index].import - this.PriceProductGetter[this.index].price;
            }

            return 0;
        },

    },

}
</script>

<style scoped>
    td button.btn-outline-danger{
        margin-right: .8rem;
    }
    td button.btn-outline-success{
        margin-left: .8rem;
    }
    .prev_price {
        background-color: #ffcbcb;
    }
    .actual_price{
        background-color: #eaf6f6;
    }
	input {
		text-align: right;
	}
</style>
