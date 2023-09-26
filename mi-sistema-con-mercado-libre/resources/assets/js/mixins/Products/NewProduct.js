    import {mapGetters} from 'vuex';
    import toast_mixin from './../../mixins/toast-mixin';
    import attribute from  './../../components/app/admin/products/new/attribute';
    import price_list_table from  './../../components/app/admin/products/new/price-list-table.vue';
    import multiselect_parent_categories from  './../../components/app/admin/products/new/multiselect-parent-categories.vue';
    import multiselect_child_categories from  './../../components/app/admin/products/new/multiselect-child-categories.vue';
    import multiselect_provider from  './../../components/app/admin/products/new/multiselect-provider.vue';
    import { requiredIf, required, between } from 'vuelidate/lib/validators';
    export default {
        
        components : {
            multiselect_parent_categories, multiselect_child_categories, multiselect_provider, price_list_table,
                attribute
        },

        mixins : [toast_mixin],
        
        computed : {
            
            product_name : {
                get(){
                    return this.ProductGetter.name
                },
                set(value){
                    this.$store.commit('NEW_PRODUCT_SET_NAME', value);
                }
            },

            product_code : {
                get(){
                    return this.ProductGetter.code;
                },
                set(value){
                    this.$store.commit('NEW_PRODUCT_SET_CODE', value);
                }
            },

            stock : {
                get(){
                    return this.ProductGetter.stock;
                },
                set(value){
                    this.$store.commit('NEW_PRODUCT_SET_STOCK', value);
                }
            },

            critical_stock : {
                get(){
                    return this.ProductGetter.critical_stock;
                },
                set(value){
                    this.$store.commit('NEW_PRODUCT_SET_CRITICAL_STOCK', value);
                }
            },

            code_on_supplier : {
                get(){
                    return this.ProductGetter.code_on_supplier
                },
                set(value){
                    this.$store.commit('NEW_PRODUCT_SET_PRODUCT_CODE_ON_SUPPLIER', value);
                }
            },

            name_on_supplier : {
                get(){
                    return this.ProductGetter.name_on_supplier
                },
                set(value){
                    this.$store.commit('NEW_PRODUCT_SET_PRODUCT_NAME_ON_SUPPLIER', value);
                }
            },

            description : {
                get(){
                    return this.ProductGetter.description;
                },
                set(value){
                    this.$store.commit('NEW_PRODUCT_SET_DESCRIPTION', value);
                }
            },
            
            isCHP : {
                get(){
                    return this.ProductGetter.isCHP;
                },
                set(value){

                    if ( ! value ) {
                        this.$store.commit('NEW_PRODUCT_SET_MTS_BY_UNITY', 0);
                    }

                    this.$store.commit('NEW_PRODUCT_SET_IS_CHP', value);
                }
            },
            mts_by_unity : {
                get(){
                    return this.ProductGetter.mts_by_unity;
                },
                set(value){
                    this.$store.commit('NEW_PRODUCT_SET_MTS_BY_UNITY', value);
                }
            },

            publish_on_web : {
                get(){
                    return this.ProductGetter.publish_on_web;
                },
                set(value){
                    this.$store.commit('NEW_PRODUCT_SET_PUBLISH_ON_WEB_STORE', value);
                }
            },

            see_price_on_web : {
                get(){
                    return this.ProductGetter.see_price_on_web;
                },
                set(value){
                    this.$store.commit('NEW_PRODUCT_SET_SEE_PRICE_ON_WEB_STORE', value);
                }
            },

            category_path : {
                get(){
                    return this.NewCategory.category_path;
                },
                set(value){
                    
                }
            },

            ...mapGetters([
                'Categories',
                'AttributesOfProductGetter',
                'ProductGetter',
                'ChildCategories',
                'SelectedCategoriesFromRootGetter',
                'NewCategory'
            ]),
            
        },

        methods : {
            
            message(message)
            {
                this.success_message('Producto', message);
            },

            set_initial_status()
            {
                this.$store.commit('SET_CATEGORY_INITIAL_STATUS');
                this.$store.commit('NEW_PRODUCT_SET_INITIAL_STATUS');
            },
            
            async store_product()
            {
                this.errors = null;

                const payload = {
                    token : this.User.token,
                    product : this.ProductGetter,
                    category_path : this.NewCategory.category_path, //se añade al codigo del producto
                    categories_path : this.ChildCategories,
                    selected_categories_from_root : this.SelectedCategoriesFromRootGetter
                }
                const vm = this;
                const product = await this.$store.dispatch('store_product', payload)
                .catch(e => {
                    this.error_message('Se ha producido un error, verifique.', 'Ingreso de productos', 4000);
                    this.errors = e.response.data.errors;
                    this.loading = false;
                    Vue.set(this.data, loading, false);
                })

                if (product) {
                    return product;
                }
            },

        },

        validations(){

            const requiredISCHP = this.ProductGetter.isCHP;

            return {

                /* mts_by_unity: {
                    required: requiredIf(function (requiredISCHP) {
                    return requiredISCHP;
                    })
                }, */
                product_name: { required },
                product_code: { required },
                stock: { required, between: between(0, 9999999) },
                critical_stock: { required, between: between(0, 9999999) },

            }

        }
       
    }