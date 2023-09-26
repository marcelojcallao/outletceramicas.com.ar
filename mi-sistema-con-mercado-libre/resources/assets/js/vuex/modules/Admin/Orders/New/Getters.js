import collect from 'collect.js';

export const CustomerValue = (state) => state.order.customer;

export const DateOrderGetter = (state) => state.order.date;

export const DeliveryDateOrderGetter = (state) => state.order.delivery_date;

export const ProductFromNewOrder = (state) => state.order.products;

export const NewOrderAtLeastIHaveOneProduct = (state) => state.order.products.length;

export const NewOrderUnitPriceProductGetter = (state) => {
    return (index) => {
        return state.order.products[index].unit_price;
    }
}

// Se utiliza en multiselect
export const NewOrderProductValue = (state) => {
    return (index) => {
        return state.order.products[index].product;
    }
}

export const NewOrderQuantityProductGetter = (state) => {
    return (index) => {
        return parseFloat(state.order.products[index].quantity);
    }
}

export const NewOrderDiscountProductGetter = (state) => {
    return (index) => {
        return state.order.products[index].descuento;
    }
}

export const NewOrderMtsByUnityGetter = (state) => {
    return (index) => {
        if (state.order.products.length > 0 && state.order.products[index].product != null) {
            return state.order.products[index].mts_by_unity;
        }
        return null;
    }
}

export const NewOrderTotalProductGetter = (state) => {
    return (index) => {
        return state.order.products[index].total;
    }
}

export const NewOrderNetoByProductGetter = (state) => {
    return (index) => {
        return state.order.products[index].neto;
    }
}

export const NewOrderPriceListsProductGetter = (state) => {
    return (index) => {
        return state.order.products[index].price_lists;
    }
}

export const NewOrderPriceListGetter = (state) => {
    return (index) => {
        return state.order.products[index].price_list;
    }
}

export const NewOrderIvaOfProduct = (state) => {
    return (index) => {
        return state.order.products[index].iva;
    }
}

export const NewOrderDataGetter = (state) => {
    return state.order;
}

export const NewOrderIvaImportGetter = (state) => {
    return (index) => {
        return state.order.products[index].iva_import;
    }
}

export const NewOrderTotalsNeto = (state) => {
    return state.order.neto;
}

export const NewOrderTotalsIvaImportGetter = (state) => {
    return state.order.iva_import;
}

export const NewOrderTotalsDiscountGetter = (state) => {
    return state.order.discount;
}

export const NewOrderTotalsTotalGetter = (state) => {
    return state.order.products.reduce( (acc, current_product) =>{
		return acc + current_product.neto - current_product.descuento;
	}, 0);
}

export const NewOrderPaymentTypeGetter = (state) => {
    return state.order.payment;
}

export const NewOrderShippingGetter = (state) => {
    return state.order.shipping;
}

export const NewOrderStatusGetter = (state) => {
    return state.order.status;
}

export const NewOrderProductStockOnCurrentOrder = (state) => {
    const products = collect(state.order.products) ;
    let total = 0;
    products.map((product) => {
        if (state.current_product.id == product.id) {
            total + parseInt(product.quantity);
        }
    });

    return total;
}

export const NewOrderMultiselectProducts = (state) => {
    return state.multiselect_products;
}

export const OrderErrors = (state) => state.errors;

export const EnabledAddProductButton = (state) => state.enabledAddProductButton;

export const NewOrderGetTypeGetter = (state) => state.order.type;

export const NewOrderCurrentProduct = (state) => state.current_product;

export const OrderIsNew = (state) => state.order.isNew;

export const OrderGetter = (state) => state.order;
