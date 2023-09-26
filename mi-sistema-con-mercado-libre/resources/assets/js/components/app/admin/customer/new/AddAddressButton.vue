<template>
    <div class="btn-group dropdown"
        :class="{' open' : open}" >
        <button 
            v-tooltip="'Agregar o Modificar domicilio'"
            @click="open=!open"
            class="btn btn-icon sq-32 dropdown-toggle"  type="button">
            <span class="material-icons">map</span>
        </button>
        <ul class="dropdown-menu">
            <li>
                <span class="text-center">
                    <CustomerAddressVue />
                </span> 
            </li>
            <div class="buttons">
                <button class="btn btn-primary" @click="save_customer_address"
                    :class="{'spinner spinner-inverse spinner-sm' : spinner}"
                >Guardar Domicilio</button>
                <button class="btn btn-default" @click="open=false">Cerrar</button>
            </div>
        </ul>
    </div>
</template>
<script>
import { mapGetters } from 'vuex';
import CustomerAddressVue from './CustomerAddress.vue';

   export default {

        name: 'AddAddressButton',

        components: {
            CustomerAddressVue
        },

        data(){
            return{
                open: false,
                spinner: false
            }
        },

        computed: {

            ...mapGetters([
                'PedidoListChildRowReactivityData',
                'NewCustomerAddressGetState',
                'NewCustomerAddressGetCity',
                'NewCustomerAddressGetAddress',
                'NewCustomerAddressGetCP',
                'NewCustomerAddressGetBetween',
                'NewCustomerAddressGetObs'
            ])
        },

        methods: {

            async save_customer_address(){

                Vue.swal.fire({

                    title: 'Ingresar domicilio',
                    text: 'Está a punto de cambiar datos del domicilio del cliente, ¿Continuar?',
                    icon : 'question',
                    showDenyButton: true,
                    confirmButtonText: 'Aceptar',
                    denyButtonText: `No, cerrar`,

                }).then( async (result) => {

                    if (result.isConfirmed) {
                        
                        this.spinner = true;

                        const payload = {

                            customer_id: this.PedidoListChildRowReactivityData.customer_id,

                            address: {
                                state: this.NewCustomerAddressGetState,
                                city: this.NewCustomerAddressGetCity,
                                address: this.NewCustomerAddressGetAddress,
                                cp: this.NewCustomerAddressGetCP,
                                between: this.NewCustomerAddressGetBetween,
                                obs: this.NewCustomerAddressGetObs
                            }

                        }

                        const address = await this.$store.dispatch('save_customer_address', payload)
                        .catch((err) => console.log(err))

                        if (address) {

                            const Toast = Vue.swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            });

                            Toast.fire({
                                icon: 'success',
                                title: 'Domicilio guardado correctamente.'
                            });

                            this.$store.dispatch('setCustomerAddressAtPedidoData', address);
                        }
                    }
                }).catch((error)=>{

                    Vue.swal.fire({
                        title: 'Domicilio',
                        text: error.response.data.message,
                        icon : 'info',
                        confirmButtonText: 'Aceptar',
                    })

                }).finally(()=> this.spinner = false);


                
            }
        }
   }
</script>
<style scoped>
    .dropdown-menu{
        min-width : 360px !important;
    }
    ul.dropdown-menu {
        width: 700px;
        right: -701px;
    }
    div.media{
        display: flex;
    }
    div.media .name{
        margin-left: 1rem;
    }
    .btn-color{
        background-color: #bbe4e9;
        color : #606470
    }
    .buttons{
        width: 100%;
        height: 5rem;
        text-align: center;
        margin-top: .5rem;
        margin-bottom: .8rem;
    }
</style>
