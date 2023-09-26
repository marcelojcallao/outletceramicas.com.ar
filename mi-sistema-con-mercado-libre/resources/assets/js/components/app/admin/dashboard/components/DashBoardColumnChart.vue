
<script>
import columnChartsBase from '../../../charts/columnChartsBase.vue';
    export default {
        
        name: 'DashBoardColumnChart', 

        extends: columnChartsBase, 

        data(){
            return {
                chartData: [
                    ['Año', 'Ventas'],
                    ['2014', 1000],
                    ['2015', 1170],
                    ['2016', 660],
                    ['2017', 1030]
                ],

                chartOptions: {
                    chart: {
                        title: 'Company Performance',
                        subtitle: 'Sales, Expenses, and Profit: 2014-2017',
                    },
                    height: 600,
                }
            }
        },
    }
</script>