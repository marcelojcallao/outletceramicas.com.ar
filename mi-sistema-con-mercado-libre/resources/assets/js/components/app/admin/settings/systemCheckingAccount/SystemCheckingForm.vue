<template>
    <div class="form-container">
        <div class="multi">
            <label for="">Banco</label>
            <SystemCheckingBank name="Bancos" />
        </div>
        <div>
            <label for="">Cuenta Corriente</label>
            <input 
                class="form-control"
                type="text"
                v-model="code"
                >
        </div>
        <div>
            <button class="btn btn-primary" 
            @click="save"
            :disabled="$v.$invalid"
            > Aceptar</button>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import { required } from 'vuelidate/lib/validators';
    import SystemCheckingBank from './SystemCheckingBank.vue';
    export default {
        
        name: 'SystemCheckingForm',

        components: { SystemCheckingBank },

        data() {
            return {
                spinner: false
            }
        },

        computed: {

            ...mapGetters(['SystemCheckingAccountBank', 'SystemCheckingAccountCode', 'BankGetter', 'SystemCheckingAccountCode']),

            code: {
                get(){
                    return this.SystemCheckingAccountCode;
                },
                set(value){
                    this.$store.dispatch('systemCheckingAccountSetCode', value);
                }
            }
        },

        methods: {

            async save(){

                this.spinner = true;

                await this.sleep(250);

                const { data:checkAccount } = await this.$store.dispatch('checkingAccountStore', { bank: this.BankGetter, code: this.SystemCheckingAccountCode });

                this.$store.dispatch('systemCheckingAccountSetList', checkAccount);
            }
        },

        watch: {

            BankGetter(n){
                this.$store.dispatch('systemCheckingAccountSetBank', n);
            }
        },

        validations(){
            return {
                SystemCheckingAccountBank: { required },
                SystemCheckingAccountCode: { required },
            }
        }
    }
</script>

<style lang="scss" scoped>
    .form-container{
        display: flex;
        justify-content: space-between;
    }
    .form-container div:nth-child(1){
        width: 60%;
    }
    .form-container div:nth-child(2){
        width: 20%;
        padding-top: 1rem;
        padding-bottom: 2rem;
    }
    .form-container div:nth-child(3){
        width: 10%;
        padding-top: 3.5rem;
    }
    div input {
        height: 40px;
    }
</style>