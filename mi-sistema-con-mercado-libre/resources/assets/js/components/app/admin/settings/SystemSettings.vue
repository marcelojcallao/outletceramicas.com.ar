<template>
    <div>
        <SystemCheckingAccount />
        <button class="btn btn-primary" @click="openSideBar">Abrir</button>
        <button class="btn btn-primary" @click="closeSideBar">Cerrar</button>

        <side-bar-base :open="open">
                <template v-slot:header>
                    header
                </template>

                <template v-slot:body>
                    <div>
                        pp
                    </div>
                </template>

                <template v-slot:footer>
                    <div >
                        footer
                    </div>
                </template>
            </side-bar-base>
    </div>
</template>

<script>
import SideBarBase from '../../../Base/SideBar/SideBarBase.vue';
    import SystemCheckingAccount from './systemCheckingAccount/SystemCheckingAccount.vue';
    export default {

        name: 'SystemSettings',

        components: { SystemCheckingAccount, SideBarBase },

        data() {
            return {
                open: false,
            }
        },

        methods: {

            openSideBar(){
                this.open = true;
            },

            closeSideBar(){
                this.open = false;
            }
        },

    }
</script>

<style lang="scss" scoped>


</style>
