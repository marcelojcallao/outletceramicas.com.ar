<template>
    <div>
        <PaymentTypeNew />
        <PaymentTypeTable />
    </div>
</template>

<script>
import PaymentTypeTable from './PaymentTypeTable.vue';
import PaymentTypeNew from './PaymentTypeNew.vue';
    export default {
        
        name: 'PaymentTypeWrapper',

        components: { PaymentTypeTable, PaymentTypeNew },

        methods: {

            async getPaymentTypes(){

                const { data:paymentTypes } = await this.$store.dispatch('getPaymentTypes')

                if (paymentTypes) {
                    this.$store.commit('SET_PAYMENT_TYPES', paymentTypes);
                }
            }
        },

        async mounted(){
            this.getPaymentTypes();
        }
    }
</script>

<style lang="scss" scoped>

</style>