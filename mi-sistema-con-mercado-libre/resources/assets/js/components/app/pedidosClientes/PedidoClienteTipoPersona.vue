<template>
    <div v-if="data.customer_inscription_id == 1 && data.customer_addresses[0].state_id == 2"
        style="margin:10px" class="pull-left">
        <button 
            class="btn btn-danger btn-xs"
            :class="{'btn btn-danger spinner spinner-inverse spinner-sm' : arba_spinner}" 
            @click="getAlicuota"
            type="button">
            Comprobar alícuota de IIBB
        </button>
        <button 
            @click="billGenerate()"
            class="btn btn-success btn-xs"
            :class="{'btn btn-primary spinner spinner-inverse spinner-sm' : spinner}" 
            :disabled="data.status_id != 10"
            type="button" style="background-color:#006400">
            Facturar como consumidor final
        </button>
        <fade-transition>
            <div v-if="HasAlicuota" class="text-left">
                <span>
                    <strong>
                        {{HasAlicuota.contribuyentes.contribuyente.alicuotaPercepcion}} %
                    </strong>
                    X
                    <strong>{{data.total_raw | currency}}</strong>
                    =
                    <strong>{{HasAlicuota.contribuyentes.contribuyente.alicuotaPercepcion * data.total_raw / 100 | currency}} adicionales</strong>
                </span>
            </div>
        </fade-transition>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import collect from 'collect.js';
    import {Event} from 'vue-tables-2';
    import auth from './../../../mixins/auth';
    import toast_mixin from './../../../mixins/toast-mixin';
    import InvoiceTransformer from './../../../src/Transformers/Afip/InvoiceTransformer';
    import FECAEDetRequestTransformer from './../../../src/Transformers/Afip/WSFE/FECAEDetRequestTransformer';
    export default {
        props : ['data'],
    }
</script>

<style lang="scss" scoped>

</style>