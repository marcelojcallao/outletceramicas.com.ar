<template>
    <div>
        <SearchPersonOnAfip />
        <CustomerFormData />
        <CustomerAddress />
        <CustomerContact />
        <div class="row text-center">
            <button 
                class="btn btn-primary"
                :class="{'btn btn-primary spinner spinner-inverse spinner-sm' : spinner}"
                @click="store"
            >
                {{text_at_button}}
            </button>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import Multiselect from 'vue-multiselect';
import CustomerAddress from './CustomerAddress';
import CustomerContact from './CustomerContact';
import CustomerFormData from './CustomerFormData';
import SearchPersonOnAfip from './SearchPersonOnAfip';
import VueAutonumeric from '../../../../../../../../node_modules/vue-autonumeric/src/components/VueAutonumeric';
export default {

    props : {
        customer_id : {
            required : false
        },
        text_at_button : {
            required : false,
            type : String
        }
    },
    
    components : {
        Multiselect, VueAutonumeric, SearchPersonOnAfip, CustomerFormData, CustomerAddress, CustomerContact,
    },
    
    data() {
        return {
            spinner : false,
            //text_at_button : null
        }
    },

    /* methods : {

        async store () {

            this.spinner = true;
            
            this.sleep(500);
            
            let customer_data = this.NewCustomerCompleteData;

            let address_flag = false;

            for (const key in customer_data.address) {
                if (customer_data.address[key] != null ) {
                    address_flag = true;
                }
            }

            if (! address_flag) {
                customer_data.address = false;
            }

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

                }).finally(()=>this.spinner = false);
            
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
            }
        },

    }, */
    
    computed : {
        ...mapGetters([
            'Provinces',
            'ProviderName',
            'CustomerTipoClave',
            'PurchaserDocuments',
            'CustomerAddressProvince',
            'GetPersonWSPUC',
            'ProviderRegimen',
            'NewCustomerCompleteData'
        ]),
    },

    async mounted(){

        await this.$store.dispatch('get_accounting_accounts', this.User.token);
        let provinces = await this.$store.dispatch('getProvinces', this.User.token);

        if (provinces) {
            this.$store.commit('SET_PROVINCES', provinces.data);
        }
    },

}
</script>

<style scoped>
    .margin-top{
        margin-top: 15px;
    }
</style>