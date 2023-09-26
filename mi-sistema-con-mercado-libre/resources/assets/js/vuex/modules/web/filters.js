import collect from 'collect.js'
export default {
    state: {
        filters: {
            category: null,
        },
        pagination: {
            current_page: 0,
            from: 0,
            last_page: 0,
            per_page: 0,
            to: 0,
            total: 0,
        },
        sort : null,
        product_name : null
    },

    actions: {

        async getPublications(context, payload) {

            try {

                const url = '/get_products';

                const response = await axios.get(url,{
                    params : {
                        page : payload.page,
                        by_category : payload.by_category,
                        sort : payload.sort,
                        product_name : payload.product_name
                    }
                })
    
                return response;
    
            } catch (e) {
                throw e;
            }
        },

    },

    mutations: {

        SET_CATEGORY_FILTER(state, value){
            state.filters.category = value;
        },

        SET_SORT_FILTER(state, value){
            state.sort = value;
        },

        SET_PRODUCT_SORT(state, value){
            state.sort = value;
        },
        SET_PRODUCT_NAME(state, value){
            state.product_name = value;
        },

        SET_PAGINATION(state, pagination) {
            state.pagination.current_page = pagination.current_page,
            state.pagination.from = pagination.from,
            state.pagination.last_page = pagination.last_page,
            state.pagination.per_page = pagination.per_page,
            state.pagination.to = pagination.to,
            state.pagination.total = pagination.total
        },

        CLEAR_CATEGORY_FILTER(state) {

            state.filters.category = null;

        },

        FORCE_INIT_PAGINATION(state) {

            state.pagination.current_page = 0;
            state.pagination.from = 0;
            state.pagination.last_page = 0;
            state.pagination.per_page = 0;
            state.pagination.to = 0;
            state.pagination.total = 0;
        },

    },

    getters: {

        Pagination(state) {
            return state.pagination;
        },

        CategoryFilterGetter(state){
            return state.filters.category;
        },

        SortProductsGetter(state){
            return state.sort;
        },

        ProductNameGetter(state){
            return state.product_name;
        }

    },

}