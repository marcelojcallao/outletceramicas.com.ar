
import collect from 'collect.js';
import InvoicePdf from "./InvoicePdf";

class InvoiceTypeB extends InvoicePdf {

    headers_invoice_data(){

        this.write_text(
            [
                'CANTIDAD'
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

        this.write_text(
            [
                'DESCUENTO'
            ],
            true,
            7,
            this.first_column_text() * 9.5,
            this.first_line_height + 15,
            this.interline()
        );

        this.write_text(
            [
                'P/UNIT..'
            ],
            true,
            7,
            this.first_column_text() * 8.4,
            this.first_line_height + 15,
            this.interline()
        );

        this.write_text(
            [
                'TOTAL'
            ],
            true,
            7,
            this.first_column_text() * 11,
            this.first_line_height + 15,
            this.interline()
        );

       /*  this.write_text(
            [
                'Cheques a la orden de: '
            ],
            true,
            10,
            this.first_column_text() - (this.one_cm() / 2),
            this.margin_bottom - (this.one_cm() * 2.5),
            this.interline()
        ); */

        this.verticalLine(this.margin_right - 63, 103, this.margin_right - 64, this.margin_bottom - (this.one_cm() * 4))
        this.verticalLine(this.margin_right - 42, 103, this.margin_right - 42, this.margin_bottom - (this.one_cm() * 4))
        this.verticalLine(this.margin_right - 21, 103, this.margin_right - 21, this.margin_bottom - (this.one_cm() * 4))
    }
}

export default InvoiceTypeB;
