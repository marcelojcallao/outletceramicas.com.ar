<template>
    <div>
        <button class="btn btn-primary" @click="openModal">Cheques</button>

        <modal-base-use-this v-if="isOpen">
            <template v-slot:header>
                <h3 class="title">PAGAR CON CHEQUES</h3>
            </template>

            <template v-slot:body>
                <div class="body">

                </div>
            </template>

            <template v-slot:footer>
                <div class="footer">
                    <button class="btn btn-default" @click="closeModal">Salir</button>
                    <button class="btn btn-primary" @click="closeModal">Asignar</button>
                </div>
            </template>
        </modal-base-use-this>
    </div>
</template>

<script>
import ModalBaseUseThis from '../../../../Base/Modal/ModalBaseUseThis.vue';

    export default {

        name: 'OrderPaymentChequesModal',

        components: { ModalBaseUseThis },

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
    .footer button {
        margin-right: 1rem
    }
</style>
