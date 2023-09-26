const mutations = {

    SET_CATEGORY_LIST(state, value)
    {
        state.categories_list = value;
    },

    SET_ORIGINAL_CATEGORIES_LIST (state, value) {
        state.original_categories_list = value
    }

}

export default mutations;