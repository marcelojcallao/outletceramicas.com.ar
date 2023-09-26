<template>
    <div class="row">
        <div class="col-md-12 wrapper-purchase-invoice-list">
            <loading 
                :active.sync="loading" 
                color="#0469c7"
                :can-cancel="false" 
                :is-full-page="true">
            </loading>
            <div class="row">
                <div class="col-md-12">
                    <div class="container-button">
                        <div class="form-group col-md-6" style="padding-bottom-15px">
                            <ProviderFindByName />
                        </div>
                        <div style="padding-top:21px">
                            <PurchaseInvoiceDateFrom title="F. factura desde" />
                        </div>
                        <div style="padding-top:21px">
                            <PurchaseInvoiceDateTo title="F. factura hasta" />
                        </div>    
                        <div style="padding-top:21px">
                            <button 
                                @click="loadData"
                                class="btn btn-primary" 
                                type="button"
                                :class="{'btn btn-primary spinner spinner-inverse spinner-sm' : search_spinner}"
                                >Buscar Comprobantes
                            </button>
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="margin-top-15px">
                    <div class="container-button">
                        <OrderPaymentGenerateButton />
                        <ExportIvaCompras />
                    </div>
                </div>
            </div>
                <v-client-table
                    ref="purchase_invoice_list"
                    :columns="columns"
                    :data="rows"
                    :options="options"
                >
                
                <!-- <div slot="filter__id">
                    <input type="checkbox" 
                    class="form-control check-all" 
                    v-model='allMarked' 
                    @change="toggleAll()">
                </div>	 -->

                <template slot="id" slot-scope="props">
                    <input type="checkbox" 
                        class="form-control" 
                        :disabled="(props.row.status_id == 18 || props.row.status_id == 20)?true:false"
                        :value="{ supplier: props.row.provider_id, invoice : props.row }" 
                        v-model="markedRows">
                </template>

                </v-client-table>
            <div class="text-center">
                <paginate
                    :page-count="pageCount"
                    :click-handler="loadData"
                    :prev-text="'Ant.'"
                    :next-text="'Sig.'"
                    :container-class="'pagination'">
                </paginate>
            </div>
        </div>
    </div>
</template>

