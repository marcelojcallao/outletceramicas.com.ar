export default {

    methods : {

        async getPublications(page = 1){

            this.$store.dispatch('loading_spinner', true);

            const payload = {
                page : page,
                by_category : this.CategoryFilterGetter,
                sort : this.SortProductsGetter,
                product_name : this.ProductNameGetter
            }

            const { data } = await this.$store.dispatch('getPublications', payload)
                .catch((err) => {
                    console.log('err.response')
                    console.log(err)
                    console.log('err.response')
                })
                .finally(() => this.$store.dispatch('loading_spinner', false));

            if ( data ) {
                this.$store.commit('SET_PUBLICATIONS_WEB_WITH_PAGINATION', data.data);

                this.$store.commit('SET_PAGINATION', data.pagination)
            }
           
        },
    },
}