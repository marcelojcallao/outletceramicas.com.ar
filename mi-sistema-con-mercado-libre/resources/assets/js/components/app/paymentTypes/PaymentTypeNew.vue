<template>
    <div class="payment--new">
        <label for="">Nombre</label>
        <div class="col-md-6">
            <input class="form-control text-left"
                    type="text" 
                    @focus="$event.target.select()" 
                    v-model="payment.name"
                />
        </div>
        <div class="col-md-5 percentage">
            <label for="">Porcentaje</label>
            <currency-input class="form-control text-center"
                    type="text" 
                    @focus="$event.target.select()" 
                    :currency="null"
                    locale="es-AR"
                    :allow-negative="true"
                    :precision="2"
                    v-model="payment.percentage"
                />
        </div>
        <div class="col-md-1">
            <button 
                    class="btn btn-primary" 
                    type="button" 
                    :class="{'btn btn-primary spinner spinner-inverse spinner-sm' : spinner}"
                    @click="store"
                >
                    Guardar
                </button>
        </div>
    </div>
</template>

<script>
    export default {
        
        name: 'PaymentTypeNew',

        data(){
            return {
                spinner: false,
                payment: {
                    name: '',
                    percentage: 0,
                }
            }
        },

        methods: {

            async store(){

                this.spinner = true;

                await this.sleep(250);

                const { data:payment } = await this.$store.dispatch('storePaymentType', this.payment)
                .catch( ( err ) => {

                })
                .finally( () => this.spinner = false);

                if (payment) {

                    const Toast = Vue.swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    });

                    Toast.fire({
                        icon: 'success',
                        title: 'Método de pagho guardado correctamente'
                    });
                    
                    this.$store.dispatch('addPaymentToList', payment);
                    this.payment.name = '';
                    this.payment.percentage = 0;
                }
            }
        }

    }
</script>

<style lang="scss" scoped>
    .payment--new{
        display: flex;
    }
    .percentage{
        display: flex;
    }
</style>