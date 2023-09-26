<template>
    <button style="margin-right: 5px"
        @click="print()"
        v-tooltip="'IMPRIMIR ' + invoice.voucher.toLowerCase()"
        class="btn btn-default btn-icon sq-32" type="button"
        :class=" ( spinner ) ? 'btn btn-default spinner spinner-inverse spinner-sm' : ''"
        >
        <span class="icon icon-print"></span>
    </button>
</template>

<script>
    import PreparePedidoClientePdf from '../../../../src/pedidoCliente/Services/PreparePedidoClientePdf';
    import PrinterInvoicePdf from '../../../../src/Pdf/PrinterInvoicePdf';
    import { mapGetters } from 'vuex';
    export default {

        props: {
            invoice: {
                required: true
            }
        },

        data(){
            return {
                spinner: false,
                referButton: null
            }
        },

        methods: {

            print(){

                if ( this.invoice.voucher === 'PRESUPUESTO VENTA' ) {

                    this.presupuestoPrintPdf();

                }else if( this.invoice.voucher === 'REMITO' ) {

                    this.remitoPrintPdf();

                }else if ( this.invoice.voucher === 'COTIZACION'){

                    this.cotizacionPrintPdf();

                }else{

                    this.InvoicePrintPdf();
                }

            },

            remitoPrintPdf(){

                this.spinner = true;

                setTimeout(() => {

                    const pedido_cliente_data = this.PedidoListChildRowReactivityData;

                    const id = pedido_cliente_data.id;

                    if (pedido_cliente_data.remito_code) {
                        pedido_cliente_data.id =pedido_cliente_data.remito_code;
                    }

                    const pdf = PreparePedidoClientePdf.prepare(this.$store.getters.GetCompany, pedido_cliente_data, this.$store);

					pdf.generatePdf(['ORIGINAL']);
                    pdf.print();
                    this.spinner = false;
                    this.PedidoListChildRowReactivityData.id = id;

                }, 100);
            },

            async presupuestoPrintPdf(){

                this.spinner = true;

                const { data:presupuesto } = await this.$store.dispatch('invoice_print', this.invoice.id)
                .catch((err) => {
                    Vue.swal.fire({
                        title: 'PRESUPUESTO DE VENTA',
                        text : err.response.data.message,
                        icon : 'error',
                        width : '35%',
                        padding : '2rem',
                        backdrop : true,
                        confirmButtonText : 'Aceptar',
                        confirmButtonColor: '#00bbf0',
                    })
                }).finally(() => this.spinner = false)

                if (presupuesto) {

                    PrinterInvoicePdf.print(presupuesto, 'ORIGINAL', 'DUPLICADO');

                    this.spinner = false;

                }
            },

            async InvoicePrintPdf(){

                this.spinner = true;

                const { data:invoice } = await this.$store.dispatch('invoice_print', this.invoice.id)
                .catch((err) => {
                    Vue.swal.fire({
                        title: 'COMPROBANTE DE VENTA',
                        text : err.response.data.message,
                        icon : 'error',
                        width : '35%',
                        padding : '2rem',
                        backdrop : true,
                        confirmButtonText : 'Aceptar',
                        confirmButtonColor: '#00bbf0',
                    })
                }).finally(() => this.spinner = false)

                if (invoice) {

                    PrinterInvoicePdf.print(invoice, 'ORIGINAL', 'DUPLICADO');

                    this.spinner = false;

                }

            },

            cotizacionPrintPdf(){

                const pedido_cliente_data = this.PedidoListChildRowReactivityData;

                this.spinner = true;

                pedido_cliente_data.voucher_id = 102;

                pedido_cliente_data.code = this.invoice.number;

                pedido_cliente_data.company = this.$store.getters.GetCompany;

                setTimeout(()=>{

                    PrinterInvoicePdf.print(pedido_cliente_data, 'ORIGINAL');

                    this.spinner = false;

                },750);

            },
        },

        computed: {

            ...mapGetters([
                'PedidoListChildRowReactivityData'
            ])
        },
    }
</script>
