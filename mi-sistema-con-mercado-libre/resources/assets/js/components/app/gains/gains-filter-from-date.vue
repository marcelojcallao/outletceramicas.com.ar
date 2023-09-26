<script>
import { mapGetters } from 'vuex';
import DatePickerBase from '../../Base/DatePicker/Date-Picker-Base.vue';

    export default {
        
        name: 'GainsFilterFromDate',

        extends: DatePickerBase,

        computed: {

            ...mapGetters([
                'GainsFromDateGetter'
            ]),

            date: {
                get(){
                    return this.GainsFromDateGetter;
                },
                set(value){

                    this.$store.dispatch('gainsSetFromDate', value);
                }
            },
        },

        mounted(){
            const from = new Date(2020,0,1);
            this.$store.dispatch('gainsSetFromDate', from);
        }
    }
</script>