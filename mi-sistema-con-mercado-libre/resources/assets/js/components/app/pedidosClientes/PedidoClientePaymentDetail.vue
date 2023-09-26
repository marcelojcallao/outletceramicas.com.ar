<template>
    <div class="flex">
        <div class="item-1">
            <label >Modo de pago:</label> {{(data && data.payment_data.name) ? data.payment_data.name : ''}}
        </div>
        <div class="item-2">
            <label >{{IsDiscountText}}:</label> 
            
        </div>
        <div class="item-3">
            <p>Porcentaje: {{(data && data.payment_data.percentage) ? data.payment_data.percentage : ''}} %</p>
            
        </div>
        <div class="item-4">
            <p>Importe: {{(data && data.payment_data.value) ? data.payment_data.value : '' | currency}}</p>
        </div>
        <div class="item-5">
            <p>Estado del pedido: {{(data && data.status) ? data.status : '' }}</p>
        </div>
    </div>
</template>

<script>
export default {
    props : ['data'],
    
    computed : {

        IsDiscount(){
            return (this.data && this.data.payment_data.value >= 0) ? true : false;
        },

        IsDiscountText(){
            return (this.data && this.data.payment_data.value >= 0) ? 'Adicional' : 'Descuento';
        }
    }
 }
</script>

<style scoped>
    .flex{
        display: flex;
        justify-content : start;
        padding: 0px 1rem;
    }
    div[class*="item"]{
        font-size: 1.5rem;
        margin: 0px 2rem;
    }
</style>