<template>
<div>
    <table id="product-list" class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>P.Unit.</th>
                <th>Neto</th>
                <th>Descuento</th>
                <th>Total</th>
                <th>Borrar</th>
            </tr>
        </thead>
        <tbody>
            <template v-if="PedidoListChildRowReactivityData && PedidoListChildRowReactivityData.items">
                <tr v-for="(item, index) in PedidoListChildRowReactivityData.items" :key="index" >
                    <td class="text-center" >{{index + 1}}</td>
                    <td class="text-left" >{{
                            (item.isCHP)
                                ? `${item.product_name} - ${(parseFloat(item.real_mts) / parseFloat(item.quantity)).toFixed(2)} mts. x unid.`
                                : item.product_name
                        }}

                    </td>
                    <td class="text-center"  ><small>{{
                        (item.isCHP)
                            ? `${item.quantity} unid. x ${item.mts_by_unity} mts. + ${item.rounded_mts/item.quantity} mts. redondeo por unid.  = ${item.mts_to_invoiced} mts.`
                            : item.quantity
                    }}</small></td>
                    <!-- <td class="text-right" >{{(item.isCHP) ? item.mts_to_invoiced : item.quantity | currency}}</td> -->
                    <td class="text-right" >{{item.unit_price | currency}}</td>
                    <td class="text-right" >{{item.neto_import | currency}}</td>
                    <td class="text-right" >{{item.discount_import | currency}}</td>
                    <td class="text-right" >{{item.neto_import - item.discount_import  | currency}}</td>
                    <td class="text-center" ><PedidoClienteProductsDeleteProduct :index="index" :data="data" :tooltip="'Eliminar producto'" /> </td>
                </tr>
            </template>
        </tbody>
        <!-- <tfoot>
            {{pp.www}}
        </tfoot> -->
    </table>
    <div class="flex">
            <div class="item-1"><span>Neto:</span> {{Neto | currency}}</div>
            <div class="item-2"><span>Descuento por cantidad:</span> {{Discount | currency}}</div>
            <div class="item-2"><span>{{ ( DiscountByCash < 0 ) ? 'Descuento por pago en efectivo' : 'Adicional' }}</span> {{ ( DiscountByCash < 0 ) ? (DiscountByCash * -1) : DiscountByCash | currency}}</div>
            <div class="item-2" v-if="data.shipping"><span>Ad. envío:</span> {{data.shipping.value | currency}}</div>
            <div class="item-2"><span>Total:</span> {{Total | currency}}</div>
    </div>
</div>
</template>

<script>
import collect from 'collect.js';
import { mapGetters } from 'vuex';
import { Event } from 'vue-tables-2';
import PedidoClienteProductsDeleteProduct from './PedidoClienteProductsDeleteProduct.vue'
export default {

    props : ['data'],

    components : {PedidoClienteProductsDeleteProduct},

    computed : {

        ...mapGetters([
            'PedidoListChildRowReactivityData'
        ]),

        Neto(){

            return this.PedidoListChildRowReactivityData.items.reduce(( acc, item ) => {
                return acc + ( item.neto_import );
            }, 0);
        },

        Iva(){
            return collect(this.PedidoListChildRowReactivityData.items).sum('iva_import');
        },

        DiscountByCash(){

            if (this.PedidoListChildRowReactivityData && this.PedidoListChildRowReactivityData.payment_data.value && this.PedidoListChildRowReactivityData.payment_data.value != null) {
                return parseFloat(this.PedidoListChildRowReactivityData.payment_data.value);
            }

            return 0;
        },

        Discount(){

            const discount = this.PedidoListChildRowReactivityData.items.reduce( ( acc, item ) => {
                return acc + item.discount_import;
            }, 0 );

            return discount;
        },

        Shipping(){

            if (this.PedidoListChildRowReactivityData.shipping) {
                return parseFloat(this.PedidoListChildRowReactivityData.shipping.value);
            }

            return 0;
        },

        Total(){

            let discount = 0;

            if (this.DiscountByCash < 0) {
                discount = this.DiscountByCash *-1;
            }else{
                discount = this.DiscountByCash;

            }
            return this.Neto - this.Discount + this.Shipping - discount;
        }

    },

}
</script>

<style scoped>
    .flex{
        display: flex;
        justify-content: space-between;
        background-color: #777b80;
        height: 5rem;
        align-items: center;
        margin-top: 2.5rem;
    }
    div[class*="item"]{
        font-size: 1.5rem;
        padding: 2rem;
        color: white;
    }
    div[class*="item"] span{
        font-weight: bold;
        color: white;
    }
    #product-list thead tr th:nth-child(1),
    #product-list thead tr th:nth-child(2),
    #product-list thead tr th:nth-child(3),
    #product-list thead tr th:nth-child(4),
    #product-list thead tr th:nth-child(5),
    #product-list thead tr th:nth-child(6),
    #product-list thead tr th:nth-child(7),
    #product-list thead tr th:nth-child(8)
    {
        background-color: #343b3dc9;
        color: aliceblue;
        font-weight: bold;
        vertical-align: middle;
        text-transform: uppercase;
        font-size: 14px !important;
        text-align: center;
    }
    #product-list thead tr th:nth-child(1),
    #product-list thead tr th:nth-child(8)
    {
        width: 3%;
    }
    #product-list thead tr th:nth-child(2){
        width: 35%;
    }

    #product-list thead tr th:nth-child(3){
        width: 19%;
    }
    #product-list thead tr th:nth-child(4),
    #product-list thead tr th:nth-child(5),
    #product-list thead tr th:nth-child(6),
    #product-list thead tr th:nth-child(7)
    {
        width: 10%;
    }

</style>
