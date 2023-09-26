
<script>
    import MultiselectBase from './../../../../../../Base/Multiselect/MultiselectBase.vue';
    export default {
        
        name : 'SearchByNameSheetMetalCutting',
        
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

            async selected(el){
                console.log("🚀 ~ file: SearchByNameSheetMetalCutting.vue:44 ~ selected ~ el:", el)
                const { data } = await this.$store.dispatch('getSheetMetalCutting', el.id);
                console.log("🚀 ~ file: SearchByNameSheetMetalCutting.vue:45 ~ selected ~ response:", data)
                this.$store.dispatch('setSheetMetalCutting', data)

            },

            remove(el){
                this.product = null;
            },
        }
    }
</script>

<style lang="scss" scoped>

</style>