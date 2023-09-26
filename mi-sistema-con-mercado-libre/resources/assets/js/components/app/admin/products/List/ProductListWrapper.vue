<template>
    <div class="col-md-12">
        <div class="search-product">
            <p>Búsqueda de productos</p>
            <div id="multiselect">
                <SearchProductMultiselect 
                    :isAjax="true" 
                    name="search-product"
                    placeHolder="Buscar por Código - Nombre"
                />
            </div>
            <button 
                @click="critical_stock"
                class="btn btn-primary"
                v-tooltip="'Exportar a excel productos en stock crítico'"
                :class="{'spinner spinner-inverse spinner-sm' : spinner}"
            >
            Stock crítico</button>
        </div>
        <Product__Table />
    </div>
</template>

<script>
import Product__Table from './ProductTable';
import SearchProductMultiselect from './Search-Product-Multiselect';
import CriticalStockproduct from './../../../../../src/Excel/CriticalStockProduct';
    export default {
        
        components : {
            Product__Table,
            SearchProductMultiselect
        },

        data(){
            return {
                loading : false,
                msg : "Buscando productos",
                spinner: false
            }
        },

        methods: {

            async critical_stock(){

                this.spinner = true;

                const { data:products } = await this.$store.dispatch('critical_stock')
                .catch((err) => {
                    console.log("🚀 ~ file: ProductListWrapper.vue:49 ~ critical_stock ~ err", err)
                    
                })
                .finally(() => this.spinner = false)

                if (products) {

                    const headers = [
                        'PRODUCTO', 'CODIGO', 'STOCK CRITICO', 'STOCK ACTUAL'
                    ]
                    const file_name = `STOCK-CRITICO-${this.$moment().format('DD-MM-YYYY')}.xlsx`;

                    const excel = new CriticalStockproduct(products.flat(), headers, file_name);
                    
                    excel.create_excel();
                }

            }
        }

    }
</script>
<style scoped>
    .search-product{
        display: flex;
        margin-bottom: 2rem;
        justify-content: space-between;
    }
    .search-product p {
        width: 10%;
    }
    #multiselect{
        width: 75%;
    }
    .search-product p{
        width: 20rem;
        font-weight: bold;
        font-size: 1.5rem;
        margin-right: 3rem;
    }
</style>