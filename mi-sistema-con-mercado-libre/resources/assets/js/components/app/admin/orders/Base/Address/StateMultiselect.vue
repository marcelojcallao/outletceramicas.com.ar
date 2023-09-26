
<script>
import {mapGetters} from 'vuex';
import MultiselectBase from './../../../../../Base/Multiselect/MultiselectBase';
export default {

    extends : MultiselectBase,

    computed : {

        ...mapGetters([
            'NewOrderDataGetter',
            'Provinces'
        ]),

        Value(){
            return this.NewOrderDataGetter.address.state
        }
    },

    watch : {

        Provinces(n){
            this.options = n;
        }
    },

    methods : {
        asyncFind(){},
        updateValue(el){
            this.$store.commit('NEW_ORDER_ADDRESS_SET_STATE', el);
        },

        remove(el){
            this.$store.commit('NEW_ORDER_ADDRESS_REMOVE_STATE');
        },

        selected(){}
    },
}
</script>

<style>

</style>