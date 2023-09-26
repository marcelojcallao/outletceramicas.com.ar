<template>
    <button 
        class="btn btn-primary"
        @click="save_movement"
        :class="{'spinner spinner-inverse spinner-sm' : spinner}"
    >
        Guardar
        
    </button>
</template>

<script>
import { mapGetters } from 'vuex';
export default {

    name: 'MovementButtonAction',

    data(){
        return {
            spinner: false
        }
    },

    methods: {
        
        async save_movement(){

            this.spinner = true;

            this.DailyMovementGetter.date = this.$moment(this.DailyMovementGetter.date).format('YYYY-MM-DD');

            const { data }  = await this.$store.dispatch('saveDailyMovement', this.DailyMovementGetter)
            .finally(() => this.spinner = false)

            if (data) {
                this.$store.dispatch('setDailyMovementList', data.petty_cash);
                this.$store.dispatch('setDailyMovementSaldo', data.saldo);

                this.$store.dispatch('setDailyMovementImport', 0);
                this.$store.dispatch('setDailyMovementDescription', '')
                this.$store.dispatch('setDailyMovementType', null)
            }
        }
    },

    computed: {

        ...mapGetters(['DailyMovementGetter'])
    }
}
</script>

<style>

</style>