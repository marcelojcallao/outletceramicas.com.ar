<template>
    <div class="childRow">
        <GChart
            type="PieChart"
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

        mixins: [CurrencyFormat],

        data(){
            return {
                chartData : [],
                
                chartOptions : {
                    title: 'Company Performance',
                    width: "100%",
                    height: 600,
                    pieSliceText: 'value',
                }
            }
        },

        computed: {

            ...mapGetters([
                "GainsFromDateGetter",
                "GainsToDateGetter",
                "GainsListGetter",
                'GainsTotalEarningsGetter'
            ]),

            ChartData(){
                return this.chartData;

            }
        },

        methods: {
            onChartReady (chart, google) {
                console.log("🚀 ~ file: PieChart.vue:52 ~ onChartReady ~ chart, google:", chart, google)
                
                const formatter = new google.visualization.NumberFormat( {prefix: '$ ', negativeColor: 'red', pattern: "$ #,##0.00",negativeParens: true});
                    console.log("🚀 ~ file: PieChart.vue:55 ~ onChartReady ~ formatter:", formatter)
                    formatter.format(this.chartData, 1); // Apply formatter to second column
            } 
        },

        mounted(){

            const pp = []
            this.GainsListGetter.reduce((acc, current) => {
                pp.push(
                    [
                        current.product_name,
                        current.earning
                    ]
                )
            }, {})
            pp.unshift(['pp', '%'])
            this.chartData =pp
            
        },
    }
</script>

<style scoped>
    .VueTables__child-row-toggler {
        width: 16px;
        height: 16px;
        line-height: 16px;
        display: block;
        margin: auto;
        text-align: center;
}
</style>