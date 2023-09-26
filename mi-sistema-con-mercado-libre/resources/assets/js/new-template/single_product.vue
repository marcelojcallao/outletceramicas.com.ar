<template>
    <div class="product-thumb">
        <div class="image">
            <a @click.prevent="" href="single-product.html">
                <img :src="product.pictures.length?product.pictures[0]:'images/not-image.png'" alt="Samsung Galaxy Tab 10.1" title="Samsung Galaxy Tab 10.1" class="img-fluid">
            </a>
        </div>
        <div>
            <div class="caption">
                <h4><a href="" @click.prevent="click_prevent">{{product.name}}</a></h4>
                <p class="price">{{product.price | currency}}</p>
                <div class="rating">
                    <p>{{product.stock}} disponibles</p>
                </div>
            </div>
            <h5 class="code">{{product.code}}</h5>
            <div class="button-group">
                <button type="button" class="btn btn btn-add-to-cart"><span>Agregar al carrito</span></button>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    props : {
        product : {
            type : Object,
            required : true
        }
    },

    methods : {
        click_prevent(){}
    },
}
</script>
<style scoped>
    .code{
        width: 100;
        align-content: center;
        font-weight: 200;
        color: #ccc;
    }
</style>