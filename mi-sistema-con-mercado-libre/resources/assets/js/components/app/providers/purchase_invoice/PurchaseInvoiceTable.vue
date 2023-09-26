<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="pull-right">
                        <button 
                            @click="add_article"
                            type="button" 
                            class="btn btn-primary" 
                            :disabled=" $v.$invalid "
                        >Agregar Artículo</button>
                    </div>
                    <strong>Detalle</strong>
                </div>
                <div class="card-body" data-toggle="match-height">
                    <table id="purchase_invoice_table" class="table table-striped table-bordered" style="margin-bottom:31px">
                        <thead>
                            <tr>
                                <th class="col-md-1 text-center">#</th>
                                <th class="col-md-8 text-center">Artículo</th>
                                <th class="col-md-2 text-center">Total</th>
                                <th class="col-md-1 text-center">Borrar</th>
                            </tr>
                        </thead>
                        <PurchaseInvoiceTableRow v-for="(row, index) in PurchaseInvoiceArticleGetter" :key="index" :number="index" />
                    </table>
                    <PurchaseInvoiceTaxes />
                    <div class="totals-container">
                        <div>
                            <h3>Total Neto: {{PurchaseInvoiceNeto | currency}}</h3>
                        </div>
                        <div>
                            <h3>Total Iva: {{PurchaseInvoiceIva | currency}}</h3>
                        </div>
                        <div>
                            <h3>Total Impuestos: {{PurchaseInvoiceTax | currency}}</h3>
                        </div>
                        <div>
                            <h3>Total: {{PurchaseInvoiceTotal | currency}}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-center">
                <button 
                    @click="purchase_invoice_store"
                    :disabled=" $v.$invalid || PurchaseInvoiceTotal == 0"
                    class="btn btn-primary"
                    :class="{'btn btn-primary spinner spinner-inverse spinner-sm' : spinner}" 
                    >Guardar comprobante de compras
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import auth from './../../../../mixins/auth';
import PurchaseInvoiceTaxes from './PurchaseInvoiceTaxes';
import sleep_mixin from './../../../../mixins/sleep-mixin';
import toast_mixin from './../../../../mixins/toast-mixin';
import PurchaseInvoiceTableRow from './PurchaseInvoiceTableRow';
import { required } from 'vuelidate/lib/validators';
import { Event } from 'vue-tables-2';
export default {
    
    mixins : [auth, toast_mixin, sleep_mixin],
    
    components : {
        PurchaseInvoiceTableRow,
        PurchaseInvoiceTaxes
    },

    data()
    {
        return {
            spinner : false
        }
    },

    computed : {
        ...mapGetters(
            [
                'GetPurchaseInvoiceDate',
                'GetPurchaseInvoiceImputationDate',
                'GetPurchaseInvoiceSupplier',
                'PurchaseInvoiceArticleGetter',
                'PurchaseInvoiceArticlesErrorGetter',
                'PurchaseInvoiceNumber',
                'PurchaseInvoiceSubsidiary',
                'PurchaseInvoiceTotal',
                'PurchaseInvoiceNeto',
                'PurchaseInvoiceIva',
                'PurchaseInvoiceTax',
                'PurchaseInvoiceType',
                'TaxesGetter',
            ]
        )
    },

    methods : {
        
        add_article()
        {
            this.$store.commit('PURCHASE_INVOICE_ADD_ARTICLE');
        },

        async purchase_invoice_store  () {

            this.$store.commit('SET_PURCHASE_INVOICE_ERRORS', {});

            this.spinner = true;

            await this.sleep(250);

            const purchase_invoice = await this.$store.dispatch('purchase_invoice_store', this.User.token)

                .catch(error => {
                    this.$store.commit('SET_PURCHASE_INVOICE_ERRORS', error.response.data.errors);
                    this.error_message('No se pudo guardar el comprobante', 'Compras', 2500);
                    console.log(error.response.data.errors);
                    return false;

                }).finally(()=>this.spinner = false);

            if (purchase_invoice) {

                this.success_message('Guardado correctamente', 'Comprobante de compras', 3000);

                this.$store.commit('PURCHASE_INVOICE_SET_INITIAL_STATUS');

                this.$store.commit('PURCHASE_INVOICE_ADD_TAX');

                this.$store.commit('SET_SELECTED_PROVIDER', {id:null, name:null});
                this.$store.commit('PURCHASE_INVOICE_SET_SUBSIDIARY', null);
                this.$store.commit('PURCHASE_INVOICE_SET_NUMBER', null);
                this.$store.commit('PURCHASE_INVOICE_SET_INVOICE_TYPE', null);

                this.$store.dispatch('setVouchersByInscription', []);

                Event.$emit('purchase_invoice_set_initial_status');


            }
            
        }
    },

    watch : {

        PurchaseInvoiceTotal(n)
        {
            this.$store.commit('PURCHASE_INVOICE_SET_TOTAL', n);
        }
    },

    validations(){
        return {
            PurchaseInvoiceType : {
                required,
            },
            GetPurchaseInvoiceSupplier : {
                required,
            },
            PurchaseInvoiceSubsidiary : {
                required,
            },
            PurchaseInvoiceNumber : {
                required,
            },
            GetPurchaseInvoiceDate : {
                required,
            },
            GetPurchaseInvoiceImputationDate : {
                required,
            },
        }
    },
       
}
</script>

<style scoped>
    .totals-container{
        display: flex;
        justify-content: space-between;
    }
</style>