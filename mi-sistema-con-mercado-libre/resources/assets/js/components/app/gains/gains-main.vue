<template>
    <div>
        <GainsFilter />
        <GainsTable />
    </div>
</template>

<script>
import GainsFilter from './gains-filter.vue';
import GainsTable from './gains-table.vue';

    export default {
    name: "GainsMain",
    
    components: { GainsFilter, GainsTable }
}
</script>

<style lang="scss" scoped>

</style>