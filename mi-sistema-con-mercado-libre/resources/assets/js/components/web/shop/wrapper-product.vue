<template>
    <div>
        <loading v-if="LoadingGetter"
            color="#0469c7"
            :can-cancel="false" 
            :is-full-page="false">
        </loading>
            <div v-if="HasPublicationsWeb">
                <div class="tt-product-listing row" >
                    <product v-for="(product, index) in PublicationsWeb" :key="index" :product="product"></product>
                </div>
                <div class="text-center" style="padding-top:50px">
                    <button :disabled="VerifyIfLoadMorePublication" @click="getPublications()" class="btn btn-border">{{TextButton}}</button>
                </div>
            </div>

            <div  v-if="HasPublicationsWeb == false && HasCategoryFilters && HasBrandFilters" style="margin-top:50px; margin-left:50px; animation-duration: 2s">
                <h2 class="tt-base-color">Ésta búsqueda no arrojó resultados</h2>
            </div>
    </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import {mapGetters} from 'vuex';
export default {
    components : {
        Loading
    },
    data(){
        return {
            loading : false
        }
    },

    methods : {

        getPublications(){
            
            this.$store.dispatch('loading_spinner', true);

            this.$store.dispatch('get_products_for_web')
            .then((result) => {
                this.$store.commit('SET_PUBLICATIONS_WEB_WITH_PAGINATION', result.data.data);
                //this.select_store_mode_publications(result.data.data);

                this.$store.commit('SET_PAGINATION', result.data.pagination)

            }).catch((err) => {
                console.lgo(err.response)
            })
            .finally(() => this.$store.dispatch('loading_spinner', false))
           
        },

        changePage(page) {

            if (page == this.pagination.last_page) {

                this.load_more_button = false;

            }

            this.pagination.current_page = page;
        }
       
    },

    computed : {
        TextButton(){

            if (this.VerifyIfLoadMorePublication) {

                return 'NO HAY MÁS PRODUCTOS ASOCIADOS A LA BÚSQUEDA';

            }

            return 'VER MÁS PRODUCTOS';
        },

        ...mapGetters([
            'Loading',
            'Pagination',
            'PublicationsWeb',
            'HasBrandFilters',
            'HasCategoryFilters',
            'HasPublicationsWeb',
            'VerifyIfLoadMorePublication',
            'LoadingGetter'
        ]),
    },

    beforeMount(){

        this.$store.commit("CLEAR_PUBLICATIONS");

        this.$store.commit("FORCE_INIT_PAGINATION");

        if (window.sessionStorage.results) {

            this.$store.commit('SET_PUBLICATIONS_WEB_WITH_PAGINATION', JSON.parse(window.sessionStorage.results));
            
            this.$store.commit('SET_PAGINATION', JSON.parse(window.sessionStorage.pagination))

        }

    },

    async mounted(){

        this.loading = true;

        const products = await this.$store.dispatch('get_products_for_web');

        if (products) {
            console.log("products");
            console.log(products);
            console.log("products");
            this.$store.commit('SET_PUBLICATIONS_WEB_WITH_PAGINATION', products.data.data);
            this.$store.commit('SET_PAGINATION', products.data.pagination)
        }
    }
}
</script>