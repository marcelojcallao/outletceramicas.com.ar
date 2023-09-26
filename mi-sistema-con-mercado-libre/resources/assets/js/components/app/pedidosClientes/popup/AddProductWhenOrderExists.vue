<script>
import AddProductBase from '../../admin/orders/New/Body/AddProductBase.vue'
import AddProductToOrderwhenOrderExists from '../../../../mixins/Orders/AddProductToOrderwhenOrderExists'

export default {

    name : 'AddProductWhenOrderExists',

    extends : AddProductBase,

    mixins : [AddProductToOrderwhenOrderExists]

}
</script>

<style scoped>
    #grid {
        display: grid;
        gap: .1rem;
        grid-template-columns: auto auto auto auto auto;
        grid-template-rows: 1fr 1fr 1fr;
        padding: 1rem;
    }
    .item-grid1 {
        grid-column-start: 1;
        grid-column-end: 3;
    }
    .item-grid2 {
        grid-column-start: 4;
        grid-column-end: 6;
    }
    .item-grid3 {
        grid-column-start: 1;
        grid-column-end: 3;
    }
    .item-grid4 {
        grid-column-start: 3;
        grid-column-end: 5;
    }
    .item-grid5 {
        grid-column-start: 5;
        grid-column-end: 6;
    }
</style>