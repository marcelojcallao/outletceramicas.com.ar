<template>
    <div class="form-group">
        <div class="col-md-5" :class="{'has-error':PurchaseInvoiceTypeErrorGetter}" >
            <label class="control-label">COMPROBANTE</label>
            <multiselect placeholder="Comprobantes" 
                track-by="name" label="name"
                :options="GetVouchersByInscriptionGetter"
                :value="PurchaseInvoiceType"
                :loading="voucher_spinner"
                @input="purchaseInvoiceSelectInvoiceType"
                :clear-on-select="false" 
                >
                <span slot="noOptions">
                        Lista Vacía
                </span>
                <span slot="noResult">
                        La búsqueda no arrojó resultados
                </span>
            </multiselect>
            <p v-if="PurchaseInvoiceTypeErrorGetter">{{PurchaseInvoiceTypeErrorGetter[0]}}</p>
        </div>
        <div class="col-md-3" :class="{'has-error':PurchaseInvoiceSubsidiaryErrorGetter}">
            <label class="control-label">SUCURSAL</label>
            <currency-input 
                class="form--input"
                v-model="subsidiary"
                v-mask="'99999'"
                @focus="$event.target.select()"
                type="text" 
                :precision="0"
                :allow-negative="false"
                :value-as-integer="true"
                :currency="null"
                locale="es-AR"
            />
            <p v-if="PurchaseInvoiceSubsidiaryErrorGetter">{{PurchaseInvoiceSubsidiaryErrorGetter[0]}}</p>
        </div>
        <div class="col-md-4" :class="{'has-error':PurchaseInvoiceNumberErrorGetter}">
            <label class="control-label">NUMERO</label>
            <currency-input 
                class="form--input"
                v-model="number"
                v-mask="'99999999'"
                @focus="$event.target.select()"
                type="text" 
                :currency="null"
                locale="es-AR"
                :presicion="0"
                :allow-negative="false"
                :value-as-integer="true"
            />
            <p v-if="PurchaseInvoiceNumberErrorGetter">{{PurchaseInvoiceNumberErrorGetter[0]}}</p>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import Multiselect from 'vue-multiselect';
import AwesomeMask from 'awesome-mask'
export default {

    name: 'Multiselect-Vouchers', 

    props : {
        spinner : {
            default : false
        }
    },

    components : {
        Multiselect
    },

    directives: {
        'mask': AwesomeMask
    },

    data() {
        return {
            money : null,
            vouchers: [],
            voucher_spinner: null
        }
    },
    methods : {
        ...mapActions(['purchaseInvoiceSelectInvoiceType'])
    }, 

    watch : {

        
    },

    computed : {

        ...mapGetters([
            'PurchaseInvoiceType',
            'PurchaseInvoiceSubsidiary',
            'PurchaseInvoiceNumber',
            'PurchaseInvoiceTypeErrorGetter',
            'PurchaseInvoiceSubsidiaryErrorGetter',
            'PurchaseInvoiceNumberErrorGetter',
            'SelectedProviderGetter',
            'GetVouchersGetter',
            'GetProviderGetter',
            'GetVouchersByInscriptionGetter',
            'Vouchers'
        ]),

        subsidiary : {
            get()
            {
                return this.PurchaseInvoiceSubsidiary;
            },
            set(value)
            {
                this.$store.commit('PURCHASE_INVOICE_SET_SUBSIDIARY', value);
            }
        },

        number : {
            get()
            {
                return this.PurchaseInvoiceNumber;
            },
            set(value)
            {
                this.$store.commit('PURCHASE_INVOICE_SET_NUMBER', value);
            }
        }

    },

    async mounted(){

        this.voucher_spinner = this.spinner;

        this.voucher_spinner = true;

        const vouchers = await this.$store.dispatch('getVouchers', this.User.token);

        if (vouchers) {
            
            this.$store.dispatch('setVouchers', vouchers)
        }
        
        this.voucher_spinner = false;

    }
}
</script>

<style scoped>
    .margin-top{
        margin-top: 15px;
    }
</style>