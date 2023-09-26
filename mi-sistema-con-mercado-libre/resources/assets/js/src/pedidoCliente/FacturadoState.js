import InterfaceStatus from './InterfaceStatus';
import PrinterInvoicePdf from '../Pdf/PrinterInvoicePdf';
import DateHandler from './../../src/Date/DateHandler';

class FacturadoState extends InterfaceStatus {

    constructor(){
        super();
        this.status = 5;
    }

    async executeAction(){
        
        const FACTURA = 1;

        const $store = this.Store;

        $store.commit('WSFE_SET_CUSTOMER', this.data.customer_id);
        $store.commit('WSFE_SET_COMPANY', $store.getters.GetCompany.id);
        $store.commit('WSFE_SET_TYPE_INVOICE', FACTURA);
        $store.commit('WSFE_SET_ITEMS', this.data.items);
        $store.commit('WSFE_SET_CUSTOMER_DOCTIPO_AFIP_CODE', this.data.customer_DocTipo_afip_code);
        //$store.commit('WSFE_SET_INVOICE_DATE', DateHandler.DateNowToDDMMYYYY());

        const payload = {
            environment : $store.getters.GetCompany.afip_environment,
            type : $store.getters.WsFeGetter.type,
            customer : $store.getters.WsFeGetter.customer,
            company : $store.getters.WsFeGetter.company,
            items : $store.getters.WsFeGetter.items,
            invoice_date : $store.getters.WsFeGetter.invoice_date,
            customer_DocTipo_afip_code : this.data.customer_DocTipo_afip_code,
            pedido_cliente_id : this.data.id
        }

        const step1 = await $store.dispatch('create_invoice_step1', payload)
        
        if (step1) {

            const response = step1.data;

            PrinterInvoicePdf.print(response, 'ORIGINAL', 'DUPLICADO');

            const pedido_updated = await this.statusUpdate(this.status);
            
            return pedido_updated;
        }

    }

    async Sweet(){
        const { value: email } = await Vue.swal.fire({
            title: 'Factura de crédito electrónica',
            icon : 'warning',
            width : '35%',
            padding : '2rem',
            backdrop : true,
            input: 'text',
            inputLabel: 'CBU',
            confirmButtonText : 'Aceptar',
            confirmButtonColor: '#00bbf0',
            inputPlaceholder: 'Número de CBU',
            preConfirm: async () => {
                const response = await axios.get('/api/pay_methods/index')
                .catch(error => {
                    console.log('se rommpiooooo')
                    console.log('se rommpiooooo')
                    console.log('se rommpiooooo')
                  });
                if (response) {
                    console.log('pppppppppp')
                    console.log(response)
                    console.log('pppppppppp')
                }
            },
        })
          
        if (email) {
            Vue.swal.fire(`Entered email: ${email}`)
        }
    }    
}

export default FacturadoState;