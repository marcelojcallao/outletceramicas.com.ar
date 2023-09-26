export default {

    watch: {
        SelectedCategoriesFromRootGetter: {
            handler(categories){
                categories.forEach(category => this.$store.commit('NEW_CATEGORY_SET_CATEGORY_PATH', category.code));
            },
            deep: true
        }
      }
}