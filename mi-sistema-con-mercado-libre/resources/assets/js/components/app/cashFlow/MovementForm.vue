<template>
    <div id="movement-form">
        <div id="type">
            <label for="">Movimiento</label>
            <MultiselectMovementType name="Movimiento"/>
        </div>
        <div id="description">
            <label for="">Descripción</label>
            <MultiselectDescription name="Description" />
        </div>
        <div id="import">
            <label for="">Importe</label>
            <MovementImport />
        </div>
        <div id="action">
            <MovementButtonAction />
        </div>
    </div>
</template>

<script>
import MovementImport from './MovementImport.vue';
import MovementButtonAction from './MovementButtonAction.vue';
import MultiselectDescription from './MultiselectDescription.vue';
import MultiselectMovementType from './MultiselectMovementType.vue';
export default {

    name: 'MovementForm',

    components: {MultiselectMovementType, MultiselectDescription, MovementImport, MovementButtonAction}
}
</script>

<style scoped>
    #movement-form{
        display: grid;
        grid-template-columns: 20% 50% 15% 15%;
        grid-gap: 5px;
        margin: 1rem;
        margin-top: 2rem;
        height: 6rem;
    }
    #type{
        padding: 1rem;
    }
    #description{
        padding: 1rem;
    }
    #import{
        padding: 1rem;
    }
    #action{
        padding: 1rem;
        padding-top: 3.5rem;
    }
</style>