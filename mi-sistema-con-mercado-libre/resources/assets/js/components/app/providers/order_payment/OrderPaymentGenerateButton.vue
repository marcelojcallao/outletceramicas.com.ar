<template>
    <div>
        <button
            @click="order_payment_generate"
            :disabled="! invoicesToPayGetter.length"
            class="btn btn-warning"
            :class="{'btn btn-warning spinner spinner-inverse spinner-sm' : spinner}"
            type="button"
            >
            Generar Orden de pago
        </button>
        <slideout-panel></slideout-panel>
    </div>
</template>

<script>
import OrderPaymentPanel from './OrderPaymentPanel.vue';
import { mapGetters } from 'vuex';
    export default {

        name: 'OrderPaymentGenerateButton',

        components: {OrderPaymentPanel},

        data(){
            return {
                spinner: false,
            }
        },

        computed: {

            ...mapGetters([
                'invoicesToPayGetter',
            ])
        },

        methods: {

            async order_payment_generate(){

                const panel1Handle = await this.$showPanel({
                    component : OrderPaymentPanel,
                    openOn: 'top',
                    height: '850px',
                    props: {
                        //any data you want passed to your component
                    }
                })
            }
        }
    }
</script>
