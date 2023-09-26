<script>
import { mapGetters } from 'vuex';
import MultiselectBase from './../../Base/Multiselect/MultiselectBase.vue'
export default {

    name: 'MultiselectDescription',

    extends: MultiselectBase,

    methods: {
        
        updateValue(el){
            this.$store.dispatch('setDailyMovementDescription', el)
        },

        selected(){},

        remove(){},

        asyncFind(query){
            let arr = []
            const q = {
                id: null,
                name: query
            }
            arr.push(q);
            this.options = [];
            this.options.push(arr.pop())


        }
    },
    computed: {

        ...mapGetters(['DailyMovementDescriptionGetter']),
        
        Value(){
            return this.DailyMovementDescriptionGetter;
        },


    }

}
</script>

<style>

</style>