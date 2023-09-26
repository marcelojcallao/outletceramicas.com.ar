const actions = {

    async categories_list(context, token){
        try {
            
            const categories = await axios.post('/api/categories/index'); 

            return categories;

        } catch (e) {
            throw e;
        }
    },

    async set_categories_list({commit}, categories) {

        commit('SET_CATEGORY_LIST', categories)
    }
}

export default actions;