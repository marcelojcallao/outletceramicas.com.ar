const actions = {

    async store_product(context, payload)
    {
        try {
            window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + payload.token;

            let product = await axios.post('/api/products/store', {
                product : payload.product,
                categories_path : payload.categories_path,
                category_path : payload.category_path,
                selected_categories_from_root : payload.selected_categories_from_root
            })

            return product;

        } catch (e) {
            
            throw e;
        }
    },

    updateSupplierData(context, value)
    {
        context.commit('NEW_PRODUCT_SET_SUPPLIER_DATA', value);
    },

    newProductSetPriceAction(context, payload)
    {
        context.commit('NEW_PRODUCT_SET_PRICE', payload);
    },

    productSetApplyDiscount(context, value)
    {
        context.commit('PRODUCT_SET_APPLY_DISCOUNT', value);
    },

    productSetApplyDiscountFrom(context, value)
    {
        context.commit('PRODUCT_SET_APPLY_DISCOUNT_FROM', value);
    },

    productSetApplyDiscountPercentage(context, value)
    {
        context.commit('PRODUCT_SET_APPLY_DISCOUNT_PERCENTAGE', value);
    }
}

export default actions;