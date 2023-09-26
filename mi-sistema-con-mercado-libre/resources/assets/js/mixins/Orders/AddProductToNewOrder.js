import {mapGetters} from 'vuex';
import collect from 'collect.js';
import {Event} from 'vue-tables-2';
import DeleteRow from '../../components/app/admin/orders/New/Body/Row/DeleteRowButton';
import MultiselectIva from '../../components/app/admin/orders/New/Body/Row/MultiselectIva';
import MultiselectProduct from '../../components/app/admin/orders/New/Body/Row/MultiselectProduct';
import MultiselectPriceList from '../../components/app/admin/orders/New/Body/Row/MultiselectPriceList';

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

        async isAplicableDiscount(descuento) {

            const discount = await this.$store.dispatch( 'CurrencyFormat', descuento );

            const fromImport = await this.$store.dispatch( 'CurrencyFormat', this.NewOrderCurrentProduct.apply_discount_from );

            const message = `
                El Producto ${ this.NewOrderCurrentProduct.name } tiene un descuento del
                ${ this.NewOrderCurrentProduct.apply_discount_percentage }% cuando la compra supera los
                ${ fromImport }. El descuento es de ${discount}. ¿Desesa aplicarlo?.
            `;

            const result = await Vue.swal.fire({
                    title: 'ATENCIÓN',
                    text: message,
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Continuar',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar'
                });

            return result;

        },

        async checkDiscount(){

			let applyDiscount = false;

			if (this.NewOrderCurrentProduct.apply_discount_pay_method && this.NewOrderCurrentProduct.apply_discount_pay_method.length) {

				this.NewOrderCurrentProduct.apply_discount_pay_method.forEach(el => {

					if(el.id === this.OrderGetter.payment.id) applyDiscount = true;
				})
			}

            if ( this.NewOrderCurrentProduct.apply_discount && applyDiscount) {

                let neto;

                (this.ProductFromNewOrder[this.index].isCHP)
                    ? neto = this.ProductFromNewOrder[this.index].unit_price * ( this.ProductFromNewOrder[this.index].quantity * this.ProductFromNewOrder[this.index].mts_by_unity )
                    : neto = this.ProductFromNewOrder[this.index].unit_price * this.ProductFromNewOrder[this.index].quantity

                if ( neto >= this.NewOrderCurrentProduct.apply_discount_from ) {

                    const discount = neto * this.NewOrderCurrentProduct.apply_discount_percentage / 100;

                    const applyDiscount = await this.isAplicableDiscount(discount);

                    if (applyDiscount.isConfirmed) {

                        const new_payload = {
                            value : discount,
                            index : this.index
                        }

                        this.$store.dispatch('newOrderSetDiscountProduct', new_payload);
                    }

                }else{

                    const new_payload = {
                        value : 0,
                        index : this.index
                    }

                    this.$store.commit('NEW_ORDER_SET_DISCOUNT_PRODUCT', new_payload);
                }
            }

            return 'pp';
        },

        async chequeo_stock(){

            await this.checkDiscount();

            if (this.NewOrderGetTypeGetter.id == PEDIDO) {

                const product = this.ProductFromNewOrder[this.index]; //es el producto que esta en el formulario
                console.log("🚀 ~ file: AddProductToNewOrder.js:123 ~ chequeo_stock ~ product:", product)
                const quantity = product.quantity;
                console.log("🚀 ~ file: AddProductToNewOrder.js:125 ~ chequeo_stock ~ quantity:", quantity)
                const mts_by_unity = product.mts_by_unity;
                console.log("🚀 ~ file: AddProductToNewOrder.js:127 ~ chequeo_stock ~ mts_by_unity:", mts_by_unity)
                /* const actual_stock = this.stock - quantity;
                const critical_stock =  parseInt(this.NewOrderCurrentProduct.critical_stock); */

                if (product.isCHP) {
                    console.log("🚀 ~ file: AddProductToNewOrder.js:133 ~ chequeo_stock ~ product.isCHP:", product.isCHP)
                    this.check_sheet_metal_stock(quantity, mts_by_unity)
                }else{
                    this.check_stock(quantity);
                }
            }
            if (this.NewOrderGetTypeGetter.id == COTIZACION) {

                const product = this.ProductFromNewOrder[this.index]; //es el producto que esta en el formulario
                console.log("🚀 ~ file: AddProductToNewOrder.js:140 ~ chequeo_stock ~ product:", product)
                const quantity = product.quantity;
                console.log("🚀 ~ file: AddProductToNewOrder.js:142 ~ chequeo_stock ~ quantity:", quantity)
                const mts_by_unity = product.mts_by_unity;
                console.log("🚀 ~ file: AddProductToNewOrder.js:144 ~ chequeo_stock ~ mts_by_unity:", mts_by_unity)
                /* const actual_stock = this.stock - quantity;
                const critical_stock =  parseInt(this.NewOrderCurrentProduct.critical_stock); */

                if (product.isCHP) {
                    this.check_sheet_metal_stock(quantity, mts_by_unity)
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

        async check_sheet_metal_stock(quantity, mts_by_unity){
        console.log("🚀 ~ file: AddProductToNewOrder.js:164 ~ check_sheet_metal_stock ~ quantity, mts_by_unity:", quantity, mts_by_unity)

			//chapas menores a 13 metros
            console.log("🚀 ~ file: AddProductToNewOrder.js:168 ~ check_sheet_metal_stock ~ this.NewOrderCurrentProduct:", this.NewOrderCurrentProduct)
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

            }else if (parseFloat(mts_by_unity) == 13) {
                this.check_sheet_metal_stock_message = '';

				this.check_stock(quantity)
            }

        },

        async check_stock(quantity){

			const add_product_to_pedido_button = document.getElementById('add_product_to_pedido_button');

			add_product_to_pedido_button.disabled = false;

            const critical_stock =  parseInt(this.NewOrderProductValue(this.index).critical_stock);

			if ( parseInt( this.stock ) <= 0 ) {
				console.log("🚀 ~ file: AddProductToNewOrder.js:258 ~ check_stock ~ parseInt( this.stock ) <= 0:", parseInt( this.stock ) <= 0)
				//Éste está usando!!!
				//#####################
				const result = await Vue.swal.fire({
					title: 'PRODUCTO SIN STOCK',
					text: `Actualmente éste producto se encuentra sin stock.`,
					icon: 'error',
					showCancelButton: true,
					showConfirmButton: false,
					cancelButtonColor: '#d33',
                    cancelButtonText: 'Cerrar'
				})

				this.$store.commit('SET_ENABLED_ADD_PRODUCT_BUTTON', 'false');
				console.log("🚀 ~ file: AddProductToNewOrder.js:268 ~ check_stock ~ this.index:", this.index)
				//this.$store.commit('NEW_ORDER_DELETE_PRODUCT', this.index);
				add_product_to_pedido_button.disabled = true;
				return false;
			}

            const stock_is_sufficient = this.stock - quantity;

            if ( stock_is_sufficient <= 0 ) {

                if ( stock_is_sufficient < 0 ) {

                    let text = '';

                    if (this.NewOrderGetTypeGetter.id == PEDIDO) {
                        text = `No hay capacidad para éste producto, quedan ${this.stock} unidades. Usted solicita ${quantity} unidades.
                        ¿Desea utilizar las unidades que quedan en stock?`;
                    }else{
                        text = `No hay capacidad para éste producto, quedan ${this.stock} unidades. Usted solicita ${quantity} unidades.`;
                    }

                    Vue.swal.fire({
                        title: 'STOCK INSUFICIENTE',
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
				//################# agregué ésto
				if ( stock_is_sufficient === 0) {
					Vue.swal.fire({
                        title: 'STOCK CRÍTICO',
                        text: `Producto quedará en stock crítico, actualmente hay ${this.stock} unidades. Luego de esta transacción quedarán ${this.stock-quantity} unidades.`,
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
        },

    },

    watch : {

        ProductFromNewOrder : {

            async handler(n){

                const products = collect(n);

                const product = products.get(this.index);

                if (product.product && product.product.stock) {

                    //this.stock = parseFloat(product.product.stock);
                }

            },
            deep: true
        },

        NewOrderCurrentProduct: {

            handler(product){

				if (product != null) {

					this.stock = parseFloat(product.stock);
				}

            },
            deep: true
        }

    },

    computed : {

        ...mapGetters([
            'NewOrderUnitPriceProductGetter',
            'NewOrderQuantityProductGetter',
            'NewOrderDiscountProductGetter',
            'NewOrderMtsByUnityGetter',
            'NewOrderNetoByProductGetter',
            'NewOrderTotalProductGetter',
            'NewOrderIvaImportGetter',
            'NewOrderProductValue',
            'ProductFromNewOrder',
            'NewOrderProductStockOnCurrentOrder',
            'NewOrderCurrentProduct',
            'NewOrderGetTypeGetter',
			'OrderGetter'
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
                return parseFloat( this.NewOrderUnitPriceProductGetter(this.index) );
            },
            set(value){

                const payload = {
                    value : value,
                    index : this.index
                }

                this.$store.commit('NEW_ORDER_SET_UNIT_PRICE_PRODUCT', payload);
                this.$store.dispatch('updateTotalOnRowProduct', this.index);
            }
        },

        quantity : {
            get(){
                return parseInt( this.NewOrderQuantityProductGetter(this.index) );
            },
            async set(value){
                const payload = {
                    value : (value == '') ? 0 : value,
                    index : this.index
                }
                //this.$store.commit('NEW_ORDER_SET_QUANTITY_PRODUCT', payload);
                this.$store.dispatch('newOrderSetQuantityProduct', payload);

                if ( this.NewOrderCurrentProduct && this.NewOrderCurrentProduct.isCHP ) {

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
                return parseFloat( this.NewOrderDiscountProductGetter(this.index) );
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
                return parseFloat( this.NewOrderMtsByUnityGetter(this.index) )
            },
            set(value){

                if ( this.NewOrderCurrentProduct && this.NewOrderCurrentProduct.isCHP ) {

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
                return parseFloat( this.NewOrderNetoByProductGetter(this.index) );
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
                return parseFloat( this.NewOrderTotalProductGetter(this.index) )
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

            const products = this.ProductFromNewOrder;

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
