<template>
<div>
    <loading
            :active.sync="loading"
            color="#cccccc"
            :can-cancel="false"
            :is-full-page="false">
        </loading>
    <div class="row" id="pedido-list-child-row">
        <div class="col-md-12 pull-right">
            <div class="card">
                <div class="card-header" >
                    <PedidoClienteDataCustomerAndDeliveryData :data="Data" :key="componentKey+1" />
                </div>
                <div class="card-body">
                    <div class="col-md-12" v-if="data.delivery_addresses">
                        <div class="form form-inline">
                            <span class="control-label"><strong>Cambiar fecha de entrega</strong></span>
                            <div class="form-group">
                                <datepicker
                                    :ref="'id_'+data.id"
                                    :language="es"
                                    :value="date"
                                    input-class="my-input"
                                    :format="customFormatter"
                                    :disabled-dates="DisabledDates"
                                    v-model="date"
                                    @selected="update_delivery_date"
                                ></datepicker>
                            </div>
                            <button
                                @click.prevent="update_date"
                                class="btn btn-primary btn-xs"
                                type="button"
                                :class="{'btn btn-primary spinner spinner-inverse spinner-sm' : spinner_date}"
                                >Actualizar
                            </button>
                        </div>
                    </div>
                </div>
                    <PaymentDetail :data="PedidoListChildRowReactivityData" :key="componentKey+2" v-if="PedidoListChildRowReactivityData"/>
                    <ProductsDetail :data="PedidoListChildRowReactivityData" :key="componentKey+3" v-if="PedidoListChildRowReactivityData"/>
            </div>
        </div>
    </div>
    <div>
        <div class="card" v-if="GetPedidoCliente.status != 17 && data.type == 'PEDIDO CLIENTE'"
            :aria-expanded="(card_comments)?'true':'false'"
            :class="{'card-collapse': card_comments==false}">
            <div class="card-header verde">
                <div class="card-actions">
                        <button type="button"
                            class="btn verde btn-xs"
                            @click="card_comments=!card_comments"
                            :title="(card_comments)?'Cerrar':'Abrir'"
                            :aria-expanded="(card_comments)?'true':'false'">
                            <strong>{{(card_comments)?'Cerrar Panel':'Abrir Panel'}}</strong>
                            </button>

                    </div>
                <div class="col-md-4">
                    <strong class="white-text">Comentarios / Recordatorios del Pedido</strong>
                </div>
            </div>

            <div class="card-body" v-if="HasComment" :style="{'display':(card_comments)?'block':'none'}">
                <table class="table table-bordered" id="table--comments">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Comentario / Observación</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
					<tbody>
						<tr v-for="(comment, index) in PedidoClienteGetComments" :key="index" >
							<td>{{index + 1}}</td>
							<td>{{comment.description}}</td>
							<td>{{comment.date}}</td>
							<td>{{comment.name}}</td>
						</tr>
					</tbody>
                </table>
            </div>

            <div class="card-footer" :style="{'display':(card_comments)?'block':'none'}">
                <div class="post-comment-box col-md-10">
                        <div action="#">
                            <input
                                v-model="comment"
                                class="form-control input-sm"
                                type="text"
                                placeholder="Agregar comentario...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button
                            v-tooltip="'Agregar comentario'"
                            @click="save_comment"
                            class="btn btn-warning btn-sm"
                            :disabled="comment == null || PedidoListChildRowReactivityData.status_id > 3"
                            :class="{'btn btn-warning spinner spinner-inverse spinner-sm' : comment_spinner}"
                            type="button">
                            Agregar comentario
                        </button>
                    </div>
            </div>
        </div>
    <!--  <PedidoMessagePanel :data="Data" :key="componentKey"/> -->
    </div>
