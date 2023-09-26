<template>
    <div class="action">
        <button style="margin-right: 5px"
            @click="print"
            v-tooltip="'Imprimir comprobante de Venta'"
            class="btn btn-default btn-icon sq-32" type="button"
            :class="{'btn btn-default spinner spinner-inverse spinner-sm' : print_spinner}"
            >
            <span class="icon icon-print"></span>
        </button>
        <PartialCellDeleteInvoice :data="data"/>
    </div>
</template>

<script>
import PartialCellDeleteInvoice  from './PartialCellDeleteInvoice';
import PrinterInvoicePdf from './../../../../src/Pdf/PrinterInvoicePdf';
    export default {
        props: ['data', 'index'],
        components : {
            PartialCellDeleteInvoice
        },
        data() {
            return {
                token : null,
                print_spinner : false,
            }
        },

        methods : {
            
            print(){
                this.print_spinner = true;

                setTimeout(() => {

                    PrinterInvoicePdf.print(this.data, 'ORIGINAL', 'DUPLICADO');
                    console.log("🚀 ~ file: PartialCellPrintInvoice.vue:38 ~ setTimeout ~ this.data:", this.data)

                    this.print_spinner = false;

                }, 750);
            }
        }
       
    }
</script>
<style scoped>
    .action{
        display: flex;
        flex-direction: row;
    }
</style>