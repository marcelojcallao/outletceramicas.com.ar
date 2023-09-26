<template>
    <div id="product-multiselect">
        <label v-if="showTitle" >Producto</label>
        <multiselect placeholder="Buscar producto" 
            id="ajax"
            :class="'multiselect_product'"
            track-by="name" 
            label="name"
            selectLabel="Seleccionar"
            deselectLabel="Deseleccionar"
            :loading="show_spinner"
            :options="products"
            :value="ProductValue"
            @input="updateProductValue"
            :searchable="true"
            :internal-search="true" 
            :clear-on-select="false" 
            @search-change="asyncFind"
            @select="selectedProduct"
            @remove="removeProduct"
            open-direction="bottom"
            >
            <span slot="noOptions">
                {{noOptions}}
            </span>
            <span slot="noResult">
                {{noResult}}
            </span>
        </multiselect>
    </div>
</template>

<script>

import Multiselect from 'vue-multiselect';

export default {
    
    props : {
        index : {
            type : Number,
        },
        noOptions : {
            type : String,
            required : false,
            default : 'Lista Vacía'
        },
        noResult : {
            type : String,
            required : false,
            default : 'La búsqueda no arrojó resultados'
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
    #product-multiselect{
        width: 100%;
    }
    .multiselect{
        min-width: 100%;
    }
</style>
