<template>
    <div id="header" style="position: sticky;top: 4rem;">
        <ul class="main_category" >
            <!-- <a  @click="open=!open">{{main_category.name}} - {{main_category.code}}<span> ></span> </a> -->
            <Category :category="main_category"/>
            <template  v-if="main_category.children.length">
                    <sub-categories :sub_categories="main_category.children" />
            </template>
        </ul>
    </div>
</template>

<script>

import SubCategories from "./SubCategories";
import Category from './Category.vue'
    export default {
        props : {
            main_category : {
                type : Object,
                required : true
            }
        },
        components : {SubCategories, Category},

        data(){
            return {
                open : false
            }
        },
        
    }
</script>

<style scoped>
    ul {
        list-style: none;
        padding: 0;
    }
    ul > ul {
        position: relative;
        top : 0px;
    }
    li {
        padding-left: 1;
        padding-bottom: 1rem;
    }
    li:before {
        content: "\f0da"; /* FontAwesome Unicode */
        font-family: FontAwesome;
        display: inline-block;
        margin-left: -1.3em; /* same as padding-left set on li */
        width: 1.3em; /* same as padding-left set on li */
    }
    .title{
        font-weight: 400;
        color: rgb(153, 150, 150);
    }
    .hide-children {
        display: none;
    }
    .show-children{
        display: inline-block;
    }
    a{
        padding: .5rem;
        text-decoration:none;
        color: rgb(83, 83, 83);
        font-weight: bold;
    }
</style>