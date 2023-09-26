<template >
    <div class="footer">
        <div class="total-1">
            <p>  Importe Neto: </p>
            <p>{{NewOrderTotalsNeto | currency}}</p>
        </div>
        <div class="total-2">
            <!-- <p>  Importe Iva: </p>
            <p>{{NewOrderTotalsIvaImportGetter | currency}}</p> -->
			<p>---</p>
        </div>
        <div class="total-3">
            <p> Importe Descuento: </p>
            <p>{{NewOrderTotalsDiscountGetter | currency}}</p>
        </div>
        <div class="total-4">
        <p>  Costo de envío: </p>
            <p>{{NewOrderShippingGetter.value | currency}}</p>
        </div>
        <div class="total-5">
            <p>{{IsAditional}} {{NewOrderPaymentTypeGetter.percentage}} % </p>
            <p>{{NewOrderPaymentTypeGetter.value | currency}}</p>
        </div>
        <div class="total-6">
            <p>  Total del Pedido: </p>
            <p> {{NewOrderTotalsTotalGetter | currency}}</p>
        </div>
    </div>
</template>
<script>
import {mapGetters} from 'vuex';

export default {
    computed : {

        ...mapGetters([
            'NewOrderDataGetter',
            'NewOrderTotalsNeto',
            'NewOrderTotalsIvaImportGetter',
            'NewOrderTotalsDiscountGetter',
            'NewOrderTotalsTotalGetter',
            'NewOrderShippingGetter',
            'NewOrderPaymentTypeGetter'
        ]),

        IsAditional(){
            return (this.NewOrderPaymentTypeGetter.value >= 0) ? 'Adicional' : 'Descuento';
        }
    },

    watch : {
        NewOrderDataGetter : {
            handler: async function (val, oldVal) {

                const neto = await this.$store.dispatch('calculeNetoAction');
                this.$store.commit('NEW_ORDER_SET_NETO', neto);

                const iva = await this.$store.dispatch('calculeIvaImportAction');
                this.$store.commit('NEW_ORDER_SET_IVA_IMPORT', iva);

                const discount = await this.$store.dispatch('calculeDiscountAction');
                this.$store.commit('NEW_ORDER_SET_DISCOUNT', discount);

                const total = await this.$store.dispatch('calculeTotalAction');
                this.$store.commit('NEW_ORDER_SET_TOTAL', total);

                /* const shipping = await this.$store.dispatch('calculateShippingAction');
                this.$store.commit('NEW_ORDER_SET_SHIPPING_VALUE', shipping);
				*/

                const aditional_value = await this.$store.dispatch('calculateAditionalByPaymentTypeAction');
                this.$store.commit('NEW_ORDER_SET_PAYMENT_ADITIONAL_VALUE', aditional_value);
            },
            deep: true
        }
    }
}
</script>

<style scoped>
    .footer{
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        grid-template-rows: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    div[class*="total"]{
        background-color : rgb(212, 212, 212);
        display : flex;
        vertical-align : middle;
    }

    div[class="total-6"] p {
        font-size: 1.5rem;
    }
    p {
        padding : .5rem 1rem;
        font-size : 1.5rem;
        font-weight: bold;
        color : black;
    }
</style>
