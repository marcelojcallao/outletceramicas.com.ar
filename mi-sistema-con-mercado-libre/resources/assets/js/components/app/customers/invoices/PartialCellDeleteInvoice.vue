<template>
    <button 
        v-tooltip="(data.voucher_has_nota_credito) ? 'Éste comprobante ya ha sido anulado con una nota de crédito' : 'Anular comprobante, se generará una Nota de Crédito'"
        class="btn btn-danger btn-icon sq-32" type="button"
        :disabled="(data.voucher_id == 3 || data.voucher_id == '003' || data.voucher_id == 8 || data.voucher_id == '008' || data.voucher_id == 13 || data.voucher_id == '013' || data.voucher_id == 94 || data.voucher_id == '94' || data.voucher_id == 88 || data.voucher_id == '88' || data.voucher_has_nota_credito)"      
        :class="{'btn btn-danger spinner spinner-inverse spinner-sm' : spinner}"
        @click="generate_nota_credito"
        >
        <span class="icon icon-trash"></span>
    </button>
</template>

<script>
    export default {
        props: ['data', 'index'],

        data() {
            return {
                spinner : false,
            }
        },
        
        methods : {
            
            async generate_nota_credito(){

                this.spinner = true;

                const message = `¿Desea anular el comprobante ${this.data.voucher_number}?`;

                Vue.swal.fire({

                    title: 'Anular comprobante de venta',
                    text: message,
                    icon : 'question',
                    showDenyButton: true,
                    confirmButtonText: 'Aceptar',
                    denyButtonText: `No, cerrar`,

                }).then( async (result) => {

                    if (result.isConfirmed) {
                        
                        const response = await this.$store.dispatch('generate_nota_credito', this.data)
                                .catch((e) => {
                                    this.error_message(e.response.data.message, 'AFIP');
                                })
                                .finally(()=> this.spinner = false);
                            
                        if(response){
                            this.success_message('Comprobante registrado correctamente', 'AFIP');
                        }
                    }

                }).catch((error)=>{

                    Vue.swal.fire({

                        title: 'Anular comprobante de venta',
                        text: error.response.data.message,
                        icon : 'info',
                        confirmButtonText: 'Aceptar',

                    })

                }).finally(()=> this.spinner = false);
            }
        }
       
    }
</script>