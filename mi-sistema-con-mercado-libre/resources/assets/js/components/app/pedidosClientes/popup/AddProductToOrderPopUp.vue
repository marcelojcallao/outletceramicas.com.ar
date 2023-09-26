<template>
    <ul class="nav navbar-nav navbar-right">
        <li class="dropdown" :class="(open) ? 'open' : '' ">
            <a @click.prevent="open=!open"
                class="dropdown-toggle" href="#" role="button" aria-haspopup="true">
                <span v-tooltip="'Agregar productos al pedido'">
                    <span class="icon icon-envelope-o icon-lg" ></span>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg" >
                <div class="dropdown-header">
                    <!-- <a class="dropdown-link" href="compose.html">New Message</a> -->
                    <h5 class="dropdown-heading">Agregar productos al pedido</h5>
                </div>
            <div class="dropdown-body">
                <div class="custom-scrollable-area" style="position: relative; overflow: hidden; width: 100%; height: 100%;">
                    <div 
                        class="list-group list-group-divided custom-scrollbar" 
                        style="overflow-y: scroll; width: 100%; height: 100%;"
                    >
                    <div>puto</div>
                    <www />
                    </div>
                    <div 
                        class="custom-scrollbar-gripper" 
                        style="background: rgb(0, 0, 0); width: 6px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 5px;">
                    </div>
                    <div 
                        class="custom-scrollbar-track" 
                        style="width: 6px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 5px;">
                    </div>
                </div>
            </div>
            <div 
                class="dropdown-footer">
                <a @click.prevent="openDropdown" class="dropdown-btn" href="#">Ver mensajes recibidos</a>
            </div>
        </div>
        </li>
    </ul>
</template>

<script>
    import {mapGetters} from 'vuex';
    import www from "./../../admin/products/List/Search-Product-Multiselect.vue"
    import AddProductWhenOrderExists from './AddProductWhenOrderExists.vue'
    export default {
        
        name : 'AddProductToOrderPopUp',

        props : ['data'],

        components : {AddProductWhenOrderExists, www},

        data(){
            return {
                open : false
            }
        },

        computed : {

            ...mapGetters([
                
            ]),
        },

        methods : {

            openDropdown(){
            }
        }
        
    }
</script>

<style scoped>
    .dropdown-menu-lg{
        width: 600px;
    }
    .dropdown-toggle{
        padding: 7px;
        margin-right: 15px;
    }
</style>