<script>
import { mapGetters } from 'vuex'
import sleepMixin from '../../../../../../mixins/sleep-mixin';
import MultiselectProductBase from './../../../../../Base/Product/MultiselectProductBase.vue';  
export default {
    extends : MultiselectProductBase,

    props : ['index'],

    mixins: [sleepMixin],

    data() {
        return {
            show_spinner : false,
            products: [],
            ProductValue: {}
        }
    },

    methods : {

        asyncFind (query) {1

            if (query != '') {
                
                axios.post('/api/products/find_by_name', {
                    product_name : query,
                }).then((result) => {
                    
                    const { data: products } = result;

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

                    this.products = products;

                }).catch((err) => {
                    console.log("🚀 ~ file: multiselect-product.vue ~ line 42 ~ asyncFind ~ err", err)
                    
                });
            }

        },
        
        removeProduct(el)
        {
            this.ProductValue = null;
             
            this.$store.dispatch('orderUpdateSetInitialValues');
    
            this.$store.dispatch('orderUpdateSetPriceLists', []);
        },

        updateProductValue(el){
            console.log("🚀 ~ file: multiselect-product.vue:58 ~ updateProductValue ~ el", el)
            this.$store.dispatch('orderUpdateSetProduct', el)
        },

        selectedProduct(product)
        {
            this.$store.dispatch('orderUpdateSetInitialValues');
            this.ProductValue = product;
            this.$store.dispatch('orderUpdateSetPriceLists', product.price_list);
            this.$store.dispatch('orderUpdateSetPriceList', null); 
            this.$store.dispatch('checkStockSetProduct', product) //este no sirve, ver si se le puede dar utilidad

            if (product.isCHP) {
                
                if (this.CheckStockDisponibleStock.length) {
                    
                    const index = this.CheckStockDisponibleStock.findIndex(stock => {
                        return stock[0].product_id == product.sheet_metal_cuttings[0].product_id
                    })
                    
                    if (index === -1) {
                        this.$store.dispatch('setDisponibleStock', product.sheet_metal_cuttings);
                    }
                }else{
                    this.$store.dispatch('setDisponibleStock', product.sheet_metal_cuttings);
                }
            }
        },

    },

    computed : {
        ...mapGetters(['CheckStockDisponibleStock']),
    },

}
</script>
<style>
    .multiselect .multiselect__tags .multiselect__content-wrapper .multiselect__element .multiselect__option { 
        z-index: 10000;
    }
</style>
