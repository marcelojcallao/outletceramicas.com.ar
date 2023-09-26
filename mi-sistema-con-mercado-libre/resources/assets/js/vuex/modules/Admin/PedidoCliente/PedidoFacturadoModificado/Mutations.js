export const SET_COMMENTS_TO_INVOICE = (state, value) => state.comments = value;

export const SET_ITEMS_TO_FACTURAR = (state, value) => state.items.push(value);

export const IS_SENDING_TO_AFIP = (state, value) => state.isSendingToAfip = value;

export const SET_ITEMS_TO_FACTURAR_INITIAL_VALUE = (state) => state.items = [];

export const SET_ITEMS_TO_FACTURAR_UNIT_PRICE = (state, {index, value}) => {

    state.items[index].unit_price = value;

    const neto_import = value * state.items[index].quantity;

    state.items[index].neto_import = parseFloat(neto_import.toFixed(2));

    const iva_import = state.items[index].neto_import * (parseFloat(state.items[index].iva_name) / 100);

    state.items[index].iva_import = parseFloat(iva_import.toFixed(2));

    const total_raw = state.items[index].neto_import + state.items[index].iva_import;

    state.items[index].total_raw = parseFloat(total_raw.toFixed(2));

    state.items[index].unit_price_invoiceA = value;

    const unit_price_invoiceB = value + value * (parseFloat(state.items[index].iva_name) / 100);

    state.items[index].unit_price_invoiceB = parseFloat(unit_price_invoiceB.toFixed(2))

    state.items[index].total_invoiceA = state.items[index].total_raw;

    state.items[index].total_invoiceB = state.items[index].total_raw;
}

export const SET_ITEMS_TO_FACTURAR_NETO_IMPORT = (state, {index, value}) => {
    state.items[index].neto_import = value;

    const unit_price = state.items[index].neto_import / state.items[index].quantity
    state.items[index].unit_price = parseFloat(unit_price.toFixed(2));

    const iva_import = state.items[index].neto_import * (parseFloat(state.items[index].iva_name) / 100);
    state.items[index].iva_import = parseFloat(iva_import.toFixed(2))

    const total_raw = state.items[index].neto_import + state.items[index].iva_import;

    state.items[index].total_raw = parseFloat(total_raw.toFixed(2));

    state.items[index].unit_price_invoiceA = state.items[index].unit_price;

    const unit_price_invoiceB = state.items[index].unit_price + state.items[index].unit_price * (parseFloat(state.items[index].iva_name) / 100);
    state.items[index].unit_price_invoiceB = parseFloat(unit_price_invoiceB.toFixed(2));

    state.items[index].total_invoiceA = state.items[index].total_raw;
    state.items[index].total_invoiceB = state.items[index].total_raw;

}
