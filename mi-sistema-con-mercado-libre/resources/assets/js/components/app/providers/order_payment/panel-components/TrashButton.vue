<template>
    <div>
        <button 
            @click="delete_invoice" 
            v-tooltip="'Quitar'"
            class="btn btn-outline-danger btn-icon sq-32" 
            :disabled="invoicesToPayGetter.length == 1"
            type="button">
            <span class="icon icon-trash"></span>
        </button>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
    export default {
        
        props: {
            data: {
                required: true
            },
            index:{
                required: false
            }
        },

        methods: {

            delete_invoice(){
                this.$store.dispatch('removeInvoice', this.index-1)
            }
        },

        computed: {

            ...mapGetters([
                'invoicesToPayGetter'
            ])
        }
    }
</script>

<style lang="scss" scoped>

</style>