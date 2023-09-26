import Pdf from "../Pdf";
import collect from 'collect.js';
import Logo from './../Img/Logo';
import QrAfip from '../../QrCode/Afip/QrAfip';
import BackGroundWater from './../Img/BackGroundWater';
import NumerosALetras from './../NumerosALetras';

class InvoicePdf extends Pdf {

    constructor(BillCbteTipo) {
        super()

        this.BillCbteTipo = BillCbteTipo;

        this.heightPosition_add_page = true;

        this.company = {
            name: false,
            address: false,
            inscription: false,
            cuit: false,
            iibb: false,
            activity_init: false,
            document_type: false,
            iibb_conv: false
        }

        this.voucher = {
            name: false,
            letter: false,
            number: false,
            date: false,
            details_product: false,
            pay_condition: {
                condition: false,
                fch_vto: false,
            },
            expiration_date: false,
            totals: false,
            cae: false,
            expiration_cae: false,
            comments: false,
            percep_iibb : false,
            modo_pago : false,
            vto_payment : false,
            pedido_cliente_delivery_date : false,
            delivery_address: false,
            obs: false,
            pedido_code: false,
        }

        this.customer = {
            name: false,
            address: false,
            iva: false,
            cuit: false,
            docTipo: false,
            inscription: false,
            obs: false,
            cell_phone: false,
            phone: false
        }

        this.qrAfip = {}
    }

    setCompanyName(name) {
        this.company.name = name;
        return this;
    }

    setCompanyAddress(address) {
        this.company.address = address;
        return this;
    }

    setCompanyInscription(inscription) {
        this.company.inscription = inscription;
        return this;
    }

    setCompanyCuit(cuit) {
        this.company.cuit = cuit;
        return this;
    }

    setCompanyIibb(iibb) {
        this.company.iibb = iibb;
        return this;
    }

    setCompanyActivityInit(activity_init) {
        this.company.activity_init = activity_init;
        return this;
    }

    setCompanyDocumentType(document_type) {
        this.company.document_type = document_type;
        return this;
    }

    setCompanyIibbConv(iibb_conv) {
        this.company.iibb_conv = iibb_conv;
        return this;
    }

    setVoucherName(name) {
        this.voucher.name = name;
        return this;
    }

    setVoucherLetter(letter) {
        this.voucher.letter = letter;
        return this;
    }

    setVoucherNumber(number) {
        this.voucher.number = number;
        return this;
    }

    setVoucherDate(date) {
        this.voucher.date = date;
        return this;
    }

    setVoucherDetailsProduct(details_product) {
        this.voucher.details_product = details_product;
        return this;
    }

    setVoucherPayCondition(pay_condition) {
        this.voucher.pay_condition.condition = pay_condition.condition;
        this.voucher.pay_condition.fch_vto = pay_condition.fch_vto;
        return this;
    }

    setVoucherExpirationDate(expiration_date) {
        this.voucher.expiration_date = expiration_date;
        return this;
    }

    setVoucherTotals(totals) {
        this.voucher.totals = totals;
        return this;
    }

    setVoucherCae(cae) {
        this.voucher.cae = cae;
        return this;
    }

    setVoucherExpirationCae(expiration_cae) {
        this.voucher.expiration_cae = expiration_cae;
        return this;
    }

    setVoucherComments(comments) {
        this.voucher.comments = comments;
        return this;
    }
    setVoucherPercepIIBB(percep_iibb) {
        this.voucher.percep_iibb = percep_iibb;
        return this;
    }
    setVoucherModoPago(modo_pago) {
        this.voucher.modo_pago = modo_pago;
        return this;
    }

    setVoucherVtoPayment(vto_payment) {
        this.voucher.vto_payment = vto_payment;
        return this;
    }

    setVoucherPedidoClienteDeliveryDate(pedido_cliente_delivery_date) {
        this.voucher.pedido_cliente_delivery_date = pedido_cliente_delivery_date;
        return this;
    }

    setVoucherDeliveryAddress(delivery_address) {
        this.voucher.delivery_address = delivery_address;
        return this;
    }

    setVoucherObs(obs) {
        this.voucher.obs = obs;
        return this;
    }

