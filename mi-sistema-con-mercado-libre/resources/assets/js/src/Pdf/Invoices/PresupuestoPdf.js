import collect from "collect.js";
import InvoiceTypeB from "./InvoiceTypeB";
import WpImg from './../Img/Whatsapp';

class PresupuestoPdf extends InvoiceTypeB {

    constructor(){
        super();
    }

    details_of_product(title){
        let height_position = this.first_line_where_write_details; //desde 103 a 220, en ese rango se imprimwn los detalles 50075036 00000000000429
        let current_page = 1;
        let width_position = 0;
        let options = {};
        this.numberOfPages(current_page)
        this.invoice_original(title);

        collect(this.voucher.details_product).each((product, index) => {
        console.log("🚀 ~ file: PresupuestoPdf.js:20 ~ PresupuestoPdf ~ collect ~ product:", product)

            if (height_position == this.first_line_where_write_details) {
                this.writeData();
            }
            height_position = height_position + 4;

            //##### QUANTITY ######
            width_position = 14;
            options = {
                lineHeightFactor : 1.7,
                maxWidth : 15,
                align : 'center'
            }
            this.pdf.setFontSize(this.size_text_details);
            let quantity = parseFloat(product.quantity);
            this.pdf.text(String(quantity), width_position, height_position, options);

            //##### DESCRIPTION ######
            width_position = 24;
            options = {
                lineHeightFactor : 1.7,
                maxWidth : 150,
                align : 'left'
            }
            this.pdf.setFontSize(this.size_text_details);
            let description = '';
            let description_when_isCHP = '';

            if (product.isCHP) {

                const rounded_mts_by_unit = parseFloat(product.rounded_mts) / parseFloat(product.quantity);

                const mts_by_unit = parseFloat(product.real_mts) / parseFloat(product.quantity);

                description = `${product.product_name} x ${mts_by_unit.toFixed(2)} mts.`

                if (product.rounded_mts > 0) {
                    //description_when_isCHP = `Ajuste por corte ${rounded_mts_by_unit} mts./chapa - Total: ${product.mts_to_invoiced} mts.`
                }
            }else{
                description = product.product_name;
            }

            const product_name_width = this.pdf.getTextDimensions(description);

            if (product_name_width.w > 135) {
                const array_text = this.pdf.splitTextToSize(description, 135);
                array_text.forEach((line, i) => {
                    this.pdf.text(line, width_position, height_position, options);
                    height_position = height_position + 4;
                });
                this.pdf.text(description_when_isCHP, width_position, height_position, options);

                height_position = height_position - 4;

            }else{
                this.pdf.text(description, width_position, height_position, options);

                if (product.rounded_mts > 0) {
                    //height_position = height_position + 4;
                    this.pdf.text(description_when_isCHP, width_position, height_position, options);
                }
            }

            //##### UNIT PRICE ######
            width_position = 156;
            options = {
                lineHeightFactor : 1.7,
                maxWidth : 23,
                align : 'right'
            }
            this.pdf.text(this.CurrencyFormat(product.unit_price_Presupuesto) , width_position, height_position, options);
            console.log("🚀 ~ file: unit price  ~ height_position:", height_position)

            width_position = 170;
            options = {
                lineHeightFactor : 1.7,
                maxWidth : 23,
                align : 'right'
            }
            this.pdf.setFontSize(8);
            //##### DISCOUNT ######
            width_position = 178;
            options = {
                lineHeightFactor : 1.7,
                maxWidth : 23,
                align : 'right'
            }
            this.pdf.text(this.CurrencyFormat(product.discount_import) , width_position, height_position, options);

            width_position = 200;
            options = {
                lineHeightFactor : 1.7,
                maxWidth : 23,
                align : 'right'
            }
            this.pdf.setFontSize(this.size_text_details);
            //##### TOTAL ######
            this.pdf.text(this.CurrencyFormat(product.neto_import - product.discount_import), width_position, height_position, options);

            if (height_position > 208) {
                this.pdf.addPage();
                height_position = 103;
                this.invoice_original(title);
                current_page = current_page + 1;
                this.numberOfPages(current_page);
            }
        });

    }

