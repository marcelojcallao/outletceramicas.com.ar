<template>
    <div class="text-left">
        <label for="add_stock">Cargar Stock  {{MessageStock}}</label>
        <form class="form form-inline">
            <div class="form-group">
                <div class="input-with-icon">
                    <input
                        id="add_stock"
                        class="form-control"
                        type="text"
                        placeholder=""
                        v-model="stock"
                        @focus="$event.target.select()"
                        @blur="isNumber($event)"
                        @keypress="isNumber($event)"
                        :disabled="NewOrderCurrentProduct == null || User.type_user_id === 3"
                    >
                    <div class="icon icon-server input-icon"></div>
                </div>
            </div>

            <button
                class="btn btn-primary btn-xs"
                :class="{'btn btn-primary btn-xs spinner spinner-inverse spinner-sm' : loading_update}"
                type="button"
                :disabled="NewOrderCurrentProduct == null || User.type_user_id === 3"
                @click="add_stock"
            >Guardar</button>

        </form>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    export default {

        name : 'AddStockToproduct',

        props : {
            product : {
                required : false,
            }
        },

        data(){
            return {
                stock : null,
                loading_update : false,
                open : false
            }
        },

        methods : {

            isNumber($event) {
                const quantity = /^[0-9]+$/;

                const char = String.fromCharCode($event.keyCode);
                if (quantity.test(char)) return true;
                else $event.preventDefault();
            },

            dropdown_open()
            {
                this.open = ! this.open;
            },

            async add_stock()
            {
                this.loading_update = true;

                const payload = {
                    product_id : this.product.id,
                    stock : this.stock
                }

                const product = await this.$store.dispatch('add_stock', payload)
                    .catch((err) => {

                        Vue.swal.fire({
                            title: 'Cargar stock a producto',
                            text : 'Ha ocurrido un error, no se pudo cargar stock al producto.',
                            icon : 'error',
                            width : '35%',
                            padding : '2rem',
                            backdrop : true,
                            time : 2000
                        });
                    })
                    .finally(() => this.loading_update = false)

                if (product) {

                    const pr = product.data;

                    this.$store.commit('NEW_ORDER_SET_CURRENT_PRODUCT', pr);

					const payload = {
						id: pr.id,
						value: pr.stock
					}
                    this.$store.commit('NEW_ORDER_SET_STOCK_TO_THE_PRODUCT_IN_THE_FORM', payload);

					this.NewOrderMultiselectProducts.map( product => {
						if ( product.id === pr.id ) {
							console.log("🚀 ~ file: AddStockToProduct.vue:107 ~ product:", product)
							product.stock = pr.stock;
							product.name = 'WWWW'
						}
					})
                    Vue.swal.fire({
                            title: 'Cargar stock a producto',
                            text : `Se agregó stock satisfactoriamente. ${pr.name} tiene ${pr.stock} unidades.`,
                            icon : 'success',
                            width : '35%',
                            padding : '2rem',
                            backdrop : true,
                            time : 2000
                        });
                }
            }
        },

        computed : {

            ...mapGetters([
                'NewOrderCurrentProduct',
				'NewOrderMultiselectProducts'
            ]),

            MessageStock(){
                let message = '';

                if (this.NewOrderCurrentProduct != null) {
                    message = ` - Actualmente hay ${this.NewOrderCurrentProduct.stock} unidades `;
                }
                if (this.NewOrderCurrentProduct && this.NewOrderCurrentProduct.isCHP) {
                    message = ` ${message} de 13 Mts. de longitud `;
                }

                return message;
            }
        }

    }
</script>
<style scoped>
    .dropdown-menu{
        min-width : 360px !important;
    }
</style>
