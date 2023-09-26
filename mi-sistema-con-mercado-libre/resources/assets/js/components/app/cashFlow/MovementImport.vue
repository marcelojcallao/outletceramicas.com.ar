<template>
    <vue-autonumeric
        class="form-control text-center height-autonumeric"
        :options="options"
        v-model="movement_import"
    ></vue-autonumeric>
</template>

<script>
import { mapGetters } from 'vuex';
import VueAutonumeric from './../../../../../../node_modules/vue-autonumeric/src/components/VueAutonumeric';
export default {

    name: 'MovementImport',

    components: {VueAutonumeric},

    data() {
        return {
            options : {
                decimalPlaces : 2,
                digitGroupSeparator: '',
                decimalCharacter: ',',
                decimalCharacterAlternative: '.',
                currencySymbol: '$ ',
                currencySymbolPlacement: 'p',
                roundingMethod: 'U',
                minimumValue: '1',
                modifyValueOnWheel  : false,
                showOnlyNumbersOnFocus : true,
            },
        }
    },

    computed: {

        ...mapGetters(['DailyMovementImportGetter']),

        movement_import: {
            get(){
                return this.DailyMovementImportGetter;
            },
            set(value){
                this.$store.dispatch('setDailyMovementImport', value);
            }
        }
    }

}
</script>

<style scoped>
    .height-autonumeric{
        height: 41px;
    }
</style>