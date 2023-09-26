<template>
    <div id="add-movement-form">
        <div id="movement-date">
            <MovementDate />
            <p class="saldo" :class="(DailyMovementSaldoGetter < 0) ? 'red' : 'green'">Saldo: {{DailyMovementSaldoGetter | currency}}</p>
        </div>
        <MovementForm />
        <MovementList />
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import MovementDate from './MovementDate.vue';
import MovementForm from './MovementForm.vue';
import MovementList from './MovementList.vue'
export default {

    name: 'AddMovementForm',

    components: {MovementDate, MovementForm, MovementList},

    computed: {

        ...mapGetters(['DailyMovementSaldoGetter'])
    }

}
</script>

<style scoped>
    #add-movement-form{
        padding: 2rem;
    }
    #movement-date{
        display: flex;
        justify-content: space-between;
        padding: 1rem;
    }
    .saldo {
        font: bold;
        font-size: large;
        text-transform: uppercase;
    }
    .red{
        color: red;
    }
    .green{
        color: black;
    }
</style>