export const NEW_ORDER_SET_CUSTOMER = (state, value) => {
    state.order.customer = value;
}

export const NEW_ORDER_SET_DATE = (state, value) => {
    state.order.date = value;
}

export const NEW_ORDER_SET_DELIVERY_DATE = (state, value) => {
    state.order.delivery_date = value;
}

export const NEW_ORDER_ADD_NEW_ROW_PRODUCT = (state) => {
    state.order.products.push(
        {
            product : null,
            unit_price : 0,
            quantity : 0,
            iva : {
                id: 6,
                code: "5",
                name: "21%",
                percentage: "21",
            },
            isCHP : false,
            rounded_mts : 0,
            real_mts : 0,
            mts_to_invoiced : 0,
            mts_by_unity : 0,
            iva_import : 0,
            neto : 0,
            discount : 0,
            total : 0,
            price_lists : [],
            price_list : null,
            descuento: 0
        }
    );
}

export const NEW_ORDER_SET_UNIT_PRICE_PRODUCT = (state, payload) => {
    state.order.products[payload.index].unit_price = payload.value;

}

export const NEW_ORDER_SET_MTS_BY_UNITY = (state, payload) => {
    state.order.products[payload.index].mts_by_unity = payload.value;
}

export const NEW_ORDER_SET_QUANTITY_PRODUCT = (state, payload) => {
    state.order.products[payload.index].quantity = payload.value;
}

export const NEW_ORDER_SET_DISCOUNT_PRODUCT = (state, payload) => {
    state.order.products[payload.index].descuento = payload.value;
}
export const NEW_ORDER_REMOVE_PRICE_LIST = (state, index) => {
    state.order.products[index].unit_price = 0;
    state.order.products[index].quantity = 1;
    state.order.products[index].mts_by_unity = 0;
    state.order.products[index].mts_to_invoiced = 0;
    state.order.products[index].neto = 0;
    state.order.products[index].iva_import = 0;
    state.order.products[index].discount = 0;
    state.order.products[index].total = 0;
    state.order.products[index].descuento = 0;
}

export const NEW_ORDER_SET_PRODUCT = (state, payload) => {
    state.order.products[payload.index].price_list = [];
    state.order.products[payload.index].unit_price = 0;
    state.order.products[payload.index].quantity = 1;
    state.order.products[payload.index].mts_by_unity = 0;
    state.order.products[payload.index].mts_to_invoiced = 0;
    state.order.products[payload.index].neto = 0;
    state.order.products[payload.index].iva_import = 0;
    state.order.products[payload.index].discount = 0;
    state.order.products[payload.index].total = 0;
    state.order.products[payload.index].descuento = 0;
    state.order.products[payload.index].product = payload.value;
}

export const NEW_ORDER_SET_PRICE_LISTS_OF_A_PRODUCT = (state, payload) => {
    state.order.products[payload.index].price_lists = payload.value;
}

export const NEW_ORDER_SET_PRICE_LIST = (state, payload) => {
    state.order.products[payload.index].price_list = payload.value;
}

export const NEW_ORDER_SET_PRICE_PRODUCT = (state, payload) => {

    state.order.products[payload.index].unit_price = payload.value;

    const product = state.order.products[payload.index];

    if (product.isCHP) {
        product.neto = (parseFloat(product.unit_price) * parseFloat(product.mts_to_invoiced))  ;
    }else{
        product.neto = (parseFloat(product.unit_price) * parseFloat(product.quantity)) ;
    }

    product.iva_import = (product.neto - parseFloat(product.descuento)) * parseFloat(product.iva.percentage) / 100;

    product.total = parseFloat(product.neto) - parseFloat(product.descuento) + parseFloat(product.iva_import);
}

export const NEW_ORDER_SET_IVA_OF_PRODUCT = (state, payload) => {
    state.order.products[payload.index].iva = payload.value;
}

export const NEW_ORDER_SET_ISCHP = (state, payload) => {
    state.order.products[payload.index].isCHP = payload.product.isCHP;
    state.order.products[payload.index].mts_by_unity = payload.product.mts_by_unity
}

