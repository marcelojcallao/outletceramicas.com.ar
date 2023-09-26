const getters = {

    CategoriesList(state){
        return state.categories_list;
    },

    OriginalCategoriesListGetter(state){
        return state.original_categories_list;
    }
}

export default getters;
