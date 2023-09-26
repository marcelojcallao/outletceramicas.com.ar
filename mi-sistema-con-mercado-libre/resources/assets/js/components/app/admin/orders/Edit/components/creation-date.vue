<template>
    <div id="date-container">
        <div class="created-date">
            <p>Fecha de creación:</p>
            <p>{{OrderEditDataGetter.created}}</p>
        </div>
        <div class="delivery-date">
            <p>Fecha de Entrega:</p>
            <date-picker  
                v-model="deliveryDate" 
                value-type="format"
                format="DD-MM-YYYY"
                :lang="lang"
                :disabled-date="(date) => date <= disabledBefore"
                ></date-picker>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import { Event } from 'vue-tables-2';
import DatePicker from 'vue2-datepicker';
export default {

    name: 'creation-date',
    
    components: {DatePicker},

    data(){
        return {
            lang: {
                // the locale of formatting and parsing function
                formatLocale: {
                    // MMMM
                    months: ['Enero', 'Febreo', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    // MMM
                    monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    // dddd
                    weekdays: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado'],
                    // ddd
                    weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sáb'],
                    // dd
                    weekdaysMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
                    // first day of week
                    firstDayOfWeek: 1,
                    // first week contains January 1st.
                    firstWeekContainsDate: 1,
                    // format 'a', 'A'
                    /* meridiem: (h: Number, _: Number, isLowercase: boolean) {
                        const word = h < 12 ? 'AM' : 'PM';
                        return isLowercase ? word.toLocaleLowerCase() : word;
                    }, */
                    // parse ampm
                    //meridiemParse: /[ap]\.?m?\.?/i;
                    // parse ampm
                    /* isPM: (input: string) {
                        return `${input}`.toLowerCase().charAt(0) === 'p';
                    } */
                },
                // the calendar header, default formatLocale.weekdaysMin
                days: [],
                // the calendar months, default formatLocale.monthsShort
                months: [],
                // the calendar title of year
                yearFormat: 'YYYY',
                // the calendar title of month
                monthFormat: 'MMM',
                // the calendar title of month before year
                monthBeforeYear: false,
            }
        }
    },

    computed: {

        ...mapGetters([
            'OrderEditDataGetter'
        ]),

        deliveryDate: {
            get(){
                return this.OrderEditDataGetter.delivery_date
            },
            set(date){
                this.$store.dispatch('orderUpdateDeliveryDate', date);
            }
        },

        disabledBefore(){
            return this.$moment(Date.now()).subtract(1, 'days').toDate()
        }
    },

    /* methods: {
        async updateDeliveryDate(date){
            
            const payload = {
                pedido_id: this.OrderEditDataGetter.id, 
                date
            } 
            const pedido = await this.$store.dispatch('update_delivery_date', payload)

            Event.$emit('pedido_cliente_update_delivery_date', pedido);
        }
    },

    watch: {

        deliveryDate(newDate){
            this.updateDeliveryDate(newDate);
        }
    } */
}
</script>

<style>
#date-container{
    display: flex;
    justify-content: space-between;
}
.created-date{
    margin-left: 1rem;
    margin-right: 1rem;
}
.delivery-date{
    margin-left: 1rem;
    margin-right: 3rem;
}
.delivery-date > p{
    color: black;
}
</style>