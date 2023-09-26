const actions = {

        async fetchParentCategories(context, token){
            try {
                window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                
                const response = await axios.post('/api/categories/parent')

                return response;

            } catch (e) {
                throw e;
            
            }
        },

        async fetchChildCategories(context, payload){
            try {
                window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + payload.token;
                
                const response = await axios.post('/api/categories/child', {
                    category_id : payload.category_id
                }); 

                return response;

            } catch (e) {
                throw e;
            
            }
        },

        async category_store(_, payload)
        {
            try {
                const category = await axios.post('/api/categories/store', {
                    name : payload.name,
                    code : payload.code,
                    parent_id : payload.parent_id,
                    attributes : payload.attributes,
                    category_path : payload.category_path
                })

                return category;

            } catch (e) {
                throw e;
            }
        },

        newCategorySetName(context, name)
        {
            context.commit('NEW_CATEGORY_SET_NAME', name);
        },

        updateCategoryValue(context, category)
        {
            if (category && category.id) {
                context.commit('ADD_CATEGORY_TO_SELECTED_CATEGORIES_FROM_ROOT', category);
                if (parseInt(category.parent_id) > 0) {
                    context.commit('NEW_CATEGORY_SET_PARENT_ID', category);
                }
            }else{
                context.commit('SET_CATEGORY_INITIAL_STATUS');
            }
        },

        async setCategoryStatus(_, payload)
        {
            try {
                const category = await axios.post('/api/categories/set_status', {
                    category_id : payload.category_id,
                    code : payload.code,
                    status : payload.status,
                })

                return category;

            } catch (e) {
                throw e;
            }
        },

        async fetchCategories(_) //Tienda - categorias habilitadas
        {
            try {

                const categories = await axios.post('/get_categories');

                return categories;

            } catch (e) {
                throw e;
            }
        },

        async fetchAllCategories(_) // todas las categorias no importa el estado
        {
            try {

                const categories = await axios.post('/get_all_categories');

                return categories;

            } catch (e) {
                throw e;
            }
        },

        async updateCategoryName (_, payload) {

            try {
                const category = await axios.post('/api/categories/update_name',{
                    category_id : payload.id,
                    category_name : payload.name
                });

                return category;

            } catch (error) {
                throw e;
            }
        }

}

export default actions;