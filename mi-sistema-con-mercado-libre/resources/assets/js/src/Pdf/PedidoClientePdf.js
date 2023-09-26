
import collect from 'collect.js';
import WpImg from './Img/Whatsapp';
import InvoiceTypeB from "./Invoices/InvoiceTypeB";

class PedidoClientePdf extends InvoiceTypeB {

    constructor(){
        super();
    }

    customer_constant_text_data(customer = null, address = null, iva = null, cuit = null, cbte_tipo=null, doctTipo='CUIT', betweenStreets='', cellphone='', phone1='', phone2='', phone3='', delivery_address=null){

        const text = [
            'SEÑOR/ES : ' + customer,
            'DOMICILIO : ' + address,
        ];

        if (delivery_address) {
            text.push( 'DOMICILIO ENTREGA: ' + delivery_address );
        }

        this.pdf.setFontSize(10);
        this.pdf.setFont('Helvetica','bold');

        let height_position = this.first_header_height() + 1.5;

        text.forEach((t, index) => {

            const dim = this.pdf.getTextDimensions(t);

            if (dim.w > 181) {

                const array_text = this.pdf.splitTextToSize(t, 181);

                array_text.forEach((line, i) => {
                    height_position = height_position + (i + 3);
                    this.pdf.text(line, this.first_column_text(), height_position, {lineHeightFactor : 1.80});
                })
            }else{
                height_position = height_position + (index + 3);
                this.pdf.text(t, this.first_column_text(), height_position, {lineHeightFactor : 1.80});
            }
        });

        let tel = '';

        if(cellphone){
            tel = cellphone;
        }

        if(phone1){
            tel = tel + ' ' + phone1;
        }

        if(phone2){
            tel = tel + ' ' + phone2;
        }

        if(phone3){
            tel = tel + ' ' + phone3;
        }

        this.write_text(
            [
                'TEL.: ' + tel
            ],
            true,
            10,
            this.first_column_text(),
            this.first_header_height() + 7 + (this.interline() * 3), // 3 por que la ubico en la cuarta linea de texto
            this.interline(),
            {
                maxWidth : 131
            }
        );
        this.write_text(
            [
                doctTipo + ': ' + cuit
            ],
            true,
            10,
            this.middle_width() + this.quarter_width() - this.one_cm(),
            this.first_header_height() + 7 + (this.interline() * 3), // 3 por que la ubico en la cuarta linea de texto
            this.interline()

        );

        this.write_text(
            [
                'CANTIDAD' + quantity
            ],
            true,
            7,
            this.first_column_text() - 8,
            this.first_line_height + 15,
            this.interline()
        );

        this.write_text(
            [
                'DESCRIPCIÓN'
            ],
            true,
            7,
            this.first_column_text() * 3.5,
            this.first_line_height + 15,
            this.interline()
        );
    }

    internal_footer(){
        this.horizontalLine(
            this.margin_left,
            this.margin_bottom - (this.one_cm() * 4),
            this.margin_right,
            this.margin_bottom - (this.one_cm() * 4),
        );
    }

