import collect from 'collect.js';
import {mapGetters} from 'vuex';

export default {

    methods : {

        transform_categories(data){

            const catgs = collect(data).sortBy('parent_id');
            this.$store.commit('SET_ORIGINAL_CATEGORIES_LIST', catgs.toArray());
            const categories_list = this.category_handle(catgs.toArray());

            const sort_categories = collect(categories_list).sortBy('name');
            
            this.$store.dispatch('set_categories_list', sort_categories.toArray());
        },

        category_handle(categories)
        {
            let arr = [];

            collect(categories).map(category => {
                if (category.parent_id == 0) {
                    
                    arr.push({
                        'id' : category.id,
                        'parent_id' : category.parent_id,
                        'name' :  category.name,
                        'code' : category.code,
                        'status' : category.status,
                        'children' : [],
                    })
                }
                this.category_son(arr, category);
            });
            
            return arr;
        },

        category_son(arr, cat)
        {
            collect(arr).map(el => {
                    if (el.id == cat.parent_id) {
                        el.children.push({
                            'id' : cat.id,
                            'parent_id' : cat.parent_id,
                            'name' : cat.name,
                            'code' : cat.code,
                            'status' : cat.status,
                            'children' : [],
                        });
                    }else{
                        this.category_son(el.children, cat);
                    }
                })
        }
    },

    computed : {
        ...mapGetters([
            'CategoriesList'
        ]),
    },
    
}