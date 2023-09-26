<template>
    <div style="min-height:300px">
        <div class="price-base">
            <p>Lista de precios</p>
            <div class="costo">
                <p>Precio Base</p>
                <input 
                    type="text" 
                    class="form-control" 
                    @focus="$event.target.select()"
                    v-model="price_base">
            </div>
        </div>
        <table class="table table-hover table-bordered">
            
            <thead>
                <tr>
                    <th class="text-center" width="10%">#</th>
                    <th class="text-center" width="10%">Habilitar para este producto</th>
                    <th class="text-center" width="40%">Lista de precio</th>
                    <th class="text-center" width="10%">Beneficio (%)</th>
                    <th class="text-center" width="10%">Costo</th>
                    <th class="text-center" width="10%">Importe a publicar</th>
                    <th class="text-center" width="10%">Ganancia</th>
                </tr>
            </thead>
            <tbody>
                <price_list_row v-for="(price_list, index) in PriceProductGetter" :key="index" :index="index" :price_list="price_list" >
                    
                </price_list_row>
            </tbody>
        </table>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import collect from 'collect.js';
import price_list_row from './price-list-row';
import sleep_mixin from './../../../../../mixins/sleep-mixin';
    export default {

        components : {price_list_row},

        mixins : [sleep_mixin],

        computed : {
            ...mapGetters([
                'ProductGetter',
                'PriceProductGetter',
            ]),

            price_base : {
                get(){
                    return this.ProductGetter.price_base;
                },
                set(value){
                    this.$store.commit('NEW_PRODUCT_SET_PRICE_BASE', value);
                }
            }
        },

    }
</script>

<style scoped>
    table caption{
        font-weight: bold;
        font-size: 1.5rem;
        color: rgba(0,0,0,.5);
    }
    .price-base, .costo{
        display: flex;
        justify-content: space-between;
    }
</style>