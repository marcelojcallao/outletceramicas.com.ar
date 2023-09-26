import currency from '../../../../../mixins/currency';
import SheetMetal from '../../../../../src/SheetMetal/SheetMetal';

export const SET_PEDIDO_LIST_CHILD_ROW_REACTIVITY_DATA = (state, data) => {
    state.data = data;
}
export const SET_PEDIDO_LIST_CHILD_ROW_REACTIVITY_DATA_ADD_COMMENTS = (state, comments) => {
    state.data.comments = comments;
}

export const SET_WHO_PREPARED = (state, value) => {
    state.whoPrepared = value;
}

export const SET_WHO_PREPARED_SPINNER = (state, value) => {
    state.whoPreparedSpinner = value;
}

export const SET_OPEN_WHO_PREPARED_INPUT = (state, value) => {
    state.openWhoPreparedInput = value;
}

export const SET_WHO_DISPATCH = (state, value) => {
    state.whoDispatch = value;
}

export const SET_WHO_DISPATCH_SPINNER = (state, value) => {
    state.whoDispatchSpinner = value;
}

export const SET_OPEN_WHO_DISPATCH_INPUT = (state, value) => {
    state.openWhoDispatchInput = value;
}
export const SET_OPEN_CHANGE_INVOICE_DATE = (state, value) => {
    state.openChangeInvoiceDate = value;
}

export const SET_STATUS_ID_AT_PEDIDO_DATA = (state, value) => state.data.status_id = value;

export const SET_CUSTOMER_ADDRESS_AT_PEDIDO_DATA = (state, value) => state.data.customer_address = value;

export const SET_DELIVERY_ADDRESS_AT_PEDIDO_DATA = (state, value) => state.data.delivery_addresses = value;

export const REMOVE_PRODUCT_FROM_PEDIDO_CLIENTE = (state, data) => {
    const { product_id } = data;
    state.data.items.map((item, index) => {

        if (item.product_id == product_id) {
            state.data.items.splice(index, 1)
        }
    })
}

export const SET_QUANTITY_FROM_PRODUCT_STOCK = (state, data) => {
    //data es un array
    for (const key in data) {
        state.data.items.forEach(item => {
            if (item.product_id == data[key].produc_id) {
                const { stock } = data[key];
                item.quantity = stock;
                item.neto_import = ((parseFloat(stock) * parseFloat(item.unit_price)) - item.discount_import);
                item.total_Presupuesto = item.neto_import;
                let iva = '';
                if (item.iva_id == 6) {
                    iva = 21
                }
                if (item.iva_id == 5) {
                    iva = 10.5
                }
                item.iva_import = (item.neto_import * iva / 100);
                item.total_invoiceA = item.neto_import + item.iva_import;
                item.total_invoiceB = item.total_invoiceA;
                item.total_raw = item.total_invoiceA;
                item.total = '$ ' + currency.methods.CurrencyFormat(item.total_invoiceA)
                item.total_raw = currency.methods.CurrencyFormat(item.total_invoiceA)
            }
        });
    }
}

export const ORDER_UPDATE_QUANTITY = ( state, { index, value } ) => {

    const item = state.data.items[index];

    const neto_import = (parseFloat(item.unit_price) * parseFloat(value))  - item.discount_import

    state.data.items[index].quantity = value;

    state.data.items[index].neto_import = neto_import;

    let iva = '';
    if (item.iva_id == 6) {
        iva = 21
    }
    if (item.iva_id == 5) {
        iva = 10.5
    }

    state.data.items[index].iva_import = (neto_import * iva / 100);

    state.data.items[index].total_raw = item.neto_import + item.iva_import;
}

export const ORDER_UPDATE_ADD_PRODUCT = (state) => {

}

export const ORDER_UPDATE_SET_PRODUCT = (state, value) => {

    if (value === null) {
        state.newProduct.product_id = null
        state.newProduct.product_name = null
        state.newProduct.stock = null;
        state.newProduct.critical_stock = null;
        state.newProduct.isCHP = null;
        state.newProduct.mts_by_unity = null;
        state.newProduct.code = null;
        state.newProduct.sheet_metal_cuttings = null;

    }else{
        state.newProduct.product_id = value.id;
        state.newProduct.product_name = value.name;
        state.newProduct.stock = value.stock;
        state.newProduct.critical_stock = value.critical_stock;
        state.newProduct.isCHP = value.isCHP;
        state.newProduct.mts_by_unity = value.mts_by_unity;
        state.newProduct.code = value.code;
        state.newProduct.sheet_metal_cuttings = value.sheet_metal_cuttings;

    }
}

export const ORDER_UPDATE_SET_PRICE_LISTS = (state, value) => state.newProduct.price_lists = value;

export const ORDER_UPDATE_SET_PRICE_LIST = (state, value) => {
    if (value == null) {
        state.newProduct.price_list = null;
        state.newProduct.pricelist_id = null;
    }else{

        state.newProduct.price_list = value;
        state.newProduct.pricelist_id = value.id;
    }
}

