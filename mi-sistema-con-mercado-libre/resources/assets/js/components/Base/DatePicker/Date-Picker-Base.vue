<template>
<div style="display:flex">
    <label >{{title}}</label>
    <date-picker 
        v-model="date"  
        format="DD-MM-YYYY"
        :lang="lang"
        :disabled-date="(date) => date <= disabledDate"
    >
    </date-picker>
</div>
</template>
<script>
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';

export default {

    props: {
        title: {
            default: 'Fecha',
            type: String
        },
    },

    data() {
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

    components: { DatePicker},    
}
</script>
<style >
label {
    padding-right: 1rem;
}
</style>
