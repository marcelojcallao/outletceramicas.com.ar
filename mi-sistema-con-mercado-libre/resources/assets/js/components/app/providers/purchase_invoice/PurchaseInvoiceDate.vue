<script>

import {mapGetters, mapActions} from 'vuex';
import DatePickerBase from '../../../Base/DatePicker/Date-Picker-Base.vue';
export default {

    extends: DatePickerBase,

    props : {
        spinner : {
            default : false,
        }
    },

    watch : {

        date(n)
        {
            const imputation_date = this.$time(this.GetPurchaseInvoiceImputationDate);
            
            if( imputation_date.isBefore(n) )
            {
                this.$store.commit('PURCHASE_INVOICE_DATE_IS_OK', false);
                
            }else{

                this.$store.commit('PURCHASE_INVOICE_DATE_IS_OK', true);
            }
            
            this.$store.commit('PURCHASE_INVOICE_SET_DATE', n);

        }
    },

    methods : {

        ...mapActions([
            'setPurchaseInvoiceDate'
        ]),

    },

    computed : {

        ...mapGetters([
            'GetPurchaseInvoiceDate',
            'PurchaseInvoiceDateErrorGetter',
            'GetPurchaseInvoiceImputationDate',
        ]),

        date:{
            get(){
                return this.GetPurchaseInvoiceDate;
            },
            set(value){
                this.$store.dispatch('setPurchaseInvoiceDate', value);
            }
        },

        disabledDate(){}
    },

    mounted(){
            const invoiceDate = new Date();
            this.$store.dispatch('setPurchaseInvoiceDate', invoiceDate);
        }
}
</script>

<style scoped>
    .margin-top{
        margin-top: 15px;
    }
</style>