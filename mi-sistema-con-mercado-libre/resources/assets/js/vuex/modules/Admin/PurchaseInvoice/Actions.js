export const getPurchaserInvoice = async({commit}, url) =>  {
    try {
        
        const response = await axios.get(url);

        return response

    } catch (e) {
        console.log('error en getAddressType action');
        console.log(e);
        throw e;
    }
}

export const setPurchaseInvoiceList = ({ commit }, value) => commit('SET_PURCHASE_INVOICE_LIST', value);

export const purchaseInvoiceListSetDateFrom = ({ commit }, value) => commit('PURCHASE_INVOICE_LIST_SET_DATE_FROM', value);

export const purchaseInvoiceListSetDateTo = ({ commit }, value) => commit('PURCHASE_INVOICE_LIST_SET_DATE_TO', value);

export const purchaseInvoiceListSetSupplier = ({ commit }, value) => commit('PURCHASE_INVOICE_LIST_SET_SUPPLIER', value);
