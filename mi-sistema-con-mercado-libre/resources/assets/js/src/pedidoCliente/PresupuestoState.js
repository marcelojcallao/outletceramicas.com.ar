import PresupuestoPdf from '../Pdf/Invoices/PresupuestoPdf';
import InterfaceStatus from './InterfaceStatus';

class PresupuestoState extends InterfaceStatus {

    constructor(){
        super();
        this.status = 4;

    }

    async executeAction(){

        const new_presupuesto = await this.savePresupuesto();

        this.printPdf(new_presupuesto.data);

        const pedido_updated = await this.statusUpdate(this.status);

        return pedido_updated;
    }

    async savePresupuesto(){

        this.data.company_id = this.Store.getters.GetCompany.id;

        const new_presupuesto = await axios.post('/api/sale_invoice/save_presupuesto', {
            presupuesto : this.data
        })
        .catch((err) => {
            console.log(err);
        });

        if ( new_presupuesto ) {

            return new_presupuesto;
        }
    }

    printPdf(presupuesto)
    {
        const company = this.Store.getters.GetCompany;

        let address = '';

        if (presupuesto.customer_address) {
            const state = (presupuesto.customer_address.state) ? presupuesto.customer_address.state : '';
            const city = (presupuesto.customer_address.city) ? ' - ' + presupuesto.customer_address.city : '';
            const cp = (presupuesto.customer_address.cp) ? ' - ' + presupuesto.customer_address.cp : '';
            const street = (presupuesto.customer_address.street) ? ' - ' + presupuesto.customer_address.street : '';
            const number = (presupuesto.customer_address.number) ? ' - ' + presupuesto.customer_address.number : '';
            const obs = (presupuesto.customer_address.obs) ? presupuesto.customer_address.obs : '';
            address =  `${state} ${city} ${cp} ${street} ${number}`;
        }

        const pdf = new PresupuestoPdf()
            .setCompanyName(company.name)
            .setCompanyAddress(company.address)
            .setCompanyInscription(company.inscription)
            .setCompanyCuit(company.cuit)
            .setCompanyIibb(company.iibb)
            .setCompanyActivityInit(company.activity_init)
            .setCompanyDocumentType(company.document_type)
            .setCompanyIibbConv(company.iibb_conv)
            .setVoucherName('PRESUPUESTO VENTA')
            .setVoucherLetter('P')
			.setVoucherComments(presupuesto.comments)
            .setVoucherNumber(presupuesto.voucher_number)
            .setVoucherDate(presupuesto.date)
            .setVoucherDetailsProduct(presupuesto.products)
            .setVoucherTotals(presupuesto.totals)
            .setVoucherPayCondition({condition : `Condición de Pago: ${presupuesto.aditional_payment.type.name} Porc. ${presupuesto.aditional_payment.type.percentage}% Importe: ${presupuesto.aditional_payment.value}`, fch_vto : `Fecha de vencimiento:`})
            .setCustomerName(presupuesto.customer.name)
            .setCustomerAddress(address)
            .setCustomerIva(presupuesto.customer.inscription)
            .setCustomerCuit(presupuesto.customer.id_number)
            .setCustomerDocTipo(presupuesto.docTipo)
            .setCustomerInscripton(presupuesto.customer.inscription)


        pdf.generatePdf(['ORIGINAL']);
        pdf.print();
    }

}

export default PresupuestoState;
