<template>
    <MultiselectBase 
        :options="AfipWsEnvironmetMultiselect"
        name="afip_environment"
        :value="AfipWsEnvironmet"
        :searchable="false"
    />
</template>

<script>
import {mapGetters} from 'vuex';
import MultiselectBase from '../../Base/Multiselect/MultiselectBase.vue';
export default {

    components : {MultiselectBase},

    computed : {
     
        ...mapGetters([
            'AfipWsEnvironmet',
            'AfipWsEnvironmetMultiselect'
        ]),
    },

    methods : {

        selected(){},
        remove(){}
    }

}
</script>