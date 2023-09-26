<template>
    <div class="panel">
        <div class="panel-inside">
            <div class="panel-inside-header">
                <h4>
                    Generar orden de Pago:
                </h4>
                <h4> Proveedor: {{ invoicesToPayGetter[0].invoice.provider_name }}</h4>
                <h4> Fecha: {{  $time(OrderToPayDateGetter).format("DD-MM-YYYY") }}</h4>
            </div>
            <TableInvoice  />
            <ToPayDetail />
        </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import TableInvoice from './panel-components/TableInvoice.vue';
    import ToPayDetail from './panel-components/ToPayDetail.vue';
    import OrderPaymentModalNotacredito from './OrderPaymentModalNotacredito.vue';
    export default {
        
        name: 'OrderPaymentPanel',

        components: {TableInvoice, ToPayDetail, OrderPaymentModalNotacredito},

        computed: {

            ...mapGetters([
                'invoicesToPayGetter',
                'OrderToPayDateGetter'
            ])
        }
    }
</script>

<style >
    .panel{
        height: 835px;
        padding: 15px;
        border: solid #ccc 1px;
        background-color: #f6f6fa;
    }
    .panel-inside{
        background-color: #f6f6fa;
        height: 800px;
        border-radius: 10px;
        border: solid #ccc 1px;
        padding: 2rem;

    }
    .panel:hover{
        background-color: #efeff5;
    }
    .panel-inside-header{
        display: flex;
        justify-content: space-between;
        padding: 1rem 2rem;
    }
    .VueTables--client {
        min-height: 200px !important;
    }
</style>