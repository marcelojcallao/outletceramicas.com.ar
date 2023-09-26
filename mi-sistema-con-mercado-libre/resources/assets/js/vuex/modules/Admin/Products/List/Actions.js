export const getProductsList = async(context, payload) => {

    try{

        const response = await axios.post('/api/products/index?page=' + payload.page);

        return response;

    } catch (e) {
        throw e;
    }
}

export const loadData = async (context, payload) => {

    try{

        const response = await context.dispatch('getProductsList', payload);

        if (response) {
            //context.commit('ADD_PRODUCTS_TO_LIST_PRODUCTS', products.data);
            return response;
        }

    } catch(e){

    }
}

export const searchProductAction = async(_, query) => {

    try {

        const response = await axios.post('/api/products/find_by_name',{
            product_name : query
        });

        if (response) {
            return response;
        }

    } catch (error) {
        throw error;
    }
}
export const deleteProduct = async(_, product_id) => {

    try {

        const response = await axios.post('/api/products/delete_product',{product_id});

        if (response) {
            return response;
        }

    } catch (error) {
        throw error;
    }
}
export const critical_stock = async(_) => {

    try {

        const response = await axios.post('/api/products/critical_stock');

        if (response) {
            return response;
        }

    } catch (error) {
        throw error;
    }
}
