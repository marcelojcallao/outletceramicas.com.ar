<template>
    <div>
        <Multiselect :options="types" 
            :searchable="false"
            placeholder="Pedido o Cotización"
            track-by="name" 
            label="name"
            @input="pedidoClientesSetTypeAction"
            :value="NewOrderGetTypeGetter"
            :disabled="!Object.keys(CustomerValue).length"
        />
        <p class="has-error" v-if="Object.keys(CustomerValue).length === 0">Para habilitar el combo debe seleccionar un cliente</p>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect';
import {mapGetters, mapActions} from 'vuex';
export default {

    name : 'pedidoType',
    
    components : {Multiselect},

    data(){
        return {
            types : [
                {id : 101, code : 'PD-', name : 'PEDIDO CLIENTE'},
                {id : 102, code : 'CZ-', name : 'COTIZACION'},
            ],
            
        }
    },

    methods : {

        ...mapActions([
            'pedidoClientesSetTypeAction'
        ])
    },

    computed : {

        ...mapGetters(['NewOrderGetTypeGetter', 'CustomerValue'])
    }
}
</script>

<style scoped>
    .has-error{
        color: red;
        border-color: red;
        font-weight: bold;
    }
</style>