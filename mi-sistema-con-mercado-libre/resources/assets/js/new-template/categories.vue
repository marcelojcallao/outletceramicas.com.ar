<template>
    <aside class="widget">
        <categor />
        <!-- <h3 class="widget-title">CATEGORIAS</h3>
        <ul class="list-group" v-for="category in categories" :key="category.id">
          <li class="list-group-item">
            <a href="#" @click.prevent="search_by_category(category)">{{category.name.toUpperCase()}}</a></li>
        </ul> -->
    </aside>
</template>

<script>
import categor from './../components/web/nav/categories.vue'
export default {

    data(){
        return {
            categories : []
        }
    },

    components : {categor},

    methods : {

        async search_by_category(category){
                
            /* this.$store.dispatch('loading_spinner', true);
            this.$store.commit('SET_CATEGORY_FILTER', category.code)
            const {data} = await this.$store.dispatch('get_products_for_web', category.code)
                .catch((err) => {
                })
                .finally(() => this.$store.dispatch('loading_spinner', false));
                
            if (data) {
                this.$store.commit('SET_PUBLICATIONS_WEB_WITH_PAGINATION', data.data);
                this.$store.commit('SET_PAGINATION', data.pagination);
            } */
            this.$store.commit('SET_CATEGORY_FILTER', category.code);
        },

        async getPublications(){
            this.$store.dispatch('loading_spinner', true);

            const {data} = await this.$store.dispatch('get_products_for_web')
                .catch((err) => {
                    console.lgo(err.response)
                })
                .finally(() => this.$store.dispatch('loading_spinner', false))

            if (data) {
                this.$store.commit('SET_PUBLICATIONS_WEB_WITH_PAGINATION', data.data);
                //this.select_store_mode_publications(data.data);

                this.$store.commit('SET_PAGINATION', data.pagination)
            }
           
        },
    },

}
</script>

<style>

</style>