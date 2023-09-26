<script>
    import { mapGetters } from 'vuex';
import MultiselectBase from './../../../Base/Multiselect/MultiselectBase';
import { required } from 'vuelidate/lib/validators';
    export default {
        
        name: 'Multiselect-tax',

        extends: MultiselectBase,

        data(){
            return {
                show_spinner: false
            }
        },

        computed: {

            ...mapGetters([
                'TaxesGetter',
                'PurchaseInvoiceType',
                'GetPurchaseInvoiceSupplier',
                'PurchaseInvoiceSubsidiary',
                'PurchaseInvoiceNumber',
                'GetPurchaseInvoiceDate',
                'GetPurchaseInvoiceImputationDate'
            ]),

            Value(){
                return this.$store.state.PurchaseInvoice.invoice.taxes[this.index].tax;
            }

        },

        watch: {

            TaxesGetter(n){
                this.options = n;
            }
        },

        methods: {

            updateValue(){

            },

            selected(el){

                const payload = {
                    index: this.index,
                    tax: el
                }

                this.$store.commit('PURCHASE_INVOICE_SET_TAX', payload);
            },

            remove(){},

            asyncFind(){},

        },

        mounted(){

            this.options = this.TaxesGetter
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

<style lang="scss" scoped>

</style>