export const NEW_ORDER_SET_IVA_IMPORT_OF_PRODUCT = (state, payload) => {
    state.order.products[payload.index].iva.id = payload.value.id;
    state.order.products[payload.index].iva.code = payload.value.code;
    state.order.products[payload.index].iva.name = payload.value.name;
    state.order.products[payload.index].iva.percentage = payload.value.percentage;

    let subtotal = parseFloat(state.order.products[payload.index].unit_price) * parseFloat(state.order.products[payload.index].quantity);

    state.order.products[payload.index].iva_import = subtotal * parseFloat(payload.value.percentage) / 100;
}

export const NEW_ORDER_SET_TOTALS_FROM_PRODUCT = (state, index) => {

    const product = state.order.products[index];

    if (product != undefined) {

        if (state.order.products[index].isCHP) {
            state.order.products[index].neto = (parseFloat(state.order.products[index].unit_price) * parseFloat(state.order.products[index].mts_to_invoiced));
        }else{
            state.order.products[index].neto = (parseFloat(state.order.products[index].unit_price) * parseFloat(state.order.products[index].quantity));
        }

        state.order.products[index].iva_import = ( state.order.products[index].neto - parseFloat(state.order.products[index].descuento) )  * parseFloat(state.order.products[index].iva.percentage) / 100;

        const total = parseFloat(state.order.products[index].neto) - parseFloat(state.order.products[index].descuento) + parseFloat(state.order.products[index].iva_import);

        state.order.products[index].total = total.toFixed(2);
    }
}

export const NEW_ORDER_SET_TOTAL_PRODUCT = (state, payload) => {
    state.order.products[payload.index].total = payload.value;
}

export const NEW_ORDER_SET_REAL_MTS_CHAPA = (state, payload) => {
    state.order.products[payload.index].real_mts = payload.real_mts;
}

export const NEW_ORDER_SET_ROUNDED_MTS_CHAPA = (state, payload) => {
    state.order.products[payload.index].rounded_mts = payload.rounded_mts;
}

export const NEW_ORDER_SET_MTS_TO_INVOICED_CHAPA = (state, payload) => {
    state.order.products[payload.index].mts_to_invoiced = payload.mts_to_invoiced;
}

export const NEW_ORDER_SET_NETO = (state, neto) => {
    state.order.neto = neto;
}

export const NEW_ORDER_SET_IVA_IMPORT = (state, iva_import) => {
    state.order.iva_import = iva_import;
}

export const NEW_ORDER_SET_DISCOUNT = (state, discount) => {
    state.order.discount = parseFloat(discount);
}

export const NEW_ORDER_SET_TOTAL = (state, total) => {
    state.order.total = total;
}

export const NEW_ORDER_DELETE_PRODUCT = (state, index) => {
    state.order.products.splice(index, 1);
}

export const NEW_ORDER_SET_PAYMENT_TYPE = (state, value) => {
    state.order.payment = value;
}

export const NEW_ORDER_SET_PAYMENT_ADITIONAL_VALUE = (state, value) => {
    state.order.payment.value = value;
}

export const NEW_ORDER_SET_SHIPPING_PERCENTAGE = (state, value) => {
    state.order.shipping.percentage = value;
}

export const NEW_ORDER_SET_SHIPPING_VALUE = (state, value) => {
    state.order.shipping.value = value;
}

export const NEW_ORDER_SET_STATUS = (state, status) => {
    state.order.status = status;
}

