<template>
    <form @submit.prevent="sendEmail">
        <div class="row" v-if="! loading">
            <div class="col-lg-6">
                <input class="form-control" 
                type="text" 
                name="from_name" v-model="from_name" 
                value="" size="40" aria-required="true" aria-invalid="false" placeholder="Tu nombre"
                :class="{
                    'is-invalid': submitted && $v.from_name.$error
                  }"
                >
            </div>
            <div class="col-lg-6">
                <input class="form-control" 
                type="email" 
                name="user_email" v-model="user_email" 
                value="" size="40" aria-required="true" aria-invalid="false" placeholder="Tu email"
                :class="{
                    'is-invalid': submitted && $v.user_email.$error
                  }"
                >
            </div>
            <div class="col-lg-6">
                <input class="form-control" 
                type="text" 
                name="subject" v-model="subject" 
                value="" size="40" aria-invalid="false" placeholder="Asunto"
                :class="{
                    'is-invalid': submitted && $v.subject.$error
                  }"
                >
            </div>

            <div class="col-lg-6">
                <input class="form-control" 
                type="text" 
                name="phone" v-model="phone" 
                value="" size="40" aria-required="true" aria-invalid="false" placeholder="Teléfono"
                :class="{
                    'is-invalid': submitted && $v.phone.$error
                  }"
                >
            </div>
            <div class="col-xs-12">
                <textarea class="form-control" name="message" v-model="message" cols="40" rows="4" aria-invalid="false" placeholder="Tu mensaje"
                :class="{
                    'is-invalid': submitted && $v.message.$error
                  }"
                ></textarea>
            </div>
            <div class="col-xs-12">
                <input class="btn btn-primary" 
                type="submit" 
                value="ENVIAR MENSAJE">
            </div>
        </div>
        <loading 
            class="custom-position"
            :active.sync="loading" 
            :can-cancel="false" 
            :is-full-page="false"></loading>
    </form>

</template>

<script>
import emailjs from '@emailjs/browser';
import Loading from 'vue-loading-overlay';
import { required, email } from "vuelidate/lib/validators";
export default {
  
    components : {Loading},

    data() {
        return {
            loading : false,
            submitted : false,
            from_name: '',
            user_email: '',
            phone: '',
            subject: '',
            message: ''
        }
    },

    methods: {

        sendEmail(e) {
            this.loading = true;
            this.submitted = true;

            this.$v.$touch();
            if (this.$v.$invalid) {
                this.loading = false;
                return;
            }
            emailjs.send('service_1r7dfgl', 'template_v3vsuzs', {
                from_name : this.from_name.toUpperCase(),
                user_email : this.user_email,
                message : this.message,
                phone : this.phone,
                subject : this.subject.toUpperCase(),
            }, 'wDXOeoHn7VTRmJZ0z')
            //emailjs.sendForm('service_1r7dfgl', 'template_v3vsuzs', e.target, 'wDXOeoHn7VTRmJZ0z')
            .then((result) => {
                    this.from_name = '';
                    this.user_email = '';
                    this.message = '';
                    this.phone = '';
                    this.subject = '';

                    const Toast = Vue.swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                        });

                        Toast.fire({
                            icon: 'success',
                            title: 'Mensaje enviado correctamente.'
                        });
                        
                }, (error) => {
                    console.log('FAILED...', error.text);
                }).finally(()=> {
                    this.loading = false;
                    this.submitted = false;
                })
        }
    },

    validations: {
        phone: { required },
        subject: { required },
        from_name: { required },
        message: { required },
        user_email: { required, email },
    },
}
</script>
<style scoped>
    .custom-position{
        padding : 10rem; 
        text-align: center
    }
    .is-invalid{
        background: hsla(0, 90%, 70%, 1);
    }
</style>