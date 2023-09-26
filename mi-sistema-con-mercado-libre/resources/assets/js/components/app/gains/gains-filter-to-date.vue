<script>
import { mapGetters } from 'vuex';
import DatePickerBase from '../../Base/DatePicker/Date-Picker-Base.vue';

    export default {
        
        name: 'GainsFilterToDate',

        extends: DatePickerBase,

        computed: {

            ...mapGetters([
                'GainsToDateGetter'
            ]),

            date: {
                get(){
                    return this.GainsToDateGetter;
                },
                set(value){

                    this.$store.dispatch('gainsSetToDate', value);
                }
            },
        },

        mounted(){
            const To = new Date();
            this.$store.dispatch('gainsSetToDate', To);
        }
    }
</script>