<template>
    <div class="wrapper-invoice-list-table">
        <loading
            :active.sync="loading"
            color="#0469c7"
            :can-cancel="false"
            :is-full-page="true">
        </loading>
        <div class="row">
            <form class="form flex " >
                <div class="form-group col-md-5 pd-t-10" style="padding-bottom-15px;">
                    <multiselect placeholder="Buscar Cliente"
                        id="findCustomer"
                        track-by="name" label="name"
                        v-model="customer"
                        :options="customers"
                        :searchable="true"
                        :internal-search="false"
                        :clear-on-select="true"
                        @search-change="asyncFind"
                        @select="selectedCustomer"
                        @remove="removeCustomer"
                        selectLabel="Cliente"
                        selectedLabel="Seleccionado"
                        deselectLabel="Quitar"
                        >
                        <span slot="noOptions">
                                Lista Vacía
                        </span>
                        <span slot="noResult">
                                La búsqueda no arrojó resultados
                        </span>
                    </multiselect>
                </div>
                <div class="col-md-5 pd-t-10">
                    <multiselect placeholder="Comprobantes"
                        track-by="name" label="name"
                        :options="Vouchers"
                        v-model="voucher"
                        @select="selectedVoucher"
                        @remove="removeVoucher"
                        :clear-on-select="false"
                        >
                        <span slot="noOptions">
                                Lista Vacía
                        </span>
                        <span slot="noResult">
                                La búsqueda no arrojó resultados
                        </span>
                    </multiselect>
                </div>
                <!-- <div class="col-md-2">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Desde</label>
                        <date-picker
                            v-model="date_from"
                            format="DD-MM-YYYY"
                            :lang="lang"
                        />
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Hasta</label>
                        <date-picker
                        class="input--form"
                            v-model="date_to"
                            format="DD-MM-YYYY"
                            :lang="lang"
                        />
                    </div>
                </div> -->
                <div class="pd-t-20">
                    <button
                        class="btn btn-primary btn-sm btn-height"
                        type="button"
                        @click.prevent="loadData()"
                        v-tooltip="'Buscar datos'"
                        >Buscar
                    </button>
                    <button
                        @click="excel"
                        class="btn btn-bg-green btn-sm btn-labeled btn-height"
                        :class="{'spinner spinner-inverse spinner-sm' : spinner}"
                        type="button">
                    <span class="btn-label">
                        <span class="icon icon-download icon-lg icon-fw"></span>
                    </span>
                    Excel
                    </button>
                </div>

            </form>
        </div>
		<div class="row">
            <div class="col-md-6">
                <label >Búsqueda de comprobantes de venta en base a un producto</label>
                <multiselect placeholder="Buscar por producto"
                    id="ajax"
                    :class="'multiselect_product'"
                    track-by="name"
                    label="name"
                    selectLabel="Seleccionar"
                    deselectLabel="Deseleccionar"
                    :loading="show_spinner"
                    :options="products"
                    :searchable="true"
                    :internal-search="true"
                    :clear-on-select="false"
                    @search-change="asyncFindProduct"
                    open-direction="bottom"
                    v-model="search_by_product"
                    >
                    <span slot="noOptions">
                        Lista Vacía
                    </span>
                    <span slot="noResult">
                        La búsqueda no arrojó resultados
                    </span>
                </multiselect>
            </div>
			<div class="col-md-6 padding--date">
				<label for="">Entre fechas</label>
				<date-picker
					class="input--form"
					v-model="between_dates"
					format="DD-MM-YYYY"
					:lang="lang"
					range
				/>
			</div>
        </div>
        <v-client-table
            ref="invoice-list"
            :columns="columns"
            :data="rows"
            :options="options"
        >
        </v-client-table>
        <div class="text-center">
            <paginate
                :page-count="pageCount"
                :click-handler="loadData"
                :prev-text="'Ant.'"
                :next-text="'Sig.'"
                :container-class="'pagination'">
            </paginate>
        </div>
        <BlockUI :msg="msg" v-if="loading"></BlockUI>
    </div>
</template>

