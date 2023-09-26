
<script>
    import MultiselectBase from './../../../../../../Base/Multiselect/MultiselectBase.vue';
    export default {

        name : 'SearchSheetmetalCutting',

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

                    const products = await this.$store.dispatch('searchProductAction', q)
                    .catch((e) => console.log('error en ' + e))
                    .finally(()=>this.show_spinner = false)

                    if (products) {
                        this.options = products.data.filter(product => product.isCHP);
                    }
                }

            },

            updateValue(el){
                this.product = el
            },

            selected(el){
                this.$store.dispatch('newSheetMetalCuttingSetId', el.id);
            },

            remove(el){
                this.product = null;
                this.$store.dispatch('newSheetMetalCuttingSetId', '');
            },
        }
    }
</script>

<style lang="scss" scoped>

</style>
