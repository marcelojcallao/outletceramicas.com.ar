<template>
    <div>
        <div class="customer-form">
            <h3>Ingreso de Clientes</h3>
            <search-person-on-afip />
            <customer-form-data />
            <customer-contact />
            <customer-address />
            <div class="buttons">
                <button 
                    class="btn btn-primary"
                    :class="{'btn btn-primary spinner spinner-inverse spinner-sm' : spinner}"
                    @click="store"
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
import SearchPersonOnAfip from '../../../customer/new/SearchPersonOnAfip.vue';
import CustomerFormData from '../../../customer/new/CustomerFormData.vue';
import CustomerAddress from '../../../customer/new/CustomerAddress.vue';
import CustomerContact from '../../../customer/new/CustomerContact.vue';
import RowProduct from '../Body/RowProduct.vue';
import toast_mixin from './../../../../../../mixins/toast-mixin';
export default {

    components : {
        RowProduct,
        SearchPersonOnAfip,
        CustomerFormData,
        CustomerAddress,
        CustomerContact
    },

    mixins : [toast_mixin],

    data() {
        return {
            spinner: false
        }
    },

    methods : {

        closePanel() {
            this.$store.dispatch('newCustomerSetInitialStatus');
            this.$emit('closePanel', {});
            this.$store.dispatch('newCustomerSetNumber', '');
        },

        async store () {

            this.spinner = true;
            
            const customer_data = this.NewCustomerCompleteData;

            const customer = await this.$store.dispatch('customer_store', customer_data)
                .catch((error) => {

                    const errors = error.response.data.errors;

                    let message = '';

                    for (const key in errors) {
                        if (errors.hasOwnProperty.call(errors, key)) {
                            message += errors[key][0] + '  ';
                        }
                    }

                    Vue.swal.fire({
                        title: 'Ingreso de clientes',
                        text : message,
                        icon : 'error',
                        width : '35%',
                        padding : '2rem',
                        backdrop : true,
                        time : 2000
                    });

                }).finally(()=>{
                    this.spinner = false;
                    this.$store.dispatch('newCustomerSetNumber', '');
                    this.closePanel();
                });
            
            if (customer) {

                Vue.swal.fire({
                    title: 'Ingreso de clientes',
                    text : 'Se ingresó correctamente',
                    icon : 'success',
                    width : '35%',
                    padding : '2rem',
                    backdrop : true,
                    time : 2000
                });

                this.$store.dispatch('newCustomerSetInitialStatus');
                this.$store.commit('NEW_ORDER_SET_CUSTOMER', customer);
            }
        },

        set_new_customer(customer){
            this.$store.commit('NEW_ORDER_SET_CUSTOMER', customer);
        },

        
    },

    computed: {
        ...mapGetters(['NewCustomerCompleteData'])
    },

    mounted(){

       /*  window.addEventListener('keydown', (e) =>{

            if (e.ctrlKey == true && e.key == 'F6') {
                $(this.$refs.customer_modal).modal('show');
                this.$refs.dniInput.focus();
            }

            if (e.ctrlKey == true && e.key == 'F10') {  
                $(this.$refs.customer_modal).modal('hide');
            }
            
        }); */
    }
}
</script>

<style scoped>
    .bg-gray {
        background-color: gray;
    }
    .customer-form{
        padding: 2rem;
    }
    .buttons{
        width: 100%;
        text-align: center;
    }
</style>