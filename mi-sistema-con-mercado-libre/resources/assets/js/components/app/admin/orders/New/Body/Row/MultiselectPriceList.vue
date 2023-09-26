<script>
import {mapGetters} from 'vuex';
import MultiselectPriceListBase from './../../../../../../Base/PriceList/MultiselectPriceListBase';
export default {

    extends : MultiselectPriceListBase,

    props : ['index'],

    data() {
        return {
            show_spinner : false,
            products : [],
        }
    },

    methods : {

        updatePriceListValue(el){

            const payload = {
                index : this.index,
                value : el
            }

            this.$store.commit('NEW_ORDER_SET_PRICE_LIST', payload);

            setTimeout(() => {
                this.$store.dispatch('updateTotalOnRowProduct', this.index);
            }, 250);

        },

        selectedPriceList(el)
        {
            const payload = {
                index : this.index,
                value : el.price_raw
            }

            this.$store.commit('NEW_ORDER_SET_PRICE_PRODUCT', payload);

            setTimeout(() => {
                this.$store.dispatch('updateTotalOnRowProduct', this.index);
            }, 250);


			const quantity_input = document.getElementById('quantity-input');

			quantity_input.focus();

			quantity_input.value = 0;

			quantity_input.select();
        },

        removePriceList(el){
            this.$store.commit('NEW_ORDER_REMOVE_PRICE_LIST', this.index);

            setTimeout(() => {
                this.$store.dispatch('updateTotalOnRowProduct', this.index);
            }, 250);
        }


    },

    computed : {

        ...mapGetters([
            'NewOrderPriceListsProductGetter',
            'NewOrderPriceListGetter',
            'GetIvas',
            'NewOrderProductValue'
        ]),

        price_list(){
            if (this.NewOrderPriceListsProductGetter(this.index) ) {
                return this.NewOrderPriceListsProductGetter(this.index);
            }
            return [];
        },

        PriceListValue(){
            return this.NewOrderPriceListGetter(this.index);
        }
    }

}
</script>
