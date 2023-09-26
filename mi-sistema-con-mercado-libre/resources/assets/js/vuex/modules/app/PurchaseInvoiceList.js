export default {

    state : {
        purchase_invoices : []
        
    },

    mutations : {

        PURCHASE_INVOICE_LIST(state, value)
        {
            state.purchase_invoices = value
        }
    },

    getters : {

        PurchaserInvoiceList(state)
        {
            return state.purchase_invoices;
        }
    },

    actions : {

        
    }
}