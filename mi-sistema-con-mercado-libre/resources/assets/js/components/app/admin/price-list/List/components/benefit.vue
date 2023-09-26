<template>
    <div>
        <button
                class="btn btn-outline-danger btn-icon sq-24"
                type="button"
                @click="update_benefit(1)"
                :disabled="spinner_down"
                :class="{'spinner spinner-inverse spinner-sm' : spinner_down}"
                v-tooltip="'Disminuir beneficio'">
                <span class="icon icon-caret-down"></span>
            </button>
            <input
                type="text"
                @focus="$event.target.select()"
                @input="numbers($event)"
                :value="Benefit"
            >
            <button
                class="btn btn-outline-success btn-icon sq-24"
                type="button"
                @click="update_benefit(2)"
                :disabled="spinner_up"
                :class="{'spinner spinner-inverse spinner-sm' : spinner_up}"
                v-tooltip="'Aumentar beneficio'">
                <span class="icon icon-caret-up"></span>
            </button>
    </div>
</template>

<script>
    import sleep_mixin from './../../../../../../mixins/sleep-mixin';
    import benefit_mixin from './../../../../../../mixins/PriceList/Benefit-mixin';
    export default {

        props : ['data', 'index'],

        mixins : [sleep_mixin, benefit_mixin],
    }
</script>

<style scoped>
    div button.btn-outline-danger{
        margin-right: 1.2rem;
    }
    div button.btn-outline-success{
        margin-left: 1.2rem;
    }
    div input{
        height: 2.5rem;
        width: 6rem;
        padding-left: .2rem;
        font-weight: bold;
        text-align: center;
    }
    input:focus{
        border: .2rem solid #0077ff;
        outline: none;
    }
</style>