<script>
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
    import {Event} from 'vue-tables-2';
    import {mapGetters} from 'vuex';
    import Paginate from 'vuejs-paginate';
    import cell_date from './PartialCellDate';
    import Multiselect from 'vue-multiselect';
    import Loading from 'vue-loading-overlay';
    import Excel from './../../../../src/Excel/Excel';
    import 'vue-loading-overlay/dist/vue-loading.css';
    import cell_print from './PartialCellPrintInvoice';
    import InvoiceListChildRow from './InvoiceListChildRow';
    import row_number from './../../publications/partials/row-number.vue';
    import asyc_find_customer from './../../../../mixins/asyc-find-customer';
    export default {

        components : {
            Paginate, InvoiceListChildRow, Multiselect, DatePicker, Loading
        },

        mixins : [asyc_find_customer],

        data() {
            return {
				show_spinner: false,
				products: [],
				search_by_product: null,
				between_dates: null,
                lang: {
                // the locale of formatting and parsing function
                    formatLocale: {
                        // MMMM
                        months: ['Enero', 'Febreo', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                        // MMM
                        monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                        // dddd
                        weekdays: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado'],
                        // ddd
                        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sáb'],
                        // dd
                        weekdaysMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
                        // first day of week
                        firstDayOfWeek: 1,
                        // first week contains January 1st.
                        firstWeekContainsDate: 1,
                        // format 'a', 'A'
                        /* meridiem: (h: Number, _: Number, isLowercase: boolean) {
                            const word = h < 12 ? 'AM' : 'PM';
                            return isLowercase ? word.toLocaleLowerCase() : word;
                        }, */
                        // parse ampm
                        //meridiemParse: /[ap]\.?m?\.?/i;
                        // parse ampm
                        /* isPM: (input: string) {
                            return `${input}`.toLowerCase().charAt(0) === 'p';
                        } */
                    },
                    // the calendar header, default formatLocale.weekdaysMin
                    days: [],
                    // the calendar months, default formatLocale.monthsShort
                    months: [],
                    // the calendar title of year
                    yearFormat: 'YYYY',
                    // the calendar title of month
                    monthFormat: 'MMM',
                    // the calendar title of month before year
                    monthBeforeYear: false,
                },
                msg : 'Cargando datos... ',
                date : new Date(),
                loading : false,
                spinner : false,
                pageCount : 1,
                customer : null,
                customers : [],
                voucher: null,
                columns : [
                    'number',
                    'customer_name',
                    'voucher_name',
                    'voucher_number',
                    'voucher_date',
                    'voucher_total_import',
                    'action',
                ],
                rows : [],
                options: {
                    perPage: 50,
                    perPageValues: [10, 25, 50, 100],
                    pagination: { dropdown:true },
                    headings: {
                        number : '#',
                        customer_name : 'Cliente',
                        voucher_name : 'Comprobante',
                        voucher_number : 'Número',
                        voucher_date : 'Fecha',
                        voucher_total_import : 'Importe',
                        action : 'Imprimir',
                    },
                    templates: {
                        number : row_number,
                        voucher_date : cell_date,
                        action : cell_print
                    },
                    columnsClasses: {

                    },
                    //childRow : InvoiceListChildRow,
                    showChildRowToggler : true,
                    toggleGroups : true,
                    filterable: [],
                }
            }
        },

        computed : {
            ...mapGetters([
                'InvoicesList',
                'Vouchers'
            ])
        },

        watch : {
            InvoicesList(n)
            {
                this.rows = n
            }
        },

        methods : {

			async asyncFindProduct (query) {

				if (query != '') {

					const { data:products } = await axios.post('/api/products/find_by_name', {
						product_name : query,
					}).catch((err) => {

					});

					if (products) {

						products.forEach(product => {
							if (product.$isDisabled) {
								delete product.$isDisabled;
							}
						});

						this.products = products

					}
				}

			},

            async loadData(page=1)
            {
                this.msg = 'Buscando comprobantes de venta...';

                this.loading = true;

                let url = `/api/sale_invoice/index?page=${page}`;

                if (this.customer != null) {
                    url = url + '&customer='+this.customer.id;
                }

                if (this.status != null) {
                    url = url + '&status='+this.status.status_id;
                }

                if (this.voucher != null) {
                    url = url + '&voucher='+this.voucher.id;
                }

				if (this.search_by_product != null) {
                    url = url + '&product='+this.search_by_product.id;
                }

				if (this.between_dates != null && this.between_dates[0] != null) {

					const from = this.$moment(this.between_dates[0]).format('YYYY-MM-DD 00:00:00');
                    url = `${url}&date_from=${from}`;

					const to = this.$moment(this.between_dates[1]).format('YYYY-MM-DD 23:59:59');
					url = `${url}&date_to=${to}`;
                }

                const response = await axios.post(url)
                .finally(()=> this.loading = false);

                if (response) {
                    this.pageCount = response.data.pagination.last_page;
                    this.rows = response.data.data
                }
            },

            async excel(){

				const ANDPERSAN = '&';

				const QUESTION = '?';
                this.spinner = true;

                if (this.$moment(this.date_to).isBefore(this.date_from, 'day')) {

                    Vue.swal.fire({
                        title: 'Listado de comprobantes',
                        text : 'La fecha DESDE no puede ser mayor que la fecha HASTA',
                        icon : 'error',
                        width : '35%',
                        padding : '2rem',
                        backdrop : true,
                        time : 3000
                    });

                    this.spinner = false

                    return false;
                }

                /* const payload = {
                    customer : this.customer,
                    from : from,
                    to : to,
                    status : this.status,
                } */

                let url = `/api/sale_invoice/excel`;

                if (this.customer != null) {
					(url === '/api/sale_invoice/excel')
						? url = url + '?customer='+this.customer.id
						: url = url + '&customer='+this.customer.id;

                }

                if (this.status != null) {
					(url === '/api/sale_invoice/excel')
						? url = url + '?status='+this.status.status_id
						: url = url + '&status='+this.status.status_id;
                }

                if (this.voucher != null) {
					(url === '/api/sale_invoice/excel')
						? url = url + '?voucher='+this.voucher.id
						: url = url + '&voucher='+this.voucher.id;

                }

				if (this.between_dates != null && this.between_dates[0] != null) {

					const from = this.$moment(this.between_dates[0]).format('YYYY-MM-DD 00:00:00');
					(url === '/api/sale_invoice/excel')
						? url = `${url}?from=${from}`
						: url = `${url}&from=${from}`;


					const to = this.$moment(this.between_dates[1]).format('YYYY-MM-DD 23:59:59');
					url = `${url}&to=${to}`;
				}

                const { data:invoices } = await axios.post(url)
                .finally(()=> this.spinner = false);

                if (invoices) {

                    if (invoices.length == 0) {
                        Vue.swal.fire({
                            title: 'Listado de comprobantes',
                            text : 'No se encontraron comprobantes entre las fechas seleccionadas',
                            icon : 'error',
                            width : '35%',
                            padding : '2rem',
                            backdrop : true,
                            time : 3000
                        });

                        return false;
                    }
                    const headers = [
                        'CLIENTE', 'COMPROBANTE', 'NUMERO', 'FECHA', 'CAE', 'NETO'
                    ]

                    const file_name = `V-${this.$moment(this.date_from).format('DD-MM-YYYY')}-${this.$moment(this.date_to).format('DD-MM-YYYY')}.xlsx`
                    const excel = new Excel(invoices, headers, file_name);
                    excel.create_excel();
                }

            },

            GroupByDelete()
            {
                this.$set(this.options, 'groupBy', '');
            },

            selectedVoucher(el){
                this.voucher = el;
            },

            removeVoucher(el){
                this.voucher = null;
            }

        },

        async mounted() {

            this.loadData();

            this.loading = false;

            const vouchers = await this.$store.dispatch('getVouchers');

            this.$store.commit('SET_VOUCHERS', vouchers);

            Event.$on('add_invoice_to_list', (invoice) => {

                this.$refs.invoice_list.tableData.unshift(invoice);

                /* this.$refs.invoice_list.tableData.findIndex((row, index) => {

                    if (row.id == invoice.parent.id) {
                        this.$refs.invoice_list.tableData[index].status = 'CERRADO';
                        this.$refs.invoice_list.tableData[index].voucher_type =  invoice.voucher_type;
                    }
                });  */
            });
        }
    }
</script>
<style>



    .wrapper-invoice-list-table tr.VueTables__row  {
        font-size: 12px !important;
    }
    .wrapper-invoice-list-table table thead tr th:nth-child(1) {
        width: 3%;
        text-align: center;
        text-transform: uppercase;
        font-size: 14px !important;
    }
    .wrapper-invoice-list-table table thead tr th:nth-child(2) {
        width: 25%;
        text-align: center;
        text-transform: uppercase;
        font-size: 14px !important;
    }
    .wrapper-invoice-list-table table thead tr th:nth-child(3),
    .wrapper-invoice-list-table table thead tr th:nth-child(4),
    .wrapper-invoice-list-table table thead tr th:nth-child(5)
    {
        width: 15%;
        text-align: center;
        text-transform: uppercase;
        font-size: 14px !important;
    }
    .wrapper-invoice-list-table table thead tr th:nth-child(6) {
        width: 22%;
        text-align: center;
        text-transform: uppercase;
        font-size: 14px !important;
    }
    .wrapper-invoice-list-table table thead tr th:nth-child(7) {
        width: 5%;
        text-align: center;
        text-transform: uppercase;
        font-size: 14px !important;
    }
    .wrapper-invoice-list-table table thead tr {
        background-color: #00bbf0;
        color: aliceblue;
        font-weight: bold;
        vertical-align: middle;
    }
    .wrapper-invoice-list-table table tbody tr td:nth-child(1) {
        text-align: center;
    }
    .wrapper-invoice-list-table table tbody tr td:nth-child(2),
    .wrapper-invoice-list-table table tbody tr td:nth-child(3) {
        text-align: left;
        font-weight: bold;
    }

    .wrapper-invoice-list-table table tbody tr td:nth-child(4),
    .wrapper-invoice-list-table table tbody tr td:nth-child(5)
    {
        text-align: center;
    }
    .wrapper-invoice-list-table table tbody tr td:nth-child(6) {
        text-align: right;
    }
    .wrapper-invoice-list-table table tbody tr td:nth-child(7) {
        text-align: center;
    }
    .flex {
        display: flex;
    }
    .btn-height{
        height: 3rem;
        margin-right: .5rem;
    }
    .btn-bg-green{
        background-color: rgb(15, 92, 15);
        color: aliceblue;
    }
    .pd-t-10{
        padding-top: 10px
    }
    .pd-t-20{
        padding-top: 20px
    }
</style>
