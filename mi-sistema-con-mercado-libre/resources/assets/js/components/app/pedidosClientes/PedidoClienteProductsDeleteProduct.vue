<script>
import { mapGetters } from 'vuex';
import DeleteProduct from '../../Base/Button/DeleteRowBase.vue';
import deleteProductMixin from './../../../mixins/Products/delete-product-mixin'
export default {

    name : 'PedidoClienteProductsDeleteProduct',

    extends : DeleteProduct,

    mixins: [deleteProductMixin],

    data(){
        return {
            text: ''
        }
    },

    computed : {

        ...mapGetters([
            'PedidoListChildRowReactivityData', 'OrderEditDataGetter'
        ]),

        IsDisabled(){
            
            if (this.data.status_id == 2) {
                return false;
            }

            return true;
        }
            
    },
    
}
</script>