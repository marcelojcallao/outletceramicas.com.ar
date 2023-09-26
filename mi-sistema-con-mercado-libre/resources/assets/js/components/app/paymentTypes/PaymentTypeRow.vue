<template>
    <tr>
        <td style="width: 5%" class="text-right"> {{ index + 1 }}</td>
        <td style="width: 45%" class="text-left" >

            <span v-if="isSetting">
                <input class="form-control text-left"
                    type="text" 
                    @focus="$event.target.select()" 
                    v-model="payment_edit.name"
                />
            </span>
            <span v-else>
            {{ payment.name }}
            </span>

        </td>
        <td style="width: 10%" class="text-center">
            
            <span v-if="isSetting">
                <currency-input class="form-control text-center"
                    type="text" 
                    @focus="$event.target.select()" 
                    :currency="null"
                    locale="es-AR"
                    :allow-negative="true"
                    :precision="2"
                    v-model="payment_edit.percentage"
                />
            </span>
            <span v-else>
            {{ payment.percentage }}
            </span>
            
        </td>
        <td style="width: 20%" class="text-center">
            <p :class="(Status) ? 'label label-success' : 'label label-danger'">{{ StatusName }}</p>
        </td>
        <td style="width: 20%" class="text-center">
            <div v-if="! isSetting">
                <button class="btn btn-outline-default btn-icon sq-32" type="button"
                    v-tooltip="'Editar modo de pago'"
                    @click="editPayment"
                >
                    <span class="icon icon-edit"></span>
                </button>
                <button 
                    class="btn btn-icon sq-32" 
                    type="button" :class="( Status ) ? 'btn-outline-success ': 'btn-outline-danger'"
                    v-tooltip="( Status ) ? 'Deshabilitar': 'Habilitar'"
                    @click="enablePaymentType"
                >
                    <span :class="( Status ) ? 'icon icon-check': 'icon icon-circle' "></span>
                </button>
               <!--  <button class="btn btn-outline-danger btn-icon sq-32" type="button"
                    @click="deletePaymentType"
                    v-tooltip="'Eliminar método de pago'"
                >
                    <span class="icon icon-trash"></span>
                </button> -->
            </div>
            <div v-else>
                <button class="btn btn-primary btn-xs" type="button"
                    :class="{'btn btn-primary spinner spinner-inverse spinner-sm' : spinner_update}"
                    @click="update"
                >Guardar</button>
                <button class="btn btn-default btn-xs" type="button"
                    @click="cancelEdit"
                >Cancelar</button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {
        
        name: 'PaymentTypeRow',

        props: {
            payment: {
                required: true
            },

            index: {
                type: Number
            }
        },

        data(){
            return {
                spinner_update: false,
                spinner_delete: false,
                spinner_enable: false,
                isSetting: false,
                payment_edit: {
                    id: '',
                    name: '',
                    percentage: '',
                }
            }
        },

        methods: {

            editPayment(){

                this.isSetting = true;
                this.payment_edit.id = this.payment.id;
                this.payment_edit.name = this.payment.name;
                this.payment_edit.percentage = this.payment.percentage;
            },

            cancelEdit(){
                this.isSetting = false;
            },

            async update(){

                const text = `¿Desea actualizar el método de pago ${this.payment.name}?`;

                const flag = await this.openDialog(text);

                if (flag) {
                    
                    this.spinner_update = true;

                    await this.sleep(250);

                    const { data } = await this.$store.dispatch('updatePaymentType', this.payment_edit )
                        .catch((err) => {

                            let text = 'Error al actualizar método de pago';

                            if (err.response.data.message == 'Ya existe un método de pago con éste nombre') {
                                text = err.response.data.message;
                            }
                            
                            Vue.swal.fire({
                                title: 'ATENCIÓN',
                                text: text ,
                                icon: 'error',
                            });
                            
                        })
                        .finally( () => {
                            this.spinner_update = false;
                            this.isSetting = false;
                        } )
                    
                    if ( data ) {
                        
                        const text = 'Método de pago actualizado correctamente';

                        this.Toast(text);

                        this.payment.name = data.name;
                        this.payment.percentage = data.percentage;
                    }
                }
            },

            Toast(text){

                const Toast = Vue.swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                });

                Toast.fire({
                    icon: 'success',
                    title: text
                });
            },


            async openDialog(text){

                const result = await Vue.swal.fire({
                    title: 'ATENCIÓN',
                    text: text,
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Continuar',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar'
                });

                return result.isConfirmed

            },

            async enablePaymentType(){

                const enable = (this.Status) ? 'deshabilitar' : 'habilitar';

                const text = `¿Desea ${enable} el método de pago ${this.payment.name}?`;

                const flag = await this.openDialog(text);

                if (flag) {
                    
                    this.spinner_enable = true;

                    this.sleep(250);

                    const status_id = ( this.payment.status_id == 1) ? 0 : 1;

                    const { data } = await this.$store.dispatch('enablePaymentType', { id: this.payment.id, status_id });

                    if (data) {
                        
                        const text = `Método de pago actualizado`;

                        this.Toast(text);

                        this.payment.status_id = data.status_id;
                    }

                }
            },
            
            async deletePaymentType(){

                const text = `¿Desea eliminar el método de pago ${this.payment.name}?`;

                const flag = await this.openDialog(text);

                if (flag) {
                    
                    this.spinner_enable = true;

                    this.sleep(250);

                    const status_id = ( this.payment.status_id == 1) ? 0 : 1;

                    const { data } = await this.$store.dispatch('deletePaymentType', { id: this.payment.id });

                    if (data) {
                        
                        const text = `Método de pago eliminado`;

                        this.Toast(text);

                        this.$store.dispatch('removePaymentType', this.payment.id);
                    }

                }
            }
        },

        computed: {

            Status(){

                if (this.payment.status_id == 1) {
                    return true;
                }

                return false;
            },

            StatusName(){

                if (this.payment.status_id == 1) {
                    return 'Activo';
                }

                return 'Deshabilitado';
            }
        },
      
    }
</script>

<style lang="scss" scoped>
    
</style>