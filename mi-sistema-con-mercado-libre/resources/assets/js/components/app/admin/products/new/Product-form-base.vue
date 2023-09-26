<template>
    <ProductFormSlots>
        <template #categories>
            <div class="col-md-12 separate">
                <div class="col-md-6 separate">
                    <div :class="{'has-error' : errors && errors.hasOwnProperty('categories_path')}">
                        <label class="control-label">Categorías</label>
                        <multiselect_parent_categories ></multiselect_parent_categories>
                        <p v-if="errors && errors.hasOwnProperty('categories_path')">{{errors['categories_path'][0]}}</p>
                    </div>
                </div>
                <div class="col-md-12 separate" v-if=" ChildCategories && ChildCategories.length > 0">
                    <div class="col-md-4" v-for="(child, index) in ChildCategories" :key="index">
                        <div :class="{'has-error' : errors && errors.hasOwnProperty('selected_categories_from_root')}">
                            <label>Subcategorías</label>
                            <multiselect_child_categories :index="index"/>
                            <p v-if="errors && errors.hasOwnProperty('selected_categories_from_root')">{{errors['selected_categories_from_root'][0]}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template #name>
            <div class="col-md-12 separate" >
                <div class="col-md-6" >
                    <div class="form-group-md" :class="{'has-error' : errors && errors.hasOwnProperty('product.name') || $v.product_name.$invalid }">
                        <label class="control-label" >Nombre del Producto</label>
                        <input class="form-control" type="text" placeholder="Nombre" v-model="product_name">
                        <p v-if="errors && errors.hasOwnProperty('product.name')">{{errors['product.name'][0]}}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div >
                        <label class="control-label" >Orden Genealógico</label>
                        <input class="form-control"
                        type="text"
                        placeholder="Código del producto"
                        readonly
                        v-model="category_path">
                    </div>
                </div>
                <div class="col-md-3">
                    <div :class="{'has-error' : errors && errors.hasOwnProperty('product.code') || $v.product_code.$invalid} ">
                        <label class="control-label" >Código</label>
                        <input class="form-control" type="text" placeholder="Código del producto" v-model="product_code">
                        <p v-if="errors && errors.hasOwnProperty('product.code')">{{errors['product.code'][0]}}</p>
                    </div>
                </div>
            </div>
        </template>

        <template #supplier>
            <div class="col-md-12 separate">
                <div class="col-md-3">
                    <label class="control-label" >Cantidad</label>
                    <div :class="{'has-error' : errors && errors.hasOwnProperty('product.stock') || $v.stock.$invalid } ">
                        <currency-input class="form-control text-center"
                            type="text"
                            @focus="$event.target.select()"
                            :currency="null"
                            locale="es-AR"
                            :allow-negative="false"
                            :precision="0"
                            v-model="stock"
                        />
                    <p v-if="errors && errors.hasOwnProperty('product.stock')">{{errors['product.stock'][0]}}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div :class="{'has-error' : errors && errors.hasOwnProperty('product.critical_stock') || $v.critical_stock.$invalid } ">
                        <label class="control-label" >Stock Crítico</label>
                        <currency-input class="form-control text-center"
                            type="text"
                            @focus="$event.target.select()"
                            :currency="null"
                            locale="es-AR"
                            :allow-negative="false"
                            :precision="0"
                            v-model="critical_stock"
                        />
                        <p v-if="errors && errors.hasOwnProperty('product.critical_stock')">{{errors['product.critical_stock'][0]}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div :class="{'has-error' : errors && errors.hasOwnProperty('product.supplier')}">
                        <label class="control-label" >Proveedor</label>
                        <multiselect_provider />
                        <p v-if="errors && errors.hasOwnProperty('product.supplier')">{{errors['product.supplier'][0]}}</p>
                    </div>
                </div>
            </div>
        </template>
        <template #supplier_product>
            <div class="col-md-12 separate">
                <div class="col-md-4" >
                    <div class="form-group-md">
                        <label class="control-label" >Nombre del producto en el proveedor</label>
                        <input class="form-control" type="text"
                            v-model="name_on_supplier"
                        >
                    </div>
                </div>
                <div class="col-md-3" >
                    <div class="form-group-md">
                        <label class="control-label" >Código del producto en el proveedor</label>
                        <input class="form-control" type="text"
                            v-model="code_on_supplier"
                        >
                    </div>
                </div>
                <div class="col-md-3" >
                    <div class="form-group-md" style="padding-top: 2.2rem;">
                        <label class="col-sm-6 control-label">Venta por metro</label>
                        <div class="col-sm-6">
                            <select id="form-control-21" class="custom-select" v-model="isCHP">
                                <option :value="true">SI</option>
                                <option :value="false">NO</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2" >
                    <div class="form-group-md">
                        <label class="control-label" >Metros por unidad</label>
                        <currency-input class="form-control text-center"
                            type="text"
                            @focus="$event.target.select()"
                            ref="mts_by_unity"
                            :currency="null"
                            locale="es-AR"
                            :allow-negative="false"
                            :precision="2"
                            v-model="mts_by_unity"
                            :disabled="! ProductGetter.isCHP"
                        />
                    </div>
                </div>
            </div>
            <div class="col-md-12 separate">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-sm-6 control-label" for="form-control-21">Publicar en la tienda</label>
                        <div class="col-sm-6">
                            <select id="form-control-21" class="custom-select" v-model="publish_on_web">
                                <option :value="true">SI</option>
                                <option :value="false">NO</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-sm-6 control-label" for="form-control-2">Ver precio en la tienda</label>
                        <div class="col-sm-6">
                            <select id="form-control-2" class="custom-select" v-model="see_price_on_web">
                                <option :value="true">SI</option>
                                <option :value="false">NO</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 separate">
                <div class="col-md-4" style="padding-top: 2rem;">
                    <label for="">Aplicar descuento cuando la compra es mayor a: </label>
                    NO
                    <label class="switch switch-primary">
                        <input class="switch-input" type="checkbox" checked="checked" v-model="apply_discount">
                        <span class="switch-track"></span>
                        <span class="switch-thumb"></span>
                    </label>
                    SI
                </div>
                <div class="col-md-2">
                    <label for="">Monto: </label>
                    <currency-input class="form-control text-center"
                        type="text"
                        @focus="$event.target.select()"
                        :currency="null"
                        locale="es-AR"
                        :allow-negative="false"
                        :precision="2"
                        v-model="apply_discount_from"
                        :disabled=" ! ProductGetter.apply_discount"
                    />
                </div>
                <div class="col-md-2">
                    <label for="">Porcentaje </label>
                    <currency-input class="form-control text-center"
                        type="text"
                        @focus="$event.target.select()"
                        :currency="null"
                        locale="es-AR"
                        :allow-negative="false"
                        :precision="2"
                        v-model="apply_discount_percentage"
                        :disabled=" ! ProductGetter.apply_discount"
                    />
                </div>
                <div class="col-md-4">
                    <label for="">Modo de Pago </label>
                    <MultiselectPayMode />
                </div>
            </div>
        </template>

        <template #attributes>
            <div class="col-md-12 separate" v-if="AttributesOfProductGetter.length">
                <h5 >Atributos del producto</h5>
                <attribute v-for="(attr, index) in AttributesOfProductGetter" :key="index" :attr="attr" :index="index"/>
            </div>
        </template>

        <template #price_list>
            <div class="col-md-12 separate">
                <price_list_table />
            </div>
        </template>

        <template #description>
            <div class="col-md-12 separate">
                <h5 >Descripción</h5>
                <ckeditor v-model="description"
                ></ckeditor>
            </div>
        </template>

        <template #upload-images>
            <div class="col-md-12 separate" v-if="pictures.length">
                <ul class="file-list image-flex" >
                    <template v-for="(picture, index) in pictures" >
                        <imageProduct :image="picture" :key="index"/>
                    </template>
                </ul>
            </div>
            <div class="col-md-12 separate">
                <h5 >Imágenes</h5> <small>Se permite haste 5 por producto</small>
                <vue-dropzone
                    ref="dropzoneProductImage"
                    id="images"
                    :options="dropzoneOptions"
                    :vdropzone-sending="sendingEvent"
                    :vdropzone-success="SuccessMethod"
                    :vdropzone-removed-file="fileRemoved"
                    @vdropzone-success-multiple="SuccessMultipleFiles"
                >

                </vue-dropzone>
            </div>
        </template>
        <template #submit-button>
            <div class="text-center">
                <button
                    class="btn btn-primary"
                    @click="upload_product()"
                    :disabled="$v.$invalid"
                >Guardar</button>
            </div>
            <BlockUI :message="msg" v-if="loading"></BlockUI>
        </template>

    </ProductFormSlots>
</template>

<script>
import {mapGetters} from 'vuex';
import collect from 'collect.js';
import vueDropzone from "vue2-dropzone";
import ProductFormSlots from './../Base/Product-Form-Slots';
import imageProduct from './../Edit/imageProduct.vue';
import MultiselectPayMode from './multiselect-pay-mode.vue';
export default {

    name : 'Product-form-base',

    props : {
        buttonSaveName : {
            type : String,
            required : false,
            default : 'Guardar'
        }
    },

    components : { ProductFormSlots, vueDropzone, imageProduct, MultiselectPayMode },

    data(){
        return {
            parent_id : '',
            msg : 'Guardando producto...',
            loading : false,
            errors : null,
            pictures : [], //las muestra cuando edito el producto
        }
    },

    methods : {
        //cuando se ingresa un producto
        add_price_list_formated_to_product(price_lists)
        {
            const price_list = collect(price_lists).map(pl => {
                return {
                    price_list_id : pl.price_list_id,
                    name : pl.name,
                    enabledPriceListToProduct : pl.enabledPriceListToProduct,
                    benefit : pl.benefit,
                    import : 0,
                    price : 0
                }
            }).toArray();

            this.$store.commit('NEW_PRODUCT_ADD_PRICE_LIST', price_list);
        }
    },

    computed : {

        ...mapGetters([
            'ProductPriceBaseGetter',
            'GetEnablePriceList',
            'PriceProductGetter',
            'ProductGetter'
        ]),

        apply_discount: {
            get(){
                return this.ProductGetter.apply_discount;
            },
            set(value){
                this.$store.dispatch('productSetApplyDiscount', value);

                if (! value) {
                    this.$store.dispatch('productSetApplyDiscountFrom', 0);
                    this.$store.dispatch('productSetApplyDiscountPercentage', 0);
                }
            }
        },

        apply_discount_from: {
            get(){
                return this.ProductGetter.apply_discount_from;
            },
            set(value){
                this.$store.dispatch('productSetApplyDiscountFrom', value);
            }
        },

        apply_discount_percentage: {
            get(){
                return this.ProductGetter.apply_discount_percentage;
            },
            set(value){
                this.$store.dispatch('productSetApplyDiscountPercentage', value);
            }
        }
    },

    watch : {

        ProductPriceBaseGetter(n, o){
            this.PriceProductGetter.forEach((pl, index) => {

                let price = 0;

                if (pl.price != 0 ) {
                    price = n
                }

                let payload = {
                    index : index,
                    price : n
                }

                this.$store.commit('NEW_PRODUCT_SET_PRICE', payload);
            });
        },

    },


}
</script>

<style scoped>
    input[type=text] {
        height: 41px;
    }
    .separate {
        margin-bottom: 2rem;
    }
    textarea{
        resize: none;
    }
    .has-error{
        color: red;
    }
    .image-flex{
        display: flex;
        flex-direction: row;
    }
</style>
