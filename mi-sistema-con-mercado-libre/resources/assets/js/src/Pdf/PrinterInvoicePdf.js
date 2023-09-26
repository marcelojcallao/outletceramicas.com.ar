
import FactoryInvoicePdf from './Invoices/Factory/FactoryInvoicePdf'

class PrinterInvoicePdf {

    /**
     *
     * @param {*} data from PdfSaleInvoiceTransformer
     * @param  {...any} nameOfCopies ex. 'ORIGINAL', 'DUPLICADO'
     */
    static print(data, ...nameOfCopies){
    console.log("🚀 ~ file: PrinterInvoicePdf.js:12 ~ PrinterInvoicePdf ~ print ~ data:", data)

        const pdfCreator = FactoryInvoicePdf.createInstance(data.voucher_id);

        const pdf = pdfCreator.setPdfData(data);

        pdf.generatePdf(nameOfCopies);

        pdf.print();
    }
}

export default PrinterInvoicePdf;
