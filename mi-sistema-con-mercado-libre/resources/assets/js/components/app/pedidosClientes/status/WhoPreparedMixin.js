import {mapGetters} from 'vuex';
import {Event} from 'vue-tables-2';
    import StateFactory from './../../../../src/pedidoCliente/StateFactory';
    import Context from './../../../../src/pedidoCliente/ContextPedidoCliente';
export default {
        
    props : {
        button: {
            required: true
        }
    },

    data(){
        return {
            spinner : null,
            my_status: null,
            status_order: null,
            open : false,
            loading_update : false,
        }
    },

    methods : {

        dropdown_open()
        {
            const open = ! this.OpenWhoPreparedInputGetter;
            
            this.$store.commit('SET_OPEN_WHO_PREPARED_INPUT', open);
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

                    this.dropdown_open();
                }
                
            },

    },

    computed : {

        ...mapGetters([
            'WhoPreparedGetter',
            'NewOrderStatusGetter',
            'WhoPreparedSpinnerGetter',
            'OpenWhoPreparedInputGetter',
            'PedidoListChildRowReactivityData',
        ]),

        who : {
            get(){
                return this.WhoPreparedGetter;
            },
            set(value){
                this.$store.commit('SET_WHO_PREPARED', value);
            }
        },

        OpenWhoInputGetter(){
            return this.OpenWhoPreparedInputGetter;
        },

        WhoSpinnerGetter(){
            return this.WhoPreparedSpinnerGetter;
        },

        EnableSaveButton(){
            return this.WhoPreparedGetter;
        },

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

    beforeMount() {
        this.status_order = this.PedidoListChildRowReactivityData.status_id;
        this.my_status = this.button.status_id;
    },
}