export const NEW_ORDER_SET_INITIAL_STATUS = (state) => {

    const order = {
        customer : {},
        date : new Date,
        delivery_date : new Date,
        flags : {
            hasState : true,
            hasCity : false,
            hasCp : false,
            hasStreet : false,
            hasNumber : false
        },
        address : {
            state : {
                id : 2,
                name : 'BUENOS AIRES'
            },
            city : {
                cp : null,
                name: null
            },
            street : null,
            number : null,
            between : null,
            obs : null
        },
        user_id : null,
        products : [
            {
                product : null,
                unit_price : 0,
                quantity : 0,
                iva : {
                    id: 6,
                    code: "5",
                    name: "21%",
                    percentage: "21",
                },
                isCHP : false,
                rounded_mts : 0,
                real_mts : 0,
                mts_to_invoiced : 0,
                mts_by_unity : 0,
                iva_import : 0,
                neto : 0,
                discount : 0,
                total : 0,
                price_lists : [],
                price_list : null,
                descuento: 0
            }
        ],
        payment : {
            id : null,
            name : null,
            percentage : 0,
            value : 0
        },
        required_shipping : false,
        shipping : {
            percentage : 0,
            value : 0
        },
        delivery_service : 0,
        iva_import : 0,
        neto : 0,
        discount : 0,
        total : 0,
        status : null,
        type : {
            id : 101,
            code : 'PD-',
            name : 'PEDIDO CLIENTE'
        }
    }

    state.order = order;
}

export const NEW_ORDER_ADDRESS_SET_STATE = (state, value) => {
    state.order.address.state = value;
    state.order.flags.hasState = true;
}

export const NEW_ORDER_ADDRESS_REMOVE_STATE = (state) => {

    state.order.address.state = {};

    for (const key in state.order.flags) {
        state.order.flags[key] = false;
    }
}

export const NEW_ORDER_ADDRESS_SET_CITY = (state, value) => {
    state.order.address.city.name = value.name;
    state.order.address.city.cp = value.cp;
    state.order.flags.hasCity = true;
    state.order.flags.hasCp = true;
}

export const NEW_ORDER_ADDRESS_REMOVE_CITY = (state) => {
    state.order.address.city = {};
}

export const NEW_ORDER_ADDRESS_SET_CP = (state, value) => {
    state.order.address.city.cp = value;
    state.order.flags.hasCp = true;
}

export const NEW_ORDER_ADDRESS_SET_STREET = (state, value) => {
    state.order.address.street = value;
    state.order.flags.hasStreet = true;
}

export const NEW_ORDER_ADDRESS_SET_NUMBER = (state, value) => {
    state.order.address.number = value;
    state.order.flags.hasNumber = true;
}

export const NEW_ORDER_SET_REQUIRED_SHIPPING = (state, value) => {
    state.order.required_shipping = value;
}

export const NEW_ORDER_ADDRESS_SET_OBS = (state, value) => {
    state.order.address.obs = value;
}
export const NEW_ORDER_ADDRESS_SET_BETWEEN_STREETS = (state, value) => {
    state.order.address.between = value;
}

export const NEW_ORDER_SET_CURRENT_PRODUCT = (state, value) => {
    state.current_product = value;
}

export const NEW_ORDER_ADDRESS_INITIAL_STATUS = (state) => {
    state.order.address = {
        state : {
            id : 2,
            name : 'BUENOS AIRES'
        },
        city : {
            cp : null
        },
        street : null,
        number : null,
        obs : null
    }

    state.order.flags = {
        hasState : true,
        hasCity : false,
        hasCp : false,
        hasStreet : false,
        hasNumber : false
    }
}

export const NEW_ORDER_SET_PRODUCTS_ON_MULTISELECT_PRODUCTS = (state, products) => {
    state.multiselect_products = products
}


export const SET_ORDER_ERRORS = (state, errors) => {
    state.errors = errors;
}

export const SET_ENABLED_ADD_PRODUCT_BUTTON = (state, value) => {
    (value === 'true')
        ? state.enabledAddProductButton = true
        : state.enabledAddProductButton = false;
}

export const NEW_ORDER_PEDIDO_CLIENTES_SET_TYPE = (state, value) => {
    state.order.type = value;
}

export const ORDER_IS_NEW = (state, value) => state.order.isNew = value;

export const NEW_ORDER_SET_STOCK_TO_THE_PRODUCT_IN_THE_FORM = (state, payload) => {

	const index = state.order.products.findIndex( product => product.id === payload.id);

	if (index) {
		state.order.products[index].stock = payload.stock;
	}
}




