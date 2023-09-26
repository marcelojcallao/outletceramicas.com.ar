<template>
  <div class="col-6 col-md-4 tt-col-item">
        <div class="tt-product thumbprod-center">
            <div class="tt-image-box" v-if="product.pictures">
                    
                <!-- <a href="#" class="tt-btn-quickview" data-toggle="modal" data-target="#ModalquickView"	data-tooltip="Vista rápida" data-tposition="left"></a>
                <a href="#" class="tt-btn-compare" data-tooltip="Agregar para comparar" data-tposition="left"></a> -->
                <a>
                    <!-- <span class="tt-img-roll-over" v-if="!img_loading"><img src="images/loader.svg" :alt="product.name"></span> -->
                    <span class="tt-img"><img :src="(img_loading) ? product.pictures[0] : 'images/loader.svg'"  :alt="product.name"></span>
                </a>
            </div> 
            <div class="tt-description">
                <div class="tt-row">
                    <ul class="tt-add-info">
                        <li><a href="#">{{product.code}}</a></li>
                    </ul>
                    <!-- <div class="tt-rating">
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star"></i>
                        <i class="icon-star-half"></i>
                        <i class="icon-star-empty"></i>
                    </div> -->
                </div>
                <h2 class="tt-title"><a href="#">{{product.name}}</a></h2>
                <div class="tt-price" v-if="product.hasPrice">
                    {{ product.price | currency }}
                </div>
                <div class="tt-price" v-else>
                    Consultar precio
                </div>
                <!-- <div class="">
                    <div class="tt-row-btn">
                        <a href="#" class="tt-btn-addtocart thumbprod-button-bg" data-toggle="modal" data-target="#modalAddToCartProduct">AGREGAR AL CARRO</a>
                    </div>
                    <div class="tt-row-btn">
                        <a href="#" class="tt-btn-quickview" data-toggle="modal" data-target="#ModalquickView"></a>
                        <a href="#" class="tt-btn-wishlist"></a>
                        <a href="#" class="tt-btn-compare"></a>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</template>

<script>
import random_number from "./../../../mixins/random_number";
export default {

    name : 'product',

    props : {
        product : {
            type : Object,
            required : true
        }
    },

    mixins : [random_number],

    data(){
        return {
            img_loading : false
        }
    },

    mounted(){

        setTimeout(() => {
            this.img_loading = true;
        }, this.random_number(100,350));
    }

    
  
};
</script>
<style scoped>
    
</style>