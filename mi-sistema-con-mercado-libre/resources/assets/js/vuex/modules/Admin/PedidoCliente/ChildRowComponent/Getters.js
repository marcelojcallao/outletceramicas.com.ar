export const PedidoListChildRowReactivityData = (state) => {
    return state.data;
}

export const PedidoDeliveryAddressGetter = (state) => {
    if (state.data) {
        return state.data.delivery_addresses;
    }
    return false;
}

export const WhoPreparedGetter = (state) => {
    return state.whoPrepared;
}

export const WhoPreparedSpinnerGetter = (state) => {
    return state.whoPreparedSpinner;
}

export const OpenWhoPreparedInputGetter = (state) => {
    return state.openWhoPreparedInput;
}

export const WhoDispatchGetter = (state) => {
    return state.whoDispatch;
}

export const WhoDispatchSpinnerGetter = (state) => {
    return state.whoDispatchSpinner;
}

export const OpenWhoDispatchInputGetter = (state) => {
    return state.openWhoDispatchInput;
}

export const OpenChangeInvoiceDateGetter = (state) => state.openChangeInvoiceDate;

export const OrderUpdateNewProductGetter = (state) =>  state.newProduct;

export const OrderUpdateNewProductProductName = (state) =>  state.newProduct.product_name;

export const OrderUpdateNewProductPriceList = (state) =>  state.newProduct.price_list;

export const OrderUpdateNewProductQuantity = (state) =>  state.newProduct.quantity;