<template>
    <table class="table" id="invoices-table">
        <caption>COMPROBANTES ASOCIADOS AL PEDIDO</caption>
        <thead>
            <tr>
                <th>#</th>
                <th>Comprobante</th>
                <th>Fecha</th>
                <th>Número</th>
                <th>Importe</th>
                <th>Imprimir</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(invoice, index) in this.PedidoListChildRowReactivityData.invoices" :key="index">
                <td> {{ index + 1 }}</td>
                <td >{{invoice.voucher}}</td>
                <td>{{invoice.date}}</td>
                <td>{{ invoice.number }}</td>
                <td>{{ invoice.import | currency}}</td>
                <td>
                    <printButton :invoice="invoice" />
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    import { mapGetters } from 'vuex';
    import printButton from './print-button.vue';
    export default {

        components: {printButton},

        computed: {

            ...mapGetters(['PedidoListChildRowReactivityData'])
        }
    }
</script>

<style lang="scss" scoped>
#invoices-table thead tr {
    background-color: rgb(191, 120, 107)
}
#invoices-table thead tr th{
    text-align: center;
}
#invoices-table thead tr th:nth-child(1){
    width: 5%;
    text-align: center;
}
#invoices-table thead tr th:nth-child(2){
    width: 25%;
}
#invoices-table thead tr th:nth-child(3){
    width: 15%;
}
#invoices-table thead tr th:nth-child(4){
    width: 20%;
}
#invoices-table thead tr th:nth-child(5){
    width: 20%;
}
#invoices-table tbody tr td:nth-child(1),
#invoices-table tbody tr td:nth-child(3),
#invoices-table tbody tr td:nth-child(4),
#invoices-table tbody tr td:nth-child(6){
    text-align: center;
}
#invoices-table tbody tr td:nth-child(5){
    text-align: right;
}
</style>