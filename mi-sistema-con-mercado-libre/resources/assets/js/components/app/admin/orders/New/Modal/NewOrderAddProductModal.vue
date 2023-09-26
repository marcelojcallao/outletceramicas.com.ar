<template>
    <div class="modal fade "
        ref="product_modal"
        id="product_modal"
        tabindex="-1"
        role="dialog" aria-labelledby="myModalLabel"
        data-keyboard="false"
        data-backdrop="false"
    >

        <div class="modal-dialog" role="document">
            <div class="modal-content" id="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Producto</h4>
                </div>
                <div class="modal-body">
                    <RowProduct :index="ProductFromNewOrder.length -1"/>
                </div>
                <div class="footer">
                    <AddStockToproduct :product="NewOrderCurrentProduct" />
                    <div class="buttons">
                        <button
                            :disabled="EnabledAddProductButton"
                            type="button"
                            id="add_product_to_pedido_button"
                            class="btn btn-primary"
                            data-dismiss="modal">Agregar producto
                        </button>
                        <button
							@click="cancel"
                            type="button" class="btn btn-danger" data-dismiss="modal">Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import RowProduct from '../Body/RowProduct.vue';
import AddStockToproduct from './AddStockToProduct.vue'
export default {

    components : {RowProduct, AddStockToproduct},

    computed : {

        ...mapGetters([
            'ProductFromNewOrder',
            'EnabledAddProductButton',
            'NewOrderCurrentProduct',
            'NewOrderTotalsTotalGetter',
            'NewOrderGetTypeGetter'
        ]),

    },

    methods : {

        cancel(){

            let flag = false;

            if (this.ProductFromNewOrder.length -1 == 0) flag = true;

            this.$store.commit('NEW_ORDER_DELETE_PRODUCT', this.ProductFromNewOrder.length -1)

            this.$store.commit('NEW_ORDER_SET_CURRENT_PRODUCT', null);

            if(flag) this.$store.commit('NEW_ORDER_ADD_NEW_ROW_PRODUCT');

        },

    },

    mounted(){

        const vm = this;

        $(this.$refs.product_modal).on('shown.bs.modal', function() {

            const name = 'div.multiselect.multiselect_product';
            const multiselect = document.querySelector(name);
            multiselect.focus();
        });

        $(this.$refs.product_modal).on('hidden.bs.modal', function() {

           vm.$store.commit('NEW_ORDER_SET_CURRENT_PRODUCT', null);
        });

        window.addEventListener('keydown', (e) =>{

            if (e.ctrlKey == true && e.key == 'F9' && this.NewOrderGetTypeGetter != null) {
                $(this.$refs.product_modal).modal('show');

                if (this.ProductFromNewOrder[0].total > 0) {
                    this.$store.commit('NEW_ORDER_ADD_NEW_ROW_PRODUCT');
                }
            }

            if (e.ctrlKey == true && e.key == 'F10') {
                $(this.$refs.product_modal).modal('hide');
            }

        });

    }
}
</script>

<style scoped>
.modal {
    position: absolute;
    top: -400px;
    left: -70px;
    z-index: 100;
}
/* .modal-dialog{
    width: 100%;
} */
div.modal-header{
    background-color: rgb(144, 220, 255);
    color: black
}
.modal-open .modal  {
    overflow: hidden;
}
#modal-content {
    position: absolute;
    left: -100px;
    top: 0px;
    box-shadow : 0 50px 100px 0 rgb(0 0 0 / 0.5);
    width: 140%;
}
#b-ok:focus {
    background-color: blue;
}
#b-cancel:focus {
    background-color: crimson;
}
.footer{
    display: flex;
    flex-direction: row;
    padding-bottom: 2rem;
    padding-left: 2.5rem;
    padding-right: 2.5rem;
    justify-content: space-between;
}
</style>
