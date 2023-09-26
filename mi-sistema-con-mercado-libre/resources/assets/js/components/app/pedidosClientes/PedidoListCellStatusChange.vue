<template>
    <div class="flexBox">
        <div v-for="(button, index) in buttons" :key="index"
            style=" float: left"
        >
            <statusButton :button="button" v-if="button.type == 'normal'"/>

            <WhoPrepared v-if="button.type == 'whoPrepared'"
                :button="button"
            />

            <WhoDispatch v-if="button.type == 'whoDispatch'"
                :button="button"
            />

            <ChangeDateInvoice v-if="button.type == 'change_invoice_date'"
                :button="button"
            />
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import * as constants from './../../../src/const/Status';
    import WhoPrepared from './status/WhoPrepared';
    import WhoDispatch from './status/WhoDispatch';
    import ChangeDateInvoice from './status/ChangeDateInvoice';
    import statusButton from './status/status-button.vue';
    export default {

        props: ['data'],

        components:{
            WhoPrepared, WhoDispatch,
            ChangeDateInvoice, statusButton
        },

        data() {
            return {
                buttons : [
                    {
                        status_id : constants.REMITIDO,
                        type : 'normal',
                        tooltip : 'Generar remito',
                        click : 'una función',
                        method : this.changeStatus,
                        class : 'btn btn-default btn-icon sq-32',
                        icon : 'description',
                    },
                    {
                        status_id : constants.PRESUPUESTADO,
                        type : 'normal',
                        tooltip : 'Generar presupuesto',
                        click : 'una función',
                        method : this.changeStatus,
                        class : 'btn btn-default btn-icon sq-32',
                        icon : 'post_add',
                    },
                    {
                        status_id : constants.FACTURADO,
                        type : 'change_invoice_date',
                        tooltip : 'Generar factura',
                        click : 'una función',
                        method : this.changeStatus,
                        class : 'btn btn-default btn-icon sq-32',
                        icon : 'edit_note',
                    },
                    {
                        status_id : constants.PREPARADO,
                        type : 'whoPrepared',
                        tooltip : 'Preparar pedido',
                        title : 'Nombre de quien prepara el pedido',
                        click : 'una función',
                        method : this.changeStatus,
                        class : 'btn btn-default btn-icon sq-32',
                        icon : 'done',
                    },
                    {
                        status_id : constants.RETIRADO,
                        type : 'normal',
                        tooltip : 'Retirar pedido',
                        click : 'una función',
                        method : this.changeStatus,
                        class : 'btn btn-default btn-icon sq-32',
                        icon : 'logout',
                    },
                    {
                        status_id : constants.DESPACHADO,// a enviar
                        type : 'normal',
                        tooltip : 'Despachar pedido',
                        click : 'una función',
                        method : this.changeStatus,
                        class : 'btn btn-default btn-icon sq-32',
                        icon : 'login',
                    },
                    {
                        status_id : constants.ENTREGADO,
                        type : 'whoDispatch',
                        title : 'Nombre de quien entrega',
                        tooltip : 'Entregar pedido',
                        click : 'una función',
                        method : this.changeStatus,
                        class : 'btn btn-default btn-icon sq-32',
                        icon : 'account_circle',
                    },
                ]
            }
        },

        methods : {

            async wsdeError(text){

            if(text.includes('No hay stock para el producto')){

                const errorMessage = text.split('cut_flag');

                let html = '';

                errorMessage.forEach(element => {
                    if (element[1] != '|') {
                        html = `${html} <p> ${element} </p>`
                    }
                });
                Vue.swal.fire({
                    title: 'No hay stock disponible',
                    html : html,
                    icon : 'error',
                    width : '35%',
                    padding : '2rem',
                    backdrop : true,
                    confirmButtonText: 'Cerrar',
                    confirmButtonColor: '#d33',
                });

            }else{

                const errorMessage = text.split('cut_flag');

                const stockProduct = text.split('|');

                let html = '';

                errorMessage.forEach(element => {
                    if (element[1] != '|') {
                        html = `${html} <p> ${element} </p>`
                    }
                });

                    await Vue.swal.fire({
                        title: 'PEDIDO - Se ha producido un error',
                        html : html,
                        icon : 'error',
                        width : '35%',
                        padding : '2rem',
                        backdrop : true,
                        showCancelButton: true,
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Cerrar',
                        confirmButtonText : 'Asignar Stock disponible',
                        confirmButtonColor: '#00bbf0',
                    }).then( async (result) => {

                        if (result.isConfirmed) {

                            const payload = {
                                pedido_id: this.PedidoListChildRowReactivityData.id,
                                products: stockProduct[1]
                            }

                            const Toast = Vue.swal.mixin({
                                    toast: true,
                                    position: 'bottom-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });

                            this.$store.commit('SET_QUANTITY_FROM_PRODUCT_STOCK', JSON.parse(stockProduct[1]));

                            const resp = await this.$store.dispatch('update_items_of_pedido', payload)
                            .catch((err) => {

                                Toast.fire({
                                    icon: 'error',
                                    title: err.response.data
                                });
                            });

                            if (resp) {
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Pedido actualizado correctamente'
                                });
                            }
                        }
                    });
                }
            },

            async changeStatus($event)
            {
                this.spinner = true;

                const stateFactory = new StateFactory;

                stateFactory.setCurrentStatus(this.PedidoListChildRowReactivityData.status_id);

                const contextExecutor = new Context;

                const State = stateFactory.getInstance();

                //se instancia la clase del estado que se pide
                const StateClass = new State;

                StateClass.setData(this.PedidoListChildRowReactivityData);

                StateClass.setStore(this.$store);

                contextExecutor.setState(StateClass);

                const pedido_updated = await contextExecutor.executeAction()
                .catch((err)=> {
                    this.wsdeError(err.response.data.message);
                })
                .finally(() => this.spinner = false)

                if (pedido_updated) {
                    this.success_message('Actualizado', 'El estado del pedido ahora es ' + pedido_updated.data.status);

                    //Vue.set(this.data, 'status_id', pedido_updated.data.status_id);
                    //this.$store.commit('SET_STATUS_ID_AT_PEDIDO_DATA', pedido_updated.data.status_id)

                    Event.$emit('pedido_cliente_list', pedido_updated.data);

                    this.$store.commit('SET_PEDIDO_LIST_CHILD_ROW_REACTIVITY_DATA', pedido_updated.data);
                }

            },

        },

        computed : {
            ...mapGetters([
                'PedidoListChildRowReactivityData'
            ]),

        },
    }
</script>
<style scoped>
    .flexBox{
        display: flex;
    }
    .btn-color{
        background-color: #bbe4e9;
        color : #606470
    }
</style>

