<template>
    <div class="row">
        <div class="col-md-12">
            <div class="pull-right" style="margin-bottom:15px">
                <button 
                    type="button" 
                    class="btn btn-primary" 
                    :disabled=" $v.$invalid || PurchaseInvoiceTotal == 0"
                    @click="add_tax"
                    >Agregar Impuesto
                </button>
               <!--  <button
                    @click="open_add_tax_modal" 
                    type="button" 
                    class="btn btn-success" 
                    >Crear Impuesto
                </button> -->
            </div>
            <strong>Impuestos del comprobante</strong>
            <table class="table table-striped table-bordered" v-if="PurchaserInvoiceHasTax">
                <thead>
                    <tr>
                        <th class="col-md-1 text-center">#</th>
                        <th class="col-md-7 text-center">Impuesto</th>
                        <th class="col-md-3 text-center">Importe</th>
                        <th class="col-md-1 text-center">Borrar</th>
                    </tr>
                </thead>
                <tbody v-for="(row, index) in PurchaseInvoiceTaxes" :key="index">
                    <PurchaseInvoiceTaxRow  :number="index" :tax="row"/>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import auth from './../../../../mixins/auth';
import toast_mixin from './../../../../mixins/toast-mixin';
import PurchaseInvoiceTaxRow from './PurchaseInvoiceTaxRow';
import { required } from 'vuelidate/lib/validators';
export default {
    
    mixins : [auth, toast_mixin],
    
    components : {
        PurchaseInvoiceTaxRow
    },

    data()
    {
        return {
            spinner : false,
        }
    },

    computed : {

        ...mapGetters(
            [
                'SelectedProviderGetter',
                'PurchaseInvoiceTaxes',
                'PurchaseInvoiceType',
                'GetPurchaseInvoiceSupplier',
                'PurchaseInvoiceSubsidiary',
                'PurchaseInvoiceNumber',
                'GetPurchaseInvoiceDate',
                'GetPurchaseInvoiceImputationDate',
                'PurchaseInvoiceTotal',
                'PurchaserInvoiceHasTax'
            ]
        ),

    },

    methods: {

        add_tax(){
            this.$store.commit('PURCHASE_INVOICE_ADD_TAX');
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