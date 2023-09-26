<template>
    <div id="header" style="position: sticky;top: 4rem;">
        <ul class="nav_c ">
            <li class="title">CATEGORÍAS</li>
            <li class="main_category" v-for="category in CategoriesList" :key="category.id">
                <template  v-if="category.children.length">
                        <a  @click.stop="click_prevent">{{category.name}} <span>></span> </a>
                        <category_recursive :sub_categories="category.children" />
                </template>
                <category_link v-if="category.children.length == 0" :category="category" />
            </li>
        </ul>
    </div>
</template>

<script>
import category_link from './category-link';
import category_recursive from './category_recursive.vue';
import prepare_categories_mixin from "./../../../mixins/Categories/prepare"
export default {
    
    name : 'categories',

    components : {category_link, category_recursive},
    
    mixins : [prepare_categories_mixin],

    methods : {

        click_prevent(){

        }
    },

    async mounted(){

        const {data} = await this.$store.dispatch('fetchCategories');

        if (data) {
            this.transform_categories(data)
        }
    }
    
}
</script>
<style scoped>
    * {
        padding: 0px;
        margin: 0px;
    }
    #header{
        font-family: "Hind", Verdana, Geneva, Tahoma, sans-serif;
        z-index: 50000;
    }
    ul, ol {
        list-style: none;
    }
    .nav_c li a {
        background-color: #053930;
        color: aliceblue;
        text-decoration: none;
        padding: 1rem 1rem;
        display: block;
        z-index: 50000;
    }
    .nav_c li a:hover{
        background-color: #858585;
    } 
    .nav_c .main_category {
        text-transform : uppercase;
        display: flex;
        flex-direction: column;
        width: 19rem;
    }
    .nav_c li ul {
        display: none;
        position: absolute;
        width: 19rem;
        margin-left: 19rem;
        z-index: 50000;
        overflow: visible;
    }
    .nav_c li:hover > ul {
        display: block;
    }
    .nav_c li ul li {
        position: relative;
        width: 19rem;
        margin-left: 19rem;
        z-index: 50000;
    }
    .nav_c li ul li ul {
        top:0px;
        width: 19rem;
        margin-left: 19rem;
        z-index: 50000;
    }
    .nav_c li ul li ul li ul{
        top:0px;
        width: 19rem;
        margin-left: 19rem;
        z-index: 50000;
    }
    a span {
        float: right;
        font-weight: bold;
    }
    li a {
        font-weight: bold;
    }
    .title{
        margin-top: 5px;
        font-weight: bold;
        text-align: center;
        background-color: #73410a;
        color: beige;
        width: 19rem;
        padding: 1rem 1rem;
        text-transform : uppercase;
    }
    
</style>
    