export const ORDER_UPDATE_SET_UNIT_PRICE_PRODUCT = (state, value) => {

    state.newProduct.unit_price = value;
    state.newProduct.neto_import = ((parseFloat(state.newProduct.unit_price) * parseFloat(state.newProduct.quantity)) - parseFloat(state.newProduct.discount)).toFixed(2);
    state.newProduct.iva_import = (state.newProduct.neto_import * parseFloat(state.newProduct.iva.percentage) / 100).toFixed(2);
    state.newProduct.total_raw = (parseFloat(state.newProduct.neto_import) + parseFloat(state.newProduct.iva_import)).toFixed(2);
}

export const ORDER_UPDATE_SET_QUANTITY_PRODUCT = (state, value) => {

    const quantity = parseInt(value);

    state.newProduct.quantity = quantity;

    if (state.newProduct.isCHP) {

        const rounded_mts =  parseFloat(SheetMetal.roundedMts(state.newProduct.mts_by_unity));

        state.newProduct.rounded_mts = rounded_mts;

        const sheet_metal = parseFloat(state.newProduct.mts_by_unity) + parseFloat(rounded_mts);

        const real_mts = parseFloat(state.newProduct.mts_by_unity) * parseInt(quantity);

        state.newProduct.real_mts = real_mts;

        const total_rounded_mts = rounded_mts * quantity;

        state.newProduct.total_rounded_mts = total_rounded_mts;

        const mts_to_invoiced = sheet_metal * quantity;

        state.newProduct.mts_to_invoiced = mts_to_invoiced;

        state.newProduct.neto_import = (( parseFloat(state.newProduct.unit_price) * parseFloat(state.newProduct.mts_to_invoiced) ) - parseFloat(state.newProduct.discount)).toFixed(2);

    }else{

        state.newProduct.neto_import = (( parseFloat(state.newProduct.unit_price) * parseFloat(state.newProduct.quantity) ) - parseFloat(state.newProduct.discount)).toFixed(2);
    }
    state.newProduct.iva_import = (state.newProduct.neto_import * parseFloat(state.newProduct.iva.percentage) / 100).toFixed(2);
    state.newProduct.total_raw = (parseFloat(state.newProduct.neto_import) + parseFloat(state.newProduct.iva_import)).toFixed(2);
}

export const ORDER_UPDATE_SET_IVA_PRODUCT = (state, value) => {
    state.newProduct.iva = value;
    state.newProduct.neto_import = ((parseFloat(state.newProduct.unit_price) * parseFloat(state.newProduct.quantity)) - parseFloat(state.newProduct.discount)).toFixed(2);
    state.newProduct.iva_import = (state.newProduct.neto_import * parseFloat(state.newProduct.iva.percentage) / 100).toFixed(2);
    state.newProduct.total_raw = (parseFloat(state.newProduct.neto_import) + parseFloat(state.newProduct.iva_import)).toFixed(2);
}

export const ORDER_UPDATE_SET_MTS_BY_UNITY_PRODUCT = (state, value) => {

    const mts_by_unity = parseFloat(value);

    state.newProduct.mts_by_unity = mts_by_unity;

    const rounded_mts =  parseFloat(SheetMetal.roundedMts(mts_by_unity));

    state.newProduct.rounded_mts = rounded_mts;

    const sheet_metal = parseFloat(state.newProduct.mts_by_unity) + parseFloat(rounded_mts);

    const real_mts = parseFloat(mts_by_unity) * parseInt(state.newProduct.quantity);

    state.newProduct.real_mts = real_mts;

    const total_rounded_mts = rounded_mts * state.newProduct.quantity;

    state.newProduct.total_rounded_mts = total_rounded_mts;

    const mts_to_invoiced = sheet_metal * state.newProduct.quantity;

    state.newProduct.mts_to_invoiced = mts_to_invoiced;

    state.newProduct.neto_import = ((parseFloat(state.newProduct.unit_price) * parseFloat(state.newProduct.mts_to_invoiced)) - parseFloat(state.newProduct.discount)).toFixed(2);

    state.newProduct.iva_import = (state.newProduct.neto_import * parseFloat(state.newProduct.iva.percentage) / 100).toFixed(2);
    state.newProduct.total_raw = (parseFloat(state.newProduct.neto_import) + parseFloat(state.newProduct.iva_import)).toFixed(2);
}

export const ORDER_UPDATE_SET_REAL_MTS = (state, value) => state.newProduct.real_mts = value;

export const ORDER_UPDATE_SET_ROUNDED_MTS = (state, value) => state.newProduct.rounded_mts = value;

export const ORDER_UPDATE_SET_MTS_TO_INVOICED = (state, value) => state.newProduct.mts_to_invoiced = value;

export const ORDER_UPDATE_SET_INITIAL_STATUS = (state) => {
    state.newProduct = {
        code: null,
        product: null,
        product_name: null,
        unit_price: 0,
        quantity: 1,
        iva:
        {
            id: 6,
            code: "5",
            name: "21%",
            percentage: "21"
        },
        isCHP: false,
        sheet_metal_cuttings: false,
        rounded_mts: 0,
        real_mts: 0,
        mts_to_invoiced: 0,
        mts_by_unity: 0,
        iva_import: 0,
        neto_import: 0,
        stock: 0,
        critical_stock: 0,
        discount: 0,
        total_raw: 0,
        price_lists: [],
        price_list: null
    }
}



