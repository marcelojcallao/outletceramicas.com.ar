<template>
    <div class="form form-inline" :class="{'has-error' : PurchaseInvoiceProviderErrorGetter}">
            <label class="control-label">PROVEEDOR: NOMBRE / CUIT</label>
            <multiselect 
                placeholder="Buscar Comprobante de venta" 
                id="ajax"
                track-by="name" label="name"
                :loading="spinner"
                :value="GetPurchaseInvoiceSupplier"
                @input="setSelectedProvider"
                :options="GetProvidersGetters"
                :searchable="true"
                :internal-search="false" 
                :clear-on-select="true" 
                @search-change="asyncFind"
                @remove="removeSupplier"
                selectLabel="Seleccionar"
                selectedLabel="Seleccionado"
                deselectLabel="Remover"
                >
                
                <span slot="noOptions">
                        Lista Vacía
                </span>
                <span slot="noResult">
                        La búsqueda no arrojó resultados
                </span>
            </multiselect>
        <p class="text-danger" v-if="PurchaseInvoiceProviderErrorGetter">{{PurchaseInvoiceProviderErrorGetter[0]}}</p>
    </div>
</template>

<script>
import {Event} from 'vue-tables-2';
import Multiselect from 'vue-multiselect';
import auth from './../../../mixins/auth';
import { mapGetters } from 'vuex';
import { required } from 'vuelidate/lib/validators';
export default {
    components : {
        Multiselect
    },
    mixins : [auth],
    data() {
        return {
            spinner : false
        }
    },

    methods : {

        async asyncFind (query) {

            const payload = {
                token : this.User.token,
                provider : query
            }

            const { data:suppliers } = await this.$store.dispatch('provider_find_by_name', payload);

            if (suppliers) {
                
                this.$store.commit('SET_PROVIDERS', suppliers);
            }
        },

        removeSupplier(el){
            
        },

        setSelectedProvider(el){
            //this.$store.dispatch('purchaseInvoiceListSetSupplier', el)
            this.$store.commit('PURCHASE_INVOICE_SET_SUPPLIER', el)
        }
    },

    computed : {

        ...mapGetters([
            'SelectedProviderGetter',
            'GetProvidersGetters',
            'PurchaseInvoiceProviderErrorGetter',
            'GetIvas',
            'Vouchers',
            'GetProviderGetter',
            'GetPurchaseInvoiceSupplier'
        ])
    },

    validations(){
        return {
            GetProviderGetter:{
                required
            }
        }
    },

    watch : {

        GetProviderGetter(n)
        {

            const vouchers = this.Vouchers.filter(voucher => voucher.inscription_id == n.inscription_id );
            
            this.$store.dispatch('setVouchersByInscription', vouchers);

        }
    },

    mounted() {
        Event.$on('clean_async_customers', () => {
            this.customers = [];
            this.customer = null;
        })
    },
}
</script>
