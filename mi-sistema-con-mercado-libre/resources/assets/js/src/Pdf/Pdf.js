//import 'jspdf-autotable'
import * as jsPDF from 'jspdf'

import bwipjs from 'bwip-js';
import NumerosALetras from './NumerosALetras';

class Pdf {

    constructor(){
        this.A4 = {
            orientation: 'p',
            unit: 'mm',
            format: 'a4',
            putOnlyUsedFonts:true,
            floatPrecision: 16 // or "smart", default is 16
        }
        /**
         * largo de la hoja A4 29,7 cm
         * largo del recuadro 27 cm
         * 10 % 2,7 cm
         * fuera de recuadro 2 cm
         */
        this.margin_left = 7;
        this.margin_right = 200;
        this.margin_top = 5;
        this.margin_bottom = 260;
        
        /**
         * first_line_height es después de customer data
         */
        this.first_line_height = 85;

        this.size_text_details = 8;

        this.size_text_totals = 10;

        this.first_line_where_write_details = 103;
        
        this.last_line_where_write_details = 220;

        this.width_description_area = 135;

        this.pdf = new jsPDF(this.A4);
    }

    /**
     * 
     * @param {*} array_text 
     * @param {*} bold 
     * @param {*} size_text 
     * @param {*} width_position 
     * @param {*} height_position 
     * @param {*} interline 
     * @param {*} options 
     */

    write_text(array_text, bold=false, size_text=8, width_position, height_position, interline, options = false){
        
        this.pdf.setFontSize(size_text);
        
        if (bold) {
            this.pdf.setFont('Helvetica','bold');
        } else {
            this.pdf.setFont('Helvetica');
        }

        array_text.forEach((element, index) => {
            
            if (options) {
                this.pdf.text(element, width_position, height_position, options);
            }else{
                this.pdf.text(element, width_position, height_position);
            }
            height_position = height_position + interline;

        });
    }

    barcode(text){
        
        let canvas = document.createElement("canvas");

        try {
            bwipjs.toCanvas(canvas,
            {
                bcid:        'interleaved2of5',       // Barcode type
                text:        text,    // Text to encode
                height:      11,              // Bar height, in millimeters
                includetext: true,            // Show human-readable text
                textxalign:  'center',        // Always good to set this
            });
        } catch (e) {
            console.log(e);
        } 
       
        let imgData = canvas.toDataURL();

        this.pdf.addImage(imgData, 'PNG', 10, 280, 100, 9);
    }

    print(){
        this.pdf.save('a4.pdf');
    }

}

export default Pdf;