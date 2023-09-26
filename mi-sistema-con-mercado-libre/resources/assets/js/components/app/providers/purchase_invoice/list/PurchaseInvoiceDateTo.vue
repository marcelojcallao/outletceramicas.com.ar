
<script>
    import { mapGetters } from 'vuex';
    import DatePickerBase from '../../../../Base/DatePicker/Date-Picker-Base.vue';
    export default {
        
        name: 'PurchaseInvoiceDateTo', 

        extends: DatePickerBase,

        computed: { 

            ...mapGetters([
                'PurchaseInvoiceListDateToGetter'
            ]),

            date:{
                get(){
                    return this.PurchaseInvoiceListDateToGetter;
                },
                set(value){
                    const date = this.$moment(value).toDate();

                    this.$store.dispatch('purchaseInvoiceListSetDateTo', date);
                }
            }
        },

        mounted(){
            this.$store.dispatch('purchaseInvoiceListSetDateTo', this.$moment(new Date()).toDate());
        }
    }
</script>

<style lang="scss" scoped>

</style>