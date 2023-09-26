<script>
import {mapGetters} from 'vuex';
import Base from './../../../../../Base/DatePicker/Date-Picker-Base.vue';

export default {
    
    extends : Base,

    computed : {

        ...mapGetters([
            'DateOrderGetter',
            'DeliveryDateOrderGetter',
            'OrderErrors'
        ]),

        date : {

            get(){
                return this.DeliveryDateOrderGetter;
            },
            set(value){
                this.$store.commit('NEW_ORDER_SET_DELIVERY_DATE', value);
            }
        },

        disabledDate(date) {}
    },

    watch : {

        DeliveryDateOrderGetter(n){

            let delivery_date = this.$moment(n)
            let order_date = this.$moment(this.DateOrderGetter);

            if (delivery_date.isBefore(order_date)) {
                this.error_message('La fecha de entrega no puede ser menor que la del pedido.')
                this.setToday();
                return;
            }

        }

    },

    methods : {

       setToday(){
           this.$store.commit('NEW_ORDER_SET_DELIVERY_DATE', new Date);
       }
    },

    mounted(){
        this.setToday();
    }

}
</script>