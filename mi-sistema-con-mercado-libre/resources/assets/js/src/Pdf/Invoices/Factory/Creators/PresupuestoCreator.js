import moment from 'moment';
import PresupuestoPdf from '../../PresupuestoPdf';

class PresupuestoCreator {

    constructor(){
        this.pdf = null;
    }

    createInvoice()
    {
        return new PresupuestoPdf;
    }
    CurrencyFormat(value) {
        return new Intl.NumberFormat('es-AR',
            {
                style:"currency",
                currency:"ARS",
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
                useGrouping : true,
            }).format(parseFloat(value));
    }

    setPdfData(data)
    {
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

        let payment_data = '';

        if (data.payment_data) {
            payment_data =  `${data.payment_data.name} ${data.payment_data.percentage}% ${this.CurrencyFormat(data.payment_data.value)}`;
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
            .setVoucherName('PRESUPUESTO VENTA')
            .setVoucherLetter('P')
            .setVoucherNumber('N° ' + data.voucher_number)
            .setVoucherDate(voucher_date)
            .setVoucherDetailsProduct(data.voucher_items)
            .setVoucherCae(data.voucher_cae)
			.setVoucherComments(data.comments)
            .setVoucherExpirationCae(voucher_vto_cae)
            .setVoucherModoPago(data.voucher_modo_pago)
            .setVoucherVtoPayment(data.voucher_vto_pago)
            .setVoucherTotals(data.totals)
            .setVoucherPayCondition({condition : `Condición de Pago: ${payment_data}`, fch_vto : `Fecha de entrega: ${pedido_cliente_delivery_date}`})
            .setCustomerName(data.customer_name)
            .setCustomerAddress(address)
            .setCustomerIva(data.customer_inscription_name)
            .setCustomerCuit(data.customer_cuit)
            .setCustomerDocTipo(data.customer_purchaser_document)
            .setCustomerInscripton(data.customer_inscription)


        return this.pdf
    }
}

export default PresupuestoCreator;
