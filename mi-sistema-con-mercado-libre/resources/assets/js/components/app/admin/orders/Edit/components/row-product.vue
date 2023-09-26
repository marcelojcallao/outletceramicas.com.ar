<template>
    <tr>
        <td class="text-right"  style="width:5%">{{ index }}</td>
        <td class="text-left"   style="width:20%">{{ item.product_name }}</td>
        <td class="text-center" style="width:10%">
            <div>
                <currency-input class="form-control text-center"
                    type="text"
                    @focus="$event.target.select()"
                    :currency="null"
                    locale="es-AR"
                    v-model="quantity"
                    width="50%"
                    :allow-negative="false"
                    :value-as-integer="true"
                    :precision="0"
                />
                {{ (parseInt( item.quantity ) > 1 ) ? 'unidades' : 'unidad' }}
            </div>
            <div v-if="item.isCHP">
                <currency-input class="form-control text-center"
                    type="text"
                    @focus="$event.target.select()"
                    :currency="null"
                    locale="es-AR"
                    v-model="mts_by_unity"
                    width="50%"
                    :allow-negative="false"
                    :precision="2"
                />
                {{ `Metros por chapa.`}}
            </div>
        </td>
        <td class="text-right"  style="width:15%">
			<currency-input class="form-control text-center"
				type="text"
				@focus="$event.target.select()"
				:currency="null"
				locale="es-AR"
				v-model="unit_price"
				width="50%"
				:allow-negative="false"
				:precision="2"
			/>
		</td>
        <!-- <td class="text-right"  style="width:15%">{{item.unit_price | currency}}</td> -->
        <td class="text-right"  style="width:15%">{{item.neto_import | currency}}</td>
        <td class="text-right"  style="width:10%">{{item.iva_import | currency}}</td>
        <td class="text-right"  style="width:15%">{{TotalItem | currency}}</td>
        <td class="text-right"  style="width:10%">
            <deleteProduct :data="OrderEditDataGetter" :index="index" :product="item" tooltip="Eliminar producto" />
        </td>
    </tr>
</template>

<script>
import { mapGetters } from 'vuex';
import deleteProduct from './delete-product.vue';
export default {

    name: 'row-product',

    props: {
        item: {
            required: false
        },
        index: {
            type: Number,
            required: true
        }
    },


    components: {deleteProduct},

    data() {
        return {
            spinner: false
        }
    },

    methods: {

        delete_product(){
            this.$store.dispatch('orderUpdateDeleteProduct', this.index - 1)
        }
    },

    computed: {

        ...mapGetters(['OrderEditDataGetter']),

		unit_price: {
			get(){
                return this.OrderEditDataGetter.items[this.index - 1].unit_price;
            },
			set(value){

				const payload = {
                        index: this.index - 1,
                        value
                    }
				console.log("🚀 ~ file: row-product.vue:103 ~ set ~ payload:", payload)

				this.$store.dispatch('orderUpdateSetUnitPrice', payload);
			}
		},

        quantity: {
            get(){
                return this.OrderEditDataGetter.items[this.index - 1].quantity;
            },
            set: async function(value){

                this.$store.dispatch('checkStockSetProduct', this.OrderEditDataGetter.items[this.index - 1]);

                const result = await this.$store.dispatch('check_stock', value)

                let payload = null;

                if (result.isConfirmed) {

                    payload = {
                        index: this.index - 1,
                        value: result.quantity
                    }

                    this.$store.dispatch('orderUpdateSetQuantityProductAtList', payload);

                }else{

                    payload = {
                        index: this.index - 1,
                        value: null
                    }

                    this.$store.dispatch('orderUpdateSetMtsByUnityProductAtList', payload);
                }

            }

        },

        mts_by_unity: {
            get(){
                return this.OrderEditDataGetter.items[this.index - 1].mts_by_unity;
            },
            set: async function(value){
            console.log("🚀 ~ file: row-product.vue:121 ~ set:function ~ value:", value)

                const result = await this.$store.dispatch('check_sheet_metal_stock', { product_id: this.OrderEditDataGetter.items[this.index - 1].product_id, quantity: this.OrderEditDataGetter.items[this.index - 1].quantity, mts_by_unity: value})

                let payload = null;

                if (result.isConfirmed) {
                    payload = {
                        index: this.index - 1,
                        value
                    }
                    this.$store.dispatch('orderUpdateSetMtsByUnityProductAtList', payload);
                }else{

                    payload = {
                        index: this.index - 1,
                        value: null
                    }

                    this.$store.dispatch('orderUpdateSetMtsByUnityProductAtList', payload);
                }


            }
        },

        TotalItem(){
            return this.OrderEditDataGetter.items[this.index - 1].total_raw;
        }
    },

}
</script>
