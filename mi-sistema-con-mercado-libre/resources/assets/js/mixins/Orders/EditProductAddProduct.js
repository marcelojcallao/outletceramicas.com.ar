import {mapGetters} from 'vuex';
import {Event} from 'vue-tables-2';
import DeleteRow from '../../components/app/admin/orders/New/Body/Row/DeleteRowButton';
import MultiselectIva from '../../components/app/admin/orders/New/Body/Row/MultiselectIva';
import MultiselectProduct from '../../components/app/admin/orders/Edit/components/multiselect-product.vue';
import MultiselectPriceList from '../../components/app/admin/orders/Edit/components/multiselect-price-list.vue';

const PEDIDO = 101;
const COTIZACION = 102;
export default {

    components : {
        DeleteRow,
        MultiselectIva,
        MultiselectProduct,
        MultiselectPriceList
    },

    data() {
        return {
            expresions : {
                quantity : /^[0-9]+$/,
            },
            stock : 0,
            real_mts : 0,
            mts_to_invoiced : 0,
            rounded_mts : 0,
            check_sheet_metal_stock_message : '',
            is_sufficient_sheet_metal_stock : false
        }
    },

    methods: {

        isNumber($event) {
            let char = String.fromCharCode($event.keyCode);
            if (this.expresions.quantity.test(char)) return true;
            else $event.preventDefault();

        },

        isDecimal($event) {
            let keyCode = ($event.keyCode ? $event.keyCode : $event.which);

            // only allow number and one dot
            if ((keyCode < 48 || keyCode > 57) && (keyCode !== 46 || $event.target.value.indexOf('.') != -1)) { // 46 is dot
                $event.preventDefault();
            }

            // restrict to 2 decimal places
            if($event.target.value!=null && $event.target.value.indexOf(".")>-1 && ($event.target.value.split('.')[1].length > 1)){
                $event.preventDefault();
            }

        },

        chequeo_stock(){

                if (this.NewOrderGetTypeGetter.id == PEDIDO) {

                    const product = this.ProductFromNewOrder[this.index];
                    const quantity = product.quantity;
                    const mts_by_unity = product.mts_by_unity;
                    const actual_stock = this.stock - quantity;
                    const critical_stock =  parseInt(this.NewOrderCurrentProduct.critical_stock);

                    if (product.isCHP) {
                        this.check_sheet_metal_stock(quantity, mts_by_unity)
                    }else{
                        this.check_stock(quantity);
                    }
                }
        },


        rounded(value){

            const VALUE = 0.5;

            if (String(value).indexOf('.') > 0){

                const parteEntera = parseInt(Math.trunc(value));

                const subido = parteEntera + parseFloat(VALUE);

                if(subido >= value)
                {
                    const result = subido - parseFloat(value)

                    return result.toFixed(2);
                }

                return Math.ceil(value) - value;
            }

            return 0;
        },


        check_sheet_metal_stock(quantity, mts_by_unity){

            if (parseFloat(mts_by_unity) < 13 && this.NewOrderCurrentProduct.sheet_metal_cuttings) {

                const sheet_metal_cuttings_enable = this.NewOrderCurrentProduct.sheet_metal_cuttings.filter(smc => {
                    return parseFloat(smc.mts) >= parseFloat(mts_by_unity);
                }).sort((a, b) => a.mts - b.mts);

                let total_chapas_que_alcanzan = 0;
                let message = 'Stock de recortes: ';

                sheet_metal_cuttings_enable.forEach((smc, ind) => {
                    message = `${message} ${(ind==0)?'':'|'} ${smc.quantity} chapas de ${smc.mts} metros.`

                    total_chapas_que_alcanzan = total_chapas_que_alcanzan + parseInt(smc.quantity);
                });

                if (total_chapas_que_alcanzan < quantity) {

                    const stock_que_necesito_de_13_mts = quantity - total_chapas_que_alcanzan;
                    const critical_stock =  parseInt(this.NewOrderCurrentProduct.critical_stock);

                    if ((this.stock - stock_que_necesito_de_13_mts) <= 0) {

                        if (this.stock <= 0) {
                            Vue.swal.fire({
                                title: 'PRODUCTO SIN STOCK',
                                text: `Actualmente éste producto se encuentra sin stock.`,
                                icon: 'error',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Cerrar',
                            }).then((result) => {
                                this.$store.commit('SET_ENABLED_ADD_PRODUCT_BUTTON', 'true');
                                return false;
                            })
                        }
                        if (this.stock > 0) {
                            Vue.swal.fire({
                                title: 'PRODUCTO SIN STOCK',
                                text: `No hay capacidad para éste producto, quedan ${this.stock} unidades. Usted solicita ${stock_que_necesito_de_13_mts} unidades.
                                    ¿Desea utilizar las unidades que quedan en stock?`,
                                icon: 'error',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Continuar',
                                showCancelButton: true,
                                cancelButtonColor: '#d33',
                                cancelButtonText: 'Cancelar'
                            }).then((result) => {

                                if (result.isConfirmed) {
                                    this.$store.commit('NEW_ORDER_SET_QUANTITY_PRODUCT', {value : this.stock, index : this.index});
                                    this.$store.dispatch('updateTotalOnRowProduct', this.index);
                                    this.$store.commit('SET_ENABLED_ADD_PRODUCT_BUTTON', 'false');
                                }else{
                                    this.$store.commit('SET_ENABLED_ADD_PRODUCT_BUTTON', 'true');
                                }
                            })
                        }


                    }else if  ((this.stock - stock_que_necesito_de_13_mts) <= critical_stock) {

                        Vue.swal.fire({
                            title: 'STOCK CRÍTICO',
                            text: `Producto en stock crítico, actualmente hay ${this.stock} unidades. Luego de esta transacción quedarán ${this.stock-stock_que_necesito_de_13_mts} unidades.`,
                            icon: 'warning',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Continuar',
                            /* cancelButtonColor: '#d33',
                            showCancelButton: true,
                            cancelButtonText: 'Cancelar' */
                        }).then((result) => {

                            if (result.isConfirmed) {
                                //this.$refs['mts_by_unity_input'].focus()
                                //return false
                            }
                        });
                }
                }

                this.check_sheet_metal_stock_message = message;

            }else{
                this.check_sheet_metal_stock_message = '';
            }
        },

        check_stock(quantity){

            const critical_stock =  parseInt(this.NewOrderProductValue(this.index).critical_stock);
            const actual_stock = this.stock - quantity;
            console.log("🚀 ~ file: EditProductAddProduct.js:192 ~ check_stock ~ actual_stock:", actual_stock)

            if (actual_stock < 0) {

                if (actual_stock < 0 && this.stock > 0) {

                    let text = '';

                    if (this.NewOrderGetTypeGetter.id == PEDIDO) {
                        text = `No hay capacidad para éste producto, quedan ${this.stock} unidades. Usted solicita ${quantity} unidades.
                        ¿Desea utilizar las unidades que quedan en stock?`;
                    }else{
                        text = `No hay capacidad para éste producto, quedan ${this.stock} unidades. Usted solicita ${quantity} unidades.`;
                    }

                    Vue.swal.fire({
                        title: 'PRODUCTO SIN STOCK',
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Continuar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {

                            this.$store.commit('NEW_ORDER_SET_QUANTITY_PRODUCT', {value : this.stock, index : this.index});
                            this.$store.dispatch('updateTotalOnRowProduct', this.index);
                            this.$store.commit('SET_ENABLED_ADD_PRODUCT_BUTTON', 'false');

                        }else{
                            this.$store.commit('SET_ENABLED_ADD_PRODUCT_BUTTON', 'true');
                        }
                    })
                }
            }else if  ((this.stock - quantity) <= critical_stock) {

                    Vue.swal.fire({
                        title: 'STOCK CRÍTICO',
                        text: `Producto en stock crítico, actualmente hay ${this.stock} unidades. Luego de esta transacción quedarán ${this.stock-quantity} unidades.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Continuar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {

                        if (result.isConfirmed) {
                            this.$store.commit('NEW_ORDER_SET_QUANTITY_PRODUCT', {value : quantity, index : this.index});
                            this.$store.dispatch('updateTotalOnRowProduct', this.index);
                            this.$store.commit('SET_ENABLED_ADD_PRODUCT_BUTTON', 'false');
                        }else{
                            this.$store.commit('SET_ENABLED_ADD_PRODUCT_BUTTON', 'true');
                        }
                    });
            } else {

                this.$store.commit('NEW_ORDER_SET_QUANTITY_PRODUCT', {value : quantity, index : this.index});
                this.$store.dispatch('updateTotalOnRowProduct', this.index);
                this.$store.commit('SET_ENABLED_ADD_PRODUCT_BUTTON', 'false');

            }
        }
    },

    watch : {

        /* ProductFromNewOrder : {

            handler(n){

                const products = collect(n);

                const product = products.get(this.index);

                if (product.product && product.product.stock) {

                    this.stock = parseFloat(product.product.stock);
                }

            },
            deep: true
        },  */
    },

    computed : {

        ...mapGetters([

        ]),

        isUserOfSale()
        {
            if (this.User.type_user_id == 3) {
                return true;
            }

            return false;
        },

        IvaImport(){
            return this.NewOrderIvaImportGetter(this.index);
        },

        unit_price : {
            get(){
                return this.NewOrderUnitPriceProductGetter(this.index);
            },
            set(value){

                let payload = {
                    value : value,
                    index : this.index
                }

                this.$store.commit('NEW_ORDER_SET_UNIT_PRICE_PRODUCT', payload);
                this.$store.dispatch('updateTotalOnRowProduct', this.index);
            }
        },

        quantity : {
            get(){
                return this.NewOrderQuantityProductGetter(this.index);
            },
            set(value){
                let payload = {
                    value : (value == '') ? 0 : value,
                    index : this.index
                }
                this.$store.commit('NEW_ORDER_SET_QUANTITY_PRODUCT', payload);
                if (this.NewOrderCurrentProduct.isCHP) {

                    //const mts_by_unity = this.NewOrderCurrentProduct.mts_by_unity;//fijo 13 mts
                    const mts_by_unity = this.NewOrderMtsByUnityGetter(this.index);//fijo 13 mts

                    this.real_mts = parseFloat(value) * parseFloat(mts_by_unity);
                    const mts_total = parseFloat(this.rounded(mts_by_unity)) + parseFloat(mts_by_unity);

                    this.rounded_mts = this.rounded(mts_by_unity) * value;
                    this.mts_to_invoiced = mts_total * value;

                    this.$store.commit('NEW_ORDER_SET_REAL_MTS_CHAPA', {index : this.index, real_mts : this.real_mts});
                    this.$store.commit('NEW_ORDER_SET_ROUNDED_MTS_CHAPA', {index : this.index, rounded_mts : this.rounded_mts});
                    this.$store.commit('NEW_ORDER_SET_MTS_TO_INVOICED_CHAPA', {index : this.index, mts_to_invoiced : this.mts_to_invoiced});

                    this.$store.dispatch('updateTotalOnRowProduct', this.index);

                }

                this.$store.dispatch('updateTotalOnRowProduct', this.index);

            }
        },

        descuento : {
            get(){
                return this.NewOrderDiscountProductGetter(this.index);
            },
            set(value){

                const payload = {
                    value : value,
                    index : this.index
                }
                this.$store.dispatch('newOrderSetDiscountProduct', payload);
            }
        },
        mts_by_unity : {
            get(){
                return this.NewOrderMtsByUnityGetter(this.index);
            },
            set(value){

                if (this.NewOrderCurrentProduct.isCHP) {

                    if (parseFloat(value) > 13) {

                        Vue.swal.fire({
                            title: 'CHAPAS',
                            text: `Las chapas no superan los 13 mts..`,
                            icon: 'warning',
                            confirmButtonText: 'Cerrar',
                        }).then((result) => {

                            if (result.isConfirmed) {
                                this.$refs['mts_by_unity_input'].focus()
                                this.$refs['mts_by_unity_input'].value = 13;
                                this.$refs['mts_by_unity_input'].select()
                            }
                        });
                    }else{

                        const mts_by_unity = parseFloat(value);

                        const quantity = this.NewOrderQuantityProductGetter(this.index);

                        this.real_mts = parseFloat(quantity) * parseFloat(mts_by_unity);
                        const mts_total = parseFloat(this.rounded(mts_by_unity)) + parseFloat(mts_by_unity);

                        this.rounded_mts = this.rounded(mts_by_unity) * quantity;
                        this.mts_to_invoiced = mts_total * quantity;

                        this.$store.commit('NEW_ORDER_SET_MTS_BY_UNITY', {index : this.index, value : value});
                        this.$store.commit('NEW_ORDER_SET_REAL_MTS_CHAPA', {index : this.index, real_mts : this.real_mts});
                        this.$store.commit('NEW_ORDER_SET_ROUNDED_MTS_CHAPA', {index : this.index, rounded_mts : this.rounded_mts});
                        this.$store.commit('NEW_ORDER_SET_MTS_TO_INVOICED_CHAPA', {index : this.index, mts_to_invoiced : this.mts_to_invoiced});
                        this.$store.dispatch('updateTotalOnRowProduct', this.index);
                    }
                }

                this.$store.dispatch('updateTotalOnRowProduct', this.index);
            }
        },

        neto : {
            get(){
                return this.NewOrderNetoByProductGetter(this.index);
            },
            set(value){}
        },
        total : {
            get(){
                if (isNaN(this.NewOrderTotalProductGetter(this.index))) {
                    this.$store.commit('SET_ENABLED_ADD_PRODUCT_BUTTON', 'true');

                    return 0;
                }
                this.$store.commit('SET_ENABLED_ADD_PRODUCT_BUTTON', 'false');
                return this.NewOrderTotalProductGetter(this.index);
            },
            set(value){

                let payload = {
                    value : value,
                    index : this.index
                }

                this.$store.commit('NEW_ORDER_SET_TOTAL_PRODUCT', payload);
                this.$store.dispatch('updateTotalOnRowProduct', this.index);
            }
        },

        Code()
        {
            if (this.NewOrderProductValue(this.index) && this.NewOrderProductValue(this.index).code) {
                return this.NewOrderProductValue(this.index).code;
            }
            return '';
        },

        IsCHP(){
            let products = this.ProductFromNewOrder;

            return products[this.index].isCHP;
        },

        RoundedMts(){
            if (this.IsCHP) {
                return `Metros ${this.real_mts}, se facturan ${this.mts_to_invoiced} metros `;
            }
        },

        CheckSheetMetalStockMessage(){
            return this.check_sheet_metal_stock_message;
        },


    },

    mounted(){
        Event.$on('save_order', (value) => {
            this.display_show_critico_message = value;
        })
    }

}