    setPedidoCode(pedido_code){
        this.voucher.pedido_code = pedido_code;
        return this;
    }

    setCustomerName(name) {
        this.customer.name = name;
        return this;
    }

    setCustomerAddress(address) {
        this.customer.address = address;
        return this;
    }

    setCustomerIva(iva) {
        this.customer.iva = iva;
        return this;
    }

    setCustomerCuit(cuit) {
        this.customer.cuit = cuit;
        return this;
    }
    /** CUIT - CUIL - DNI */
    setCustomerDocTipo(docTipo) {
        this.customer.docTipo = docTipo;
        return this;
    }

    setCustomerInscripton(inscription) {
        this.customer.inscription = inscription;
        return this;
    }

    setCustomerObs(obs) {
        this.customer.obs = obs;
        return this;
    }

    setCustomerCellPhone(cell_phone) {
        this.customer.cell_phone = cell_phone;
        return this;
    }

    setCustomerPhone(phone) {
        this.customer.phone = phone;
        return this;
    }

    setQrAfip(qr){
        this.qrAfip = qr;
        return this;
    }

    leftVerticalBorder(){
        this.pdf.setLineWidth(0.5);
        this.pdf.line(this.margin_left, this.margin_top, this.margin_left, this.margin_bottom);
    }

    rightVerticalBorder(){
        this.pdf.setLineWidth(0.5);
        this.pdf.line(this.margin_right, this.margin_top, this.margin_right, this.margin_bottom);
    }

    topBorder(){
        this.pdf.setLineWidth(0.5);
        this.pdf.line(this.margin_left, this.margin_top, this.margin_right, this.margin_top);
    }

    bottomBorder(){
        this.pdf.setLineWidth(0.5);
        this.pdf.line(this.margin_left, this.margin_bottom, this.margin_right, this.margin_bottom);
    }

    first_header_height(){
        return this.margin_top * 11;
    }

    headerLeft(){
        this.pdf.line(this.margin_left,
        this.first_header_height(),
        this.middle_width(),
        this.first_header_height());
    }

    invoice_type(invoice_letter='Z'){
        this.pdf.setFontSize(20);
        this.pdf.setFont('Helvetica','bold');
        this.pdf.text(invoice_letter,96.5, 12);
        this.pdf.rect(93, 5, 13, 10);
    }

    headerRight(){
        this.pdf.line(
            this.middle_width(),
            this.first_header_height(),
            this.margin_right,
            this.first_header_height()
        );
    }

    leftHeaderCompanyData(company){
        const size_text = 8;
        const interline = 10;
        let height_position = 37;

        const companyData = [
            'Razón social: ' + company.name,
            'Domicilio: ' + company.address,
            'Cond. IVA: ' + company.inscription
        ]

        this.pdf.setFontSize(8);

        companyData.forEach((t, index) => {

            const dim = this.pdf.getTextDimensions(t);

            if (dim.w > 92) {

                const array_text = this.pdf.splitTextToSize(t, 92);

                array_text.forEach((line, i) => {
                    height_position = height_position + 3.4;
                    this.pdf.text(line, this.first_column_text() - 5, height_position);
                })
            }else{
                height_position = height_position + 3.4;
                this.pdf.text(t, this.first_column_text() - 5, height_position);
            }
        });
    }

    rightHeaderCompanyData(cuit = 12345678, iibb = 'iibb', act = '22/04/1973'){
        this.write_text(
            [
                'CUIT: ' + cuit,
                'Ingresos Brutos: ' + iibb,
                'Fecha inicio de Actividades: ' + act
            ],
            true,
            8,
            105,
            41,
            5
        );
    }

    invoice_type_name(voucher = null, voucher_number = null, date){
        let voucher_width = this.pdf.getTextDimensions(voucher);
        let size_text = 16;
        if (voucher_width.w > 50) {
            size_text = 9;
        }
        this.write_text(
            [
                voucher,
            ],
            true, //bold
            size_text, //size text
            110, //width posi.
            18, //heigh posi
            5
        );

        this.write_text(
            [
                voucher_number,
            ],
            true,
            16,
            110,
            26,
            5
        );
        this.write_text(
            [
                'Fecha: ' + date,
            ],
            true,
            12,
            110,
            33,
            5
        );
    }