    details_of_totals() {

        let height_position = this.margin_bottom - 40;
        this.pdf.setFontSize(12);
        this.pdf.setFont('Helvetica','bold');

        if (this.voucher.totals) {
        console.log("🚀 ~ file: PresupuestoPdf.js:139 ~ PresupuestoPdf ~ details_of_totals ~ this.voucher.totals:", this.voucher)

            collect(this.voucher.totals).each((total, index) => {
                console.log("🚀 ~ file: PresupuestoPdf.js:142 ~ PresupuestoPdf ~ collect ~ total:", total)
                if (typeof total === "object") {

                    height_position = height_position + 5;
                    let options = {};

                    options = {
                        lineHeightFactor: 1.2,
                        maxWidth: 100,
                        align: 'right'
                    }

                    this.pdf.text(total.neto_name, this.first_column_text() * 8.5, height_position, options);

                    options = {
                        lineHeightFactor: 1.2,
                        maxWidth: 100,
                        align: 'right'
                    }
                    this.pdf.text(this.CurrencyFormat(total.neto_import), this.first_column_text() * 11.5, height_position, options);


                    if (total.aditional_payment_value != 0) {
                        height_position = height_position + 5;

                        options = {
                            lineHeightFactor: 1.2,
                            maxWidth: 100,
                            align: 'right'
                        }

                        this.pdf.text(total.aditional_payment_name, this.first_column_text() * 8.5, height_position, options);

                        options = {
                            lineHeightFactor: 1.2,
                            maxWidth: 100,
                            align: 'right'
                        }

                        this.pdf.text(this.CurrencyFormat(total.aditional_payment_value), this.first_column_text() * 11.5, height_position, options);
                    }
                    if (total.discount_import != 0) {
                        height_position = height_position + 5;

                        options = {
                            lineHeightFactor: 1.2,
                            maxWidth: 100,
                            align: 'right'
                        }

                        this.pdf.text(total.discount_name, this.first_column_text() * 8.5, height_position, options);

                        options = {
                            lineHeightFactor: 1.2,
                            maxWidth: 100,
                            align: 'right'
                        }

                        this.pdf.text(this.CurrencyFormat(total.discount_import *-1), this.first_column_text() * 11.5, height_position, options);
                    }
                }

            }); // Close map

            height_position = height_position + 5;

            let options = {
                lineHeightFactor: 1.2,
                maxWidth: 100,
                align: 'right'
            }

            this.pdf.text(this.voucher.totals.total_name, this.first_column_text() * 8.5, height_position, options);

            options = {
                lineHeightFactor: 1.2,
                maxWidth: 100,
                align: 'right'
            }
            this.pdf.text(this.CurrencyFormat(this.voucher.totals.total_import), this.first_column_text() * 11.5, height_position, options);

            this.total_a_letras(this.voucher.totals.total_import);
        }
    }

    writeData(){
        this.estructureBase();

        this.cae(this.voucher.cae, this.voucher.expiration_cae);
        this.invoice_type(this.voucher.letter);
        this.customer_data(this.customer.name, this.customer.address, this.customer.inscription, this.customer.cuit, this.customer.docTipo);
        this.headers_invoice_data();
        this.leftHeaderCompanyData(this.company);
        this.rightHeaderCompanyData(this.company.cuit, this.company.iibb_conv, this.company.activity_init);
        this.invoice_type_name(this.voucher.name, this.voucher.number + '', this.voucher.date);
        this.details_of_totals();
    }

    generatePdf(titles){

        let titles_collection = collect(titles);

        let pageCount = 0;

        titles_collection.each((title) => {

            this.pdf.addImage(WpImg.base_64(), 'PNG', 161, 25, 37.5, 31.5);
            this.details_of_product(title);
            this.details_of_totals();

			if (this.voucher.comments) {

				let height_position_obs = 265;

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

export default PresupuestoPdf;
