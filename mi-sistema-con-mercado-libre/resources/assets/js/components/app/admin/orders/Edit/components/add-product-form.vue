<template>
    <div>
        <div>
            <div id="grid">
                <div class="item-grid1">
                    <MultiselectProduct />
                </div>
                <div class="item-grid9">
                    <MultiselectPriceList />
                </div>
                <div class="item-grid2">
                    <label>Código</label>
                    <p >{{ Code }}</p>
                </div>
                <div class="item-grid3">
                    <label class="form--label" for="unit-price-input">P. Unitario {{ ( IsCHP ) ? ' por metro de chapa' : ''}}</label>
                    <p class="form--input">
                        {{UnitPrice | currency}}
                    </p>
                </div>
                <div class="item-grid4">
                    <label class="form--label" for="quantity-input">Cantidad</label>
                    <currency-input class="form-control text-center"
                            type="text"
                            @focus="$event.target.select()"
                            ref="quantity"
                            :currency="null"
                            locale="es-AR"
                            :allow-negative="false"
                            :value-as-integer="true"
                            :precision="0"
                            v-model="quantity"
                        />
                </div>
                <div class="item-grid11">
                    <label class="form--label" for="mts_by_unity_input">Metros por unidad {{ ( IsCHP ) ? ' a vender' : ''}}</label>
                    <currency-input
                        class="form--input"
                        :class="{'isDisabled': ! IsCHP}"
                        id="mts_by_unity_input"
                        type="text"
                        @focus="$event.target.select()"
                        :currency="null"
                        locale="es-AR"
                        v-model="mts_by_unity"
                        :disabled="! IsCHP"
                        />
                </div>
                <div class="item-grid5">
                    <MultiselectIva />
                </div>
                <div class="item-grid12">
                    <label>Importe Neto</label>
                    <p class="form--input">
                        {{Neto | currency}}
                    </p>
                </div>
                <div class="item-grid6">
                    <label>Importe Iva</label>
                    <p class="form--input">
                        {{IvaImport | currency}}
                    </p>
                </div>
                <div class="item-grid7">
                    <label class="form--label" for="discount-input">Descuento</label>
                    <currency-input
                        class="form--input"
                        :class="{'isUserOfSale':isUserOfSale}"
                        id="discount-input"
                        @focus="$event.target.select()"
                        type="text"
                        :currency="null"
                        locale="es-AR"
                        />
                </div>
                <div class="item-grid8">
                    <label class="form--label" for="total-input">Total</label>
                    <p class="form--input">
                        {{Total | currency}}
                    </p>
                </div>
            </div>
        </div>
        <div v-if="IsCHP">
            {{CheckSheetMetalStockMessage}}
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import MultiselectIva from './../components/multiselect-iva.vue';
import MultiselectProduct from './../components/multiselect-product.vue';
import MultiselectPriceList from './../components/multiselect-price-list.vue';
export default {

    name: 'AddProductForm',

    data() {
        return {
            newProduct: {},
            stock: 0
        }
    },

    components: {
        MultiselectIva,
        MultiselectProduct,
        MultiselectPriceList,
    },

    computed: {

        ...mapGetters([
            'OrderUpdateSetPriceListGetter',
            'OrderUpdateNewProductGetter',
            'OrderUpdateNewProductProductName',
            'CheckStockProductGetter'
        ]),

        isDisabled(){
            return true;
        },

        isUserOfSale(){
            return true;
        },

        UnitPrice() {
            return this.OrderUpdateNewProductGetter.unit_price;
        },

        quantity: {
            get(){
                return this.OrderUpdateNewProductGetter.quantity;
            },
            set: async function(value){
				console.log("🚀 ~ file: row-product.vue:121 ~ set:function ~ value:", value)

                //this.$store.dispatch('checkStockSetProduct', this.OrderEditDataGetter.items[this.index - 1]);
                //multiselect product ya guarda el producto en this.$store.dispatch('checkStockSetProduct'
                const result = await this.$store.dispatch('check_stock', value)

                let payload = null;

                if (result.isConfirmed) {

                    payload = {
                        value: result.quantity
                    }

                    this.$store.dispatch('orderUpdateSetQuantityProduct', payload.value);

                }else{

                    payload = {
                        value: null
                    }

                    this.$store.dispatch('orderUpdateSetQuantityProduct', payload.value);
                }

            }
        },

        mts_by_unity: {
            get(){
                return this.OrderUpdateNewProductGetter.mts_by_unity;
            },
            set: async function(value){
            console.log("🚀 ~ file: add-product-form.vue:171 ~ set:function ~ value:", value)

                const result = await this.$store.dispatch('check_sheet_metal_stock', { product_id: this.OrderUpdateNewProductGetter.product_id, quantity: this.OrderUpdateNewProductGetter.quantity, mts_by_unity: value})

                let payload = null;

                if (result.isConfirmed) {
                    payload = {
                        value
                    }
                    this.$store.dispatch('orderUpdateSetMtsByUnityProduct', payload.value);
                }else{

                    payload = {
                        value: null
                    }

                    this.$store.dispatch('orderUpdateSetMtsByUnityProduct', payload.value);
                }

            }
        },

        Neto(){
            return this.OrderUpdateNewProductGetter.neto_import;
        },

        IvaImport(){
            return this.OrderUpdateNewProductGetter.iva_import;
        },

        Total(){
            return this.OrderUpdateNewProductGetter.total_raw;
        },

        IsCHP(){
            return this.OrderUpdateNewProductGetter.isCHP;
        },

        Code(){
            return this.OrderUpdateNewProductGetter.code;
        },

        CheckSheetMetalStockMessage(){
                return `Cantidad de chapas ${this.OrderUpdateNewProductGetter.quantity}, cada chapa mide ${this.OrderUpdateNewProductGetter.mts_by_unity} mts.
                el redondeo es de ${this.OrderUpdateNewProductGetter.rounded_mts} mts. por chapa, cada chapa a facturar es de ${this.OrderUpdateNewProductGetter.mts_to_invoiced / this.OrderUpdateNewProductGetter.quantity} mts.,  Metros de chapa a facturar ${this.OrderUpdateNewProductGetter.mts_to_invoiced} mts.
                `;
        }

    },

}
</script>

