
const getters = {

    PurchaseInvoiceType(state)
    {
        return state.invoice.type;
    },

    PurchaseInvoiceSubsidiary(state)
    {
        return state.invoice.subsidiary;
    },

    PurchaseInvoiceNumber(state)
    {
        return state.invoice.number;
    },

    PurchaseInvoiceMoney(state)
    {
        return state.invoice.money;
    },

    PurchaseInvoiceArticleGetter(state)
    {
        return state.invoice.products;
    },

    PurchaseInvoiceTaxes(state)
    {
        return state.invoice.taxes;
    },

    PurchaseInvoiceTotal(state)
    {
        return state.invoice.products.reduce(( acc, current ) => {
            return acc + current.total_import;
        }, 0)
        
    },

    PurchaseInvoiceNeto(state)
    {
        return state.invoice.products.reduce(( acc, current ) => {
            return acc + current.neto_import;
        }, 0)
        
    },

    PurchaseInvoiceIva(state)
    {
        return state.invoice.products.reduce(( acc, current ) => {
            return acc + current.iva_import;
        }, 0)
        
    },

    PurchaseInvoiceTax(state)
    {
        return state.invoice.taxes.reduce(( acc, current ) => {
            return acc + current.import;
        }, 0)
        
    },

    PurchaseInvoiceArticlesCount(state)
    {
        if (state.invoice.products.length > 1) {
            return true;
        }
        return false;
    },

    GetPurchaseInvoiceDate(state)
    {
        return state.invoice.date;
    },

    GetPurchaseInvoiceImputationDate(state)
    {
        return state.invoice.imputation_date;
    },

    GetPurchaseInvoiceSupplier(state)
    {
        return state.invoice.supplier;
    },

    PurchaseInvoiceDateIsOk(state)
    {
        return state.invoice.date_is_ok;
    },

    GetVouchersGetter(state){
        return state.vouchers;
    },
    GetVouchersByInscriptionGetter(state){
        return state.vouchers_by_inscription;
    },

    GetProviderGetter(state){
        return state.invoice.supplier;
    },

    PurchaserInvoiceHasTax(state){
        if (state.invoice.taxes.length) {
            return true;
        }
        return false;
    }

}

export default getters;