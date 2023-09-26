
<script>
    import { mapActions, mapGetters } from 'vuex';
    import PaymentTypeMultiselectBase from './../../../../admin/paymentType/PaymentTypeMultiselectBase.vue';
    export default {

        extends : PaymentTypeMultiselectBase,

        data() {
            return {
                show_spinner : false,
            }
        },

        methods : {

            ...mapActions(['updatePaymentTypeValue']),

            updatePaymentTypeValue(el){
                this.$store.commit('NEW_ORDER_SET_PAYMENT_TYPE', el);
            }
        },

        computed : {

            ...mapGetters([
                'PaymentTypesGetter',
                'PaymentTypeGetter',
                'NewOrderPaymentTypeGetter',
                'NewOrderTotalsNeto',
                'PedidoListChildRowReactivityData'
            ]),

            PaymentTypeGetter(){
                return this.NewOrderPaymentTypeGetter;
            }
        },

        async mounted() {
            await this.$store.dispatch('getPaymentTypes')
        },
    }
</script>
<style scoped>
    .multiselect {
        width: 100%;
    }
</style>
