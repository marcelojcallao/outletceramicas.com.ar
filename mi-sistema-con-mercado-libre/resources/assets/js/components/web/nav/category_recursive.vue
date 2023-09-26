<template>
    <ul> 
        <li v-for="category in sub_categories" :key="category.id">
            
            <template v-if="category.children.length">
                <a @click.prevent="prevent" href="">{{category.name}} <span>></span></a>
                <category_recursive :sub_categories="category.children" />
            </template >

            <!-- <a v-if="category.children.length == 0" href="#">{{category.name}}</a> -->
            <category_link v-if="category.children.length == 0" :category="category" />
        </li>
    </ul>
</template>

<script>
    import category_link from "./category-link.vue";

    export default {
        props : ['sub_categories'],

        name : 'category_recursive',

        components : {category_link},

        methods : {

            prevent(){}
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
        min-width: 19rem;
        margin-left: 19rem;
        z-index: 50000;
        overflow: visible;
    }
    .nav_c li:hover > ul {
        display: block;
    }
    .nav_c li ul li {
        position: relative;
        z-index: 50000;
    }
    .nav_c li ul li ul {
        top:0px;
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