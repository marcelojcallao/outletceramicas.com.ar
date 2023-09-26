<script>
    import { Event } from 'vue-tables-2';
    import EditAndTrashButton from '../../../../Base/Button/EditAndTrashButton.vue';
    export default {

        extends : EditAndTrashButton,

        props: ['data', 'index'],

        methods : {
            
            goTo()
            {
                const product_id = this.data.id;
                window.location.href = "/empresa/productos/edicion/" + product_id;
            },

            async delete_product(){

                Vue.swal.fire({

                    title: 'Eliminar Producto',
                    text: `Se eliminará el producto ${this.data.name}`,
                    icon : 'question',
                    showDenyButton: true,
                    confirmButtonText: 'Aceptar',
                    denyButtonText: `No, cerrar`,

                }).then( async (result) => {

                    if (result.isConfirmed) {
                        
                        this.spinner = true;

                        const product = await this.$store.dispatch('deleteProduct', this.data.id);
                        
                        if (product) {

                            Event.$emit('delete_product_from_data_base', this.data);

                            Vue.swal.fire('El producto ha sido eliminado correctamente', '', 'success');

                        }
                    }
                    
                }).catch((error)=>{

                    Vue.swal.fire({
                        title: 'Eliminar Producto',
                        text: error.response.data.message,
                        icon : 'info',
                        confirmButtonText: 'Aceptar',
                    })
                    
                }).finally(()=> this.spinner = false);
            }
            
        },
       
    }
</script>