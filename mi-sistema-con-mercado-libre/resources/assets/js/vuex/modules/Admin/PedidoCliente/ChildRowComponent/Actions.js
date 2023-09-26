export const who_prepared_update = async (_, payload) => {

    try {
        
        const response = await axios.put('/api/pedido_cliente/who_prepared_update', {
            order_id : payload.order_id,
            name : payload.who
        });

        return response;

    } catch (error) {
        throw error;
    }
}

export const who_dispatch_update = async (_, payload) => {

    try {
        
        const response = await axios.put('/api/pedido_cliente/who_dispatch_update', {
            order_id : payload.order_id,
            name : payload.who
        });

        return response;

    } catch (error) {
        throw error;
    }
}

export const invoice_print = async (_, sale_invoice_id) => {

    try {

        const response = await axios.post('/api/pedido_cliente/print_invoice', { sale_invoice_id });

        return response;
        
    } catch (error) {
        throw error;
    }
}

export const cotizacion_print = async (_, cotizacion) => {

    try {
        const pdf = await axios.post('/api/pedido_cliente/cotizacion_print',{cotizacion})

        return pdf;

    } catch (error) {
        throw error;
    }
}

export const add_delivery_address_to_pedido = async (_, address) => {

    try {
        const { data } = await axios.post('/api/pedido_cliente/add_delivery_address_to_pedido',{address})

        return data;

    } catch (error) {
        throw error;
    }
}

export const setOpenChangeInvoiceDate = ({commit}, value) => commit('SET_OPEN_CHANGE_INVOICE_DATE', value);

export const setCustomerAddressAtPedidoData = ({commit}, value) => commit('SET_CUSTOMER_ADDRESS_AT_PEDIDO_DATA', value);

export const setDeliveryAddressAtPedidoData = ({commit}, value) => commit('SET_DELIVERY_ADDRESS_AT_PEDIDO_DATA', value);

export const update_items_of_pedido = async (_, pedido) => {

    try {
        const { data } = await axios.post('/api/pedido_cliente/update_items_of_pedido',{pedido})

        return data;

    } catch (error) {
        throw error;
    }
}

export const addItemToPedido = async (_, item) => {

    try {
        const response  = await axios.post('/api/pedido_cliente/add_item_to_pedido',{ item });

        return response;
        
    } catch (error) {
        console.log("🚀 ~ file: Actions.js ~ line 115 ~ addItemToPedido ~ error", error)
        
    }
}

export const RowProductUpdateQuantity = async (_, payload) => {

    try {
        const response  = await axios.put('/api/pedido_cliente/row_product_update_quantity',{ payload });

        return response;
        
    } catch (error) {
        console.log("🚀 ~ file: Actions.js ~ line 131 ~ RowProductUpdateQuantity ~ error", error)
        
    }
}

export const orderUpdateNewProductSetInitialStatus = ({ commit }) => commit('ORDER_UPDATE_SET_INITIAL_STATUS');

export const orderUpdateSetOrderData = ({ commit }, value) => commit('ORDER_UPDATE_SET_ORDER_DATA', value);

export const orderUpdateDeleteProduct = ({ commit }, value) => commit('ORDER_UPDATE_DELETE_PRODUCT', value);

export const orderUpdateDeliveryDate = ({ commit }, value) => commit('ORDER_UPDATE_DELIVERY_DATE', value);

export const orderUpdateQuantity = ({ commit }, value) => commit('ORDER_UPDATE_QUANTITY', value)

export const orderUpdateSetProduct = ({ commit }, value) => commit('ORDER_UPDATE_SET_PRODUCT', value);

export const orderUpdateSetPriceLists = ({ commit }, value) => commit('ORDER_UPDATE_SET_PRICE_LISTS', value);

export const orderUpdateSetPriceList = ({ commit }, value) => commit('ORDER_UPDATE_SET_PRICE_LIST', value);

export const orderUpdateSetUnitPriceProduct = ({ commit }, value) => commit('ORDER_UPDATE_SET_UNIT_PRICE_PRODUCT', value);

export const orderUpdateSetQuantityProduct = ({ commit }, value) => commit('ORDER_UPDATE_SET_QUANTITY_PRODUCT', value);

export const orderUpdateSetIvaProduct = ({ commit }, value) => commit('ORDER_UPDATE_SET_IVA_PRODUCT', value);

export const orderUpdateSetMtsByUnityProduct = ({ commit }, value) => commit('ORDER_UPDATE_SET_MTS_BY_UNITY_PRODUCT', value);

export const orderUpdateSetRoundedMts = ({ commit }, value) => commit('ORDER_UPDATE_SET_ROUNDED_MTS', value);

export const orderUpdateSetRealMts = ({ commit }, value) => commit('ORDER_UPDATE_SET_REAL_MTS', value);

export const orderUpdateSetMtsToInvoiced = ({ commit }, value) => commit('ORDER_UPDATE_SET_MTS_TO_INVOICED', value);

export const orderUpdateAddItemToOrder = ({ commit }, value) => commit('ORDER_UPDATE_ADD_ITEM_TO_ORDER', value);

export const orderUpdateSaveCurrentOrder = async (_, order) => {

    try {
        const { data } = await axios.post('/api/pedido_cliente/order_update_save_current_order',{order})

        return data;

    } catch (error) {
        throw error;
    }
}

export const orderUpdateSetInitialValues = ( { dispatch }) => {
    dispatch('orderUpdateSetQuantityProduct', 1);
    //dispatch('orderUpdateSetMtsByUnityProduct', 1);
    dispatch('orderUpdateSetUnitPriceProduct', 0);
    dispatch('orderUpdateSetPriceList', null);
}