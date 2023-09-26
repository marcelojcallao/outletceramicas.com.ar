<template>
    <div class="card">
        <div class="card-header">
            <strong>Detalle de Productos</strong>
            <button class="btn btn-primary" @click="showPanelAddProduct">Agregar producto</button>
        </div>
        <div class="card-body" style="height: 400px;" id="list-products">
            <table class="table table-hover table-borderless">
                <thead>
                    <tr>
                        <th class="text-center" style="width:5%">#</th>
                        <th class="text-center" style="width:20%">Producto</th>
                        <th class="text-center" style="width:10%">Cantidad</th>
                        <th class="text-center" style="width:15%">Precio unitario</th>
                        <th class="text-center" style="width:15%">Neto</th>
                        <th class="text-center" style="width:10%">Iva</th>
                        <th class="text-center" style="width:15%">Total (iva Incl.)</th>
                        <th class="text-center" style="width:10%">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <RowProduct v-for="(item, index) in OrderEditDataGetter.items" :key="index" :index="index + 1" :item="item" />
                </tbody>
            </table>
            <tfoot>
                <p>Neto: {{TotalNeto | currency}}</p>
                <p>Total: {{Total | currency}}</p>
            </tfoot>
        </div>
        <div class="card-footer">
            <div class="buttons">
                <button 
                    class="btn btn-primary"
                    :class="{'btn btn-primary spinner spinner-inverse spinner-sm' : spinner}"
                    @click="updatePedido"
                    :disabled="$v.$invalid"
                >
                    Guardar cambios
                </button>
                <button 
                    class="btn btn-default"
                    @click="closeEditPanel"
                >
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import { Event } from 'vue-tables-2';
import RowProduct from './row-product.vue';
import AddProduct from './../../../../pedidosClientes/panel/panelAddProduct.vue';
import { required, minValue } from 'vuelidate/lib/validators'
export default {

    name: 'list-products',

    components: {
        RowProduct,
        AddProduct
    },

    data() {
        return {
            spinner: false
        }
    },

    methods: {
        
        async updatePedido(){

            this.spinner = true;

            this.OrderEditDataGetter.customer = {};
            this.OrderEditDataGetter.date = this.OrderEditDataGetter.created_date;
            this.OrderEditDataGetter.customer.id = this.OrderEditDataGetter.customer_id;
            this.OrderEditDataGetter.pricelist_id = this.NewOrderPriceListsProductGetter.id;

            const data = await this.$store.dispatch('orderUpdateSaveCurrentOrder', this.OrderEditDataGetter);

            if(data){

                this.$store.commit('SET_PEDIDO_LIST_CHILD_ROW_REACTIVITY_DATA', data);
                
                const Toast = Vue.swal.mixin({
                        toast: true,
                        position: 'top-end',
                        timer: 4000,
                        timerProgressBar: true,
                    });

                    Toast.fire({
                        icon: 'success',
                        title: 'El pedido ha sido actualizado correctamente.'
                    });
            }


            this.closeEditPanel();
        },

        closeEditPanel(){
            this.$store.dispatch('orderUpdateNewProductSetInitialStatus');
            Event.$emit('closeEditPedidoPanel');
        },

        showPanelAddProduct() {
            
            const panel1Handle = this.$showPanel({
                component : AddProduct,
                openOn: 'top',
                height: '500px',
                props: {
                    //any data you want passed to your component
                }
            })

            panel1Handle.promise
            .then(result => {
                
            });
        },
    },

    computed: {
        
        ...mapGetters([
            'OrderEditDataGetter', 
            'NewOrderPriceListsProductGetter',
            'OrderEditDataGetterItems'
        ]),

        TotalNeto(){

            const { items } = this.OrderEditDataGetter;

            return items.reduce((acc, item) => acc + parseFloat(item.neto_import), 0 ) ;
        },

        Total(){

            const { items } = this.OrderEditDataGetter;

            return items.reduce((acc, item) => acc + parseFloat(item.total_raw), 0 ) ;
        }
    },

    validations(){
        const self = this;
        return {
            Total : {
                required,
                minValue: minValue(1)
            },

            TotalNeto: {
                required,
                minValue: minValue(1)
            }
        }
    },
}
</script>

<style scoped>
    tfoot p{
        padding: 2rem;
    }
    .buttons{
        display: flex;
        height: 31px;
        width: 100%;
        justify-content: center;
    }

    .buttons button {
        margin-left: 1rem;
    }
    .buttons p {
        margin: .5rem;
    }
    .totals{
        height: 4rem;
        width: 100%;
        padding: 1rem;
        margin-top: 250px;
        margin-bottom: 3rem;
        display: flex;
        justify-content: space-between;
        font-size: large;
    }
    .card-header{
        display: flex;
        justify-content: space-between;
    }
    .footer{
        width: 100%;
        padding: 0 31px;
        display: flex;
        justify-content: space-between;
    }
    .footer p {
        font-size: large;
    }
    /* #list-products tbody {
        width: 100%;
        display: block;
        height: 250px;       
        overflow-y: auto;    
    }
    #list-products tbody tr{
        width: 100%;
        display: block;
    } */
    table {
        width: 100%;
        border-spacing: 0;
    }

#list-products thead, tbody, tr, th, td { display: block; }

#list-products thead  tr {
    background-color: gray !important;
}

#list-products tr:after {  /* clearing float */
    content: ' ';
    display: block;
    visibility: hidden;
    clear: both;
}

#list-products tbody {
    height: 300px;
    overflow-y: auto;
    overflow-x: hidden;
}

#list-products tbody td, thead th {
    width: 19%;  /* 19% is less than (100% / 5 cols) = 20% */
    float: left;
}

</style>