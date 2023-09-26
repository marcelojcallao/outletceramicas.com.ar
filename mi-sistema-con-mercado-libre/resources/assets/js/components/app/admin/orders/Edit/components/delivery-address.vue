<template>
    <div id="delivery-address">
            <p>
                Domicilio de entrega: 
                {{(PedidoListChildRowReactivityData.delivery_address)
                    ? PedidoListChildRowReactivityData.delivery_address
                    : 'Falta definir'
                }}
            </p>
            <!-- <button @click="showPanelAddDeliveryAdrress" id="button-change-address" class="btn btn-primary">Cambiar</button> -->
        </div>
</template>

<script>

import { mapGetters } from 'vuex';
import PanelAddDeliveryAdrress from './../../../../pedidosClientes/panel/PanelAddDeliveryAdrress.vue';
export default {

    name: 'delivery-address',

    components: {PanelAddDeliveryAdrress},

    methods: {
        
        showPanelAddDeliveryAdrress() {
                const panel1Handle = this.$showPanel({
                    component : PanelAddDeliveryAdrress,
                    openOn: 'right',
                    height: '500px',
                    props: {
                        //any data you want passed to your component
                    }
                })

                panel1Handle.promise
                .then(result => {
                    
                });
            },
    },

    computed: {

        ...mapGetters(['PedidoListChildRowReactivityData']),
    }
}
</script>

<style>
    #delivery-address{
        display: flex;
        justify-content: space-between;
        margin-left: 1rem;
        height: 3rem;
        width: 100%;
        margin-bottom: 2rem;
    }
</style>