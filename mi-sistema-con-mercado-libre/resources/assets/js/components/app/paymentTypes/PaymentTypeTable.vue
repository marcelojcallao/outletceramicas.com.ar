<template>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%" class="text-center">#</th>
                    <th style="width: 45%" class="text-center">Método de pago</th>
                    <th style="width: 10%" class="text-center">Recargo %</th>
                    <th style="width: 20%" class="text-center">Estado</th>
                    <th style="width: 20%" class="text-center">Acción</th>
                </tr>
            </thead>
            <tbody>
                <PaymentTypeRow v-for="(payment, index) in PaymentTypesGetter" :key="index" :payment="payment" :index="index"/>
            </tbody>
        </table>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import PaymentTypeRow from './PaymentTypeRow.vue';
    export default {
        
        name: 'PaymentTypeTable',

        components: { PaymentTypeRow },

        computed: {

            ...mapGetters([
                'PaymentTypesGetter'
            ])
        }
    }
</script>

<style lang="scss" scoped>

</style>