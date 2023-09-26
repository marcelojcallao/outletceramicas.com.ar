<template>
    <div>
        <button style="margin-bottom: .8rem;"
            @click="goTo" 
            v-tooltip="'Editar producto'"
            class="btn btn-default btn-icon sq-32" 
            type="button">
            <span class="icon icon-pencil"></span>
        </button>
        <button 
            @click="delete_product" 
            v-tooltip="'Eliminar producto'"
            class="btn btn-danger btn-icon sq-32" 
            type="button">
            <span class="icon icon-trash"></span>
        </button>
    </div>
</template>
<script>
import edit_button_mixin from '../../../mixins/Products/EditProduct';
    export default {
        
        mixins : [edit_button_mixin],

    }
</script>