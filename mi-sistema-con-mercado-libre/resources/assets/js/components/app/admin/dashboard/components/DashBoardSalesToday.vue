<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="pull-left">
                    <h4 class="card-title">Ventas</h4>
                </div>
                <div class="pull-right" data-toggle="buttons">
                    <button v-for="(button, index) in buttons" :key="index" class="btn btn-outline-primary btn-pill" type="button" :ref="button.ref" @click="option=button.value">{{button.name}}</button>
                    
                </div>
            </div>
            <div class="card-body">
                <DashBoardColumnChart />
            </div>
        </div>
    </div>
</template>

<script>
import DashBoardColumnChart from './DashBoardColumnChart.vue';
    export default {
        
        name: 'DashBoardSalesToday',

        components: { DashBoardColumnChart },

        data(){
            return {
                buttons: [
                    {ref: 'uno', name: 'Hoy', value: 0},
                    {ref: 'siete', name: 'Últimos 7 días', value: 7} ,  
                    {ref: 'treinta', name: 'Últimos 30 días', value: 30}
                ],

                option: null
            }
        },

        methods: {

            async sales_column_chart(){

                
                console.log("🚀 ~ file: DashBoardSalesToday.vue:41 ~ sales_column_chart ~ response:", response)
            }
        },

        watch: {

            async option(n){
                const from = this.$moment().format('YYYY-MM-DD');
                const to = this.$moment().subtract(n, 'days').format('YYYY-MM-DD');
                
                const response = await this.$store.dispatch('sales_column_chart', { from, to })
                console.log("🚀 ~ file: DashBoardSalesToday.vue:56 ~ option ~ response:", response)
            }
        }

    }
</script>

<style lang="scss" scoped>

</style>