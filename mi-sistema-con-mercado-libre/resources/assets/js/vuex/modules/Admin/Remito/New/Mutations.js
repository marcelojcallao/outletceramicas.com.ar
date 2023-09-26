export const SET_REMITO = (state, remito) => {
    state.remito.pedido_cliente_id = remito.pedido_cliente_id;
    state.remito.delivery_date = remito.delivery_date;
    state.remito.items = remito.items;
    state.remito.payment_type_id = remito.payment_type_id;
    state.remito.total = remito.total;
}