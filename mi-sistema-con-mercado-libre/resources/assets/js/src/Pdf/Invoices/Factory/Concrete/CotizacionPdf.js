import collect from 'collect.js';
import InvoiceTypeB from "../../InvoiceTypeB";
import WpImg from "./../../../Img/Whatsapp";

class CotizacionPdf extends InvoiceTypeB {

    constructor(BillCbteTipo){
        super()

        this.BillCbteTipo = BillCbteTipo;

    }

    details_of_product(title){

        let height_position = this.first_line_where_write_details; //desde 103 a 220, en ese rango se imprimwn los detalles
        let current_page = 1;
        let width_position = 0;
        let options = {};
        this.numberOfPages(current_page)
        this.invoice_original(title);

        this.pdf.addImage(WpImg.base_64(), 'PNG', 161, 25, 37.5, 31.5);
        this.write_text(
            [
                'PRECIOS NO INCLUYEN IVA'
            ],
            true,
            10,
            this.middle_width() - this.one_cm(),
            this.margin_bottom - (this.one_cm()),
            this.interline()
        );
        collect(this.voucher.details_product).each((product, index) => {

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
            const quantity = parseFloat(product.quantity);
            this.pdf.text(String(quantity), width_position, height_position, options);

            //##### DESCRIPTION ######
            this.pdf.setFontSize(9);
            width_position = 24;
            options = {
                lineHeightFactor : 1.7,
                maxWidth : 150,
                align : 'left'
            }

            let description = '';
            let description_when_isCHP = '';

            if (product.isCHP) {

                const rounded_mts_by_unit = parseFloat(product.rounded_mts) / parseFloat(product.quantity);

                const mts_by_unit = parseFloat(product.real_mts) / parseFloat(product.quantity);

                description = `${product.product_name} x ${mts_by_unit.toFixed(2)} mts.`

                if (product.rounded_mts > 0) {
                    //description_when_isCHP = `Ajuste por corte ${rounded_mts_by_unit} mts. - Total: ${product.mts_to_invoiced} mts.`
                }

            }else{
                description = product.product_name;
            }

            const product_name_width = this.pdf.getTextDimensions(description);

            if (product_name_width.w > 135) {
                let array_text = this.pdf.splitTextToSize(description, 135);
                array_text.forEach((line, i) => {
                    this.pdf.text(line, width_position, height_position, options);
                    height_position = height_position + 4;
                });
                this.pdf.text(description_when_isCHP, width_position, height_position, options);

                height_position = height_position - 4;

            }else{
                this.pdf.text(description, width_position, height_position, options);
                if (product.rounded_mts > 0) {
                    height_position = height_position + 4;
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

            if (product.isCHP){
                const unit_price = product.unit_price * (product.mts_to_invoiced / product.quantity);
                this.pdf.text(this.CurrencyFormat(unit_price) , width_position, height_position, options);
            }else{
				this.pdf.text(this.CurrencyFormat(product.unit_price) , width_position, height_position, options);
			}

            width_position = 170;
            options = {
                lineHeightFactor : 1.7,
                maxWidth : 23,
                align : 'right'
            }

            //##### DISCOUNT ######
            this.pdf.setFontSize(8);
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

            //##### TOTAL ######
            /* if (product.isCHP){
                console.log("🚀 ~ file: CotizacionPdf.js:141 ~ CotizacionPdf ~ collect ~ product:", product)
                const total = parseFloat(product.unit_price) * parseFloat(product.mts_by_unit) * parseFloat(product.quantity);
                this.pdf.text(this.CurrencyFormat(total), width_position, height_position, options);
            }else{

                const total = parseFloat(product.unit_price) * parseFloat(product.quantity);
                this.pdf.text(this.CurrencyFormat(total), width_position, height_position, options);
            } */
			this.pdf.text(product.total, width_position, height_position, options);
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

            height_position = height_position + 5;

            this.pdf.text('TOTAL:', this.first_column_text() * 8.5, height_position, {
                lineHeightFactor: 1.2,
                maxWidth: 100,
                align: 'right'
            });


            this.pdf.text(this.CurrencyFormat(this.voucher.totals), this.first_column_text() * 11.5, height_position, {
                lineHeightFactor: 1.2,
                maxWidth: 100,
                align: 'right'
            });

            this.total_a_letras(this.voucher.totals);

        }
    }
}

export default CotizacionPdf;
