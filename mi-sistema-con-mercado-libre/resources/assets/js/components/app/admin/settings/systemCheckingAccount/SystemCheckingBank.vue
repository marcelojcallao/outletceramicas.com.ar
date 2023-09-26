
<script>
    import { mapGetters } from 'vuex';
import MultiselectBase from '../../../../Base/Multiselect/MultiselectBase.vue';
    export default {
        
        name: 'SystemCheckingBank',

        extends: MultiselectBase,

        methods: {

            updateValue(el){
            console.log("🚀 ~ file: SystemCheckingBank.vue:13 ~ updateValue ~ el:", el)
                this.$store.dispatch('banksSetBank', el)
            },

            selected(){},

            remove(){},

            async asyncFind(){}
        },

        computed: {

            ...mapGetters([
                'BankGetter'
            ]),

            Value(){
                
                return this.BankGetter;
            }
        },

        async mounted(){

            const { data:banks } = await this.$store.dispatch('getBanks')
            
            if (banks) {
                this.options = banks;
            }

        }

    }
</script>

<style lang="scss" scoped>

</style>