</div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import collect from 'collect.js';
    import {Event} from 'vue-tables-2';
    import auth from './../../../mixins/auth';
    import Multiselect from 'vue-multiselect';
    import Datepicker from 'vuejs-datepicker';
    import {es} from 'vuejs-datepicker/dist/locale';
    import sleep from './../../../mixins/sleep-mixin';
    import { CollapseTransition } from 'vue2-transitions'
    import PedidoMessagePanel from './PedidoMessagePanel';
    import toast_mixin from './../../../mixins/toast-mixin';
    import PaymentDetail from './PedidoClientePaymentDetail';
    import Payment from './../../../src/MercadoLibre/Payment';
    import ProductsDetail from './PedidoClienteProductsDetail';
    import PedidoClienteTipoPersona from './PedidoClienteTipoPersona'
    import PedidoClienteDataCustomerAndDeliveryData from './PedidoClienteDataCustomerAndDeliveryData';
    import QuestionWrapper from './../mercadolibre/questions/QuestionWrapper';
    import CustomerSearchAfipData from './../../app/customers/CustomerSearchAfipData';
    import Loading from 'vue-loading-overlay';
    import Billing_info from '../mercadolibre/orders/billing_info.vue';

    export default {

        name : 'PedidoListChildRow',

        props: ['data'],

        components : {PedidoClienteDataCustomerAndDeliveryData, Datepicker, CustomerSearchAfipData,
            PedidoClienteTipoPersona, Multiselect, QuestionWrapper, CollapseTransition, PedidoMessagePanel,
                Billing_info, ProductsDetail, PaymentDetail, Loading
        },

        mixins : [auth, toast_mixin, sleep],

        data() {
            return {
                loading : true,
                provider_data : false,
                card_comments : true,
                token : null,
                spinner : false,
                fisrt_contact_spinner : false,
                comment_spinner : false,
                spinner_date : false,
                comment : null,
                es : es,
                print_comment : [],
                //date : new Date((this.data.date == 'Falta fecha') ? this.$moment() : this.$moment(this.data.date).format("DD-MM-YYYY")),
                //date : new Date(),
                date : null,
                first_contact : null,
                fiscal_address : null,
                delivery_address : null,
                save_fiscal_address_spinner : false,
                save_delivery_address_spinner : false,
                componentKey : 0
            }
        },

        methods : {

            open_customer_edit_modal()
            {
                let type = {
                    id : this.data.customer_type_id,
                    name : this.data.customer_type,
                }
                this.$store.commit('SET_CUSTOMER_TYPE', type);

                let accounting_account_child = {
                    id : (this.data.customer_type_id == 1) ? 173 : 174,
                    name : (this.data.customer_type_id == 1) ? 'CLIENTES MINORISTAS' : 'CLIENTES MAYORISTAS'
                }
                this.$store.commit('SET_CUSTOMER_SUBLEVEL_ACCOUNTING_ACCOUNT', accounting_account_child);

                this.$store.commit('SET_CUSTOMER_NAME', this.data.customer);
                this.$store.commit('SET_CUSTOMER_ID', this.data.customer_id);
                this.$store.commit('SET_CUSTOMER_CONTACT_EMAIL', this.data.email);

                let inscription = {
                    id : this.data.customer_inscription_id,
                    name : this.data.customer_inscription_name,
                }
                this.$store.commit('SET_CUSTOMER_INSCRIPTION', inscription);

                let purchase_document = {
                    id : this.data.customer_DocTipo_id,
                    name : this.data.customer_DocTipo,
                }

                let accounting_accounts_child = [
                    {
                        id : 173,
                        name : 'CLIENTES MINORISTAS'
                    },
                    {
                        id : 174,
                        name : 'CLIENTES MAYORISTAS'
                    }
                ];

                this.$store.commit('SET_ACCOUNTING_ACCOUNTS', accounting_accounts_child);

                this.$store.commit('SET_CUSTOMER_PURCHASE_DOCUMENT', purchase_document);

                this.$store.commit('SET_CUSTOMER_CONTACT_NAME', this.data.customer_contact);

                this.$store.commit('SET_CUSTOMER_CONTACT_CELLPHONE', this.data.customer_cellphone);

                this.$store.commit('SET_CUSTOMER_CONTACT_PHONE1', this.data.customer_phone1);

                this.$store.commit('SET_CUSTOMER_CONTACT_PHONE2', this.data.customer_phone2);

                this.$store.commit('SET_CUSTOMER_CONTACT_PHONE3', this.data.customer_phone3);

                this.$store.commit('SET_CUSTOMER_NUMBER', this.data.customer_document_number);

                if (this.data.customer_addresses) {

                    this.data.customer_addresses.forEach((address, index) => {

                        if (index > 0) {
                            this.sleep(150);
                            this.$store.commit('ADD_NEW_ADDRESS');
                            this.sleep(150);
                        }

                        const type = {
                            id : address.type_id,
                            name : address.type,
                        }
                        this.$store.commit('SET_CUSTOMER_ADDRESS_TYPE', {index : index, data : type});

                        const state = {
                            id : address.state_id,
                            name : address.state,
                        }
                        this.$store.commit('SET_CUSTOMER_ADDRESS_PROVINCE', {index : index, data : state});

                        const city = {
                            id : address.city_id,
                            name : address.city,
                        }
                        this.$store.commit('SET_CUSTOMER_ADDRESS_CITY', {index : index, data : city});

                        this.$store.commit('SET_CUSTOMER_ADDRESS_CP', {index : index, data : address.cp});

                        this.$store.commit('SET_CUSTOMER_ADDRESS_NUMBER', {index : index, data : address.number});

                        this.$store.commit('SET_CUSTOMER_ADDRESS_BETWEEN', {index : index, data : address.between_streets});

                        this.$store.commit('SET_CUSTOMER_ADDRESS_OBS', {index : index, data : address.obs});

                        this.$store.commit('SET_CUSTOMER_ADDRESS_ID', {index : index, data : address.id});

                        this.$store.commit('SET_CUSTOMER_ADDRESS_ADDRESS', {index : index, data : address.domicilio});

                    });
                }

                Event.$emit('open_customer_edit_modal');
            },

            add_comment_to_print_comment($e)
            {
                if ($e.target.checked) {
                    this.print_comment.push($e.target.value);
                    this.$store.commit('SET_PRINT_COMMENT', this.print_comment);
                }else{
                    const index = this.print_comment.indexOf($e.target.value);
                    this.print_comment.splice(index, 1);
                    this.$store.commit('SET_PRINT_COMMENT', this.print_comment);
                }
            },

            update_delivery_date(value)
            {
                const date = this.$moment(value).format("YYYY-MM-DD");
                const seven_days = this.$moment().add(7, 'days');

                if (this.$moment().add(7, 'days').diff(this.$moment(value), 'days') >= 0) {
                    this.info_message('Fecha de entrega', 'La entrega se realizará antes de 7 días')
                }

                this.$store.commit('UPDATE_DELIVERY_DATE', date);

            },

            status(value)
            {
                let payment = new Payment;

                return payment.status(value)
            },

            status_color(value)
            {
                const payment = new Payment;
                return payment.status_color(value);
            },

            payment_type(value)
            {
                let payment = new Payment;

                return payment.payment_type(value)
            },

            async update_date()
            {
                this.spinner_date = true;

                let payload = {
                    token : this.User.token,
                    pedido_id : this.data.id,
                    date : this.$moment(this.date).format("YYYY-MM-DD")
                }

                let date = await this.$store.dispatch('update_delivery_date', payload);
                if (date) {
                    Event.$emit('pedido_cliente_update_delivery_date', date);

                    this.success_message('Fecha de entrega actualizada correctamente', 'Pedidos');
                }
                this.spinner_date = false;

            },

            cancel_pedido()
            {
                this.$store.commit('CLEAR_PEDIDO');

                this.error_message('Pedido cancelado', 'Pedidos');
            },

            async status_update()
            {
                this.fisrt_contact_spinner = true;
                let payload = {
                    id : this.data.id,
                    code : this.data.code,
                    status : this.data.status_id
                }

                this.$store.commit('SET_PEDIDO_CODE', payload);

                this.$store.commit('SET_FIRST_CONTACT', this.first_contact);

                this.sleep(1500);

                let pedido = await this.$store.dispatch('status_update', this.User.token);

                if (pedido) {

                    this.success_message('Estado actualizado', 'Pedidos');

                }

                this.fisrt_contact_spinner = false;
            },

            async save_comment()
            {
                this.spinner = true;

                const payload = {
                    token : this.User.token,
                    pedido_id : this.data.id,
                    comment : this.comment
                }

                const comment = await this.$store.dispatch('save_comment', payload)
                .catch(err => {
                    this.error_message('Se ha producido un error, vuelva a intentarlo.', 'Pedidos');
                }).finally(()=>{
                    this.spinner = false;
                    this.comment = null;
                });

                if (comment) {
                    this.success_message('Comentario se agregó correctamente.', 'Pedidos');
                    const comments = {
                        id : this.data.id,
                        comments : comment.data
                    }
                    this.print_comment.push(this.comment);

					this.$store.commit('SET_PEDIDO_LIST_CHILD_ROW_REACTIVITY_DATA_ADD_COMMENTS', comment.data);

                    Event.$emit('pedido_cliente_list_update_comments', comments);
                }

            },
            customFormatter(date){
                return this.$moment(date).format("dddd D [de] MMMM [de] YYYY");
            },

            delivery_date(value){

                const date = this.$moment(value).format("YYYYMMDD");

                this.$store.commit('SET_DELIVERY_DATE', date);
            },

            async save_fiscal_address(element)
            {
                this.save_fiscal_address_spinner = true;

                const payload = {
                    token : this.User.token,
                    address : element.id,
                    pedido_id : this.data.id
                }

                this.sleep(250);

                const address = await this.$store.dispatch('save_fiscal_address', payload)
                    .catch(err => {
                        this.error_message(err.response.data, 'Código: '+err.response.status, 4000);
                    }).finally(() => this.save_fiscal_address_spinner = false);
                if (address) {
                    const data = {
                        address : address,
                        pedido_id : this.data.id
                    }
                    Event.$emit('save_fiscal_address', data);
                }

            },

            async remove_fiscal_address(element)
            {
                this.save_fiscal_address_spinner = true;

                let payload = {
                    token : this.User.token,
                    address : element.id,
                    pedido_id : this.data.id
                }

                this.sleep(250);

                const address = await this.$store.dispatch('remove_fiscal_address', payload)
                    .catch(err => {
                        this.error_message(err.response.data, 'Código: '+err.response.status, 4000);
                    }).finally(() => this.save_fiscal_address_spinner = false);
                if (address) {
                    const data = {
                        pedido_id : this.data.id
                    }
                    Event.$emit('remove_fiscal_address', data);
                }
            },

            async save_delivery_address(element)
            {
                this.save_delivery_address_spinner = true;

                const payload = {
                    token : this.User.token,
                    address : element.id,
                    pedido_id : this.data.id
                }

                this.sleep(250);

                const address = await this.$store.dispatch('save_delivery_address', payload)
                    .catch(err => {
                        this.error_message(err.response.data, 'Código: '+err.response.status, 4000);
                    }).finally(() => this.save_delivery_address_spinner = false);
                if (address) {
                    const data = {
                        address : address,
                        pedido_id : this.data.id
                    }
                    Event.$emit('save_delivery_address', data);
                }
            },

            async remove_delivery_address(element)
            {
                this.save_delivery_address_spinner = true;

                const payload = {
                    token : this.User.token,
                    address : element.id,
                    pedido_id : this.data.id
                }

                this.sleep(250);

                const address = await this.$store.dispatch('remove_delivery_address', payload)
                    .catch(err => {
                        this.error_message(err.response.data, 'Código: '+err.response.status, 4000);
                    }).finally(() => this.save_delivery_address_spinner = false);
                if (address) {
                    const data = {
                        pedido_id : this.data.id
                    }
                    Event.$emit('remove_delivery_address', data);
                }
            },

            async getData()
            {
                const payload = {
                    token : this.User.token,
                    code : this.data.code
                }

                const response = await this.$store.dispatch('pedido_show', payload);

                this.provider_data = response.data;

                this.loading = false;

                return response.data;

            }
        },

        computed : {

            Data(){
                return this.provider_data;
            },

            DisabledDates(){
                return {
                    to:  this.$moment(this.Today).toDate(),
                    from: this.$moment(this.Today).add(365, 'days').toDate(),
                    days : [0]
                }
            },

            Today(){
                return this.$moment(Date.now());
            },
            ...mapGetters([
                'GetPedidoCliente',
                'GetCustomerAddress',
                'PedidoClientesAddNewAddressGetter',
                'GetCustomersData',
                'PedidosClientesFiscalAddressGetter',
                'PedidosClientesDeliveryAddressGetter',
                'PedidoListChildRowReactivityData',
                'CheckStockDisponibleStock'
            ]),

            HasComment()
            {
                const comments = collect(this.data.comments);

                if (comments.count() > 0) {
                    return true;
                }

                return false;
            },

            PedidoClienteGetComments()
            {
                return this.data.comments;
            },

            ThisDataCustomerAddresses()
            {
                return this.data.customer_addresses;
            },

        },

        watch : {

            GetCustomerAddress(n)
            {
                const payload = {
                    data : this.data,
                    address : collect(n).last()
                }
                Event.$emit('set_customer_address', payload);
            },

            GetCustomersData(n)
            {
                this.sleep(150);

                this.data.customer_addresses = n.addresses;
                this.sleep(150);
                const payload = {
                    data : this.data,
                    customer_updated_data : n
                }
                Event.$emit('set_customer_data_updated', payload);
            },

        },

        /* provide(){
            const pp = {};

            Object.defineProperty(pp, 'www',
            {
                enumerable: true,
                get: () => this.PedidoListChildRowReactivityData
            });
            return { pp }
        }, */

        async mounted() {

            Event.$on('pedido_cliente_list', data => {
               this.provider_data = data;
            });

            Event.$on('delete_product_of_pedido', data => {
                const { product_id } = data;

                this.PedidoListChildRowReactivityData.items.forEach((item, index) => {
                    if (item.product_id == product_id) {
                        delete this.PedidoListChildRowReactivityData.items[index];
                    }
                })
            });

            this.$moment.locale('es');

            if (this.PedidoClienteGetComments) {

                collect(this.PedidoClienteGetComments).each( comment => {
                    this.print_comment.push( comment.description );
                });

                this.$store.commit('SET_PRINT_COMMENT', this.print_comment);

            }

            this.$store.commit('SET_CUSTOMER_ID', this.data.customer_id);

            await this.sleep(200);

            this.first_contact = this.data.first_contact;

            if (this.data.pedido_fiscal_address) {

                this.data.customer_addresses.forEach(address => {
                    if (address.id == this.data.pedido_fiscal_address.id) {
                        this.fiscal_address = this.data.pedido_fiscal_address
                    }
                });
            }

            if (this.data.pedido_delivery_address) {

                this.data.customer_addresses.forEach(address => {
                    if (address.id == this.data.pedido_delivery_address.id) {
                        this.delivery_address = this.data.pedido_delivery_address
                    }
                });
            }

            this.$store.commit('SET_MELI_MESSAGES', this.data.meli_messages);

            const pedido_list_child_row = document.querySelector('#pedido-list-child-row');

            this.$scrollTo(pedido_list_child_row, {duration: 2000 , offset: -100});

            const date = this.$moment(this.data.delivery_date, "DD-MM-YYYY");

            this.date = new Date(date.get('year'), date.get('month'), date.get('date'));

            this.$store.commit('SET_WHO_PREPARED', this.User.name);

        },

        async beforeMount(){

            const data = await this.getData();

            this.$store.commit('SET_PEDIDO_LIST_CHILD_ROW_REACTIVITY_DATA', data);
            this.$store.dispatch('orderUpdateSetOrderData', data);
            const { customer_address:address } = data;
            this.$store.dispatch('newCustomerAddressSetCity', address.city);
            this.$store.dispatch('newCustomerAddressSetCp', address.cp);
            this.$store.dispatch('newCustomerAddressSetAddress', address.street);
            this.$store.dispatch('newCustomerAddressSetBetween', address.between_streets);
            this.$store.dispatch('newCustomerAddressSetObs', address.obs);
            this.$store.dispatch('newCustomerAddressSetState', {id: address.state_id, name: address.state});

            Event.$emit('open_child_row_component', data);

            const { items } = data;

            this.$store.dispatch('cleanDisponibleStock');

			//filtra productos que son chapas
            const filtered_sheet_metal_cuttings = items.filter(item => item.isCHP);

            if (filtered_sheet_metal_cuttings.length) {

				//junto las chapas del mismo id
                const sheet_metal_cuttings_stock = filtered_sheet_metal_cuttings.reduce((acc, chapa) => {

                    if (chapa.isCHP) {
                        (acc[chapa.product_id] = acc[chapa.product_id] || []).push(chapa);
                        return acc;
                    }
                }, {});

				//groupID por product_id
                for (const product_id in sheet_metal_cuttings_stock) {
					this.$store.dispatch('setDisponibleStock', sheet_metal_cuttings_stock[product_id][0].sheet_metal_cuttings);
                }
            }
        }

    }
</script>
<style scoped>
    .my-input{
        background-color: white;
        border-style:solid !important;
        border-color:#5d5d5d !important;
        border-width:1px !important;
        text-align: center;
        width: 250px !important;
    }
    .my-success{
        background-color: #4C8A48;
    }
    .my-danger{
        background-color: #E91808;
    }
    .my-info {
        background-color: #555e7a;
    }
    .my-warning {
        background-color: #ff9c00;
    }
    .verde{
        background-color: #4C8A48;
    }
    .white-text{
        color: white;
    }
    .card-header{
        margin-bottom: 1.5rem;
    }
	#table--comments thead tr th:nth-child(1){
		width: 5%;
	}
	#table--comments thead tr th:nth-child(2){
		width: 75%;
	}
	#table--comments thead tr th:nth-child(3),
	#table--comments thead tr th:nth-child(5)
	{
		width: 10%;
	}
	#table--comments tbody tr td:nth-child(1),
	#table--comments tbody tr td:nth-child(3),
	#table--comments tbody tr td:nth-child(4),
	#table--comments tbody tr td:nth-child(5)
	{
		text-align: center;
	}
	#table--comments tbody tr td{
		vertical-align: middle;
	}
</style>
