<template>
    <div class="flexBox">
        <div v-for="(button, index) in buttons" :key="index"
            v-tooltip="button.tooltip"
            style=" float: left"
        >
            <ButtonStatus :external_data="data" :button="button"/>
        </div>
    </div>
</template>

<script>
    import ButtonStatus from "./ButtonStatus.vue"
    import {mapGetters} from 'vuex';
    import auth from './../../../mixins/auth';
    import sleep from './../../../mixins/sleep-mixin';
    import toast_mixin from './../../../mixins/toast-mixin';
    import * as constants from './../../../src/const/Status';
    import PedidoClienteTipoPersona from './PedidoClienteTipoPersona';
    import CustomerSearchAfipData from './../../app/customers/CustomerSearchAfipData';
    
    export default {

        props: ['data'],

        components:{
            CustomerSearchAfipData, PedidoClienteTipoPersona, ButtonStatus
        },

        mixins : [auth, toast_mixin, sleep],

        data() {
            return {
                spinner : null,
                status_pedido_cliente : null,
                bill_type : 'F',
                cbte_tipo : null,
                tributos_import : 0,
                array_tributos : [],
                total_import : 0,
                FECAEDetRequest : null,
                cbtes_asociados : [],
                impo_Tot_Conc : 0,
                buttons : [
                    {
                        status_id : constants.REMITIDO,
                        type : 'normal',
                        tooltip : 'Generar remito',
                        click : 'una función',
                        method : this.set_pedidocliente_data,
                        class : 'btn btn-default btn-icon sq-32',
                        icon : 'description',
                    },
                    {
                        status_id : constants.PRESUPUESTADO,
                        type : 'normal',
                        tooltip : 'Generar presupuesto',
                        click : 'una función',
                        method : this.set_pedidocliente_data,
                        class : 'btn btn-default btn-icon sq-32',
                        icon : 'post_add',
                    },
                    {
                        status_id : constants.FACTURADO,
                        type : 'normal',
                        tooltip : 'Generar factura',
                        click : 'una función',
                        method : this.set_pedidocliente_data,
                        class : 'btn btn-default btn-icon sq-32',
                        icon : 'edit_note',
                    }, 
                    {
                        status_id : constants.PREPARADO,
                        type : 'whoPrepared',
                        tooltip : 'Preparar pedido',
                        title : 'Nombre de quien prepara el pedido',
                        click : 'una función',
                        method : this.set_pedidocliente_data,
                        class : 'btn btn-default btn-icon sq-32',
                        icon : 'done',
                    },
                    {
                        status_id : constants.RETIRADO,
                        type : 'normal',
                        tooltip : 'Retirar pedido',
                        click : 'una función',
                        method : this.set_pedidocliente_data,
                        class : 'btn btn-default btn-icon sq-32',
                        icon : 'logout',
                    },
                    {
                        status_id : constants.DESPACHADO,// a enviar
                        type : 'normal',
                        tooltip : 'Despachar pedido',
                        click : 'una función',
                        method : this.set_pedidocliente_data,
                        class : 'btn btn-default btn-icon sq-32',
                        icon : 'login',
                    },
                    {
                        status_id : constants.ENTREGADO,
                        type : 'whoDispatch',
                        title : 'Nombre de quien entrega',
                        tooltip : 'Entregar pedido',
                        click : 'una función',
                        method : this.set_pedidocliente_data,
                        class : 'btn btn-default btn-icon sq-32',
                        icon : 'account_circle',
                    },
                ]
            }
        },

        
    }
</script>
<style scoped>
    .flexBox{
        display: flex;
    }
    
</style>