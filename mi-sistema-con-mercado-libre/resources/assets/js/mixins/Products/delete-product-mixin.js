export default {

    methods : {

        async remove_product(){

            this.spinner = true;

            const payload = {
                pedido_id: this.PedidoListChildRowReactivityData.id,
                product_id: this.PedidoListChildRowReactivityData.items[this.index].product_id
            }

            const { data } = await this.$store.dispatch('delete_product', payload)
            .catch((err)=>{
            })
            .finally(() => {
                this.spinner = false
            })

            if (data) {

                const Toast = Vue.swal.mixin({
                    toast: true,
                    position: 'top-end',
                    timer: 4000,
                    timerProgressBar: true,
                });

                Toast.fire({
                    icon: 'success',
                    title:  `El producto ${this.PedidoListChildRowReactivityData.items[this.index].product_name} fue eliminado del pedido exitosamente.`
                });
                
                const { product_id } = data;

                this.PedidoListChildRowReactivityData.items.map((item, index) => {
                    
                    if (item.product_id == product_id) {
                        this.PedidoListChildRowReactivityData.items.splice(index, 1)
                    }
                });

                this.OrderEditDataGetter.items.map((item, index) => {
                    
                    if (item.product_id == product_id) {
                        this.OrderEditDataGetter.items.splice(index, 1)
                    }
                });
                
            }
        },
            
        delete_item(){

            if (this.PedidoListChildRowReactivityData.items.length === 1) {
                this.text = `Sólo queda un producto en el pedido, si lo elimina se borrará el pedido, ¿Desea continuar?`;
            }else{
                this.text = `¿Desea eliminar ${this.PedidoListChildRowReactivityData.items[this.index].product_name} del pedido?`;
            }
            
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
    },
}