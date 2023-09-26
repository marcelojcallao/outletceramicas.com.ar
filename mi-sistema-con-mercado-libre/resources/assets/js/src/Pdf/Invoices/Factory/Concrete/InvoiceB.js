import collect from 'collect.js';
import InvoiceTypeB from "../../InvoiceTypeB";
import QrAfip from '../../../../QrCode/Afip/QrAfip'
class InvoiceB extends InvoiceTypeB {

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
            let quantity = parseFloat(product.quantity);
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
                    description_when_isCHP = `Ajuste por corte ${rounded_mts_by_unit} mts. - Total: ${product.mts_to_invoiced} mts.`
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
            this.pdf.text(this.CurrencyFormat(product.unit_price_invoiceB) , width_position, height_position, options);

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
            this.pdf.setFontSize(9);
            this.pdf.text(this.CurrencyFormat(product.total_invoiceB), width_position, height_position, options);
            
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
       
            collect(this.voucher.totals).each((total, index) => {
                if (typeof total === "object") {
                
                    /* height_position = height_position + 5;
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
                    let subtotal = parseFloat(total.neto_import) + parseFloat(total.iva_import);
                    this.pdf.text(this.CurrencyFormat(subtotal), this.first_column_text() * 11.5, height_position, options);

                    if (total.aditional_payment_value > 0) {
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
                    
                    
                    if (total.hasOwnProperty('iibb_name')) {
                        height_position = height_position + 5;

                        options = {
                            lineHeightFactor: 1.2,
                            maxWidth: 100,
                            align: 'right'
                        }

                        this.pdf.text(total.iibb_name, this.first_column_text() * 8.5, height_position, options);

                        options = {
                            lineHeightFactor: 1.2,
                            maxWidth: 100,
                            align: 'right'
                        }
                        let iibb_import = (total.iibb_import == '') ? '' : this.CurrencyFormat(total.iibb_import);
                        this.pdf.text(iibb_import, this.first_column_text() * 11.5, height_position, options);

                    } */
                
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
}

export default InvoiceB;