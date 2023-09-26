
<template>
    <multiselect placeholder="Modo de Pago"
            id="payment_type"
            track-by="name" label="name"
            :options="PaymentTypesGetter"
            @input="updatePayMethodToDiscount"
            :value="ApplyMethodPayDiscount"
            selectLabel="Seleccionar"
            selectedLabel="Seleccionado"
            deselectLabel="Quitar"
			:multiple="true"
            >

            <span slot="noOptions">
                    Lista Vacía
            </span>
            <span slot="noResult">
                    La búsqueda no arrojó resultados
            </span>
        </multiselect>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import { mapGetters } from 'vuex';
    export default {

        components : {
            Multiselect
        },

        data() {
            return {
                show_spinner : false,
            }
        },

        methods : {

            updatePayMethodToDiscount(el){
                this.$store.commit('PRODUCT_SET_APPLY_DISCOUNT_PAY_METHOD', el);
            }
        },

        computed : {

            ...mapGetters([
                'PaymentTypesGetter',
                'PaymentTypeGetter',
                'NewOrderPaymentTypeGetter',
                'NewOrderTotalsNeto',
                'PedidoListChildRowReactivityData',
				'ProductApplyMethodPayDiscount'
            ]),

            ApplyMethodPayDiscount(){
                return this.ProductApplyMethodPayDiscount;
            }
        },

        async mounted() {

			const { data } = await this.$store.dispatch('getPaymentTypes');

			this.$store.commit('SET_PAYMENT_TYPES', data);
        },
    }
</script>
<style scoped>
    .multiselect {
        width: 100%;
    }
</style>
