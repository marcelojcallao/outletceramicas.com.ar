import SheetMetal from '../../../../../src/SheetMetal/SheetMetal';

export const ORDER_UPDATE_SET_ORDER_DATA = (state, value) => state.order_data = JSON.parse(JSON.stringify(value));

export const ORDER_UPDATE_DELETE_PRODUCT = (state, value) => state.order_data.items.splice(value, 1);

export const ORDER_UPDATE_ADD_ITEM_TO_ORDER = (state, product) => {

    if (product.isCHP) {

        const mts_by_unity = parseFloat(product.real_mts) / parseInt(product.quantity);

        const index = state.order_data.items.findIndex(item => item.product_id == product.product_id && item.pricelist_id == product.price_list.id && (parseFloat(item.real_mts) / parseInt(item.quantity)) == mts_by_unity);

        if (index > -1) {
            state.order_data.items[index].quantity = parseInt(state.order_data.items[index].quantity) + parseInt(product.quantity)
        } else {
            state.order_data.items.push(product);
        }

    }else{

        const index = state.order_data.items.findIndex(item => item.product_id == product.product_id && item.pricelist_id == product.price_list.id);

        if (index > -1) {
            state.order_data.items[index].quantity = parseInt(state.order_data.items[index].quantity) + parseInt(product.quantity)
        }else{
            state.order_data.items.push(product);
        }
    }
}

export const ORDER_UPDATE_DELIVERY_DATE = (state, value) => state.order_data.delivery_date = value;

/** A partir de acá actualizo los datos en la tablde de EDITAR PEDIDO CLIENTE */
export const ORDER_UPDATE_SET_QUANTITY_PRODUCT_AT_LIST = (state, payload) => {

    state.order_data.items[payload.index].quantity = payload.value;

    if (state.order_data.items[payload.index].isCHP) {

        const rounded_mts =  parseFloat(SheetMetal.roundedMts(state.order_data.items[payload.index].mts_by_unity));

        state.order_data.items[payload.index].rounded_mts = rounded_mts;

        const sheet_metal = parseFloat(state.order_data.items[payload.index].mts_by_unity) + parseFloat(rounded_mts);

        const real_mts = parseFloat(state.order_data.items[payload.index].mts_by_unity) * parseInt(state.order_data.items[payload.index].quantity);

        state.order_data.items[payload.index].real_mts = real_mts;

        const total_rounded_mts = rounded_mts * state.order_data.items[payload.index].quantity;

        state.order_data.items[payload.index].total_rounded_mts = total_rounded_mts;

        const mts_to_invoiced = sheet_metal * state.order_data.items[payload.index].quantity;

        state.order_data.items[payload.index].mts_to_invoiced = mts_to_invoiced;

        state.order_data.items[payload.index].neto_import = ((parseFloat(state.order_data.items[payload.index].unit_price) * parseFloat(state.order_data.items[payload.index].mts_to_invoiced)) - parseFloat(state.order_data.items[payload.index].discount)).toFixed(2);

    }else{

        state.order_data.items[payload.index].neto_import = ((parseFloat(state.order_data.items[payload.index].unit_price) * parseFloat(state.order_data.items[payload.index].quantity)) - parseFloat(state.order_data.items[payload.index].discount)).toFixed(2);
    }

    state.order_data.items[payload.index].iva_import = (state.order_data.items[payload.index].neto_import * parseFloat(state.order_data.items[payload.index].iva.percentage) / 100).toFixed(2);

    state.order_data.items[payload.index].total_raw = (parseFloat(state.order_data.items[payload.index].neto_import) + parseFloat(state.order_data.items[payload.index].iva_import)).toFixed(2);
}

export const ORDER_UPDATE_SET_MTS_BY_UNITY_PRODUCT_AT_LIST = (state, payload) => {

    state.order_data.items[payload.index].mts_by_unity = payload.value;


    const rounded_mts =  parseFloat(SheetMetal.roundedMts(state.order_data.items[payload.index].mts_by_unity));

    state.order_data.items[payload.index].rounded_mts = rounded_mts;

    const sheet_metal = parseFloat(state.order_data.items[payload.index].mts_by_unity) + parseFloat(rounded_mts);

    const real_mts = parseFloat(state.order_data.items[payload.index].mts_by_unity) * parseInt(state.order_data.items[payload.index].quantity);

    state.order_data.items[payload.index].real_mts = real_mts;

    const total_rounded_mts = rounded_mts * state.order_data.items[payload.index].quantity;

    state.order_data.items[payload.index].total_rounded_mts = total_rounded_mts;

    const mts_to_invoiced = sheet_metal * state.order_data.items[payload.index].quantity;

    state.order_data.items[payload.index].mts_to_invoiced = mts_to_invoiced;

    state.order_data.items[payload.index].neto_import = ((parseFloat(state.order_data.items[payload.index].unit_price) * parseFloat(state.order_data.items[payload.index].mts_to_invoiced)) - parseFloat(state.order_data.items[payload.index].discount)).toFixed(2);

    state.order_data.items[payload.index].iva_import = (state.order_data.items[payload.index].neto_import * parseFloat(state.order_data.items[payload.index].iva.percentage) / 100).toFixed(2);

    state.order_data.items[payload.index].total_raw = (parseFloat(state.order_data.items[payload.index].neto_import) + parseFloat(state.order_data.items[payload.index].iva_import)).toFixed(2);
}

export const ORDER_UPDATE_SET_UNIT_PRICE = (state, payload) => {

    state.order_data.items[payload.index].unit_price = payload.value;

	if (state.order_data.items[payload.index].isCHP){
		state.order_data.items[payload.index].neto_import = ((parseFloat(state.order_data.items[payload.index].unit_price) * parseFloat(state.order_data.items[payload.index].mts_to_invoiced)) - parseFloat(state.order_data.items[payload.index].discount)).toFixed(2);

		state.order_data.items[payload.index].iva_import = (state.order_data.items[payload.index].neto_import * parseFloat(state.order_data.items[payload.index].iva.percentage) / 100).toFixed(2);

		state.order_data.items[payload.index].total_raw = (parseFloat(state.order_data.items[payload.index].neto_import) + parseFloat(state.order_data.items[payload.index].iva_import)).toFixed(2);

	}else{

		state.order_data.items[payload.index].neto_import = ((parseFloat(state.order_data.items[payload.index].unit_price) * parseFloat(state.order_data.items[payload.index].quantity)) - parseFloat(state.order_data.items[payload.index].discount)).toFixed(2);

		state.order_data.items[payload.index].iva_import = (state.order_data.items[payload.index].neto_import * parseFloat(state.order_data.items[payload.index].iva.percentage) / 100).toFixed(2);

		state.order_data.items[payload.index].total_raw = (parseFloat(state.order_data.items[payload.index].neto_import) + parseFloat(state.order_data.items[payload.index].iva_import)).toFixed(2);
	}

}
