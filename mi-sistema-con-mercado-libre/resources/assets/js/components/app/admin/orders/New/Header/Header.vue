<template>
    <div>
        <div class="first-container">
            <div class="item-1">
                <div >
                    <customer-search />
                    <p class="has-error" v-if="Object.keys(CustomerValue).length === 0">El campo Cliente es requerido</p>
                </div>
            </div>
            <div class="item-2">
                <pedidoType />
            </div>
            <div class="item-3">
                <div :class="{'has-error' : OrderErrors && OrderErrors.hasOwnProperty('order.customer')}">
                    <date_order title="Fecha del Pedido" />
                    <p v-if="OrderErrors && OrderErrors.hasOwnProperty('order.customer')">{{OrderErrors['order.customer'][0]}}</p>
                </div>
            </div>
        </div>
        <div class="second-container">
            <div class="item-4"></div>
            <div class="item-5"></div>
            <div class="item-6"></div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import pedidoType from "./pedidoType.vue";
import date_order from './date-picker-order.vue';
import CustomerSearch from '../../../customer/CustomerSearch.vue';
export default {
    
    components : {
        CustomerSearch, 
        date_order,
        pedidoType
    },

    computed : {

        ...mapGetters([
            'NewCustomerDniGetter',
            'OrderErrors',
            'CustomerValue'
        ]),

        customer_dni : {
            get(){
                return this.NewCustomerDniGetter;
            },
            set(value){
                this.$store.commit('NEW_CUSTOMER_SET_DNI', value);
            }
        }
    }
}
</script>
<style scoped>
    .first-container, .second-container {
        display: flex;
        justify-content: space-between;
    }
    .second-container{
        margin-top: 1rem;
    }
    .my-date-component {
        margin-left: auto;
    }
    .my-button-search{
        margin-top: 2.3rem;
        margin-left: 1.7rem;
    }
    .item-2{
        padding-top : 2.3rem;
        width: 30%;
    }
    .item-3{
         text-align: right;
    }
    .item-4{

        display: flex;
    }
    .item-5{
        width: 10rem;
        color: #434343;
    }
    .second-container div[class*=item-]{
        vertical-align: middle;
    }
    #dni{
        margin-right: 2rem;
    }

    .has-error{
        color: red;
        border-color: red;
        font-weight: bold;
    }
    
</style>