<template>
    <div class="custom-pagination">
        <paginate
            :page-count="this.Pagination.last_page"
            :click-handler="getPublications"
            :prev-text="'Anterior'"
            :next-text="'Siguiente'"
            :container-class="'pagination'"
            :page-class="'page-item'"
            :page-link-class="'page-link'"
            :prev-class="'page-link nav-text nav-text-prev'"
            :next-link-class="'page-link nav-text nav-text-next'"
            :active-class="'active'"
            >
        </paginate>
    </div>
</template>

<script>
import Paginate from 'vuejs-paginate';
import { mapGetters } from 'vuex';

export default {
    components : {
        Paginate
    },

    computed : {

        ...mapGetters([
            'Pagination'
        ])
    },

    methods : {

        async getPublications(page = 1){
            console.log('estoy products componenet')
            this.$store.dispatch('loading_spinner', true);

            //const {data} = await this.$store.dispatch('get_products_for_web')
            let url = '/get_products?page=' + page;
            const {data} = await axios.get(url)
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

    async mounted(){
        await this.getPublications()
    }
}
</script>

<style scoped>
    .custom-pagination{
        width: 100%;
        align-content: center;
    }
</style>