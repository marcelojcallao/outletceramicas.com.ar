import state from "./State";

const getters = {

    AttributesOfProductGetter(state){
        return state.product.attributes;
    },

    AttributeByNameGetter(state){
        return (index) => {
            return state.product.attributes[index].value;
        }
    },

    ProductGetter(state){
        return state.product;
    },

    SupplierGetter(state)
    {
        return state.product.supplier;
    },

    PriceProductGetter(state)
    {
        //price es un array
        console.log(state.product.price);

        return state.product.price;
    },

    ProductPriceBaseGetter(state){
        return state.product.price_base;
    },

    EnabledPriceListToProduct(state){
        return (index) => {
            return state.product.price[index].enabledPriceListToProduct;
        }
    },

	ProductApplyMethodPayDiscount(state){
		return state.product.apply_discount_pay_method;
	},

	ClonePriceListsGetter(state){
		return state.clonePriceLists;
	}

}

export default getters;
