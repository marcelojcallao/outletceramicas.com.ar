<template>
    <div>
        <DashBoardSalesToday />
    </div>
</template>

<script>
import DashBoardSalesToday from './components/DashBoardSalesToday.vue';
    export default {

        name: 'DashBoardMain',

        components: {DashBoardSalesToday},

        methods: {

            message(type, title, timer = 4000){
                
                const Toast = Vue.swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: timer,
                    timerProgressBar: true,
                });

                Toast.fire({
                    icon: type,
                    title: title
                });
            },

            pedidoCreatedListener(){

                window.Echo.private('pedidoClienteChannel').listen('PedidoClienteCreated', (pedido) => {
                    
                    if (pedido.pedido.type === 'PEDIDO CLIENTE') {

                        const type = 'info';

                        const title = `Se ha creado un nuevo pedido, Cliente: ${pedido.pedido.customer}`

                        this.message(type, title)
                    }
                })
            }
        },

        mounted(){

            this.pedidoCreatedListener();
        }

        
    }
</script>

<style lang="scss" scoped>

</style>