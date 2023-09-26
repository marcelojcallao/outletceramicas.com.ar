<script>
import { mapGetters } from 'vuex';
import MultiselectBase from './../../Base/Multiselect/MultiselectBase.vue'
export default {

    name: 'MultiselectMovementType',

    extends: MultiselectBase,

    data() {
        return {
            options: [
                {id: 1, name: 'Entrada'},
                {id: 2, name: 'Salida'},
            ]
        }
    },

    methods: {
        
        updateValue(el){
            this.$store.dispatch('setDailyMovementType', el)
        },

        selected(){},

        remove(){},

        asyncFind(){}
    },
    computed: {

        ...mapGetters(['DailyMovementTypeGetter']),
        
        Value(){
            return this.DailyMovementTypeGetter;
        },


    }

}
</script>

<style>

</style>