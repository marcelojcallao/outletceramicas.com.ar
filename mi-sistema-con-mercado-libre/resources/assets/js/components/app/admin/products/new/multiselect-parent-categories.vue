<script>
    import {mapGetters} from 'vuex';
    import parent_category_mixin from './../../../../../mixins/Categories/parent-category-mixin';
    import multiselect_parent_categories from '../../categories/New/multiselect-parent-categories-new.vue'
    export default {

        extends : multiselect_parent_categories,

        mixins : parent_category_mixin,

        data(){
            return {
                loading : false,
                parent_id : ''
            }
        },

        watch : {
            parent_id(n){
                this.$store.commit('NEW_PRODUCT_SET_PARENT_ID', n);
            }
        },
    }
</script>
