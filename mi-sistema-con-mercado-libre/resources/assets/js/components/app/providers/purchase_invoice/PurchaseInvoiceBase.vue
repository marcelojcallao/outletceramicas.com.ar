<template>
    <div style="margin: 1rem 2rem;">
        <div class="row">
            <div class="col-md-12">
                <div class="form form-horizontal">
                    <div class="form-group">
                        <div class="col-md-6" >
                            <ProviderFindByName />
                        </div>
                        <div class="col-md-3 pull-right" :class="{'has-error' : PurchaseInvoiceImputationDateErrorGetter}">
                            <PurchaseInvoiceImputationDateVue title="FECHA IMPUTACION"/>
                            <p class="text-danger" v-if="PurchaseInvoiceImputationDateErrorGetter">{{PurchaseInvoiceImputationDateErrorGetter[0]}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row margin-top">
            <MultiselectVouchers :spinner="voucher_spinner"/>
        </div>
        <div class="row margin-top">
            <div class="col-md-6">
                <MultiselectMoney :spinner="money_spinner" />
            </div>
            <div class="col-md-6" style="padding-top: 2rem;">
                <InvoiceDate title="FECHA DEL COMPROBANTE"/>
            </div>
        </div>
        <div class="row margin-top">
            <PurchaseInvoiceTable />
        </div>
    </div>
</template>

<script>
import InvoiceDate from './PurchaseInvoiceDate.vue';
import PurchaseInvoiceImputationDateVue from './PurchaseInvoiceImputationDate.vue';
import Multiselect from 'vue-multiselect';
import {mapGetters, mapActions} from 'vuex';
import auth from './../../../../mixins/auth';
import MultiselectMoney from './Multiselect-Money';
import ProviderFindByName from './../ProviderFindByName';
import MultiselectVouchers from './Multiselect-Vouchers';
import PurchaseInvoiceTable from './PurchaseInvoiceTable';
import toast_mixin from './../../../../mixins/toast-mixin';
import open_article_mixin from './../../../../mixins/EventListenerOpenArticleModal-mixin';
export default {
    mixins : [auth, toast_mixin, open_article_mixin],
    components : {
        Multiselect, MultiselectVouchers, MultiselectMoney,
        InvoiceDate, PurchaseInvoiceTable, ProviderFindByName, PurchaseInvoiceImputationDateVue
    },
    data() {
        return {
            searching : false,
            cuit : null,
            money_spinner : true,
            voucher_spinner : true,
            money : null,
        }
    },

    methods : {

        ...mapActions([
            'setPurchaseInvoiceImputationDate'
        ]),

        customFormatter(date){
                return this.$moment(date).format("DD-MM-YYYY");
        },

        

        async getMoneys(){
            let moneys = await this.$store.dispatch('fetchMoney');
            this.$store.commit('ADD_MONEYS', moneys.data);
            this.money_spinner = false;
        },

    }, 
    
    computed : {
        ...mapGetters([
            'ProviderName',
            'GetMoneys',
            'Provinces',
            'TaxesGetter',
            'GetPurchaseInvoiceImputationDate',
            'PurchaseInvoiceImputationDateErrorGetter'
        ]),

        imputation_date: {
            get(){

            },
            set(value){

            }
        },

        DisabledDates(){
            return {
                to:  this.$moment(this.Today).toDate(),
                from: this.$moment(this.Today).add(15, 'days').toDate(), 
                days : [0]
            } 
        },
    },

    async mounted(){
        
        this.$moment.locale('es');

        
        await this.getMoneys();
        await this.$store.dispatch('get_articles', this.User.token);
        await this.$store.dispatch('get_accounting_accounts', this.User.token);
        await this.$store.dispatch('getMeasureUnities', this.User.token);

        const { data:taxes } = await this.$store.dispatch('get_taxes', this.User.token);
        
        if (taxes) {
            this.$store.commit('SET_TAXES', taxes);
        }
        

        /* let tax_types = await this.$store.dispatch('get_tax_types', this.User.token);
        
        if (tax_types) {
            this.$store.commit('SET_TAX_TYPES', tax_types);
        }

        let taxes = await this.$store.dispatch('get_taxes', this.User.token);

        if (taxes) {
            this.$store.commit('SET_TAXES', taxes);

            taxes.forEach(element => {
                this.$store.commit('PURCHASE_INVOICE_ADD_TAX', element);
            });
        } */

        this.$store.dispatch('fetchIvas');
    }
}
</script>

<style scoped>
    .margin-top {
        margin-top: 15px;
    }
</style>