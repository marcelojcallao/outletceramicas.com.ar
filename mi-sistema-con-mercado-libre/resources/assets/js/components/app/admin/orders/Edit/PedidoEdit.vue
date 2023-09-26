<template>
    <div id="pedido-edit">
        <h3 >EDITAR {{PedidoListChildRowReactivityData.type}}</h3>
        <div id="order-number">
            <h4>{{PedidoListChildRowReactivityData.type}} N°: {{PedidoListChildRowReactivityData.code}}</h4>
        </div>
        <DeleveryAddress />
        <CreationDate />
        <ListProducts />
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import 'vue2-datepicker/index.css';
import DeleveryAddress from './components/delivery-address.vue';
import CreationDate from './components/creation-date.vue';
import ListProducts from './components/list-products.vue';
export default {

    name: 'PedidoEdit',

    components: {
        DeleveryAddress,
        CreationDate,
        ListProducts
    },

    computed: {

        ...mapGetters(['PedidoListChildRowReactivityData'])
    },

    mounted(){

        const panel = document.getElementById("pedido-edit");
        
        panel.scrollIntoView({block: "start", behavior: "smooth"});
        
    },

}
</script>
<style>

.slideout-panel .slideout-wrapper .slideout {
    transition-timing-function: cubic-bezier(0.215, 0.61, 0.355, 1);
    transition-duration: 0.8s;
}
#pedido-edit{
    padding-top: 50px;
    padding-left: 15px;
}
p{
    display: inline-block
}

#order-number{
    height: 3rem;
    width: 100%;
    color: black;
    margin: 1rem 0rem;
    text-align: center;
}

#button-change-address{
    margin-right: 4rem;
}
#payment-type{
    display: flex;
    justify-content: space-between;
    margin-left: 1rem;
    height: 3rem;
    width: 100%;
    margin-top: 2rem;
    margin-bottom: 2rem;
}
.buttons{
        display: flex;
        height: 31px;
        width: 100%;
        justify-content: center;
    }

    .buttons button {
        margin-left: 1rem;
    }
    .buttons p {
        margin: .5rem;
    }
</style>