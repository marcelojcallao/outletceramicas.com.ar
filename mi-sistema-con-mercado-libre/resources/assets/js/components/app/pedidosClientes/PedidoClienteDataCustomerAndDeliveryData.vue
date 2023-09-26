<template>
    <div class="main-flex">
        <div class="item-1">
            <span v-if="PedidoListChildRowReactivityData && PedidoListChildRowReactivityData.customer_nick_name">Nombre en MercadoLibre:
                <strong>{{ PedidoListChildRowReactivityData.customer_nick_name}}</strong> -
            </span>
            <span v-if="PedidoListChildRowReactivityData && PedidoListChildRowReactivityData.customer_document_number">
                <span>{{ (PedidoListChildRowReactivityData.customer_document_number.length < 11) ? 'DNI' : PedidoListChildRowReactivityData.customer_DocTipo}}</span>
                <strong>{{ PedidoListChildRowReactivityData.customer_document_number}}</strong>
                <span v-if="PedidoListChildRowReactivityData.customer_inscription_name != 'Falta definir'">
                    <strong> - {{ PedidoListChildRowReactivityData.customer_inscription_name}}</strong>
                </span>
            </span>
            <p class="street" v-if="PedidoListChildRowReactivityData && PedidoListChildRowReactivityData.customer_address"><strong>Domicilio: </strong> {{PedidoListChildRowReactivityData.customer_address.state}} {{PedidoListChildRowReactivityData.customer_address.city}} {{PedidoListChildRowReactivityData.customer_address.cp}} {{PedidoListChildRowReactivityData.customer_address.street}}</p>

            <AddAddressButtonVue />

            <div class="status">
                <p>Estados del pedido</p>
                <PedidoListCellStatusChange :data="data" v-if="data.type == 'PEDIDO CLIENTE' && PedidoListChildRowReactivityData"/>
                <p v-else>COTIZACIÓN</p>
            </div>
            <div class="who">
                <div v-if="data.who_prepared">
                    Preparó: {{data.who_prepared}}
                </div>
                <div v-if="data.who_delivered">
                    Envió: {{data.who_delivered}}
                </div>
            </div>
            <div v-if="PedidoListChildRowReactivityData && PedidoListChildRowReactivityData.invoices.length">
                <TableInvoices />
            </div>
        </div>
        <div class="item-2">
            <template v-if="PedidoDeliveryAddressGetter">
                <div class="address"><label >Datos de entrega</label></div>
                <div class="address date"><label>Fecha de entrega:</label> {{data.delivery_date}}</div>
                <div class="address"><label>Domicilio:</label> {{
                    PedidoDeliveryAddressGetter[0].state + ' - ' +
                    PedidoDeliveryAddressGetter[0].city + ' - ' +
                    PedidoDeliveryAddressGetter[0].cp + ' - ' +
                    PedidoDeliveryAddressGetter[0].street}}</div>
                <div class="address" v-if="PedidoDeliveryAddressGetter[0].between_streets">
                    <label>Entre calles:</label>
                    {{PedidoDeliveryAddressGetter[0].between_streets}}
                </div>
                <div class="address" v-if="PedidoDeliveryAddressGetter[0].obs">
                    <label>Observaciones:</label>
                    {{PedidoDeliveryAddressGetter[0].obs}}
                </div>
            </template>
        </div>
        <div class="item-3">
            <slideout-panel></slideout-panel>
            <button
                class="btn btn-default btn-icon sq-32"
                v-tooltip="(data.status_id > 2) ? 'Sólo se puede editar el pedido en estado pendiente' : 'Editar pedido'"
                @click="showPanelEditPedido"
                :disabled="data.status_id > 2"
                >
                <span class="icon icon-edit"></span>
            </button>
            <button
                class="btn btn-default btn-icon sq-32"
                v-tooltip="'Asignar domicilio de entrega'"
                @click="showPanelAddDeliveryAdrress"
                >
                <span class="icon icon-map-signs"></span>
            </button>
            <!-- <button
                v-if="data.type == 'PEDIDO CLIENTE'"
                class="btn btn-outline-default btn-icon sq-32"
                @click="remitoPrintPdf"
                :class=" {'spinner spinner-inverse spinner-sm' : remito_spinner}"
                :disabled="remito_spinner || data.status_id <= 2 || User.type_user_id == 3"
                v-tooltip="'Re-Imprimir remito'"
                >
                <span class="material-icons">{{remito_icon_print}}</span>
            </button>
            <button
                v-if="data.type == 'PEDIDO CLIENTE'"
                class="btn btn-outline-default btn-icon sq-32"
                @click="InvoicePrintPdf"
                :class=" {'spinner spinner-inverse spinner-sm' : invoice_spinner}"
                :disabled="invoice_spinner || data.status_id <= 4 || User.type_user_id == 3"
                v-tooltip="'Re-Imprimir Factura'"
                >
                <span class="material-icons">{{invoice_icon_print}}</span>
            </button>
            <button
                v-if="data.type == 'PEDIDO CLIENTE' && data.cotizacion"
                class="btn btn-outline-default btn-icon sq-32"
                @click="PedidoClienteCotizacionPrint"
                :class=" {'spinner spinner-inverse spinner-sm' : pedido_cliente_cotizacion_spinner}"
                :disabled="pedido_cliente_cotizacion_spinner"
                v-tooltip="'Imprimir Cotización'"
                >
                <span class="material-icons">{{invoice_icon_print}}</span>
            </button> -->
            <button
                v-if="data.type == 'COTIZACION'"
                class="btn btn-outline-default btn-icon sq-32"
                @click="cotizacionPrintPdf"
                :class=" {'spinner spinner-inverse spinner-sm' : cotizacion_spinner}"
                :disabled="cotizacion_spinner || data.status_id == 13"
                v-tooltip="'Imprimir Cotización'"
                >
                <span class="material-icons">{{cotizacion_icon_print}}</span>
            </button>
        </div>
    </div>
