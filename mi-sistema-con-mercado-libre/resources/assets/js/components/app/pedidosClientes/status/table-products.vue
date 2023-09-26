<template>
    <form class="form form-inline" >
        <div style="padding: 10px">
            <table class="table table-hover table-borderless ">
                <thead>
                    <tr class="table-custom-header">
                        <th class="text-center" style="width:3%">#</th>
                        <th class="text-center" style="width:29%">Producto</th>
                        <th class="text-center" style="width:10%">P. Unit.</th>
                        <th class="text-center" style="width:4%">Cant.</th>
                        <th class="text-center" style="width:10%">Desc.</th>
                        <th class="text-center" style="width:14%">Neto</th>
                        <th class="text-center" style="width:12%">Iva</th>
                        <th class="text-center" style="width:18%">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <table-products-row v-for="(product, index) in ItemsToFacturar" :key="index" :index="index" :product="product"/>
                </tbody>
                <tfoot >
                    <tr class="custom-foot">
                        <th class="text-right" colspan="6">Neto: {{ NetoTotal | currency}}</th>
                        <th class="text-right" >Iva: {{ IvaTotal | currency}}</th>
                        <th class="text-right" >Total: {{ Total | currency }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="buttons-footer">
            <label for="">Fecha Factura:</label>
            <div class="form-group">
                <datepicker
                    :language="es"
                    :disabled="IsSendingToAfip || PedidoListChildRowReactivityData.status_id > 4"
                    :value="invoice_date"
                    :disabled-dates="disabledDates"
                    v-model="invoice_date"
                ></datepicker>
            </div>
            <button
                class="btn btn-primary btn-xs"
                type="button"
                :disabled="spinner || PedidoListChildRowReactivityData.status_id > 4"
                @click="generate_invoice()"
                :class="{'btn btn-primary btn-xs spinner spinner-inverse spinner-sm' : spinner}"

            >Generar factura
            </button>
            <button
                class="btn btn-default btn-xs"
                type="button"
                :disabled="spinner"
                @click="cancelInvoice"
            >Cancelar</button>
        </div>
    </form>
</template>

<script>
    import collect from 'collect.js';
    import { mapGetters } from 'vuex';
    import { Event } from 'vue-tables-2';
    import Datepicker from 'vuejs-datepicker';
    import {es} from 'vuejs-datepicker/dist/locale';
    import tableProductsRow from './table-products-row.vue';
    import DateHandler from './../../../../src/Date/DateHandler';
    import PrinterInvoicePdf from './../../../../src/Pdf/PrinterInvoicePdf';
    const FACTURA = 1;
    const FACTURADO_STATUS = 5;
    export default {

        components : {Datepicker, tableProductsRow},

        data(){
            return {
                es : es,
                open : false,
                spinner : false,
                invoice_date : new Date(),
                date : null,
                disabledDates : {
                    to: this.$moment().subtract(5, 'days').toDate(), // Disable all dates up to specific date
                    from: this.$moment().add(5, 'days').toDate(), // Disable all dates after specific date
                },

            }
        },

        methods : {

            dropdown_open()
            {
                const open = ! this.OpenChangeInvoiceDateGetter;

                this.$store.dispatch('setOpenChangeInvoiceDate', open);
            },

            cancelInvoice(){

                this.dropdown_open();

                this.$store.dispatch('setItemsToFacturarInitialValue');
            },

            DisabledButton(status){

                if (( status - this.PedidoListChildRowReactivityData.status_id ) == 1) {
                    return true;
                }
                return false;
            },

            async generate_invoice()
            {
                this.spinner = true;

                this.$store.dispatch('setIsSendingToAfip', true);

                this.$store.commit('WSFE_SET_CUSTOMER', this.PedidoListChildRowReactivityData.customer_id);
                this.$store.commit('WSFE_SET_COMPANY', this.$store.getters.GetCompany.id);
                this.$store.commit('WSFE_SET_TYPE_INVOICE', FACTURA);
                this.$store.commit('WSFE_SET_ITEMS', this.ItemsToFacturar);
                this.$store.commit('WSFE_SET_CUSTOMER_DOCTIPO_AFIP_CODE', this.PedidoListChildRowReactivityData.customer_DocTipo_afip_code);
                this.$store.commit('WSFE_SET_INVOICE_DATE', DateHandler.DateToDDMMYYYY(this.invoice_date));

                const payload = {
                    environment : this.$store.getters.GetCompany.afip_environment,
                    type : this.$store.getters.WsFeGetter.type,
                    customer : this.$store.getters.WsFeGetter.customer,
                    company : this.$store.getters.WsFeGetter.company,
                    items : this.$store.getters.WsFeGetter.items,
                    invoice_date : this.$store.getters.WsFeGetter.invoice_date,
                    customer_DocTipo_afip_code : this.PedidoListChildRowReactivityData.customer_DocTipo_afip_code,
                    pedido_cliente_id : this.PedidoListChildRowReactivityData.id,
					comments: this.CommentsGetter
                }

                const step1 = await this.$store.dispatch('create_invoice_step1', payload)
                .catch((err)=> {
                        Vue.swal.fire({
                        title: 'AFIP - Factura electrónica',
                        text : err.response.data.message,
                        icon : 'error',
                        width : '35%',
                        padding : '2rem',
                        backdrop : true,
                        confirmButtonText : 'Aceptar',
                        confirmButtonColor: '#00bbf0',
                    })
                })
                .finally(()=>{
                    this.spinner = false
                    this.$store.dispatch('setIsSendingToAfip', false);
                });

                if (step1) {

                    this.dropdown_open();

                    const response = step1.data;

                    PrinterInvoicePdf.print(response, 'ORIGINAL', 'DUPLICADO');

                    const pedido = await axios.put('/api/pedido_cliente/status_update',
                    {
                        pedido : this.PedidoListChildRowReactivityData,
                        new_status : FACTURADO_STATUS
                    })
                    .catch((err) => {
                        return false;
                    });

                    if (pedido) {
                        this.success_message('Actualizado', 'El estado del pedido ahora es ' + pedido.data.status);

                        Event.$emit('pedido_cliente_list', this.PedidoListChildRowReactivityData);

                        this.$store.commit('SET_STATUS_ID_AT_PEDIDO_DATA', FACTURADO_STATUS);

                        this.dropdown_open();
                    }
                }
            },
        },

        computed : {

            ...mapGetters([
                'OpenChangeInvoiceDateGetter',
                'PedidoListChildRowReactivityData',
                'ItemsToFacturar',
                'IsSendingToAfip',
				'CommentsGetter'
            ]),

            NetoTotal(){
                if (this.ItemsToFacturar) {
                    return collect(this.ItemsToFacturar).sum('neto_import') - collect(this.ItemsToFacturar).sum('discount_import');
                }

                return 0;
            },

            IvaTotal(){
                if (this.ItemsToFacturar) {
                    return collect(this.ItemsToFacturar).sum('iva_import');
                }

                return 0;
            },

            Total(){
                if (this.ItemsToFacturar) {
                    return collect(this.ItemsToFacturar).sum('neto_import') - collect(this.ItemsToFacturar).sum('discount_import') + collect(this.ItemsToFacturar).sum('iva_import');
                }

                return 0;
            }

        }

    }
</script>

<style  scoped>
    .custom-foot{
        height: 2rem;
        padding: 1rem 0px;
        font-size: large;
    }
    .buttons-footer{
        height: 2rem;
        margin: 2rem;
    }
    .buttons-footer > label{
        margin-right: 1rem;
    }
</style>