    details_of_product(title){

        let height_position = 103; //desde 103 a 220, en ese rango se imprimen los detalles
        let current_page = 1;
        let width_position = 0;
        let options = {};
        this.numberOfPages(current_page)
        this.invoice_original(title);

        collect(this.voucher.details_product).each((product, index) => {

            //######## NUMERO DE PEDIDO ##########
            this.write_text(
                [
                    `PEDIDO N°: ${this.voucher.pedido_code}`
                ],
                true,
                10,
                this.first_column_text(),
                this.first_header_height() + 7 + (this.interline() * 3), // 3 por que la ubico en la cuarta linea de texto
                this.interline()

            );
            //#####################
            if (height_position == 103) {
                this.writeData();
            }
            height_position = height_position + 4;
            width_position = 14;

            options = {
                lineHeightFactor : 1.7,
                maxWidth : 15,
                align : 'center'
            }
            this.pdf.setFontSize(10);

            this.pdf.text(String(product.quantity), width_position, height_position, options);

            //##### DESCRIPTION ######
            width_position = 24;
            options = {
                lineHeightFactor : 1.7,
                maxWidth : 150,
                align : 'left'
            }

            let description = '';
            let description_when_isCHP = '';
            if (product.isCHP) {
                const mts_by_unit = parseFloat(product.real_mts) / parseFloat(product.quantity);

                description = `${product.product_name} x ${mts_by_unit.toFixed(2)} mts.`

                if (product.rounded_mts > 0) {
                    //description_when_isCHP = `Ajuste por corte ${product.rounded_mts} mts. - Total: ${product.mts_to_invoiced} mts.`
                }
            }else{
                description = product.product_name;
            }

            let product_name_width = this.pdf.getTextDimensions(description);

            if (product_name_width.w > 135) {
                let array_text = this.pdf.splitTextToSize(description, 135);
                array_text.forEach((line, i) => {
                    this.pdf.text(line, width_position, height_position, options);
                    height_position = height_position + 4;
                });
                //this.pdf.text(description_when_isCHP, width_position, height_position, options);

                height_position = height_position - 4;

            }else{
                this.pdf.text(description, width_position, height_position, options);
                /* if (product.rounded_mts > 0) {
                    height_position = height_position + 4;
                    this.pdf.text(description_when_isCHP, width_position, height_position, options);
                } */
            }

            width_position = 200;
            options = {
                lineHeightFactor : 1.7,
                maxWidth : 23,
                align : 'right'
            }

            if (height_position > 208) {
                this.pdf.addPage();
                height_position = 103;
                this.invoice_original(title);
                current_page = current_page + 1;
                this.numberOfPages(current_page);
            }
        });

    }

    generatePdf(titles){
        //const titles_collection = collect(titles);

        let pageCount = 0;

        titles.forEach((title) => {

            this.details_of_product(title);

            if (this.customer.obs == null) {
                this.customer.obs = ' ';
            }

            const date = new Date;

            const time = `Fecha de impresión: ${date.getDate()}/${date.getMonth()+1}/${date.getFullYear()} - ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;

            this.write_text(
                [
                    time,
                ],
                true,
                10,
                this.first_column_text() - (this.one_cm() / 2),
                this.margin_bottom - (this.one_cm() * 3.5),
                this.interline()
            );

            this.pdf.addImage(WpImg.base_64(), 'PNG',  this.middle_width() - this.one_cm(), this.middle_height() + 2 + this.one_cm() * 9, 50, 42);

            const obs_dimensions = this.pdf.getTextDimensions(this.customer.obs);

			let height_position_obs = 265;

            if (obs_dimensions.w > 158) {

                let customer_obs = this.pdf.splitTextToSize(this.customer.obs, 158);

                customer_obs.forEach((line, i) => {
                    if (i == 0) {
                        this.pdf.text(`Observaciones: ${line}` , 12, height_position_obs);
                    }else{
                        height_position_obs = height_position_obs + 4
                        this.pdf.text(line , 12, height_position_obs );
                    }
                });

            } else {

                if (this.voucher.obs) {
                    this.pdf.text(`Observaciones: ${this.voucher.obs}` , 12, 265);
                }
            }

			if (this.voucher.comments) {

				height_position_obs = height_position_obs + 5

				this.voucher.comments.map( comment => {

					this.write_text(
						[comment.description],
						false,
						10,
						this.first_column_text() - (this.one_cm() / 2),
						height_position_obs,
						this.interline()
					);

					height_position_obs = height_position_obs + 5
				})
			}

            this.pdf.addPage();

        });

        pageCount = this.pdf.internal.getNumberOfPages();

        this.pdf.deletePage(pageCount)
    }

}

export default PedidoClientePdf;
