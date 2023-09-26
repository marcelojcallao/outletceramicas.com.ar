<template>
    <div id="table" >
        <v-client-table
            ref="table_invoices"
            :columns="columns"
            :data="rows"
            :options="options"
        >
        </v-client-table>
    </div>
</template>

<script>
    import row_number from './../../../publications/partials/row-number';
    import ToPayInput from './ToPayInput.vue';
    import Status from './Status.vue';
    import TrashButton from './TrashButton.vue';
    import { mapGetters } from 'vuex';
    export default {
        
        data(){
            return {
                columns : [
                    'number',
                    'voucher',
                    'date',
                    'long_number',
                    'total',
                    'balance',
                    'toPay',
                    'paid',
                    'status',
                    'action'
                ],
                rows : [],
                options: {
                    filterable: false,
                    orderBy: { column: false },
                    sortable: [],
                    uniqueKey : 'id',
                    perPage : 3,
                    perPageValues: [2,3],
                    pagination: { dropdown:true },
                    headings: {
                        number : '#',
                        voucher : 'Comprobante',
                        date : 'Fecha',
                        long_number : 'Número',
                        total : 'Importe',
                        balance : 'Saldo',
                        toPay: 'A pagar',
                        paid: 'Pagado',
                        status : 'Estado',
                        action : 'Acción',
                    },
                    templates: {
                        number: row_number,
                        toPay: ToPayInput,
                        status: Status,
                        action: TrashButton
                    }
                }
            }
        },

        computed: {

            ...mapGetters([
                'OrderToPayTotal',
                'invoicesToPayGetter'
            ])
        },

        methods: {

            loadTableData(){

                const invoices = this.invoicesToPayGetter.map(element => element.invoice);

                this.rows = invoices;
            }
        },

        watch: {

            invoicesToPayGetter(n){

                this.loadTableData();
            }
        },

        mounted(){
            this.loadTableData();
        }
    }
</script>

<style >
    #table table thead tr th:nth-child(1)
    {
        width: 2%;
        text-align: center;
        font-size: 14px !important;
        vertical-align: middle;
        
    }
    #table table thead tr th:nth-child(2)
    {
        width: 18%;
        text-align: center;
        font-size: 14px !important;
        vertical-align: middle;
        
    }
    #table table thead tr th:nth-child(3),
    #table table thead tr th:nth-child(4),
    #table table thead tr th:nth-child(5),
    #table table thead tr th:nth-child(6),
    #table table thead tr th:nth-child(7),
    #table table thead tr th:nth-child(8),
    #table table thead tr th:nth-child(9),
    #table table thead tr th:nth-child(10)
    {
        width: 10%;
        text-align: center;
        font-size: 14px !important;
        vertical-align: middle;
        
    }
    #table table tbody tr td:nth-child(1),
    #table table tbody tr td:nth-child(3),
    #table table tbody tr td:nth-child(4),
    #table table tbody tr td:nth-child(7),
    #table table tbody tr td:nth-child(9),
    #table table tbody tr td:nth-child(10)
    {
        text-align: center;
        vertical-align: middle;
    }
    #table table tbody tr td:nth-child(5),
    #table table tbody tr td:nth-child(6),
    #table table tbody tr td:nth-child(8)
    {
        text-align: right;
        vertical-align: middle;
    }
</style>