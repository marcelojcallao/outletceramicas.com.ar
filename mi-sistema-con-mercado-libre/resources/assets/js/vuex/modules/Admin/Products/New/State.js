const state = {
    category_id_history : [],
    product : {
        id : null,
        category_id : null,
        name : null,
        name_on_supplier : null,
        code : null,
        code_on_supplier : null,
        supplier : null,
        attributes : [],
        description : '',
        stock : 0,
        critical_stock : 10,
        price : [],
        price_base : 0,
        mts_by_unity : null,
        publish_on_web : false,
        see_price_on_web : false,
        isCHP : false, //QUE SE VENDE POR METROS,
        is_edition_product : false,
        apply_discount: false,
        apply_discount_from: 0,
        apply_discount_percentage: 0,
		apply_discount_pay_method: [],
    },
    images : [],
	clonePriceLists: null
}

export default state;
