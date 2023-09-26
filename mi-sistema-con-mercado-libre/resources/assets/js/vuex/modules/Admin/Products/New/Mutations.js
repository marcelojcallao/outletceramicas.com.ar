import collect from 'collect.js'
const mutations = {

    NEW_PRODUCT_SET_PARENT_ID(state, value)
    {
        state.product.parent_id = value;
    },

    NEW_PRODUCT_SET_PROVIDER_ID(state, value)
    {
        state.product.provider_id = value;
    },

    NEW_PRODUCT_SET_ATTRIBUTES(state, value)
    {
        state.product.attributes = value;
    },

    NEW_PRODUCT_SET_NAME(state, value)
    {
        state.product.name = value;
    },

    NEW_PRODUCT_SET_ATTRIBUTE(state, payload)
    {
        state.product.attributes[payload.index].value = payload.value;
    },

    NEW_PRODUCT_SET_CODE(state, value)
    {
        state.product.code = value;
    },

    NEW_PRODUCT_SET_PRODUCT_CODE_ON_SUPPLIER(state, value)
    {
        state.product.code_on_supplier = value;
    },

    NEW_PRODUCT_SET_PRODUCT_NAME_ON_SUPPLIER(state, value)
    {
        state.product.name_on_supplier = value;
    },

    NEW_PRODUCT_SET_SUPPLIER_DATA(state, value)
    {
        state.product.supplier = value;
    },

    NEW_PRODUCT_SET_CATEGORY_ID(state, value)
    {
        state.product.category_id = value.id;
        state.category_id_history.push(value.id);
    },

    NEW_PRODUCT_DELETE_PARENT_CATEGORY(state)
    {
        state.product.category_id = null;
        state.category_id_history = [];
    },

    NEW_PRODUCT_DELETE_CATEGORY_ID(state, category_id)
    {
        state.product.category_id = null;
    },

    NEW_PRODUCT_SET_DESCRIPTION(state, value)
    {
        state.product.description = value;
    },

    NEW_PRODUCT_ADD_PRICE_LIST(state, value){
        state.product.price = null;
        state.product.price = value;
    },
    SET_CLONE_PRICE_LISTS(state, value){
        state.clonePriceLists = value;
    },

    //costo
    NEW_PRODUCT_SET_PRICE(state, payload)
    {
        //state.product.price[payload.index].price_list_id = payload.price_list_id;

        state.product.price[payload.index].price = parseFloat(payload.price);

        const importe = parseFloat(payload.price) + parseFloat(payload.price) * parseFloat(state.product.price[payload.index].benefit) / 100;
        console.log("🚀 ~ file: Mutations.js:84 ~ importe:", importe.toFixed(2))

		state.product.price[payload.index].import = importe.toFixed(2)
    },

    NEW_PRODUCT_SET_PRICE_BASE(state, value){
        state.product.price_base = value;
    },

    NEW_PRODUCT_SET_PRICE_BY_BENEFIT(state, payload)
    {
        /* state.product.price.forEach(element => {
            if(element.price_list_id == payload.price_list_id)
            {
                element.benefit = parseFloat(payload.benefit);
                element.import = (parseFloat(element.price) <= 0) ? 0 : parseFloat(element.price) + (parseFloat(element.price) * element.benefit / 100);
            }

        }); */
        state.product.price[payload.index].benefit = parseFloat(payload.benefit);
        const price = parseFloat(state.product.price[payload.index].price).toFixed(2);
        state.product.price[payload.index].benefit = parseFloat(payload.benefit);
        state.product.price[payload.index].import = (parseFloat(state.product.price[payload.index].price) <= 0) ? 0 : parseFloat(state.product.price[payload.index].price) + (parseFloat(state.product.price[payload.index].price) * state.product.price[payload.index].benefit / 100);

    },

    NEW_PRODUCT_SET_ENABLE_PRICE_LIST(state, payload){
        state.product.price[payload.index].enabledPriceListToProduct = payload.enabledPriceListToProduct;
    },

    NEW_PRODUCT_SET_INITIAL_STATUS(state){
        state.product.category_id_history = [],
        state.product.id = null,
        state.product.category_id = null,
        state.product.name = null,
        state.product.name_on_supplier = null,
        state.product.code = null,
        state.product.code_on_supplier = null,
        state.product.supplier = null,
        state.product.attributes = [],
        state.product.description = '',
        state.product.price = [];
        state.product.stock = 0;
        state.product.critical_stock = 10;
        state.product.images = [];
        state.product.mts_by_unity = null;
        state.product.publish_on_web = false;
        state.product.see_price_on_web = false;
        state.product.isCHP = false
    },

    NEW_PRODUCT_SET_STOCK(state, value)
    {
        state.product.stock = value;
    },

    NEW_PRODUCT_SET_IS_CHP(state, value)
    {
        state.product.isCHP = value;
    },

    NEW_PRODUCT_SET_CRITICAL_STOCK(state, value)
    {
        state.product.critical_stock = value;
    },

    NEW_PRODUCT_SET_MTS_BY_UNITY(state, value)
    {
        state.product.mts_by_unity = value;
    },

    NEW_PRODUCT_SET_SEE_PRICE_ON_WEB_STORE(state, value) {
        state.product.see_price_on_web = value;
    },

    NEW_PRODUCT_SET_PUBLISH_ON_WEB_STORE(state, value) {
        state.product.publish_on_web = value;
    },

    EDIT_PRODUCT_SET_PRICE(state, payload)
    {
        state.product.price = payload
        /* payload.forEach((element, index) => {

            if(element){
                if (element.price_list_id == state.product.price[index].price_list_id) {
                    state.product.price[index].price = element.price;
                    state.product.price[index].import = element.import;
                    state.product.price[index].benefit = element.benefit;
                }
            }

        }); */

        /* const pp = collect(payload).map(p => {
            console.log('p');
            console.log(p);
            console.log('p');
            if(p.hasOwnProperty('pivot') ){
                console.log('entro al if');
                return {
                    price_list_id : p.id,
                    name : p.name,
                    price : p.pivot.costo,
                    benefit : p.pivot.benefit,
                    import : p.pivot.price,
                }
            }else{
                return {
                    price_list_id : p.id,
                    name : p.name,
                    price : 0,
                    benefit : 0,
                    import : 0
                }
            }
        });
        state.product.price = pp.toArray();
        console.log('pp');
        console.log(pp.toArray());
        console.log('pp'); */
    },

    EDIT_PRODUCT_SET_ID(state, value)
    {
        state.product.id = value;
    },

    EDIT_PRODUCT_SET_IMAGES(state, images)
    {
        state.product.images = images;
    },
    EDIT_PRODUCT_SET_IS_EDITION_PRODUCT(state, value)
    {
        state.product.is_edition_product = value;
    },

    PRODUCT_SET_APPLY_DISCOUNT(state, value){
        state.product.apply_discount = value;
    },

    PRODUCT_SET_APPLY_DISCOUNT_FROM(state, value){
        state.product.apply_discount_from = value;
    },

	PRODUCT_SET_APPLY_DISCOUNT_PERCENTAGE(state, value){
        state.product.apply_discount_percentage = value;
    },

    PRODUCT_SET_APPLY_DISCOUNT_PAY_METHOD(state, value){
        state.product.apply_discount_pay_method = value;
    }

}

export default mutations;
