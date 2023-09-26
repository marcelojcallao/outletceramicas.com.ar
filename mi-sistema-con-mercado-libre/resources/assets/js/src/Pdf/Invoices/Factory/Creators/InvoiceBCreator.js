import moment from 'moment';
import InvoiceB from "../Concrete/InvoiceB";

class InvoiceBCreator {

    constructor(){
        this.pdf = null;
    }

    createInvoice()
    {
        return new InvoiceB;
    }

    setPdfData(data)
    {
        console.log("🚀 ~ file: InvoiceBCreator.js ~ line 16 ~ InvoiceBCreator ~ data", data)
        const voucher_date = moment(data.voucher_date).format('DD-MM-YYYY');
        const voucher_vto_cae = moment(data.voucher_vto_cae).format('DD-MM-YYYY');
        let pedido_cliente_delivery_date = moment(data.pedido_cliente_delivery_date).format('DD-MM-YYYY');
        if (pedido_cliente_delivery_date === 'Invalid date' || pedido_cliente_delivery_date === 'undefined') {
            pedido_cliente_delivery_date = '';
        }
        let address = '';

        if (Array.isArray(data.customer_address) && data.customer_address.length) {
            const state = (data.customer_address[0].state) ? data.customer_address[0].state : '';
            const city = (data.customer_address[0].city) ? ' - ' + data.customer_address[0].city : '';
            const cp = (data.customer_address[0].cp) ? ' - ' + data.customer_address[0].cp : '';
            const street = (data.customer_address[0].street) ? ' - ' + data.customer_address[0].street : '';
            const number = (data.customer_address[0].number) ? ' - ' + data.customer_address[0].number : '';
            const obs = (data.customer_address[0].obs) ? data.customer_address[0].obs : '';
            address =  `${state} ${city} ${cp} ${street} ${number}`;
        }else{
            const state = (data.customer_address.state) ? data.customer_address.state : '';
            const city = (data.customer_address.city) ? ' - ' + data.customer_address.city : '';
            const cp = (data.customer_address.cp) ? ' - ' + data.customer_address.cp : '';
            const street = (data.customer_address.street) ? ' - ' + data.customer_address.street : '';
            const number = (data.customer_address.number) ? ' - ' + data.customer_address.number : '';
            const obs = (data.customer_address.obs) ? data.customer_address.obs : '';
            address =  `${state} ${city} ${cp} ${street} ${number}`;
        }

        this.pdf = this.createInvoice();

        this.pdf
            .setCompanyName(data.company_name)
            .setCompanyAddress(data.company_address)
            .setCompanyInscription(data.company_inscription_name)
            .setCompanyCuit(data.company_cuit)
            .setCompanyIibb(data.company_iibb)
            .setCompanyActivityInit(data.company_activity_init)
            .setCompanyDocumentType(data.company_purchaserDocument)
            .setCompanyIibbConv(data.company_iibb)
            .setVoucherName(data.voucher_name)
            .setVoucherLetter(data.voucher_name.substr(data.voucher_name.length -1))
            .setVoucherNumber('N° ' + data.voucher_number)
            .setVoucherDate(voucher_date)
            .setVoucherDetailsProduct(data.voucher_items)
            .setVoucherCae(data.voucher_cae)
            .setVoucherExpirationCae(voucher_vto_cae)
            .setVoucherModoPago(data.voucher_modo_pago)
            .setVoucherVtoPayment(data.voucher_vto_pago)
            .setVoucherTotals(data.totals)
            .setVoucherPayCondition({condition : 'Condición de Pago:', fch_vto : `Fecha de entrega: ${pedido_cliente_delivery_date}`})
            .setCustomerName(data.customer_name)
            .setCustomerAddress(address)
            .setCustomerIva(data.customer_inscription_name)
            .setCustomerCuit(data.customer_cuit)
            .setCustomerDocTipo(data.customer_purchaser_document)
            .setCustomerInscripton(data.customer_inscription)
            .setQrAfip(data.qr_afip)
			.setVoucherObs(data.invoice_comments)

        return this.pdf
    }
}

export default InvoiceBCreator;
