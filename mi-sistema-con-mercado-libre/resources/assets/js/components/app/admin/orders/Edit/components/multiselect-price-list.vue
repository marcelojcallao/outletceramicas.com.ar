<script>
import {mapGetters} from 'vuex';
import MultiselectPriceListBase from './../../../../../Base/PriceList/MultiselectPriceListBase.vue';  
export default {

    name: 'multiselect-price-list',
    
    extends : MultiselectPriceListBase,

    props : {
        index: {
            type: Number,
        },
    },

    data() {
        return {
            show_spinner : false,
            price: {
                id: null,
                name: null
            }
        }
    },

    methods : {

        updatePriceListValue(el){

        },

        selectedPriceList(el)
        {

            const priceList = {
                id: el.pricelist_id,
                name: el.name
            }

            this.$store.dispatch('orderUpdateSetPriceList', priceList); 

            this.$store.dispatch('orderUpdateSetUnitPriceProduct', el.price_raw); 
        },

        removePriceList(el){

            this.$store.dispatch('orderUpdateSetInitialValues'); 
        },
        
    },

    computed : {

        ...mapGetters(['OrderUpdateNewProductGetter']),

        price_list(){
            return this.OrderUpdateNewProductGetter.price_lists;
        },

        PriceListValue(){
            return this.OrderUpdateNewProductGetter.price_list;
        }
    }

}
</script>
