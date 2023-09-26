<template>
    <multiselect placeholder="Modo de Pago" 
            id="payment_type"
            track-by="name" label="name"
            :options="PaymentTypesGetter"
            @input="updatePaymentTypeValue"
            :value="PaymentTypeGetter"
            selectLabel="Seleccionar"
            selectedLabel="Seleccionado"
            deselectLabel="Quitar"
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
    export default {
        
        components : {
            Multiselect
        }

    }
</script>
<style>
.multiselect--active{
    z-index: 90000;
}
</style>