
<script>
    import { mapActions, mapGetters } from 'vuex';
    import PaymentMultiselectBase from './../../../paymentType/PaymentTypeMultiselectBase.vue';
    export default {

        extends : PaymentMultiselectBase,

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
                'NewOrderTotalsNeto'
            ]),

            PaymentTypeGetter(){
                return this.NewOrderPaymentTypeGetter;
            }
        },

        watch : {

            NewOrderPaymentTypeGetter : {

                deep : true,

                handler(value){

                    const aditional_value = parseFloat(this.NewOrderTotalsNeto) * parseFloat(value.percentage) / 100;

                    this.$store.commit('NEW_ORDER_SET_PAYMENT_ADITIONAL_VALUE', aditional_value);
                }
            }

        },

        async mounted(){

            const { data } = await this.$store.dispatch('getPaymentTypes');

            if (data) {

                const paymentTypes = data.filter(payment => payment.status_id == 1);

                this.$store.commit('SET_PAYMENT_TYPES', paymentTypes);
            }
        }
    }
</script>
<style scoped>
    .multiselect {
        width: 100%;
    }
</style>
