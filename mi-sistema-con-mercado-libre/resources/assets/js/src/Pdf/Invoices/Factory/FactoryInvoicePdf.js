import collect from "collect.js";
import InvoiceACreator from './Creators/InvoiceACreator';
import InvoiceBCreator from './Creators/InvoiceBCreator';
import CotizacionCreator from "./Creators/CotizacionCreator";
import PresupuestoCreator from "./Creators/PresupuestoCreator";

const pdfs = [
    {
        type : 1,
        creator : InvoiceACreator
    },
    {
        type : 2,
        creator : InvoiceACreator
    },
    {
        type : 3,
        creator : InvoiceACreator
    },
    {
        type : 6,
        creator : InvoiceBCreator
    },
    {
        type : 7,
        creator : InvoiceBCreator
    },
    {
        type : 8,
        creator : InvoiceBCreator
    },
    {
        type : 88,
        creator : PresupuestoCreator
    },
    {
        type : 102,
        creator : CotizacionCreator
    },
]
class FactoryInvoicePdf {

    static createInstance(type)
    {
        const creator = collect(pdfs).map(pdf => {
            if (pdf.type == type)
            {
                return pdf.creator;
            }
        }).filter().first();

        return new creator();
    }
}

export default FactoryInvoicePdf;
