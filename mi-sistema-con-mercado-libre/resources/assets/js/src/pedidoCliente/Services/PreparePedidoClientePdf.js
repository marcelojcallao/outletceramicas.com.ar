import PedidoClientePdf from "../../Pdf/PedidoClientePdf";

class PreparePedidoClientePdf {

    static zeroLeft (str, max) {
        str = str.toString();
        return str.length < max ? PreparePedidoClientePdf.zeroLeft("0" + str, max) : str;
    }

    delivery_addresses(address){

        if (Array.isArray(address) && address.length) {

            const street = (address[0].street) ? address[0].street : '';
            const number = (address[0].number) ? address[0].number : '';
            const city = (address[0].city) ? address[0].city : '';
            const cp = (address[0].cp) ? address[0].cp : '';
            const state = (address[0].state) ? address[0].state : '';
            const obs = (address[0].obs) ? address[0].obs : '';
            const between_streets = (address[0].between_streets) ? address[0].between_streets : '';
        }else{
            const street = (address.street) ? address.street : '';
            const number = (address.number) ? address.number : '';
            const city = (address.city) ? address.city : '';
            const cp = (address.cp) ? address.cp : '';
            const state = (address.state) ? address.state : '';
            const obs = (address.obs) ? address.obs : '';
            const between_streets = (address.between_streets) ? address.between_streets : '';
        }
        const addr = `${street} ${number} ${city} ${cp} ${state}`;

        return  addr + (between_streets) ? ' - ENTRE: ' + between_streets : '';
    }

