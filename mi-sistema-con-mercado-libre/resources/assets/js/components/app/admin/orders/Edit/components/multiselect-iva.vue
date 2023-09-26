<script>
import {mapGetters} from 'vuex';
import MultiselectIvaBase from './../../../../../Base/Iva/MultiselectIvaBase.vue'
export default {
    
    extends : MultiselectIvaBase,

    computed : {

        ...mapGetters([
            'GetIvas',
            'OrderUpdateNewProductGetter'
        ]),

        IvaValue(){
            return this.OrderUpdateNewProductGetter.iva;
        },

        ivas(){
            return this.GetIvas;
        }
    },

    methods : {

        updateIvaValue(el){


        },

        selectedIva(el){
            this.$store.dispatch('orderUpdateSetIvaProduct', el)
        }
        
    },

    mounted() {
        this.$store.dispatch('fetchIvas');
    },
    
}
</script>


