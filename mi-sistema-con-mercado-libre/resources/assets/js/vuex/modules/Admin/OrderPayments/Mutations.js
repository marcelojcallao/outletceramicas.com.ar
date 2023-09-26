export const SET_INVOICES_TO_PAY = (state, value) => state.invoices_to_pay = value;

export const REMOVE_INVOICE = ( state, index) => state.invoices_to_pay.splice(index, 1);

export const SET_TO_PAY_VALUE = ( state, {index, value}) => state.invoices_to_pay[index].invoice.toPay = value;