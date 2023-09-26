<template>
    <div>
        <div class="flex">
            <div class="item">#</div>
            <div class="item">Producto</div>
            <div class="item">Cantidad</div>
            <div class="item">P.Unit.</div>
            <div class="item">Importe</div>
            <div class="item">IVA</div>
            <div class="item">Total</div>
        </div>
        <div class="flex">
            <div class="item">{{index + 1}}</div>
                <div class="item">{{item.product_name}} <strong>{{(item.product_attributes != '') ? ' | ' + item.product_attributes : ''}}</strong></div>
                <div class="item">{{(item.isCHP) ? item.mts_to_invoiced : item.quantity}}</div>
                <div class="item">{{(item.neto_import / item.quantity) | currency}}</div>
                <div class="item">{{item.neto_import | currency}}</div>
                <div class="item">{{item.iva_import | currency}}</div>
                <div class="item">{{item.total_raw  | currency}}</div>
        </div>
    </div>
</template>

<script>
export default {
    props : ['index', 'item']
}
</script>

<style>
    .flex {
        display: flex;
        justify-content: space-between;
        
    }
</style>