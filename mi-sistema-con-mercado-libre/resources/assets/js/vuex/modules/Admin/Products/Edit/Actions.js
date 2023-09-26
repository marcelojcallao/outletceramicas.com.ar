const actions = {

    async find_product_by_id(context, payload)
    {
        try {
            window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + payload.token;

            let product = await axios.post('/api/products/find_by_id', {
                product_id : payload.product_id,
            })

            return product;

        } catch (e) {
            throw e;
        }
    },

    async update_product(context, payload)
    {
        try {
            window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + payload.token;

            let product = await axios.post('/api/products/update', {
                product : payload.product,
                category_path : payload.category_path,
                categories_path : payload.categories_path,
                selected_categories_from_root : payload.selected_categories_from_root
            })

            return product;

        } catch (e) {
            throw e;
        }
    },

    async delete_picture(context, payload)
    {
        try {
            window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + payload.token;

            let picture = await axios.post('/api/products/delete_picture', {
                picture_id : payload.picture_id,
            })

            return picture;

        } catch (e) {
            throw e;
        }
    },

    async add_stock(_, payload)
    {
        try {

            const product = await axios.post('/api/products/add_stock', {
                product_id : payload.product_id,
                stock : payload.stock,
            })

            return product;

        } catch (e) {
            throw e;
        }
    },

}

export default actions;