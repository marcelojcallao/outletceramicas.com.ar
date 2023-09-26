<template>
    <div class="col-xs-6 col-md-3">
            <div class="panel panel-body text-center" data-toggle="match-height" style="height: 130px;">
                <h5>Cuentas Corrientes</h5>
               <!--  <p>
                    <small>Simply use class <code>btn-primary</code></small>
                </p> -->
                <button class="btn btn-primary" @click="openModal">Administrar</button>
            </div>
            <modal-base-use-this v-if="isOpen">
                <template v-slot:header>
                    <h3 class="title">CUENTAS CORRIENTES</h3>
                </template>

                <template v-slot:body>
                    <div class="body">
                        <SystemCheckingForm />
                        <SystemCheckingAccountTable />
                    </div>
                </template>

                <template v-slot:footer>
                    <div class="footer">
                        <button class="btn btn-primary" @click="closeModal">Salir</button>
                    </div>
                </template>
            </modal-base-use-this> 
           
    </div>
</template>

<script>
import ModalBaseUseThis from '../../../../Base/Modal/ModalBaseUseThis.vue';
import SystemCheckingAccountTable from './SystemCheckingAccountTable.vue';
import SystemCheckingForm from './SystemCheckingForm.vue';
    export default {

        name: 'SystemCheckingAccount',

        components: { ModalBaseUseThis, SystemCheckingForm, SystemCheckingAccountTable },
        
        data() {
            return {
                isOpen: false
            }
        },

        methods: {

            openModal(){
                this.isOpen = true
            },

            closeModal(){
                this.isOpen = false
            }
        }
    }
</script>

<style lang="scss" scoped>
    .title{
        margin-top: 2remSS;
        text-align: center;
        height: 3rem;
    }
    .body {
        position: absolute;
        margin: 3rem;
        top: 10%;
        height: 70%;
        width: 95%;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 2rem;
    }
    .footer{
        position: absolute;
        display: flex;
        justify-content: center;
        top: 85%;
        width: 95%;
        margin: 3rem;
    }
</style>