<template>
    <div class="row panel panel-body text-center">
        <h3>AFIP - ENTORNO DE FACTURACIÓN</h3>
        <div class="col-md-12">
            <div class="panel panel-body text-center" data-toggle="match-height" style="height: 138px;">
                <h5>SELECCIONAR ENTORNO DE FACTURACIÓN</h5>
                <EnvironmetMultiselect />
            </div>
        </div>
    </div>
</template>

<script>
import EnvironmetMultiselect from './EnvironmentMultiselect.vue'
export default {
    components : {EnvironmetMultiselect}
}
</script>

<style>

</style>