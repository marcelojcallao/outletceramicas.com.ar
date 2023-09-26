<script>
import {mapGetters} from 'vuex';
import auth from "./../../../../../../../mixins/auth.js";
import MultiselectProductBase from './../../../../../../Base/Product/MultiselectProductBase';  
export default {
    extends : MultiselectProductBase,

    props : ['index'],

    mixins: {auth},

    data() {
        return {
            show_spinner : false,
        }
    },

    methods : {

        asyncFind (query) {

            if (query != '') {
                
                window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.User.token;

                axios.post('/api/products/find_by_name', {
                    product_name : query,
                }).then((result) => {
                    
                    const products = result.data;

                    products.forEach(product => {
                        if (this.User.type_user_id == 1) {
                            delete product.$isDisabled
                        }
                        if (this.User.type_user_id == 3) {
                            product.price_list.forEach(pl => {
                                if (pl.pricelist_id == 4) {
                                    pl.$isDisabled = true;
                                }
                            })
                        }
                    });
                    this.$store.commit('NEW_ORDER_SET_PRODUCTS_ON_MULTISELECT_PRODUCTS', products);
                }).catch((err) => {
                    
                });
            }

        },
        
        removeProduct(el)
        {
            this.$store.commit('NEW_ORDER_SET_CURRENT_PRODUCT', null);
        },

        updateProductValue(el){
            let payload = {
                index : this.index,
                value : el
            }

            this.$store.commit('NEW_ORDER_SET_PRODUCT', payload);

            setTimeout(() => {
                this.$store.dispatch('updateTotalOnRowProduct', this.index);
            }, 250);

        },

        selectedProduct(el)
        {
            const payload = {
                index : this.index,
                value : el.price_list,
                product : el,
            }
            
            this.$store.commit('NEW_ORDER_SET_PRICE_LISTS_OF_A_PRODUCT', payload);

            this.$store.commit('NEW_ORDER_SET_CURRENT_PRODUCT', el);

            this.$store.commit('NEW_ORDER_SET_ISCHP', payload);

            setTimeout(() => {
                this.$store.dispatch('updateTotalOnRowProduct', this.index);
            }, 250);


        }
    },

    computed : {

        ...mapGetters([
            'NewOrderProductValue',
            'NewOrderPriceListsProductGetter',
            'NewOrderMultiselectProducts',
            'NewOrderDataGetter'
        ]),

        ProductValue(){
            return this.NewOrderProductValue(this.index)
        },

        products(){
            
            const SIN_STOCK = 'SIN STOCK'; 

            if (this.NewOrderDataGetter.type && this.NewOrderDataGetter.type.id === 102) {
                
                const products = this.NewOrderMultiselectProducts.map(product => {
                    
                    product.$isDisabled = false;

                    (product.name.includes(SIN_STOCK)) 
                        ? product.name = product.name.substring(0, product.name.length - 12)
                        : product.name;
                    
                    return product;
                });

                return products || [];
            }
            
            return this.NewOrderMultiselectProducts;
        }
    }

}
</script>