</template>

<script>
import AddAddressButtonVue from '../admin/customer/new/AddAddressButton.vue';
import { Event } from 'vue-tables-2';
    import { mapGetters } from 'vuex';
    import PedidoEdit from './../admin/orders/Edit/PedidoEdit.vue'
    import PrinterInvoicePdf from '../../../src/Pdf/PrinterInvoicePdf';
    import PedidoListCellStatusChange from './PedidoListCellStatusChange';
    import AddProductPopUP from '../pedidosClientes/popup/AddProductToOrderPopUp.vue';
    import PanelAddDeliveryAdrress from './../pedidosClientes/panel/PanelAddDeliveryAdrress.vue'
    import PreparePedidoClientePdf from './../../../src/pedidoCliente/Services/PreparePedidoClientePdf';
    import TableInvoices from './invoices/table-invoices.vue';
    export default {

        props : ['data'],

        components : {
            PedidoListCellStatusChange,
            AddProductPopUP,
            PanelAddDeliveryAdrress,
            PedidoEdit,
            AddAddressButtonVue,
            TableInvoices
        },

        data(){
            return {
                remito_spinner : false,
                presupuesto_spinner : false,
                cotizacion_spinner : false,
                invoice_spinner : false,
                pedido_cliente_cotizacion_spinner : false,
                remito_icon_print : 'print',
                invoice_icon_print : 'print',
                presupuesto_icon_print : 'print',
                cotizacion_icon_print : 'print',
                pedido_cliente_cotizacion_icon_print : 'print',
                editPedidoPanel: null
            }
        },

        computed : {

            ...mapGetters(['PedidoListChildRowReactivityData', 'PedidoDeliveryAddressGetter'])
        },

        methods : {

            showPanelAddDeliveryAdrress() {
                this.$store.commit('ORDER_IS_NEW', false)
                const panel1Handle = this.$showPanel({
                    component : PanelAddDeliveryAdrress,
                    openOn: 'top',
                    height: '950px',
                    props: {
                        //any data you want passed to your component
                    }
                })

                panel1Handle.promise
                .then(result => {


                });
            },

            showPanelEditPedido() {

                this.editPedidoPanel = this.$showPanel({
                    component : PedidoEdit,
                    openOn: 'top',
                    height: '800px',
                    props: {
                        //any data you want passed to your component
                    }
                })

                this.editPedidoPanel.promise
                .then(result => {
                    const panel = document.getElementById("pedido-list-child-row");
                    panel.scrollIntoView({block: "start", behavior: "smooth"});

                });
            },

            remitoPrintPdf(){

                this.remito_spinner = true;

                this.remito_icon_print = '';

                setTimeout(() => {

                    const pedido_cliente_data = this.PedidoListChildRowReactivityData;

                    const id = pedido_cliente_data.id;

                    if (pedido_cliente_data.remito_code) {
                       pedido_cliente_data.id =pedido_cliente_data.remito_code;
                    }

                    const pdf = PreparePedidoClientePdf.prepare(this.$store.getters.GetCompany, pedido_cliente_data, this.$store);
                    pdf.generatePdf(['ORIGINAL']);
                    pdf.print();
                    this.remito_spinner = false;
                    this.PedidoListChildRowReactivityData.id = id;
                    this.remito_icon_print = 'print';

                }, 100);
            },

            async presupuestoPrintPdf(){

                this.presupuesto_spinner = true;

                this.presupuesto_icon_print = '';

                const payload = {
                    pedido_cliente_id : this.PedidoListChildRowReactivityData.id
                }

                const response = await this.$store.dispatch('invoice_print', payload)

                if (response) {

                    PrinterInvoicePdf.print(response.data, 'ORIGINAL', 'DUPLICADO');

                    this.presupuesto_spinner = false;

                    this.presupuesto_icon_print = 'print';

                }
            },

            async InvoicePrintPdf(){

                this.invoice_spinner = true;

                this.invoice_icon_print = '';

                const payload = {
                    pedido_cliente_id : this.data.id
                }

                const response = await this.$store.dispatch('invoice_print', payload)

                if (response) {

                    PrinterInvoicePdf.print(response.data, 'ORIGINAL', 'DUPLICADO');

                    this.invoice_spinner = false;

                    this.invoice_icon_print = 'print';

                }

            },

            cotizacionPrintPdf(){

                const pedido_cliente_data = this.PedidoListChildRowReactivityData;

                for (const element of pedido_cliente_data.items) {
                    const result = this.check_stock(element);
                }

                this.cotizacion_spinner = true;

                this.cotizacion_icon_print = '';

                pedido_cliente_data.voucher_id = 102;

                pedido_cliente_data.company = this.$store.getters.GetCompany;

                setTimeout(()=>{

                    PrinterInvoicePdf.print(pedido_cliente_data, 'ORIGINAL');

                    this.cotizacion_spinner = false;

                    this.cotizacion_icon_print = 'print'

                },750);

            },

            async check_stock(item){

                const critical_stock =  parseFloat(item.critical_stock);

                const actual_stock = (parseFloat(item.stock) - parseFloat(item.quantity));

                if (actual_stock < 0) {

                    const text = `No hay capacidad para ${item.product_name}, quedan ${item.stock} unidades. Usted solicita ${item.quantity} unidades.`;

                    Vue.swal.fire({
                        title: 'PRODUCTO SIN STOCK',
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Continuar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {

                            return Promise.resolve();

                        }else{
                            return Promise.reject();
                        }
                    })

                }else if  (actual_stock <= critical_stock) {

                        Vue.swal.fire({
                            title: 'STOCK CRÍTICO',
                            text: `${item.product_name} en stock crítico, actualmente hay ${item.stock} unidades. Luego de esta transacción quedarán ${item.stock-item.quantity} unidades.`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Continuar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            console.log("🚀 ~ file: PedidoClienteDataCustomerAndDeliveryData.vue:334 ~ check_stock ~ result", result)
                            if (result.isConfirmed) {

                                return Promise.resolve();

                            }else{
                                return Promise.reject();
                            }
                        })
                }
            },

            async PedidoClienteCotizacionPrint()
            {
                this.pedido_cliente_cotizacion_spinner = true;

                this.pedido_cliente_cotizacion_icon_print = '';

                const pdf_data = await this.$store.dispatch('cotizacion_print', this.PedidoListChildRowReactivityData.cotizacion)
                    .catch((err) => {})
                    .finally(() => {
                        this.pedido_cliente_cotizacion_spinner = false;

                        this.pedido_cliente_cotizacion_icon_print = 'print';
                    })

                if (pdf_data) {

                    const data = pdf_data.data;
                    data.created = data.cotization_date;
                    data.voucher_id = 102 //cotizacion

                    setTimeout(()=>{

                        PrinterInvoicePdf.print(data, 'ORIGINAL');

                    },750);
                }
            },

            zeroLeft (str, max) {
                str = str.toString();
                return str.length < max ? this.zeroLeft("0" + str, max) : str;
            },
        },

        mounted(){

            Event.$on('closeEditPedidoPanel', () => {
                this.editPedidoPanel.hide();
            })
        }

    }
</script>

<style scoped>
    .main-flex{
        display: flex;
        font-size: 1.5rem;
    }
    .main-flex .item-1, .item-2{
        width: 50%;
    }
    .item-1{
        margin-right: 1rem;
    }
    .address label {
        font-weight: bold;
    }
    .address.date{
        color: red;
        padding: .4rem;
        background-color: pink;
        margin-bottom: .5rem;
    }
    .item-1 .status{
        padding: 1rem 0px;
    }
    .status{
        margin-top: .5rem;
    }
    .status p{
        font-weight: bold;

        color: red;
    }
    .item-3{
        display: flex;
    }
    .item-3 button {
        margin-right: 0.5rem;
    }
    .item-3 button:hover:enabled {
        background-color: #606470;
    }
    .who{
        display: flex;
    }
    .who div:first-child {
        margin-right: 1rem;
    }
    .material-icons {
        color: #dbd8e3;
    }

    .street{
        display: block;
    }

</style>
