<template >
    <OrderWrapper
    >
        <template #card_header>
            <div class="reference">
                <small>
                    <p>Para ingresar un nuevo cliente.</p>
                    <button class="btn btn-primary btn-xs" @click="showPanel">Ingresar Cliente</button>
                    <slideout-panel>
                    </slideout-panel>
                </small>
                <!-- <small>
                    <strong style="color:green">Ctrl + F8</strong>
                    <p>Modificar domicilio de entrega.</p>

                </small> -->
                <small>
                    <strong style="color:green">Ctrl + F9</strong>
                    <p>Para ingresar un producto a la lista.</p>
                </small>
                <!-- <small>
                    <button class="btn btn-primary btn-xs" @click="showPanelAddDeliveryAdrress">Ingresar domicilio de entrega</button>
                </small> -->
            </div>
            <Header />
        </template>
        <template #card_body>
            <div class="body-header-container">
                <div class="item-1">
                    <label >Modalidad de pago:</label>
                </div>
                <div class="item-2">
                    <PaymentMultiselect />
                </div>
                <div class="item-3">
                    <!-- <checkbox_shipping /> -->
                </div>
                <div class="item-4">
                    <date_delivery title="Fecha de Entrega" />
                </div>
            </div>
            <Body />
        </template>
        <template #card_footer>
            <NewOrderAddProductModal />
            <NewOrderAddressModal />
            <Footer />
            <div class="text-center">
                <button
					id="save_pedido_button"
                    @click="save_order"
                    :disabled="(!status || Object.keys(CustomerValue).length === 0 || NewOrderGetTypeGetter == null)"
                    class="btn btn-primary">Guardar Pedido</button>
            </div>
            <BlockUI :message="msg" v-if="loading"></BlockUI>
        </template>
    </OrderWrapper>
</template>
<script>
import add_customer_modal from './../../../../../components/app/customers/new/add-customer-panel.vue'
    import Body from './Body/Body';
    import {mapGetters} from 'vuex';
    import Header from './Header/Header';
    import Footer from './Footer/Footer';
    import OrderWrapper from './../Base/OrderWrapper';
    import checkbox_shipping from './Header/checkbox-shipping'
    import CustomerFunctional from './Header/customer-functional';
    import date_delivery from './Header/date-picker-delivery.vue';
    import PaymentMultiselect from './Header/PaymentTypeMultiselect';
    import NewOrderAddProductModal from './Modal/NewOrderAddProductModal';
    import NewOrderAddCustomerModal from './Modal/NewOrderAddCustomerModal';
    import NewOrderAddressModal from './Modal/NewOrderAddressModal';
    import PanelAddDeliveryAdrress from '../../../pedidosClientes/panel/PanelAddDeliveryAdrress'
    export default {

        components : {
            OrderWrapper,
            Header,
            Body,
            Footer,
            date_delivery,
            NewOrderAddProductModal,
            NewOrderAddCustomerModal,
            CustomerFunctional,
            PaymentMultiselect,
            checkbox_shipping,
            NewOrderAddressModal,
            add_customer_modal,
            PanelAddDeliveryAdrress
        },

        data(){
            return {
                status : false,
                msg : 'Guardando pedido',
                loading  : false
            }
        },

        methods : {

            showPanel() {
                const panel1Handle = this.$showPanel({
                    component : NewOrderAddCustomerModal,
                    openOn: 'right',
                    props: {
                    }
                })

                panel1Handle.promise
                .then(result => {

                });
            },

            showPanelAddDeliveryAdrress() {
                const panel1Handle = this.$showPanel({
                    component : PanelAddDeliveryAdrress,
                    openOn: 'right',
                    height: '500px',
                    props: {
                        //any data you want passed to your component
                    }
                })

                panel1Handle.promise
                .then(result => {

                });
            },

            addNewRowProduct(){
                this.$store.commit('NEW_ORDER_ADD_NEW_ROW_PRODUCT');
            },

            async save_order(){

                const { address } = this.NewCustomerCompleteData;

                const city = {
                    name: address.city,
                    cp: address.cp
                }

                this.$store.commit('NEW_ORDER_ADDRESS_SET_STATE', address.state);
                this.$store.commit('NEW_ORDER_ADDRESS_SET_CITY', city);
                this.$store.commit('NEW_ORDER_ADDRESS_SET_STREET', address.address);
                this.$store.commit('NEW_ORDER_ADDRESS_SET_OBS', address.obs);
                this.$store.commit('NEW_ORDER_ADDRESS_SET_BETWEEN_STREETS', address.between);

                this.loading = true;

                this.sleep(250);

                const order = await this.$store.dispatch('new_order_store', this.NewOrderDataGetter)
                    .catch(e => {

                        Vue.swal.fire({
                            title: 'Pedidos Clientes',
                            text : 'Ha ocurrido un error al generar el pedido',
                            icon : 'error',
                            width : '35%',
                            padding : '2rem',
                            backdrop : true,
                            time : 2000
                        });
                        this.$store.commit('SET_ORDER_ERRORS', e.response.data.errors);
                    })
                    .finally(() => {
                        this.loading = false;
                        //Event.$emit('save_order', true);
                    })

                if (order) {
                    this.success_message('Se guardó correctamente', 'Pedidos');
                    this.$store.commit('NEW_ORDER_SET_INITIAL_STATUS');
                    this.$store.commit('NEW_ORDER_SET_PRODUCTS_ON_MULTISELECT_PRODUCTS', []);

                }
            },

            isEmpty(obj){

                for (let key in obj) {
                    if (obj.hasOwnProperty(key)) {
                        return false;
                    }

                    return true;
                }
            }

        },

        watch : {

            NewOrderDataGetter : {
                // This will let Vue know to look inside the array
                deep: true,
                // We have to move our method to a handler field
                handler(n){
                    if (n.total > 0 && n.date != null && n.delivery_date != null ) {
                        this.status = true;
                    }else{
                        this.status = false;
                    }
                }
            }
        },

        computed : {

            ...mapGetters([
                'NewOrderDataGetter',
                'ProductFromNewOrder',
                'CustomerValue',
                'NewOrderGetTypeGetter',
                'NewCustomerCompleteData'
            ]),

        },

        async mounted(){
            this.$store.dispatch('fetchIvas');
            const customer_multiselect = document.getElementsByClassName('diego');
            const name = 'div.multiselect.diego';
            const multiselect = document.querySelector(name);
            multiselect.focus();

            //this.$store.dispatch('getPaymentTypes');
        }
    }
</script>
<style scoped>
    .body-header-container{
        display: flex;
        padding: 1rem;
        justify-content : space-between;
    }
    .button-right {
        margin-left: auto;
    }
    .reference{
        display: flex;
        flex-wrap : wrap;
    }
    small{
        display: flex;
        flex-grow : 1;
    }
    small p{
        padding :0 1rem;
    }
    .item-2{
        width: 250px;
    }
    .item-3{
        width : 200px;
    }

</style>
