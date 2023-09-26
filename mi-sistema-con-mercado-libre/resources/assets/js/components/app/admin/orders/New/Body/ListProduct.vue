<template>
    <div class="scroll-y">
        <table class="my--table table-hover table-bordered">
            <thead>
                <tr>
                    <th width="5%" class="text-center">#</th>
                    <th width="35%" class="text-center">Nombre</th>
                    <th width="10%" class="text-center">P./Un.</th>
                    <th width="10%" class="text-center">Cantidad</th>
                    <th width="10%" class="text-center">Neto</th>
                    <th width="10%" class="text-center">Desc.</th>
                    <!-- <th width="10%" class="text-center">Iva</th> -->
                    <th width="15%" class="text-center">Total</th>
                    <th width="5%" class="text-center">Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(product, index) in ProductFromNewOrder" >
                    <tr :key="index">
                        <td class="text-center">{{ index + 1 }}</td>
                        <td class="text-left"
                            v-tooltip="(product.product != null) ? product.product.name : ''"
                        >{{( product && (product.product != null)) ? product.product.name.substring(0,31) : '' }}</td>
                        <td class="text-right">
                            <Editable_Td v-if="User.type_user_id == 1"
                                :index="index"
                                :value="product.unit_price | currency"
                                :mutation="'newOrdersetUnitPriceProduct'"
                            />
                            <span v-else>{{ product.unit_price | currency }}</span>
                        </td>
                        <td class="text-center">
                            <Editable_Td v-if="(User.type_user_id === 1 || User.type_user_id === 3)"
                                :index="index"
                                :value="(product.isCHP) ? parseFloat(product.mts_to_invoiced) : parseFloat(product.quantity)"
                                :mutation="'newOrderSetQuantityProduct'"
                            />
                            <span v-else>{{ (product.isCHP) ? parseFloat(product.mts_to_invoiced) : parseFloat(product.quantity) }}</span>
                        </td>
                        <td class="text-right">{{product.neto | currency}}</td>
                        <td class="text-right">
                            <Editable_Td v-if="User.type_user_id == 1"
                                :index="index"
                                :value="product.descuento | currency"
                                :mutation="'newOrderSetDiscountProduct'"
                            />
                            <span v-else>{{ product.descuento | currency }}</span>
                        </td>
                        <!-- <td class="text-right">{{product.iva_import | currency}}</td> -->
                        <td class="text-right">{{(product.neto - product.descuento) | currency}}</td>
                        <td class="text-center">
                            <DeleteRow :index="index" />
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import RowProduct from './RowProduct';
    import DeleteRow from './Row/DeleteRowButton';
    import Editable_Td from './Row/Editable-td';
    export default {

        components : {
            RowProduct,
            DeleteRow,
            Editable_Td,
        },

        computed : {

            ...mapGetters([
                'ProductFromNewOrder'
            ])
        },

    }
</script>

<style scoped>
    thead th {
        padding : 1rem;
        position : sticky;
        top : 0;
        background-color : lightgray;
        color : black;
        z-index : 50;
    }
    .my--table {
        position : relative;
        width : 100%;
    }
    .scroll-y {
        height:40rem;
        overflow-y: auto;
    }
    tbody tr {
        height: 3rem;
    }
	tr td{
		padding: .5rem 1rem;

	}

</style>
