
<script>
    import MultiselectBase from './../../../../Base/Multiselect/MultiselectBase';
    export default {
        
        extends : MultiselectBase,

        data(){
            return {
                product : null
            }
        },

        computed : {

            Value(){
                return this.product;
            },
           
        },

        methods : {

            async asyncFind(q){

                if (q != '') {
                    
                    this.show_spinner = true;

                    const { data:products } = await this.$store.dispatch('searchProductAction', q)
                    .catch((e) => console.log('error en ' + e))
                    .finally(()=>this.show_spinner = false)

                    if (products) {
                        products.forEach(product => {
                            delete product.$isDisabled;
                        });
                        this.options = products;
                    }
                }

            },

            updateValue(el){
                this.product = el
            },

            selected(el){
                let arr = [];
                arr.push(el)
                this.$store.commit('ADD_PRODUCTS_TO_LIST_PRODUCTS', arr);
            },

            remove(el){
                this.product = null;
            },
        }
    }
</script>

<style lang="scss" scoped>

</style>