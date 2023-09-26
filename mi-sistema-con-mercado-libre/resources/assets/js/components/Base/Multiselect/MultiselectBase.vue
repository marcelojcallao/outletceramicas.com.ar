<template>
    <multiselect 
        :placeholder="placeHolder" 
        track-by="name" 
        label="name"
        selectLabel="Seleccionar"
        deselectLabel="Deseleccionar"
        :id="(isAjax) ? 'ajax' : name + '_multiselect'"
        :class="'multiselect_product'"
        :loading="show_spinner"
        :limit="3"
        :options="options"
        :value="Value"
        :searchable="true"
        :internal-search="(isAjax) ? false : true" 
        :clear-on-select="false" 
        :disabled="disabled"
        @input="updateValue"
        @select="selected"
        @remove="remove"
        @search-change="asyncFind"
        >
        <span slot="noOptions">
            {{noOptionsText}}
        </span>
        <span slot="noResult">
            {{noResultsText}}
        </span>
    </multiselect>
</template>

<script>
import Multiselect from 'vue-multiselect';
export default {
    
    props : {
        index : {
            type : Number,
            required : false
        },
        isAjax : {
            type : Boolean,
            required : false,
            default : false
        },
        name : {
            type : String,
            required : true
        },
        disabled : {
            type : Boolean,
            required : false,
            default : false
        },
        noOptionsText : {
            type : String,
            required : false,
            default : 'Lista Vacía'
        },
        noResultsText : {
            type : String,
            required : false,
            default : 'La búsqueda no arrojó resultados'
        },
        placeHolder : {
            type : String,
            required : false,
            default : 'Buscar'
        }
    },

    components : {Multiselect},

    data(){
        return {
            show_spinner : false,
            options : []
        }
    }

}

</script>