    static prepare(...arg){

        const [company, data, store] = arg;
		const { invoices, created } = data;
        console.log("🚀 ~ file: PreparePedidoClientePdf.js:40 ~ PreparePedidoClientePdf ~ prepare ~ invoices:", invoices)

		let date = '';

		if (invoices.length > 0) {

			invoices.map( voucher => {
				if (voucher.voucher === 'REMITO') {

					date = voucher.date;
				}
			})
		}else {
			date = created;
		}

        const day = date.substring(0,2);

        const month = date.substring(3,5);

        const year = date.substring(6,10);

        let address = '';

        const pdf = new PedidoClientePdf()
            .setCompanyName(company.name)
            .setCompanyAddress(company.address)
            .setCompanyInscription(company.inscription)
            .setCompanyCuit(company.cuit)
            .setCompanyIibb(company.iibb)
            .setCompanyActivityInit(company.activity_init)
            .setCompanyDocumentType(company.document_type)
            .setCompanyIibbConv(company.iibb_conv)
            .setVoucherName('REMITO')
            .setVoucherLetter('R')
            .setVoucherNumber(data.id)
            .setVoucherDate(`${day}/${month}/${year}`)
            .setVoucherDetailsProduct(data.items)
            .setVoucherTotals([
                {
                    name :'',
                    value:''
                }
            ])
            //.setVoucherComments(store.getters.GetPrintComments)
            .setVoucherComments(data.comments)
            .setVoucherPayCondition({condition : 'Condición de Pago:', fch_vto : `Fecha de entrega ${data.delivery_date}`})
            .setCustomerName(data.customer)
            .setPedidoCode(data.code)
            .setCustomerIva(data.customer_inscription_name)
            .setCustomerCuit(data.customer_document_number)
            .setCustomerDocTipo(data.customer_DocTipo)
            .setCustomerInscripton(data.customer_inscription_name)

        if (data.customer_address) {

            if (Array.isArray(data.customer_address) && data.customer_address.length) {

                const state = (data.customer_address[0].state) ? data.customer_address[0].state : '';
                const city = (data.customer_address[0].city) ? ' - ' + data.customer_address[0].city : '';
                const cp = (data.customer_address[0].cp) ? ' - ' + data.customer_address[0].cp : '';
                const street = (data.customer_address[0].street) ? ' - ' + data.customer_address[0].street : '';
                const number = (data.customer_address[0].number) ? ' - ' + data.customer_address[0].number : '';
                const between_streets = (data.customer_address[0].between_streets) ? data.customer_address[0].between_streets : '';
                const obs = (data.customer_address[0].obs) ? data.customer_address[0].obs : '';
                const between = (between_streets) ? ' - ENTRE: ' + between_streets : '';
                address =  `${state} ${city} ${cp} ${street} ${number} ${between}`;
                pdf.setCustomerAddress(address).setCustomerObs(obs);

            }else{

                const state = (data.customer_address.state) ? data.customer_address.state : '';
                const city = (data.customer_address.city) ? ' - ' + data.customer_address.city : '';
                const cp = (data.customer_address.cp) ? ' - ' + data.customer_address.cp : '';
                const street = (data.customer_address.street) ? ' - ' + data.customer_address.street : '';
                const number = (data.customer_address.number) ? ' - ' + data.customer_address.number : '';
                const between_streets = (data.customer_address.between_streets) ? data.customer_address.between_streets : '';
                const obs = (data.customer_address.obs) ? data.customer_address.obs : '';
                const between = (between_streets) ? ' - ENTRE: ' + between_streets : '';
                address =  `${state} ${city} ${cp} ${street} ${number} ${between}`;

                pdf.setCustomerAddress(address).setCustomerObs(obs);
            }

        }else{
            pdf.setCustomerAddress('').setCustomerObs('')
        }

        if (data.customer_address) {

            if (Array.isArray(data.customer_address) && data.customer_address.length) {

                const state = (data.customer_address[0].state) ? data.customer_address[0].state : '';
                const city = (data.customer_address[0].city) ? ' - ' + data.customer_address[0].city : '';
                const cp = (data.customer_address[0].cp) ? ' - ' + data.customer_address[0].cp : '';
                const street = (data.customer_address[0].street) ? ' - ' + data.customer_address[0].street : '';
                const number = (data.customer_address[0].number) ? ' - ' + data.customer_address[0].number : '';
                const between_streets = (data.customer_address[0].between_streets) ? data.customer_address[0].between_streets : '';
                const obs = (data.customer_address[0].obs) ? data.customer_address[0].obs : '';
                const between = (between_streets) ? ' - ENTRE: ' + between_streets : '';
                address =  `${state} ${city} ${cp} ${street} ${number} ${between}`;
                pdf.setCustomerAddress(address).setCustomerObs(obs);

            }else{

                const state = (data.customer_address.state) ? data.customer_address.state : '';
                const city = (data.customer_address.city) ? ' - ' + data.customer_address.city : '';
                const cp = (data.customer_address.cp) ? ' - ' + data.customer_address.cp : '';
                const street = (data.customer_address.street) ? ' - ' + data.customer_address.street : '';
                const number = (data.customer_address.number) ? ' - ' + data.customer_address.number : '';
                const between_streets = (data.customer_address.between_streets) ? data.customer_address.between_streets : '';
                const obs = (data.customer_address.obs) ? data.customer_address.obs : '';
                const between = (between_streets) ? ' - ENTRE: ' + between_streets : '';
                address =  `${state} ${city} ${cp} ${street} ${number} ${between}`;

                pdf.setCustomerAddress(address).setCustomerObs(obs);
            }

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
                const obs = (data.delivery_addresses[0].obs) ? data.delivery_addresses[0].obs : '';
                delivery_address =  `${state} ${city} ${cp} ${street} ${number} ${between}`;

                pdf.setVoucherObs(obs)

            }else{

                const state = (data.delivery_addresses.state) ? data.delivery_addresses.state : '';
                const city = (data.delivery_addresses.city) ? ' - ' + data.delivery_addresses.city : '';
                const cp = (data.delivery_addresses.cp) ? ' - ' + data.delivery_addresses.cp : '';
                const street = (data.delivery_addresses.street) ? ' - ' + data.delivery_addresses.street : '';
                const number = (data.delivery_addresses.number) ? ' - ' + data.delivery_addresses.number : '';
                const between_streets = (data.delivery_addresses.between_streets) ? data.delivery_addresses.between_streets : '';
                const obs = (data.delivery_addresses.obs) ? data.delivery_addresses.obs : '';
                const between = (between_streets) ? ' - ENTRE: ' + between_streets : '';
                delivery_address =  `${state} ${city} ${cp} ${street} ${number} ${between}`;

                pdf.setVoucherObs(obs)
            }
        }

        pdf.setVoucherDeliveryAddress(delivery_address)

        return pdf;
    }
}

export default PreparePedidoClientePdf;
