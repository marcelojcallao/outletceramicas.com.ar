<template>
    <menu_www :categories="CategoriesList"/>
</template>

<script>
import menu_www from "./categories.vue"
import {mapGetters} from 'vuex';
export default {
    name : 'mobile_menu',

    components : {menu_www},

    computed : {

        ...mapGetters(['CategoriesList'])
    }
}
</script>

<style>

</style>