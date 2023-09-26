<template>
    <div>
        <span  class="label" :class="Status" >{{data.status}}</span>
    </div>
</template>

<script>
    //import * as constants from './../../../src/const/Status';
    export default {
        props: ['data', 'index'],
        data() {
            return {
                
            }
        },

        methods : {
            
        },

        computed : {

            Status()
            {
                if (this.data.status == 'ELIMINADO') {
                    return "eliminado";
                }

                if (this.data.status == 'PENDIENTE') {
                    return "pendiente";
                }

                if (this.data.status == 'REMITIDO') {
                    return "remitido";
                }

                if (this.data.status == 'PRESUPUESTADO') {
                    return "presupuestado";
                }

                if (this.data.status == 'FACTURADO') {
                    return "facturado";
                }

                if (this.data.status == 'PREPARADO') {
                    return "preparado";
                }

                if (this.data.status == 'RETIRADO') {
                    return "retirado";
                }

                if (this.data.status == 'DESPACHADO') {
                    return "despachado";
                }

                if (this.data.status == 'ENTREGADO') {
                    return "entregado";
                }

                if (this.data.status == 'A PAGAR') {
                    return "entregado";
                }

                if (this.data.status == 'PAGADA') {
                    return "pagada";
                }

                if (this.data.status == 'CERRADO') {
                    return "cerrado";
                }
                if (this.data.status == 'COTIZACION') {
                    return "cotizacion";
                }

            }
        },

        mounted() {

        },
       
    }
</script>

<style scoped>
    .pendiente {
        background-color: #ff9c00;
    }
    .remitido {
        background-color: #555e7a;
    }
    .presupuestado {
        background-color: #92B735;
    }
    .remitido {
        background-color: #B576AD;
    }
    .facturado{
        background-color: #4C8A48;
    }
    .preparado{
        background-color: #7CCCE5;
    }
    .en_stock {
        background-color: #BA44E5;
    }
    .cross_over{
        background-color: #66A9C1;
    }
    .despachado{
        background-color: #F455CA;
    }
    .retirado{
        background-color: #5C7BF5;
    }
    .entregado{
        background-color: #ECBEE7;
    }
    .eliminado{
        background-color: #E91808;
    }
    .pagada{
        background-color: #4C8A48;
    }
    .cerrado{
        background-color: #2c6b2a;
    }
    .cerrado{
        background-color: #2c6b2a;
    }
    .cotizacion{
        color: #E91808;
        background-color: #edf0c2;
        font-size: 14px;
    }
</style>