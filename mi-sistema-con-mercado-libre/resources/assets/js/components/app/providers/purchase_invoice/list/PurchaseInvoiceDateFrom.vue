
<script>
    import { mapGetters } from 'vuex';
    import DatePickerBase from '../../../../Base/DatePicker/Date-Picker-Base.vue';
    export default {
        
        name: 'PurchaseInvoiceDateFrom', 

        extends: DatePickerBase,

        computed: { 

            ...mapGetters([
                'PurchaseInvoiceListDateFromGetter'
            ]),

            date:{
                get(){
                    return this.PurchaseInvoiceListDateFromGetter;
                },
                set(value){
                    const date = this.$moment(value).toDate();

                    this.$store.dispatch('purchaseInvoiceListSetDateFrom', date);
                }
            }
        },

        mounted(){
            this.$store.dispatch('purchaseInvoiceListSetDateFrom', this.$moment(new Date(2023,0,1)).toDate());
        }
    }
</script>