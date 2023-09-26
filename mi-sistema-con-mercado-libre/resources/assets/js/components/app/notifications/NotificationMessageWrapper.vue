<template>
    <ul class="nav navbar-nav navbar-right">
        <li class="visible-xs-block">
            <h4 class="navbar-text text-center">Hi, Teddy Wilson</h4>
        </li>
        <li class="dropdown" >
            <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true">
                <span class="icon-with-child hidden-xs" v-tooltip="'Notificaciones de mensajes posventa'">
                <span class="icon icon-envelope-o icon-lg" ></span>
                <span class="badge badge-primary badge-above right">{{CountReceivedMessages}}</span>
                </span>
                <span class="visible-xs-block">
                <span class="icon icon-envelope icon-lg icon-fw"></span>
                <span class="badge badge-primary pull-right">{{CountReceivedMessages}}</span>
                Messages
                </span>
            </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg" >
            <div class="dropdown-header">
                <h5 class="dropdown-heading">Últimos mensajes</h5>
            </div>
            <div class="dropdown-body">
                <div class="custom-scrollable-area" style="position: relative; overflow: hidden; width: 100%; height: 100%;">
                    <div 
                        class="list-group list-group-divided custom-scrollbar" 
                        style="overflow-y: scroll; width: 100%; height: 100%;"
                         >
                        
                        <Notification v-for="(message, index) in ReceivedMessages" :key="index" 
                        :notification="message" />

                </div><div class="custom-scrollbar-gripper" style="background: rgb(0, 0, 0); width: 6px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 5px;"></div><div class="custom-scrollbar-track" style="width: 6px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 5px;"></div></div>
            </div>
            <div class="dropdown-footer">
                <a class="dropdown-btn" href="#">Ver mensajes recibidos</a>
            </div>
        </div>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true">
                <span class="icon-with-child hidden-xs" v-tooltip="'Notificaciones de órdenes de compra'">
                    <span class="icon icon-bell-o icon-lg"></span>
                    <span class="badge badge-danger badge-above right">{{NewOrderNotificationGetterCount}}</span>
                </span>
                    <span class="visible-xs-block">
                    <span class="icon icon-bell icon-lg icon-fw"></span>
                <span class="badge badge-danger pull-right">{{NewOrderNotificationGetterCount}}</span>
                Notifications
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg">
                <div class="dropdown-header">
                <h5 class="dropdown-heading">Pedidos recientes</h5>
                </div>
                <div class="dropdown-body">
                    <div class="custom-scrollable-area" style="position: relative; overflow: hidden; width: 100%; height: 100%;">
                        <div class="list-group list-group-divided custom-scrollbar" 
                        style="overflow-y: scroll; width: 100%; height: 100%;">
                            <NotificationOrder v-for="(order, index) in NewOrderNotificationGetter" :key="index" 
                            :notification_order="order" />
                    </div><div class="custom-scrollbar-gripper" style="background: rgb(0, 0, 0); width: 6px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 5px;"></div><div class="custom-scrollbar-track" style="width: 6px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 5px;"></div></div>
                </div>
                <div class="dropdown-footer">
                    <a class="dropdown-btn" href="#">Ver Todas</a>
                </div>
            </div>
        </li> 
        <li class="dropdown hidden-xs">
        <button class="navbar-account-btn" data-toggle="dropdown" aria-haspopup="true">
            <img class="circle" width="36" height="36" src="/images/default-user.png" alt="Asaliah">
            {{UserName}}
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right">
            <li class="divider"></li>
            <li><a @click.prevent="logOut" href="#">Cerrar sesión</a></li>
        </ul>
        </li>
    </ul>
</template>

<script>
import {mapGetters} from 'vuex';
import Notification from './Notification';
import NotificationOrder from './NotificationOrder';
import auth from './../../../mixins/auth';
import toast_mixin from './../../../mixins/toast-mixin';
    export default {
        
        mixins : [auth, toast_mixin],

        components : {
            Notification, NotificationOrder
        },

        computed : {

            ...mapGetters([
                'CountReceivedMessages',
                'ReceivedMessages',
                'NewOrderNotificationGetter',
                'NewOrderNotificationGetterCount'
            ]),

            UserName()
            {
                return this.User.name;
            }

        },

        methods : {

            async logOut(){
                await this.logOutUser();
            }
        },


        beforeMount()
        {
            /* window.Echo.channel('hook-message-channel')
            .listen('.Web-Hook-Message-Event', (message) => {
                console.log('########################');
                console.log(message);
                console.log('########################');
                if(message.message.from.user_id != "17220993")
                {
                    this.$store.commit('ADD_RECEIVE_MESSAGE', message)
                    this.info_message(`${message.message.text.plain}`, `Nuevo mensaje de ${message.message.from.name}`, 4000, 'bottomCenter');
                }
            });
            
            window.Echo.channel('hook-order-channel')
            .listen('.Web-Hook-Order-Event', (order) => {
                this.success_message('Se ha registrado una nueva Orden de compra', 'Órdenes de compra', 4000, 'bottomCenter');
                this.$store.commit('SET_NEW_ORDER_NOTIFICATION', order.order);
            }); */

        },
    }
</script>

<style scoped>

</style>