<script>
    import PedidoClienteProductsDeleteProduct from '../../../../pedidosClientes/PedidoClienteProductsDeleteProduct.vue';
    export default {
        
        props: {
            product: {
                required: true
            }
        },

        extends: PedidoClienteProductsDeleteProduct,

        methods: {

            async remove_product(){

                this.spinner = true;

                const Toast = Vue.swal.mixin({
                    toast: true,
                    position: 'top-end',
                    timer: 4000,
                    timerProgressBar: true,
                });

                Toast.fire({
                    icon: 'success',
                    title:  `El producto ${this.product.product_name} fue eliminado del pedido exitosamente.`
                });
                
                this.OrderEditDataGetter.items.map((item, index) => {
                    
                    if (item.product_id == this.product.product_id) {
                        this.OrderEditDataGetter.items.splice(index, 1)
                    }

                });

                this.spinner = false
                    
            },

            delete_item(){

                this.text = `¿Desea eliminar ${this.product.product_name} del pedido?`;

                Vue.swal.fire({
                    title: 'ATENCIÓN',
                    text: this.text,
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Continuar',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    
                    if (result.isConfirmed) {
                        this.remove_product()
                    }
                });

            }
        }


    }
</script>
