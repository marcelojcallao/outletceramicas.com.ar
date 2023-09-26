import InvoicePdf from "./InvoicePdf";
import collect from "collect.js";

class InvoiceTypeA extends InvoicePdf {

    headers_invoice_data(cbte_tipo){

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
                'P/UNIT.'
            ],
            true,
            7,
            this.first_column_text() * 8.5,
            this.first_line_height + 15,
            this.interline()
        );

        this.write_text(
            [
                'IVA.'
            ],
            true,
            7,
            this.first_column_text() * 9.7,
            this.first_line_height + 15,
            this.interline()
        );

        /* this.write_text(
            [
                'IVA'
            ],
            true,
            7,
            this.first_column_text() * 9.1,
            this.first_line_height + 15,
            this.interline()
        );

        this.write_text(
            [
                'IMP. IVA'
            ],
            true,
            7,
            this.first_column_text() * 9.7,
            this.first_line_height + 15,
            this.interline()
        ); */

        this.write_text(
            [
                'TOTAL'
            ],
            true,
            7,
            this.first_column_text() * 10.8,
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
        // lineas para dividir las columnas en los detalles de los productos
        this.verticalLine(this.margin_left + 15, 103, this.margin_left + 15,  this.margin_bottom - (this.one_cm() * 4))
        //this.verticalLine(this.margin_right - 86, 103, this.margin_right - 86,  this.margin_bottom - (this.one_cm() * 4))
        this.verticalLine(this.margin_right - 65, 103, this.margin_right - 65,  this.margin_bottom - (this.one_cm() * 4))
        //this.verticalLine(this.margin_right - 48, 103, this.margin_right - 48,  this.margin_bottom - (this.one_cm() * 4))
        this.verticalLine(this.margin_right - 40, 103, this.margin_right - 40,  this.margin_bottom - (this.one_cm() * 4))
        this.verticalLine(this.margin_right - 21, 103, this.margin_right - 21,  this.margin_bottom - (this.one_cm() * 4))
    }

}

export default InvoiceTypeA;
