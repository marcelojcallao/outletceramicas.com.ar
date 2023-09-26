<template>
    <div id="container">
        <div class="add-address-form" id="add-address-form">
            <h3 class="title">Asignar domicilio de entrega</h3>
            <div class="shipping">
                <label class="control-label" >Importe del traslado </label>
                <vue-autonumeric 
                        class="form-control text-right"
                        style="width:100px"
                        :options=options
                        v-model="shipping_value"
                    ></vue-autonumeric>
            </div>
            <customer-address />
                
            <div class="buttons">
                <button 
                    v-if="!OrderIsNew"
                    class="btn btn-primary"
                    :class="{'btn btn-primary spinner spinner-inverse spinner-sm' : spinner}"
                    @click="save_address"
                >
                    Guardar
                </button>
                <button 
                    class="btn btn-default"
                    @click="closePanel"
                >
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import toast_mixin from './../../../../mixins/toast-mixin';
import CustomerAddress from './../../admin/customer/new/CustomerAddress.vue';
import VueAutonumeric from './../../../../../../../node_modules/vue-autonumeric/src/components/VueAutonumeric';
export default {

    components : {
        CustomerAddress,
        VueAutonumeric
    },

    mixins : [toast_mixin],

    data() {
        return {
            spinner: false,
            options : {
                decimalPlaces : 0,
                digitGroupSeparator: '',
                decimalCharacter: ',',
                decimalCharacterAlternative: '.',
                currencySymbolPlacement: 'p',
                roundingMethod: 'U',
                minimumValue: '0',
                modifyValueOnWheel  : false,
                showOnlyNumbersOnFocus : true,
            },
        }
    },

    methods : {

        closePanel() {
            /** uso el formulario de usuario la parte del domicilio para agregarlo al pedido */
            this.$store.dispatch('newCustomerSetInitialStatus');
            this.$emit('closePanel', {});
            this.$store.dispatch('newCustomerSetNumber', '');
            /* const pedido_list_child_row = document.querySelector('#pedido-list-child-row');

            this.$scrollTo(pedido_list_child_row, {duration: 2000 , offset: -100}); */
        },

        async save_address () {

            this.spinner = true;
            
            const delivery_address = this.NewCustomerCompleteData;

            delivery_address.pedido_id = this.PedidoListChildRowReactivityData.id

            const address = await this.$store.dispatch('add_delivery_address_to_pedido', delivery_address)
                .catch((error) => {

                    const errors = error.response.data.errors;

                    let message = '';

                    for (const key in errors) {
                        if (errors.hasOwnProperty.call(errors, key)) {
                            message += errors[key][0] + '  ';
                        }
                    }

                    Vue.swal.fire({
                        title: 'Domicilio de entrega',
                        text : message,
                        icon : 'error',
                        width : '35%',
                        padding : '2rem',
                        backdrop : true,
                        time : 2000
                    });

                }).finally(()=>{
                    this.spinner = false;
                    this.$emit('closePanel', {});
                });
            
            if (address) {

                const child_row = document.getElementById("pedido-list-child-row");

                this.$scrollTo(child_row, {duration: 2000});

                Vue.swal.fire({
                    title: 'Domicilio de entrega',
                    text : 'Se ingresó correctamente',
                    icon : 'success',
                    width : '35%',
                    padding : '2rem',
                    backdrop : true,
                    time : 2000
                });

                this.$store.dispatch('setDeliveryAddressAtPedidoData', [address]);
                this.$store.dispatch('newCustomerSetInitialStatus');
            }
        },

    },

    computed: {
        ...mapGetters([
            'NewCustomerCompleteData',
            'PedidoListChildRowReactivityData',
            'NewOrderDataGetter',
            'OrderIsNew'
        ]),

        shipping_value: {
            get(){
                return this.NewOrderDataGetter.shipping.value;
            },
            set(value){
                this.$store.commit('NEW_ORDER_SET_REQUIRED_SHIPPING', true);
                this.$store.commit('NEW_ORDER_SET_SHIPPING_VALUE', value);
            } 
        }
    },

    mounted(){

        /* const panel = document.getElementById("add-address-form");
        
        panel.scrollIntoView({block: "start", behavior: "smooth"}); */

        if (this.PedidoListChildRowReactivityData) {
            
            const { delivery_addresses } = this.PedidoListChildRowReactivityData;
            
            if(delivery_addresses){

                this.$store.dispatch('newCustomerAddressSetState', {'id': delivery_addresses[0].state_id, 'name': delivery_addresses[0].state })
                this.$store.dispatch('newCustomerAddressSetCity', delivery_addresses[0].city);
                this.$store.dispatch('newCustomerAddressSetAddress', delivery_addresses[0].street)
                this.$store.dispatch('newCustomerAddressSetCp', delivery_addresses[0].cp)
                this.$store.dispatch('newCustomerAddressSetObs', delivery_addresses[0].obs)
                
            }
        }

        const elem = document.getElementById('add-address-form')
                    console.log("🚀 ~ file: PedidoClienteDataCustomerAndDeliveryData.vue:176 ~ showPanelAddDeliveryAdrress ~ elem", elem)
                    console.log("🚀 ~ file: scrollTop~ elem", elem.scrollTop)
                    console.log("🚀 ~ file: scrollHeight~ elem", elem.scrollHeight)
                    console.log("🚀 ~ file: scrollTo~ elem", elem.scrollTo)
                    window.scrollTo(0, 0);
    },

}
</script>

<style scoped>
    .bg-gray {
        background-color: gray;
    }
    .address{
       background-color: aqua;
    }
    .buttons{
        width: 100%;
        text-align: center;
    }
    #container{
        padding-top: 170px;
        padding-left: 15px;
    }
    .title{
        text-transform: uppercase;
        padding-bottom: 1rem;
    }
    .shipping{
        width: 50%;
        display: flex;
        margin-bottom: 2rem;
    }
    .shipping label{
        margin-right: 1rem;
    }
</style>