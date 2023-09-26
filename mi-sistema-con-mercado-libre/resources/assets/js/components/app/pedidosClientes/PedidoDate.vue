<template>
    <div class="form-group">
        <label class="col-sm-4 control-label">Fecha</label>
        <div class="col-sm-8">
            <datepicker 
                :language="es"
                :value="date"
                :format="customFormatter"
                :disabled-dates="DisabledDates"
                v-model="date"
                @selected="delivery_date"
                input-class="my-input"
            ></datepicker>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Datepicker from 'vuejs-datepicker';
import {es} from 'vuejs-datepicker/dist/locale';
export default {
    components: {
        Datepicker
    },
    data() {
        return {
            es : es,
            date : new Date()
        }
    },

    computed : {

        ...mapGetters(['PedidoListChildRowReactivityData']),
        
        DisabledDates(){
            return {
                to:  this.$moment(this.Today).subtract(6, 'days').toDate(),
            } 
        },

        Today(){
            return this.$moment(Date.now());
        },
    },

    methods: {
        
        customFormatter(date){
            return this.$moment(date).format("dddd D [de] MMMM [de] YYYY");
        },

        delivery_date(value){

            const date = this.$moment(value).format("YYYYMMDD");

            this.$store.commit('SET_DELIVERY_DATE', date);
        }
    },

    mounted() {
        
        this.$moment.locale('es');
        
        setTimeout(() => {

            if (this.PedidoListChildRowReactivityData.delivery_date != '' || this.PedidoListChildRowReactivityData.delivery_date != null) {
                this.date = this.PedidoListChildRowReactivityData.delivery_date
            }
            this.delivery_date(this.date);

        }, 400);
    
    },

}
</script>

