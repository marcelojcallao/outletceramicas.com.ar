<template>
    <select class="form-control" id="exampleSelect1" @change="sort">
        <option select>Ordenar por ...</option>
        <option value="name_a_z">Nombre de A -> Z</option>
        <option value="name_z_z">Nombre de Z -> A</option>
        <option value="from_highest_to_lowest">Precio de Mayor a Menor</option>
        <option value="from_lowest_to_highest">Precio de Menor a Mayor</option>
    </select>
</template>

<script>
import {mapGetters} from 'vuex';
export default {

    methods : {

        sort($e){
            this.$store.commit('SET_SORT_FILTER', $e.target.value);

            if ($e.target.value == 'from_highest_to_lowest') {
                this.PublicationsWeb.sort((a,b) => b.price - a.price)
            }

            if ($e.target.value == 'from_lowest_to_highest') {
                this.PublicationsWeb.sort((a,b) => a.price - b.price)
            }
        }
    },

    computed : {

        ...mapGetters([
            'PublicationsWeb'
        ])
    }
}
</script>

<style>

</style>