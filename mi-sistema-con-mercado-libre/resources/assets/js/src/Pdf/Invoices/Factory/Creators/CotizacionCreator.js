import moment from 'moment';
import CotizacionPdf from './../Concrete/CotizacionPdf';

class CotizacionCreator {

    constructor(){
        this.pdf = null;
    }

    createInvoice()
    {
        return new CotizacionPdf;
    }

    setPdfData(data)
    {
        const voucher_date = data.created;
        const voucher_vto_cae = '';
        const pedido_cliente_delivery_date = '';

        this.pdf = this.createInvoice();
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

        let delivery_address = '';
        if (data.delivery_addresses) {
            
            if (Array.isArray(data.delivery_addresses) && data.delivery_addresses.length) {
                const state = (data.delivery_addresses[0].state) ? data.delivery_addresses[0].state : '';
                const city = (data.delivery_addresses[0].city) ? ' - ' + data.delivery_addresses[0].city : '';
                const cp = (data.delivery_addresses[0].cp) ? ' - ' + data.delivery_addresses[0].cp : '';
                const street = (data.delivery_addresses[0].street) ? ' - ' + data.delivery_addresses[0].street : '';
                const number = (data.delivery_addresses[0].number) ? ' - ' + data.delivery_addresses[0].number : '';
                const between_streets = (data.delivery_addresses[0].between_streets) ? data.delivery_addresses[0].between_streets : '';
                const between = (between_streets) ? ' - ENTRE: ' + between_streets : '';
                const obs = (data.delivery_addresses[0].obs) ? ' - OBS. ' + data.delivery_addresses[0].obs : '';
                delivery_address =  `${state} ${city} ${cp} ${street} ${number} ${between} ${obs}`;
            }else{
                const state = (data.delivery_addresses.state) ? data.delivery_addresses.state : '';
                const city = (data.delivery_addresses.city) ? ' - ' + data.delivery_addresses.city : '';
                const cp = (data.delivery_addresses.cp) ? ' - ' + data.delivery_addresses.cp : '';
                const street = (data.delivery_addresses.street) ? ' - ' + data.delivery_addresses.street : '';
                const number = (data.delivery_addresses.number) ? ' - ' + data.delivery_addresses.number : '';
                const between_streets = (data.delivery_addresses.between_streets) ? data.delivery_addresses.between_streets : '';
                const obs = (data.delivery_addresses.obs) ? ' - OBS. ' + data.delivery_addresses.obs : '';
                const between = (between_streets) ? ' - ENTRE: ' + between_streets : '';
                delivery_address =  `${state} ${city} ${cp} ${street} ${number} ${between} ${obs}`;
            }
        }
        
        this.pdf
            .setCompanyName(data.company.name)
            .setCompanyAddress(data.company.address)
            .setCompanyInscription(data.company.inscription)
            .setCompanyCuit(data.company.cuit)
            .setCompanyIibb(data.company.iibb)
            .setCompanyActivityInit(data.company.activity_init)
            .setCompanyDocumentType(data.company.purchaserDocument)
            .setCompanyIibbConv(data.company.iibb)
            .setVoucherName('COTIZACIÓN')
            .setVoucherLetter('Z')
            .setVoucherNumber('N° ' + data.code)
            .setVoucherDate(voucher_date)
            .setVoucherDetailsProduct(data.items)
            .setVoucherCae('')
            .setVoucherExpirationCae('')
            .setVoucherModoPago('')
            .setVoucherVtoPayment('')
            .setVoucherTotals(data.total_neto)
            .setVoucherPayCondition({condition : '', fch_vto : `Fecha de vencimiento: ${voucher_date}`})
            .setVoucherDeliveryAddress(delivery_address)
            .setCustomerName(data.customer)
            .setCustomerAddress(address)  
            .setCustomerIva(data.customer_inscription_name)
            .setCustomerCuit(data.customer_document_number)
            .setCustomerDocTipo(data.customer_DocTipo)
            .setCustomerInscripton(data.customer_inscription_name)
        console.log("🚀 ~ file: CotizacionCreator.js:60 ~ CotizacionCreator ~ this.pdf", this.pdf)

            
        return this.pdf
    }
}

export default CotizacionCreator;