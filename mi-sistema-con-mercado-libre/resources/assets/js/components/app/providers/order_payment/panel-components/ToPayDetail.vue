<template>
    <div >
        <div class="card--title">
            <OrderPaymentChequesModal />
            <OrderPaymentModalNotacredito />
        </div>
        <div class="card--body" >
            <div id="notas-de-credito" v-if="ToPayDetailNotasDeCreditoGetter.length">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Comprobante</th>
                            <th>Número</th>
                            <th>Fecha</th>
                            <th>Importe</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(invoice, index) in ToPayDetailNotasDeCreditoGetter" :key="invoice.id">
                            <td>{{ index + 1 }}</td>
                            <td>{{ invoice.voucher }}</td>
                            <td>{{ invoice.long_number }}</td>
                            <td>{{ invoice.date }}</td>
                            <td>{{ invoice.total | currency }}</td>
                            <td>
                                <button 
                                    v-tooltip="'Quitar'"
                                    @click="remove_nota_credito(index)"
                                    class="btn btn-outline-danger btn-icon sq-32" 
                                    type="button">
                                    <span class="icon icon-trash"></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div id="detail">
                    <p>Total de comprobantes a pagar: {{ TotalInvoices | currency}}</p>
                    <p>Total de Notas de crédito: {{ TotalNotasDeCredito | currency}}</p>
                    <p>Total a pagar: {{ TotalToPay | currency}}</p>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import OrderPaymentChequesModal from '../cheques/OrderPaymentChequesModal.vue';
    import OrderPaymentModalNotacredito from '../OrderPaymentModalNotacredito.vue';
    export default {
        
        name: 'ToPayDetail',

        components: { OrderPaymentModalNotacredito, OrderPaymentChequesModal },
        
        methods: {

            remove_nota_credito(index){

                this.ToPayDetailNotasDeCreditoGetter.splice(index, 1);
            }
        },

        computed: {

            ...mapGetters([
                'invoicesToPayGetter',
                'ToPayDetailNotasDeCreditoGetter'
            ]),

            TotalInvoices(){

                return this.invoicesToPayGetter.reduce( (acc, current) => {
                    return acc + current.invoice.toPay;
                }, 0)
            },

            TotalNotasDeCredito(){

                return this.ToPayDetailNotasDeCreditoGetter.reduce( (acc, current) => {
                    return acc + current.total;
                }, 0);

            },

            TotalToPay(){

                return this.TotalInvoices - this.TotalNotasDeCredito;
            }
        }
    }
</script>

<style scoped>

    div .card--title {
        display: flex;
        justify-content: space-between;
    }
    div .card--title h5{
        margin-right: 2rem;
    }
    .card--body{
        margin-top: 2rem;
    }
    #notas-de-credito{
        display: flex;
        padding: 2rem;
    }
    #notas-de-credito table {
        width: 40%;
        height: auto;
    }
    #notas-de-credito table thead tr th {
        text-align: center;
        font-size: 10px !important;
    }
    #notas-de-credito table thead tr th:nth-child(1){
        width: 5%;
    }
    #notas-de-credito table thead tr th:nth-child(2){
        width: 30%;
    }
    #notas-de-credito table thead tr th:nth-child(3){
        text-align: 25%;
    }
    #notas-de-credito table thead tr th:nth-child(4),
    #notas-de-credito table thead tr th:nth-child(5){
        width: 15%;
    }
    #notas-de-credito table thead tr th:nth-child(6){
        width: 10%;
    }
    #notas-de-credito table tbody tr td:nth-child(1),
    #notas-de-credito table tbody tr td:nth-child(3),
    #notas-de-credito table tbody tr td:nth-child(4),
    #notas-de-credito table tbody tr td:nth-child(6){
        text-align: center;
    }
    #notas-de-credito table tbody tr td:nth-child(2){
        text-align: left;
    }
    #notas-de-credito table tbody tr td:nth-child(5){
        text-align: right;
    }
    #detail{
        margin-left: 2rem;
        border: 1px solid #ccc;
        padding: 1rem;
        width: 60%;
        min-height: 20rem;
    }

    #detail p{
        margin-right: 2rem;
    }
</style>