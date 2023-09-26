<template>
    <div id="container">
        <div class="add-product-form">
            <h3 class="title">Agregar un producto al pedido</h3>
                <AddProductForm />
            <div class="buttons">
                <button 
                    :disabled="$v.$invalid"
                    class="btn btn-primary"
                    :class="{'btn btn-primary spinner spinner-inverse spinner-sm' : spinner}"
                    @click="addProductToPedido"
                >
                    Guardar
                </button>
                <button 
                    class="btn btn-default"
                    @click="closePanel"
                >
                    Cerrar
                </button>
            </div>
            <p>{{ (CheckStockProductGetter) ? 'Stock disponible: ' + CheckStockProductGetter.stock + '.': ''}}</p>
            <p>{{ (CheckStockProductGetter) ? 'Stock crítico: ' + CheckStockProductGetter.critical_stock : ''}}</p>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import { required, between } from 'vuelidate/lib/validators';
import AddProductForm from './../../admin/orders/Edit/components/add-product-form.vue';
export default {

    components: {
        AddProductForm
    },

    data() {
        return {
            spinner: false
        }
    },

    methods : {

        closePanel() {

            this.$store.dispatch('orderUpdateNewProductSetInitialStatus');

            this.$emit('closePanel', {});
        },

        async addProductToPedido(){

            this.spinner = true;
            this.$store.dispatch('orderUpdateAddItemToOrder', this.OrderUpdateNewProductGetter);
            this.spinner = false;
            this.closePanel();
           
        },

    },

    computed: {
        ...mapGetters([
            'NewCustomerCompleteData',
            'OrderUpdateNewProductGetter',
            'PedidoListChildRowReactivityData',
            'OrderUpdateNewProductProductName',
            'OrderUpdateNewProductPriceList',
            'OrderUpdateNewProductQuantity',
            'CheckStockProductGetter'
        ])
    },

    validations(){
        
        return {

            OrderUpdateNewProductProductName: {
                required
            },
            OrderUpdateNewProductPriceList: {
                required
            },
            OrderUpdateNewProductQuantity: {
                between: between(1, 9000000)
            }

        }
    },

}
</script>

<style scoped>
    .add-product-form{
        padding: 4rem;
    }
    .buttons{
        display: flex;
        justify-content: center;
        width: 100%;
    }
</style>