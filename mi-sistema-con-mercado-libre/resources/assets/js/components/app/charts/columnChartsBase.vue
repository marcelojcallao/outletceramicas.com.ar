<template>
    <div class="childRow">
        <GChart
            type="ColumnChart"
            :data="ChartData"
            :options="chartOptions"
        />
    </div>
</template>

<script>
import CurrencyFormat from '../../../mixins/currency'
import { GChart } from 'vue-google-charts';
import { mapGetters } from 'vuex';

    export default {
        
        components: {GChart},

        computed: {

            ChartData(){
                return this.chartData;

            }
        },

    }
</script>