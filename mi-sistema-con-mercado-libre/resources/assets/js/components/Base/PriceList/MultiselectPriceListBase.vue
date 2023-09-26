<template>
    <div id="price-list-multiselect">
        <label v-if="showTitle">Lista de Precio</label>
        <multiselect placeholder="Buscar producto" 
            id="ajax"
            :class="'multiselect_price_list_index_' + index"
            track-by="name" 
            label="name"
            selectLabel="Seleccionar"
            deselectLabel="Deseleccionar"
            :loading="show_spinner"
            :options="price_list"
            :value="PriceListValue"
            @input="updatePriceListValue"
            :searchable="true"
            :clear-on-select="false" price-list-multiselect
            @select="selectedPriceList"
            @remove="removePriceList"
            >
        </multiselect>
    </div>
</template>

<script>

import Multiselect from 'vue-multiselect';

export default {
    
    props : {
        indes: {
            type: Number
        },
        showTitle: {
            type: Boolean,
            default: false
        }
    },

    components : {Multiselect},

}

</script>
<style>
    #price-list-multiselect{
        width: 100%;
    }
    .multiselect{
        min-width: 100%;
    }
</style>