<script>
import Paginate from 'vuejs-paginate'
import Loading from 'vue-loading-overlay';
import Multiselect from 'vue-multiselect';
import { mapGetters } from 'vuex';
import ExportIvaCompras from './ExportIvaCompras';
import 'vue-loading-overlay/dist/vue-loading.css';
import ProviderFindByName from './../../ProviderFindByName';
import row_number from './../../../publications/partials/row-number';
import PurchaseInvoiceListChildRow from './PurchaseInvoiceListChildRow';
import CellStatus from './../../../pedidosClientes/PedidoListCellStatus';
import PurchaseInvoiceDateFrom from './PurchaseInvoiceDateFrom.vue';
import PurchaseInvoiceDateTo from './PurchaseInvoiceDateTo.vue';
import OrderPaymentGenerateButton from '../../order_payment/OrderPaymentGenerateButton.vue';
export default {

    components : {
        Multiselect, ProviderFindByName, Paginate, Loading, ExportIvaCompras, PurchaseInvoiceDateFrom, PurchaseInvoiceDateTo,
        OrderPaymentGenerateButton
    },
    data() {
        return {
            spinner : false,
            search_spinner : false,
            pageCount : 1,
            searching : false,
            loading : false,
            markedRows:[],
            columns : [
                    'id',
                    'row_number',
                    'provider_name',
                    'voucher',
                    'long_number',
                    'date',
                    'total',
                    'paid',
                    'balance',
                    'status',
            ],
            rows : [],
            options: {
                uniqueKey : 'id',
                perPage : 50,
                orderBy: { column: false },
                sortable: [],
                pagination: { dropdown:false },
                headings: {
                    id : 'Chk',
                    row_number : '#',
                    provider_name: 'Proveedor',
                    voucher : 'Comprobante',
                    long_number : 'Número',
                    date : 'Fecha',
                    total : 'Importe',
                    paid : 'Pagado',
                    balance : 'Saldo',
                    status : 'Estado',
                },
                templates: {
                    row_number : row_number,
                    status : CellStatus
                },
                columnsClasses: {
                    id : 'text-center',
                    row_number : 'text-center',
                    voucher : 'text-left',
                    long_number : 'text-center',
                    date : 'text-center',
                    total : 'text-right',
                    paid : 'text-right',
                    balance : 'text-right',
                    status : 'text-center',
                },
                //groupBy : 'provider_name',
                groupMeta: [],
                /* [
                        {
                            value: 'VILLALBA NATALIA',
                            data:{
                                population:1216,
                                countries:54
                            }
                        },
                ], */
                toggleGroups : true,
                filterable: false,
                childRow : PurchaseInvoiceListChildRow,
                cellClasses:{
                    row_number: [
                        {
                            class:'text-danger',
                            condition: row => row.voucher_id == 3 || row.voucher_id == 8 || row.voucher_id == 13
                        }
                    ],
                    voucher: [
                        {
                            class:'text-danger',
                            condition: row => row.voucher_id == 3 || row.voucher_id == 8 || row.voucher_id == 13
                        }
                    ],
                    long_number: [
                        {
                            class:'text-danger',
                            condition: row => row.voucher_id == 3 || row.voucher_id == 8 || row.voucher_id == 13
                        }
                    ],
                    date: [
                        {
                            class:'text-danger',
                            condition: row => row.voucher_id == 3 || row.voucher_id == 8 || row.voucher_id == 13
                        }
                    ],
                    total: [
                        {
                            class:'text-danger',
                            condition: row => row.voucher_id == 3 || row.voucher_id == 8 || row.voucher_id == 13
                        }
                    ],
                },

            } 
        }
    },

    methods : {

        customFormatter(date){
            return this.$moment(date).format("dd/MM/yyyy");
        },

        async order_payment_generate()
        {
            

        },

        async loadData(page=1)
        {
            this.loading = true;

            let url = '/api/purchase_invoice/index?page=' + page + '&from_date='+ this.$time(this.PurchaseInvoiceListDateFromGetter).format("YYYY-MM-DD")  + '&to_date=' + this.$time(this.PurchaseInvoiceListDateToGetter).format("YYYY-MM-DD");

            if (this.PurchaseInvoiceListSupplierGetter) {
                url = url +  '&provider='+this.PurchaseInvoiceListSupplierGetter.id;
            }

            const { data:purchase_invoices } = await this.$store.dispatch('getPurchaserInvoice', url)
            .catch((err) => {
                
            }).finally(()=> this.loading = false);
            
            if (purchase_invoices) {
                this.$store.dispatch('setPurchaseInvoiceList', purchase_invoices.data)
            }

        },
      
    },

    computed : {

        ...mapGetters([
            'PurchaseInvoiceListGetter',
            'SelectedProviderGetter',
            'PurchaseInvoiceListSupplierGetter',
            'PurchaseInvoiceListDateFromGetter',
            'PurchaseInvoiceListDateToGetter',
            'invoicesToPayGetter'
        ]),

        EnablePayButton()
        {
            /* let rows = collect(this.markedRows);

            if (rows.count() > 0) {
                return true;
            }
 */
            return false;
        }
    }, 

    watch : {

        PurchaseInvoiceListGetter(n){

            this.rows = n                                                                                                                                       
        },

        markedRows(n){
            
            if (n.length) {
                
                const supplier = n[0].supplier;

                n.forEach( async (element, index) => {
                    
                    if (element.supplier != supplier) {

                        const result = await Vue.swal.fire({
                                title: 'Orden de pago',
                                text : 'No se puede generar una orden de pago de para distintos proveedores',
                                icon : 'error',
                                width : '35%',
                                padding : '2rem',
                                backdrop : true,
                            });
                        
                        if (result.isConfirmed) {
                            this.markedRows.splice(index, 1);
                        }
                    }
                    
                });
            }

            this.$store.dispatch('setInvoicesToPay', n);
        }

       /*  SelectedProviderGetter(n)
        {
            this.loadData()
        }, */

    },

    async mounted(){

        await this.loadData();
        
    }
   

}
</script>

<style>
    .margin-top{
        margin-top: 15px;
    }
    .wrapper-purchase-invoice-list table thead tr th:nth-child(1),
    .wrapper-purchase-invoice-list table thead tr th:nth-child(2)
    .wrapper-purchase-invoice-list table thead tr th:nth-child(3)
    {
        width: 1%;
        text-align: center;
        font-size: 14px !important;
        vertical-align: middle;
        
    }
    .wrapper-purchase-invoice-list table thead tr th:nth-child(4),
    .wrapper-purchase-invoice-list table thead tr th:nth-child(5)
    {
        width: 15%;
        text-align: center;
        font-size: 14px !important;
        vertical-align: middle;
        
    }

</style>
<style scoped>
.container-button{
    display: flex;
    justify-content: space-between;
}   
</style>