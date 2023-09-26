<template>
    <tr>
        <td class="text-center">
            {{number + 1}}
        </td>
        <td>
            <MultiselectTax name="Impuesto" :isAjax="false" :index="number"/>
        </td>
        <td>
            <currency-input 
                class="form--input"
                v-model="tax_import"
                @focus="$event.target.select()"
                type="text" 
                :precision="2"
                :currency="null"
                locale="es-AR"
            />
        </td>
        <td class="text-center center--vertical">
            <button 
                @click="remove_tax"
                class="btn btn-outline-danger btn-icon sq-32">
                <span class="icon icon-trash"></span>
                
            </button>
        </td>
    </tr>
</template>

<script>
    import MultiselectTax from './Multiselect-tax.vue';
    export default {
        
        props : ['number', 'tax'],
        
        components: {MultiselectTax},

        computed : {

            tax_import :{
                get(){
                    return this.$store.state.PurchaseInvoice.invoice.taxes[this.number].import
                },
                set(value){

                    const payload = {
                        index : this.number,
                        tax : this.tax,
                        import : value
                    }

                    this.$store.commit('PURCHASE_INVOICE_SET_TAX_IMPORT', payload);
                }
            }

        },

        methods: {

            remove_tax(){
                
                this.$store.commit('PURCHASE_INVOICE_REMOVE_TAX', this.number);
            }
        }

    }
</script>

<style lang="scss" scoped>

</style>