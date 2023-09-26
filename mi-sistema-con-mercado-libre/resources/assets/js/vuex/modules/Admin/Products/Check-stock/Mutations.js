export const CHECK_STOCK_SET_PRODUCT = (state, value) => state.product = value;

export const CHECK_STOCK_SET_ROUNDED_MTS = (state, value) => state.product.rounded_mts = value;

export const CHECK_STOCK_SET_MTS_BY_UNITY = (state, value) => state.product.mts_by_unity = value;

export const CHECK_STOCK_SET_REAL_MTS = (state, value) => state.product.real_mts = value;

export const CHECK_STOCK_SET_MTS_TO_INVOICED = (state, value) => state.product.mts_to_invoiced = value;

export const CHECK_STOCK_ADD_DISPONIBLE_STOCK = (state, value) => state.disponibleStock.push(value);

export const CHECK_STOCK_CLEAN_DISPONIBLE_STOCK = (state, value) => state.disponibleStock = [];

export const CHECK_STOCK_ADD_UPDATED_STOCK = (state, payload) => {

    state.disponibleStock[payload.index] = payload.stock;
}