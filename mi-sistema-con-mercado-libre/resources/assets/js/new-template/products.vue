<template>
    <div class="row">
        <loading 
            class="custom-position"
            :active.sync="LoadingGetter" 
            :can-cancel="false" 
            :is-full-page="false"></loading>
        <h3 v-if="PublicationsWeb.length == 0"
            class="entry-title custom-position">
                <a 
                    @click.prevent="returnToInit"
                    href="#"
                    >{{(LoadingGetter)?'Buscando productos.':'Su consulta no arrojó resultados, vuelva al inicio.'}}</a>
        </h3>
        <div  v-else
            class="product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12"
            v-for="product in PublicationsWeb" :key="product.id"
        >
            <single_product :product="product" />
        </div>
        <div v-if="this.Pagination.total > 12">
            <paginate
                :page-count="this.Pagination.last_page"
                :click-handler="getPublications"
                :prev-text="'Ant.'"
                :prev-class="'page-link'"
                :next-class="'page-link'"
                :next-text="'Sig.'"
                :container-class="'pagination custom-pagination'"
                :page-class="'page-item'"
                :page-link-class="'page-link'"
                :active-class="'active'"
                :first-last-button="true"
                :hide-prev-next="true"
                :first-button-text="'Primero'"
                :last-button-text="'Último'"
                >
            </paginate>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import filter_mixin from './../mixins/Web/filter-mixin';
import Loading from 'vue-loading-overlay';
import Paginate from 'vuejs-paginate';
import single_product from "./single_product.vue";
export default {

    mixins : [filter_mixin],

    components : {single_product, Paginate, Loading},

    computed : {

        ...mapGetters([
            'CategoryFilterGetter',
            'LoadingGetter',
            'Pagination',
            'PublicationsWeb',
            'SortProductsGetter',
            'ProductNameGetter'
        ]),


    },

    methods : {

        returnToInit(){
            this.$store.commit('SET_CATEGORY_FILTER', null);
            this.$store.commit('SET_SORT_FILTER', null);
        }
    },
    watch : {

        async CategoryFilterGetter(n){
            await this.getPublications()
        },

        async SortProductsGetter(n){
            
            if (n == 'name_a_z' || n == 'name_z_z') {
                await this.getPublications()
            }
        },

        async ProductNameGetter(n){
            await this.getPublications()
        }

    },

    async beforeMount(){
        await this.getPublications()
    }
}
</script>

<style scoped>
    .custom-pagination{
        width: 100%;
        margin-top: 10rem;
        display: flex;
        justify-content: center;
    }
    .margin-top{
        margin-top: 4rem;
    }
    .custom-position{
        padding-top:5rem; 
        text-align: center;
        margin-bottom: 2rem;
    }
</style>