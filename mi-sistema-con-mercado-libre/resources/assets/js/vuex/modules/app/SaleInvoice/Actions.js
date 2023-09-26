
export const invoice_store = async (context, token) => {
    try {
        
        const response = await axios.post('/api/sale_invoice/store', 
        {
            invoice : context.state.invoice,
            invoices : context.state.invoices,
        })

        return response.data;

    } catch (error) {
        console.log('Hubo un error en invoice_store');
        throw error;
    }
}

export const sale_invoice_excel = async (_, params) => {

    try {
        const response = await axios.post('/api/sale_invoice/excel', 
        {
            customer : params.customer,
            from : params.from,
            to : params.to,
            status : params.status,
        })

        return response.data;

    } catch (error) {
        console.log('Hubo un error en sale_invoice_excel');
        throw error;
    }

}
