<template>
        <button
            @click="changeStatus"
            class="btn btn-icon sq-32"
            :class="[
                ( button.status_id == PedidoListChildRowReactivityData.status_id + 1 ) ? {'spinner spinner-inverse spinner-sm' : spinner} : '',
                ( DisabledButton ) ? 'btn-color' : ''
            ]"
            type="button"
            :disabled="! DisabledButton"
            v-tooltip="button.tooltip"
            style="margin-bottom:15px">
            <span class="material-icons">{{button.icon}}</span>
        </button>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {Event} from 'vue-tables-2';
    import StateFactory from './../../../../src/pedidoCliente/StateFactory';
    import Context from './../../../../src/pedidoCliente/ContextPedidoCliente';

    export default {

        props: ['button'],

        data() {
            return {
                spinner : null,
                my_status: null,
                status_order: null
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
                console.log("🚀 ~ file: status-button.vue:143 ~ this.PedidoListChildRowReactivityData:", this.PedidoListChildRowReactivityData)

                StateClass.setStore(this.$store);

                contextExecutor.setState(StateClass);

                const pedido_updated = await contextExecutor.executeAction()
                .catch((err)=> {
                    console.log("🚀 ~ file: status-button.vue:150 ~ err:", err)
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

        beforeMount() {
            this.status_order = this.PedidoListChildRowReactivityData.status_id;
            this.my_status = this.button.status_id;
        },

        computed : {
            ...mapGetters([
                'PedidoListChildRowReactivityData'
            ]),

            DisabledButton(){
                if (( parseInt(this.my_status) - parseInt(this.status_order) ) == 1) {
                    return true;
                }

                return false;
            }
        },

        watch : {

            PedidoListChildRowReactivityData :
            {
                handler(n)
                {
                    this.status_order = n.status_id;
                },
                deep : true
            }
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

