<script>
    import AddProductBase from './AddProductBase.vue';
    import AddProductMixin from '../../../../../../mixins/Orders/AddProductToNewOrder';
    export default {

        name : 'RowProduct',

        extends : AddProductBase,

        mixins : [AddProductMixin],
    }

</script>
<style scoped>
    #grid {
        display: grid;
        gap: .1rem;
        grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
        grid-template-rows: 1fr 1fr 1fr;
    }
    div[class^="item-"]{
        margin: .3rem;
    }
    .item-grid1 {
        grid-column-start: 1;
        grid-column-end: 4;
    }
    .item-grid9 {
        grid-column-start: 4;
        grid-column-end: 6;
    }
    .item-grid5 {
        grid-column-start : 5;
        grid-column-end: 6;
    }
    .item-grid8 {
        grid-column-start : 4;
        grid-column-end: 6;
    }
    .item-grid11 {
        grid-column-start : 4;
        grid-column-end: 5;
    }
    .item-grid12 {
        grid-row-start: 3;
        grid-row-end: 4;
        grid-column-start : 1;
        grid-column-end:2;
    }
    .index-number, .delete-button{
        text-align: center;
        font-size: 32px;
        vertical-align: middle;
    }
    .form--label{
        display : block;
        font-weight : 700;
        cursor : pointer;
    }
    .form--input{
        width: 100%;
        background-color: white;
        border: 1px solid #e8e8e8;
        border-radius: 5px;
        height: 3.6rem;
        line-height: 2rem;
        padding: .8rem;
        transition: .3s ease all;
    }
    .form--input:focus{
        border: .2rem solid #0077ff;
        outline: none;
        box-shadow: .2rem 0rem 2rem rgba(163, 163, 163, .5);
    }
    .stock-message{
        font-weight: bold;
        color: red;
        font-size: 1.5rem;
    }
    .isUserOfSale {
        pointer-events: none;
        background-color: #e8e8e8;
    }
</style>
