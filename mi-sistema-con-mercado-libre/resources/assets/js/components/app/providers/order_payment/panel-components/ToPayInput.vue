<template>
    <div>
        <currency-input class="form-control text-center"
            type="text" 
            @focus="$event.target.select()" 
            ref="quantity"
            :currency="null"
            locale="es-AR"
            :allow-negative="false"
            :precision="2"
            v-model="toPay"
        />
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
    export default {
        
        props: {
            index: {
                required: true
            }
        },

        computed: {

            ...mapGetters([
                'invoicesToPayGetter'
            ]),

            toPay: {
                get(){
                    return this.invoicesToPayGetter[this.index-1].invoice.toPay;
                },
                set(value){
                    this.$store.dispatch('setToPayValue', { index: this.index-1, value })
                }
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>