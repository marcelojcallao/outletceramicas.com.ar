export const setInvoicesToPay = ({ commit }, value) => commit('SET_INVOICES_TO_PAY', value);

export const removeInvoice = ({ commit }, value) => commit('REMOVE_INVOICE', value);

export const setToPayValue = ({ commit }, { index, value }) => commit('SET_TO_PAY_VALUE', { index, value });

export const get_notas_credito_from_supplier = async (_, supplier_id) => {

    try {
        
        const response = await axios.post('/api/purchase_invoice/get_notas_credito_from_supplier', { supplier_id: supplier_id });

        return response;

    } catch (error) {
        throw error;
    }
}