    invoice_original(text='ORIGINAL'){
        this.write_text(
            [
                text,
            ],
            false,
            8,
            110,
            11,
            5
        );
    }

    dividerHeader(){
        this.pdf.line(
            this.middle_width(),
            23,
            this.middle_width(),
            this.first_header_height()
        );
    }

    code201(){
        this.pdf.setFontSize(8);
        this.pdf.setFont('Helvetica');
        this.pdf.text('Código 201',92, 19);
    }

    customer_data(customer = null, address = null, iva = null, cuit = null, docTipo=null, delivery_address=null)
    {
        let customer_address = '';

        if(address instanceof Array) {
            customer_address = address[0];
        }else{
            customer_address = address;
        }

        const text = [
            'SEÑOR/ES: ' + customer,
            'DOMICILIO: ' + customer_address,
            'COND. IVA: ' + iva,
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
                    height_position = height_position + 4;
                    this.pdf.text(line, this.first_column_text(), height_position);
                });

            }else{
                height_position = height_position + 4;
                this.pdf.text(t, this.first_column_text(), height_position);
            }
        });

        this.write_text(
            [
                docTipo + ': ' + cuit
            ],
            true,
            10,
            this.middle_width() + this.quarter_width() + 15,
            this.first_header_height() + 10 + (this.interline() * 3), // 3 por que la ubico en la cuarta linea de texto
            this.interline()
        );

    }

    total_a_letras(value)
    {
        let NumLetras = new NumerosALetras;

        let txt = NumLetras.NumeroALetras(value, {
            plural: "PESOS",
            singular: "PESO",
            centPlural: "CENTAVOS",
            centSingular: "CENTAVO"
        });

        this.write_text(
            [
                'Son Pesos: ' + txt
            ],
            true,
            8,
            this.first_column_text() - (this.one_cm() / 2),
            this.margin_bottom - 2.5,
            this.interline()
        );
    }

    addImage(data){
        this.pdf.addImage(data, 'PNG', 50, 50);
    }

    CurrencyFormat(value) {
        return new Intl.NumberFormat('es-AR',
            {
                style:"currency",
                currency:"ARS",
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
                useGrouping : true,
            }).format(parseFloat(value));
    }

    total_width(){
        return (this.margin_right - this.margin_left);
    }

    total_height(){
        return (this.margin_bottom - this.margin_top);
    }

    middle_width(){
        return  (this.total_width() / 2) + (this.margin_left / 2);
    }

    quarter_width(){
        return  this.middle_width() / 2;
    }

    middle_height(){
        return this.total_height() / 2;
    }

    quarter_height(){
        return  this.middle_height() / 2;
    }

    one_cm(){
        return 10;
    }

    horizontalLine(x1,y1,x2,y2, style='S'){
        this.pdf.line(x1,y1,x2,y2, style);
    }

    verticalLine(x1,y1,x2,y2, style='S'){
        this.pdf.line(x1,y1,x2,y2, style);
    }

    interline(value = 6){
        return value;
    }

    first_column_text(){
        return this.margin_left + this.one_cm();
    }

    addLogo(img){
        this.pdf.addImage(img, 'PNG', 10, 6, 77, 29);
    }

    pay_condition(condition = '', fch_vto = '') {
        /* this.write_text(
            [

                'REFERENCIA COMERCIAL: 49676' ,

            ],
            true,
            8,
            this.first_column_text(),
            80,
            5
        );

        this.write_text(
            [
                'CBU del Emisor: 0720050220000001317518  Alias CBU: FIGURA.OBOE.SOTANA'
            ],
            true,
            10,
            this.first_column_text(),
            this.first_line_height - 10.5,
            this.interline()
        ); */

        this.write_text(
            [
                //'Condición de Pago: Otros medios de pago habilitados por BCRA'
                condition
            ],
            true,
            10,
            this.first_column_text() - 2,
            this.first_line_height + 6.5,
            this.interline()
        );

        this.write_text(
            [
                fch_vto
            ],
            true,
            10,
            this.first_column_text() * 7.5,
            this.first_line_height + 6.5,
            this.interline()
        );
    }

    details_of_totals() {}

    details_of_product(){}

    internal_footer() {
        this.horizontalLine(
            this.margin_left,
            this.margin_bottom - (this.one_cm() * 4),
            this.margin_right,
            this.margin_bottom - (this.one_cm() * 4),
        );

        this.horizontalLine(
            this.margin_left,
            this.margin_bottom - (this.one_cm() * 0.8),
            this.margin_right,
            this.margin_bottom - (this.one_cm() * 0.8),
        );

    }
    //
    generate_afip_code(cuit, cbte, ptovta, cae, fchvto) {
        let text = cuit + cbte + '000' + ptovta + cae + fchvto;
        let dig_v = this.digVerificador(text);
        let barcode = text + dig_v;
        this.barcode(barcode);
    }

    cae(cae, cae_vto) {
        this.pdf.setFontSize(8);
        let width_position = 155;

        let height_position = 282

        let options = {
            lineHeightFactor: 1.2,
            maxWidth: 50,
            align: 'left'
        }
        if (cae) {
            this.pdf.text('CAE: ' + cae, width_position, height_position, options);
        }

        if (cae_vto) {
            this.pdf.text('F. Vto. ' + cae_vto, width_position, height_position + 5, options);
        }

    }

    digVerificador(digVerificador) {
        /*
            RUTINA PARA EL CALCULO DEL DIGITO VERIFICADOR
            Se considera para efectuar el cálculo el siguiente ejemplo:
            01234567890
            Etapa 1: Comenzar desde la izquierda, sumar todos los caracteres ubicados en las posiciones impares.
            0 + 2 + 4 + 6 + 8 + 0 = 20
            Etapa 2: Multiplicar la suma obtenida en la etapa 1 por el número 3.
            20 x 3 = 60
            Etapa 3: Comenzar desde la izquierda, sumar todos los caracteres que están ubicados en las posiciones pares.
            1 + 3 + 5+ 7 + 9 = 25
            Etapa 4: Sumar los resultados obtenidos en las etapas 2 y 3.
            60 + 25 = 85
            Etapa 5: Buscar el menor número que sumado al resultado obtenido en la etapa 4 dé un número múltiplo de 10. Este será el valor del dígito verificador del módulo 10.
            85 + 5 = 90
            De esta manera se llega a que el número 5 es el dígito verificador módulo 10 para el código 01234567890
            Siendo el resultado final:
            012345678905

            Código de barras que usa "Interleaved 2 of 5"

            - C.U.I.T. (Clave Unica de Identificación Tributaria) del emisor (11 caracteres).
            - Código de tipo de comprobante (2 caracteres).
            - Punto de venta (4 caracteres).
            - Código de Autorización de Impresión (14 caracteres).
            - Fecha de vencimiento (8 caracteres). YYYYMMDD
            - Dígito verificador (1 carácter).
        */
        let sumaPar = 0;
        let sumaImpar = 0;
        for (let i = 0; i < digVerificador.length; i++) {
            if ((i + 1) % 2 != 0) {
                sumaImpar = sumaImpar + Number(digVerificador[i]);
            } else {
                sumaPar = sumaPar + Number(digVerificador[i]);
            }
        }
        let etapa2 = sumaImpar * 3;
        let etapa4 = etapa2 + sumaPar;

        for (let i = 0; i < 10; i++) {
            if ((etapa4 + i) % 10 == 0) {
                return i;
            }
        }

    }

    obs(obs) {
        this.pdf.setFontSize(8);
		this.pdf.setFont('arial', 'italic');

        const OBS = 'OBSERVACIONES:';

        const dim = this.pdf.getTextDimensions(obs);

        let position = this.margin_bottom - (this.one_cm() * 3.5);

        if (dim.w > 90) {
            const array_text = this.pdf.splitTextToSize(obs, 90);

            array_text.forEach((line, index) => {

				if (index === 0) {
					this.pdf.text(`${OBS} ${line}`, this.first_column_text() - (this.one_cm() / 2), position);
				}else{
					this.pdf.text(line, this.first_column_text() - (this.one_cm() / 2), position);
				}
                position = position + 3.5
            });

        }else{
            this.pdf.text(`${OBS} ${obs}`, this.first_column_text() - (this.one_cm() / 2), position);
        }
    }

    numberOfPages(currentPage)
    {
        this.write_text(
            [
                'Página '+ parseInt(currentPage)
            ],
            false,
            8,
            185,
            288,
        );

    }

    estructureBase() {
        this.pdf.addImage(BackGroundWater.base_64(), 'PNG', 10, 110, 190, 100);
        this.pdf.addImage(Logo.base_64(), 'PNG', 10, 6, 77, 29);
        this.code201();
        this.topBorder();
        this.headerLeft();
        this.headerRight();
        this.bottomBorder();
        this.dividerHeader();
        this.pay_condition(this.voucher.pay_condition.condition, this.voucher.pay_condition.fch_vto);
        this.internal_footer();
        this.leftVerticalBorder();
        this.rightVerticalBorder();
        this.horizontalLine(this.margin_left, 103, this.margin_right, 103);
        this.horizontalLine(this.margin_left, 103, this.margin_right, 103);
        this.horizontalLine(this.margin_left, this.first_line_height, this.margin_right, this.first_line_height);
        this.horizontalLine(this.margin_left, this.first_line_height + 10, this.margin_right, this.first_line_height + 10);
        this.verticalLine(this.margin_left + 15, 103, this.margin_left + 15, this.margin_bottom - (this.one_cm() * 4))

    }

    writeData(){
        this.estructureBase();

        this.cae(this.voucher.cae, this.voucher.expiration_cae);
        this.invoice_type(this.voucher.letter);
        this.customer_data(this.customer.name, this.customer.address, this.customer.inscription, this.customer.cuit, this.customer.docTipo, this.voucher.delivery_address);
        this.headers_invoice_data();

        this.leftHeaderCompanyData(this.company);
        this.rightHeaderCompanyData(this.company.cuit, this.company.iibb_conv, this.company.activity_init);
        this.invoice_type_name(this.voucher.name, this.voucher.number + '', this.voucher.date);
		this.obs(this.voucher.obs)

    }

    generatePdf(titles){

        const titles_collection = collect(titles);

        let pageCount = 0;

        titles_collection.each((title) => {

            this.details_of_product(title);
            this.details_of_totals();

            const qrAfip = new QrAfip(
                this.qrAfip.ver,
                this.qrAfip.date,
                this.qrAfip.cuit,
                this.qrAfip.ptoVta,
                this.qrAfip.CbteTipo,
                this.qrAfip.nroCbte,
                this.qrAfip.importe,
                this.qrAfip.money,
                this.qrAfip.ctz,
                this.qrAfip.tipoDocRec,
                this.qrAfip.nroDocRec,
                this.qrAfip.tipoCodAut,
                this.qrAfip.codAut,
            );
            const qr_base_64 = qrAfip.generate_qr();
            this.pdf.addImage(qr_base_64, 'PNG', 5, 262, 40, 40);
            this.pdf.addPage();

        });
        pageCount = this.pdf.internal.getNumberOfPages();
        this.pdf.deletePage(pageCount)
    }

    msg_monotributo(){

        const leyendaMonotributo = 'EL CRÉDITO FICAL DISCRIMINADO EN EL PRESENTE COMPROBANTE SÓLO PODRÁ SER COMPUTADO A EFECTOS DEL PROCEDIMIENTO PERMANENTE DE TRANSICIÓN AL RÉGIMEN GENERAL - CAPÍTULO IV DE LA LEY N° 27.618';
        const array_text = this.pdf.splitTextToSize(leyendaMonotributo, 150);

        this.pdf.setFontSize(8);
        this.pdf.setTextColor('red');
        this.pdf.text(array_text, this.margin_left + 40 , this.margin_bottom + 8, {align: 'left'});
        this.pdf.setTextColor('black');

        this.pdf.rect(this.margin_left + 39, this.margin_bottom + 3, 154, 13);
    }

}

export default InvoicePdf;
