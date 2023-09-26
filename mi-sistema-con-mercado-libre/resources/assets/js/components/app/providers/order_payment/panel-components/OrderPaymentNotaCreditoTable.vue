<template>
    <div id='notas_credito_table' >
        <v-client-table
            ref='notas_credito_table'
            :columns='columns' 
            :data='rows' 
            :options='options'
        >
        </v-client-table>
    </div>
</template>

<script>
    import row_number from './../../../publications/partials/row-number';
    import { mapGetters } from 'vuex';
    import Status from './Status.vue';
    import NotaCreditoTakeit from './NotaCreditoTakeit.vue';
    import NotaCreditoTotal from './NotaCreditoTotal.vue';
    export default {
        
        name: '', 
        
        data(){
            return {
                columns : [
                    'num',
                    'voucher',
                    'date',
                    'long_number',
                    'total',
                    'status',
                    'action'
                ],
                rows : [],
                options: {
                    filterable: false,
                    orderBy: { column: false },
                    sortable: [],
                    uniqueKey : 'id',
                    pagination: { dropdown:false },
                    headings: {
                        num : '#',
                        voucher: 'Comprobante',
                        date : 'Fecha',
                        long_number : 'Número',
                        total : 'Importe',
                        status : 'Estado',
                        action : 'Incluir en el pago',
                    },
                    templates: {
                        num: row_number,
                        status: Status,
                        action: NotaCreditoTakeit,
                        total: NotaCreditoTotal
                    }
                }
            }
        },
        
        computed: {
            
            ...mapGetters([
                'invoicesToPayGetter'
            ])

        },
            
        methods: {
            
            async loadTableData(){    

                const supplier_id = this.invoicesToPayGetter[0].supplier;

                const { data:invoices } = await this.$store.dispatch('get_notas_credito_from_supplier', supplier_id );
                console.log("🚀 ~ file: OrderPaymentNotaCreditoTable.vue:68 ~ loadTableData ~ invoices:", invoices)
    
                this.rows = invoices;
            }
        },

        async mounted(){
            await this.loadTableData();
        }
    }
</script>

<style >
#notas_credito_table table thead tr th {
    text-align: center;
}
#notas_credito_table table thead tr th:nth-child(1){
    width: 5%;
}
#notas_credito_table table thead tr th:nth-child(2){
    width: 25%;
}
#notas_credito_table table tbody tr td:nth-child(1){
    text-align: center;
}
#notas_credito_table table tbody tr td:nth-child(2){
    text-align: left;
}
#notas_credito_table table tbody tr td:nth-child(3),
#notas_credito_table table tbody tr td:nth-child(4),
#notas_credito_table table tbody tr td:nth-child(6),
#notas_credito_table table tbody tr td:nth-child(7)
{
    text-align: center;
}
#notas_credito_table table tbody tr td:nth-child(5){
    text-align: right;
}
</style>