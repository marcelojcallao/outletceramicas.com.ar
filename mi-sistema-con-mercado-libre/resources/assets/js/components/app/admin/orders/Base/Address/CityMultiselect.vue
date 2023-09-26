
<script>
import {mapGetters} from 'vuex';
import MultiselectBase from './../../../../../Base/Multiselect/MultiselectBase';
export default {

    extends : MultiselectBase,

    computed : {

        ...mapGetters([
            'NewOrderDataGetter'
        ]),

        Value(){
            return this.NewOrderDataGetter.address.city
        }
    },

    methods : {

        async asyncFind(query)
        {
            this.show_spinner = true;

            this.sleep(300);
                
            let cities = await axios.post('/api/localidades/find_by_name', {
                province_id : this.NewOrderDataGetter.address.state.id,
                localidad : query
            })
            .catch((err) => {
                console.log(err);
            })
            .finally(()=> this.show_spinner = false);

            if (cities) {
                this.options = cities.data
            }

        },

        updateValue(el){
            this.$store.commit('NEW_ORDER_ADDRESS_SET_CITY', el);
        },

        remove(el){
            this.$store.commit('NEW_ORDER_ADDRESS_REMOVE_CITY');
        },

        selected(){}
    },
}
</script>

<style>

</style>