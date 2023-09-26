<template>
    <nav class="panel-menu mobile-main-menu">
        <ul>
            <li v-for="(category) in CategoriesList" :key="category.id">
                <a href="#">{{category.name}}</a>
                <!-- <template  v-if="category.children.length">
                    <mobile_categories :categories="category.children" />
                </template> -->

                <ul class="tt-list-row"  v-if="category.children.length == 0">
                    <categoty-link :category="category" />
                </ul>
            </li>
        </ul>
        <div class="mm-navbtn-names">
            <div class="mm-closebtn">Cerrar</div>
            <div class="mm-backbtn">Volver</div>
        </div>
    </nav>
</template>

<script>
import CategoryLink from "./../../nav/category-link.vue";
import prepare_categories from "./../../../../mixins/Categories/prepare";
export default {

    name : 'mobile_categories',

    props : {
        categories : {
            required : true,
            type : Array
        }
    },

    components :{CategoryLink},

    mixins : [prepare_categories],

}
</script>

<style>

</style>