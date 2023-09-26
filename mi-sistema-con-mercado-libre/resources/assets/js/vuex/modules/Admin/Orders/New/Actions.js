import collect from 'collect.js';

export const CurrencyFormat = (_, importe) => {

    return new Intl.NumberFormat('es-AR',
        {
            style:"currency",
            currency:"ARS",
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(importe);

}

export const updateTotalOnRowProduct = ({ commit }, index) => {
    commit('NEW_ORDER_SET_TOTALS_FROM_PRODUCT', index);
}

export const newOrdersetUnitPriceProduct = ({ commit, getters, dispatch }, payload) => {

    commit('NEW_ORDER_SET_UNIT_PRICE_PRODUCT', payload);

    commit('NEW_ORDER_SET_TOTALS_FROM_PRODUCT', payload.index);
}

export const newOrderSetQuantityProduct = async ({ commit, getters, dispatch }, payload) => {

    commit('NEW_ORDER_SET_QUANTITY_PRODUCT', payload);

    commit('NEW_ORDER_SET_TOTALS_FROM_PRODUCT', payload.index);
}

export const newOrderSetDiscountProduct = ({ commit }, payload) => {
    commit('NEW_ORDER_SET_DISCOUNT_PRODUCT', payload);
    commit('NEW_ORDER_SET_TOTALS_FROM_PRODUCT', payload.index);
}

export const calculeNetoAction = ({state}) => {

    const products = collect(state.order.products);

    const total = products.reduce( (acc, item) => {

        if (item.isCHP) {
            return acc + (parseFloat(item.unit_price) * parseFloat(item.mts_to_invoiced)) - parseFloat(item.descuento) ;
        }else{

            return acc + (parseFloat(item.unit_price) * (parseFloat(item.quantity) + parseFloat(item.rounded_mts))) - parseFloat(item.descuento);
        }
    });

    return total;
}

export const calculeIvaImportAction = ({state}) => {

    const products = collect(state.order.products);

    const total = products.reduce((acc, item) => {
        return acc + parseFloat(item.iva_import);
    });

    return total;
}

export const calculeDiscountAction = ({state}) => {

    const products = collect(state.order.products);

    const total = products.reduce((acc, item) => {
        return acc + parseFloat(item.discount);
    });

    return total;
}

export const calculeTotalAction = ({state}) => {

    const products = collect(state.order.products);

    const total = products.reduce((acc, item) => {
        return acc + parseFloat(item.total);
    });

    let shipping = 0;
    let payment = 0;
    if (Number.isNaN(state.order.shipping.value)) {
        shipping = 0;
    }else{
        shipping = state.order.shipping.value;
    }
    if (Number.isNaN(state.order.payment.value)) {
        payment = 0;
    }else{
        payment = state.order.payment.value;
    }

    const total_order = total + shipping + payment;

    return total_order;
}

export const calculateShippingAction = ({state}) => {
    return (parseFloat(state.order.neto) - parseFloat(state.order.discount)) * parseFloat(state.order.shipping.percentage) / 100;
}

export const calculateAditionalByPaymentTypeAction = ({state}) => {
    return (parseFloat(state.order.neto) - parseFloat(state.order.discount)) * parseFloat(state.order.payment.percentage) / 100;
}

export const new_order_store = async (_, order) => {

    try {

        const response = await axios.post('/api/pedido_cliente/store', {
            order : order
        });

        return response;

    } catch (error) {
        console.log('Hubo un error en new_order_store');
        throw error;
    }
}

export const restore_pedido = async (_, order_id) => {

    try {

        const response = await axios.put('/api/pedido_cliente/restore_pedido', {
            order_id : order_id
        });

        return response;

    } catch (error) {
        throw error;
    }
}

export const pedidoClientesSetTypeAction = ({ commit }, value) => commit('NEW_ORDER_PEDIDO_CLIENTES_SET_TYPE', value);

