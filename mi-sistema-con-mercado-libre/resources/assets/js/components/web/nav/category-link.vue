<template>
    <a @click.prevent="search_by_category" href="">{{category.name}} </a>
</template>

<script>
export default {

    name : 'category-link',

    props : {
        category : {
            type : Object,
            required : true
        }
    },

    methods : {
        
        async search_by_category(){
            
            this.$store.dispatch('loading_spinner', true);
            
            this.$store.commit('SET_CATEGORY_FILTER', this.category.code);
            this.$store.commit('SET_PUBLICATIONS_WEB_WITH_PAGINATION', []);
            this.$store.commit('FORCE_INIT_PAGINATION');

            const {data} = await this.$store.dispatch('get_products_for_web', this.category.code)
                .catch((err) => {
                })
                .finally(() => this.$store.dispatch('loading_spinner', false));
                
            if (data) {
                this.$store.commit('SET_PUBLICATIONS_WEB_WITH_PAGINATION', data.data);
                this.$store.commit('SET_PAGINATION', data.pagination);
            }

        }
    },
   
}
</script>
<style scoped>
    a {
        color: #666;
    }
</style>