<template>
    <div>
        <button v-if="button.type == 'normal'"
            :data-status="button.status_id"
            @click="set_pedidocliente_data"
            class="btn btn-icon sq-32"
            :class="[
                (button.status_id == external_data.status_id +1) ? {'spinner spinner-inverse spinner-sm' : spinner} : '',
                (!DisabledButton) ? '' : 'btn-color'
            ]"
            type="button"
            :disabled="!DisabledButton"
            style="margin-bottom:15px">
            <span class="material-icons">{{button.icon}}</span>
        </button>

        <WhoPrepared v-if="button.type == 'whoPrepared'"
            :icon="button.icon"
            :title="button.title"
            :injectedFunction="set_pedidocliente_data"
            :status_id="button.status_id"
            :data="external_data"
        />

        <WhoDispatch v-if="button.type == 'whoDispatch'"
            :icon="button.icon"
            :title="button.title"
            :injectedFunction="set_pedidocliente_data"
            :status_id="button.status_id"
            :data="external_data"
        />
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import {Event} from 'vue-tables-2';
import auth from './../../../mixins/auth';
import sleep from './../../../mixins/sleep-mixin';
import toast_mixin from './../../../mixins/toast-mixin';
import PedidoClienteTipoPersona from './PedidoClienteTipoPersona';
import StateFactory from './../../../src/pedidoCliente/StateFactory';
import Context from './../../../src/pedidoCliente/ContextPedidoCliente';
import CustomerSearchAfipData from './../../app/customers/CustomerSearchAfipData';
import WhoPrepared from './status/WhoPrepared';
import WhoDispatch from './status/WhoDispatch';
import * as constants from './../../../src/const/Status';
export default {

    name : 'ButtonStatus',

    props : {
        external_data : {
            required : false,
        },
        button : {
            required : false,
        }
    },

    components:{
            CustomerSearchAfipData, PedidoClienteTipoPersona, WhoPrepared, WhoDispatch
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

            }
        },

        methods : {

            async wsdeError(text){
                await Vue.swal.fire({
                    title: 'AFIP - Factura electrónica',
                    text : text,
                    icon : 'error',
                    width : '35%',
                    padding : '2rem',
                    backdrop : true,
                    confirmButtonText : 'Aceptar',
                    confirmButtonColor: '#00bbf0',
                })
            },

            async set_pedidocliente_data($event)
            {
                this.spinner = true;

                const stateFactory = new StateFactory;

                stateFactory.setCurrentStatus(this.external_data.status_id);

                const contextExecutor = new Context;

                const State = stateFactory.getInstance();

                //se instancia la clase del estado que se pide
                const StateClass = new State;
                StateClass.setData(this.external_data);

                StateClass.setStore(this.$store);

                contextExecutor.setState(StateClass);

                let pedido_updated = await contextExecutor.executeAction()
                .catch((err)=> {
                    this.wsdeError(err.response.data.message);
                    this.spinner = false;
                });

                this.success_message('Actualizado', 'El estado del pedido ahora es ' + pedido_updated.data.status);

                Vue.set(this.external_data, 'status_id', pedido_updated.data.status_id);

                this.status_pedido_cliente = pedido_updated.data.status_id;

                Event.$emit('pedido_cliente_list', pedido_updated.data);

                this.$store.commit('SET_PEDIDO_LIST_CHILD_ROW_REACTIVITY_DATA', this.external_data);

                this.spinner = false;

            },

            enable_button(button_status_id)
            {
                let resto = parseInt(button_status_id) - parseInt(this.external_data.status_id);

                if ( resto == 1 ) {
                    return true;
                }

                return false;

            },

            has_address_or_has_delivery_date(button_status_id, data)
            {
                //if ((!(data.pedido_fiscal_address || data.pedido_delivery_address) ) ) {

                if ((!(data.pedido_fiscal_address ) ) ) {
                    return true;
                }

                return false;
            },

        },

        computed : {
            ...mapGetters([
                'GetCompany',
                'GetPrintComments',
                'PedidoClientesAddNewAddressGetter',
                'PedidosClientesDeliveryAddressGetter',
                'PedidoListChildRowReactivityData'
            ]),

            DisabledButton(){

                if (parseInt(this.button.status_id) == parseInt(constants.REMITIDO) && parseInt(this.PedidoListChildRowReactivityData.status_id) == parseInt(constants.PENDIENTE)) {
                    return true;
                }

                if (parseInt(this.button.status_id) == parseInt(constants.PRESUPUESTADO) && parseInt(this.PedidoListChildRowReactivityData.status_id) == parseInt(constants.PENDIENTE)) {
                    return true;
                }

                if (parseInt(this.button.status_id) == parseInt(constants.FACTURADO) && parseInt(this.PedidoListChildRowReactivityData.status_id) == parseInt(constants.REMITIDO)) {
                    return true;
                }

                if (parseInt(this.button.status_id) == parseInt(constants.FACTURADO) && parseInt(this.PedidoListChildRowReactivityData.status_id) == parseInt(constants.PRESUPUESTADO)) {
                    return true;
                }

                if (parseInt(this.button.status_id) == parseInt(constants.PREPARADO) && parseInt(this.PedidoListChildRowReactivityData.status_id) == parseInt(constants.FACTURADO)) {
                    return true;
                }

                if (parseInt(this.button.status_id) == parseInt(constants.RETIRADO) && parseInt(this.PedidoListChildRowReactivityData.status_id) == parseInt(constants.PREPARADO)) {
                    return true;
                }

                if (parseInt(this.button.status_id) == parseInt(constants.DESPACHADO) && parseInt(this.PedidoListChildRowReactivityData.status_id) == parseInt(constants.PREPARADO)) {
                    return true;
                }

                if (parseInt(this.button.status_id) == parseInt(constants.ENTREGADO) && parseInt(this.PedidoListChildRowReactivityData.status_id) == parseInt(constants.RETIRADO)) {
                    return true;
                }

                if (parseInt(this.button.status_id) == parseInt(constants.ENTREGADO) && parseInt(this.PedidoListChildRowReactivityData.status_id) == parseInt(constants.DESPACHADO)) {
                    return true;
                }

                return false;

            }
        },

}
</script>

<style>
    .btn-color{
        background-color: #bbe4e9;
        color : #606470
    }
</style>
