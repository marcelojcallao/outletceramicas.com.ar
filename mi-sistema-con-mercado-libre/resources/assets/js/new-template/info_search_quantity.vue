
<template>
    <span>{{Message}}</span>
</template>

<script>
import { mapGetters } from 'vuex';
export default {

    computed : {

        ...mapGetters([
            'Pagination'
        ]),

        Message(){
            return `Mostrando ${this.Pagination.from}-${this.Pagination.to} de ${this.Pagination.total}.`
        }
    }
}
</script>

<style>

</style>