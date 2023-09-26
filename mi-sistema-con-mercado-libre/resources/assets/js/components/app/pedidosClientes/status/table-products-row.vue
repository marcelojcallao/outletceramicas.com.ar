<template>
    <tr>
        <td class="text-center" >{{ index + 1 }}</td>
        <td class="text-left" >{{product.product_name}}</td>
        <td class="text-left" >
            <currency-input class="form--input"
                :class="{'isDisabled':IsSendingToAfip}"
                type="text" 
                v-model="unit_price"
                @focus="$event.target.select()" 
                currency="ARS"
                locale="es-AR"
                :allow-negative="false"
                :precision="2"
            />
        </td>
        <td class="text-right"  >{{product.quantity}}</td>
        <td class="text-right"  >{{product.discount_import | currency}}</td>
        <td class="text-right input-inside-td" >
            <currency-input class="form--input"
                :class="{'isDisabled':IsSendingToAfip}"
                type="text" 
                v-model="neto_import"
                @focus="$event.target.select()" 
                currency="ARS"
                locale="es-AR"
                :allow-negative="false"
                :precision="2"
            />
        </td>
        <td class="text-right input-inside-td" >
            {{product.iva_import | currency}}
        </td>
        <td class="text-right" style="padding-right: 1.5rem;">{{product.total_raw | currency}}</td>        
    </tr>
</template>

<script>
import { mapGetters } from 'vuex';

    export default {

        props: {
            index: {
                required: true,
                type: Number
            },
            product: {
                required: true,
                type: Object
            }
        },

        computed: {

            ...mapGetters([
                'ItemsToFacturar',
                'IsSendingToAfip'
            ]),

            unit_price: {
                get(){

                    const value_import = this.ItemsToFacturar[this.index].unit_price;

                    return parseFloat(value_import);
                },
                set(value){

                    const index = this.index;

                    this.$store.dispatch('setItemsToFacturarUnitPrice', { index, value: parseFloat(value) });
                }
            },

            neto_import: {
                get(){

                    const value_neto_import = parseFloat(this.ItemsToFacturar[this.index].neto_import);

                    return value_neto_import;
                },
                set(value){

                    const index = this.index;

                    this.$store.dispatch('setItemsToFacturarNetoImport', { index, value: parseFloat(value) });
                }
            },

            isSendingToAfip(){
                return this.IsSendingToAfip;
            }
        }
    }
</script>
<style scoped>
.form--input{
        width: 100%;
        background-color: white;
        border: 1px solid #e8e8e8;
        border-radius: 5px;
        height: 3.6rem;
        line-height: 2rem;
        padding: .8rem;
        transition: .3s ease all;
        text-align: right;
    }
    .form--input:focus{
        border: .2rem solid #0077ff;
        outline: none;
        box-shadow: .2rem 0rem 2rem rgba(163, 163, 163, .5);
    }
    .isDisabled {
        color: currentColor;
        cursor: not-allowed;
        opacity: 0.5;
        text-decoration: none;
        pointer-events: none;
    }
    tr td:nth-child(1),
    tr td:nth-child(2),
    tr td:nth-child(4),
    tr td:nth-child(5),
    tr td:nth-child(7),
    tr td:nth-child(8){
        vertical-align: middle;
    }
</style>