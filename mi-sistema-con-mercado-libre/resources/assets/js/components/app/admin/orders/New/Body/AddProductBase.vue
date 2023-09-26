<template>
    <div>
        <div>
            <div id="grid">
                <div class="item-grid1">
                    <MultiselectProduct :index="index"/>
                </div>
                <div class="item-grid9">
                    <MultiselectPriceList :index="index"/>
                </div>
                <div class="item-grid2">
                    <label>Código</label>
                    <p >{{Code}}</p>
                </div>
                <div class="item-grid3">
                    <label class="form--label" for="unit-price-input">P. Unitario</label>
                    <currency-input
                        class="form--input"
                        :class="{'isUserOfSale':isUserOfSale}"
                        :disabled="isUserOfSale"
                        id="unit_price-input"
                        @focus="$event.target.select()"
                        type="text"
                        :currency="null"
                        locale="es-AR"
                        v-model="unit_price"
                        :allow-negative="false"
                        :precision="2"
                        @blur="chequeo_stock($event)"
                        />
                    <p class="formulario__input-error"></p>
                </div>
                <div class="item-grid4">
                    <label class="form--label" for="quantity-input">Cantidad</label>
                    <currency-input
                        class="form--input"
                        id="quantity-input"
                        @focus="$event.target.select()"
                        type="text"
                        :currency="null"
                        locale="es-AR"
                        v-model="quantity"
                        :allow-negative="false"
                        :precision="2"
                        @blur="chequeo_stock($event)"
                    />

                </div>
                <div class="item-grid11">
                    <label class="form--label" for="mts_by_unity-input">Metros por unidad</label>
                    <currency-input
                        class="form--input"
                        :disabled="!IsCHP"
                        :class="{'isDisabled':!IsCHP}"
                        id="mts_by_unity-input"
                        @focus="$event.target.select()"
                        type="text"
                        :currency="null"
                        locale="es-AR"
                        v-model="mts_by_unity"
                        :allow-negative="false"
                        :precision="2"
                        @blur="chequeo_stock($event)"
                    />
                </div>
                <div class="item-grid5">
                    <MultiselectIva :index="index" />
                </div>
                <div class="item-grid12">
                    <label>Importe Neto</label>
                    <p class="form--input">
                        {{neto | currency}}
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
                        :disabled="isUserOfSale"
                        @focus="$event.target.select()"
                        :currency="null"
                        locale="es-AR"
                        v-model="descuento"
                        :allow-negative="false"
                        :precision="2"
                    />
                </div>
                <div class="item-grid8">
                    <label class="form--label" for="total-input">Total</label>
                    <currency-input
                        class="form--input"
                        :class="{'isUserOfSale':isUserOfSale}"
                        :disabled="isUserOfSale"
                        id="quantity-input"
                        @focus="$event.target.select()"
                        type="text"
                        :currency="null"
                        locale="es-AR"
                        v-model="total"
                        :allow-negative="false"
                        :precision="2"
                    />
                </div>
            </div>
        </div>
        {{CheckSheetMetalStockMessage}}
    </div>
</template>

<script>
export default {
    props : ['index'],

}
</script>

<style >
    .isDisabled {
        color: currentColor;
        cursor: not-allowed;
        opacity: 0.5;
        text-decoration: none;
        pointer-events: none;
    }
</style>
