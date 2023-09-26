<script>
import { mapGetters } from 'vuex';
import DatePicker from './../../Base/DatePicker/Date-Picker-Base.vue';
export default {

    name: 'MovementDate',

    extends: DatePicker,

    computed: {

        ...mapGetters([
            'DailyMovementDateGetter'
        ]),

        disabledDate(){
            return this.$moment(Date.now()).subtract(1, 'days').toDate();
        },

        date: {
            get(){
                return this.DailyMovementDateGetter;
            },
            set(value){
                
                this.$store.dispatch('setDailyMovementDate', value);

            }
        }
    },

    mounted() {
        this.$store.dispatch('setDailyMovementDate', this.$moment(Date.now()).toDate());
    },
}
</script>

<style>

</style>