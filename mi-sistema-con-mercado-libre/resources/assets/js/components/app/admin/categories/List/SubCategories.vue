<template>
    <ul class="sub-category"> 
        <li v-for="category in sub_categories" :key="category.id">
            <template v-if="category.children.length">
                <!-- <a class="category-name" @click.prevent="prevent" href="">{{category.name}} - {{category.code}}<span> ></span></a> -->
                <Category :category="category"/>
                <SubCategories :sub_categories="category.children" />
            </template >
            <Category v-if="category.children.length == 0" :category="category"/>
        </li>
    </ul>
</template>

<script>
import Category from './Category.vue'
export default {

    props : ['sub_categories'],

    name : 'SubCategories',

    components : {Category},

    data(){
        return {
            open : false
        }
    },

    methods : {

        prevent(){}
    }
}
</script>

<style scoped>
    ul {
        list-style: none;
        padding: 0;
    }
    .sub-category{
        position: relative;
        top : 0;
        left: 2rem;
    }
    .category-name{
        padding: .5rem;
        text-decoration:none;
        color: rgb(167, 165, 165);
        font-weight: bold;
    }
    ul li {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

</style>