export const invoicesToPayGetter = (state) => state.invoices_to_pay;

export const OrderToPayDateGetter = (state) => state.date;

export const OrderToPayTotal = (state) => state.invoices_to_pay.reduce((acc, current) => {
    return acc + current.invoice.total_raw;
}, 0)