<style >
    .isDisabled {
        color: currentColor;
        cursor: not-allowed;
        opacity: 0.5;
        text-decoration: none;
        pointer-events: none;
        background-color: lightslategray;
    }
    #grid {
        display: grid;
        gap: .1rem;
        grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
        grid-template-rows: 1fr 1fr 1fr;
    }
    div[class^="item-"]{
        margin: .3rem;
    }
    .item-grid1 {
        grid-column-start: 1;
        grid-column-end: 4;
    }
    .item-grid9 {
        grid-column-start: 4;
        grid-column-end: 6;
    }
    .item-grid5 {
        grid-column-start : 5;
        grid-column-end: 6;
    }
    .item-grid8 {
        grid-column-start : 4;
        grid-column-end: 6;
    }
    .item-grid11 {
        grid-column-start : 4;
        grid-column-end: 5;
    }
    .item-grid12 {
        grid-row-start: 3;
        grid-row-end: 4;
        grid-column-start : 1;
        grid-column-end:2;
    }
    .index-number, .delete-button{
        text-align: center;
        font-size: 32px;
        vertical-align: middle;
    }
    .form--label{
        display : block;
        font-weight : 700;
        cursor : pointer;
    }
    .form--input{
        width: 100%;
        background-color: white;
        border: 1px solid #e8e8e8;
        border-radius: 5px;
        height: 3.6rem;
        line-height: 2rem;
        padding: .8rem;
        transition: .3s ease all;
    }
    .form--input:focus{
        border: .2rem solid #0077ff;
        outline: none;
        box-shadow: .2rem 0rem 2rem rgba(163, 163, 163, .5);
    }
    .stock-message{
        font-weight: bold;
        color: red;
        font-size: 1.5rem;
    }
    .isUserOfSale {
        pointer-events: none;
        background-color: #e8e8e8;
    }
</style>
