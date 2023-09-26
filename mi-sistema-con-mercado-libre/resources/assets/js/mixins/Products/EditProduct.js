export default {

    methods : {

        async update_product()
        {
            this.errors = null;
            
            const payload = {
                token : this.User.token,
                product : this.ProductGetter,
                category_path : this.NewCategory.category_path, //se añade al codigo del producto
                categories_path : this.ChildCategories,
                selected_categories_from_root : this.SelectedCategoriesFromRootGetter,
            }

            const product = await this.$store.dispatch('update_product', payload)
            .catch(e => {
                this.error_message('Se ha producido un error, verifique.', 'Ingreso de productos', 4000);
                this.errors = e.response.data.errors;
                this.loading = false;
                Vue.set(this.data, loading, false);
            });

            if (product) {
                return product;
            }
        },

    }
}