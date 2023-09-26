<template>
    <div>
        <loading 
            :active.sync="loading" 
            color="#0469c7"
            :can-cancel="false" 
            :is-full-page="false">
        </loading>
        <div v-for="(category, index) in CategoriesList" :key="index">
            <MainCategories :main_category="category" />
        </div>
    </div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import auth from '../../../../../mixins/auth';
import 'vue-loading-overlay/dist/vue-loading.css';
import MainCategories from './MainCategories';
import categories_mixin from "./../../../../../mixins/Categories/prepare"
import { mapGetters } from 'vuex';
    export default {
        
        mixins : [auth, categories_mixin],

        components : {
            Loading, MainCategories
        },

        data(){
            return {
                loading : false
            }
        },

        computed : {

            ...mapGetters([
                'CategoriesList'
            ])
        },

        async mounted(){

            const {data} = await this.$store.dispatch('fetchAllCategories');

            if (data) {
                this.transform_categories(data)
            }
        }

    }
</script>

<style lang="scss" scoped>

</style>