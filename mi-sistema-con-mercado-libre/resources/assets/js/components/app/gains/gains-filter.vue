<template>
    <div class="filter-container">
        <div class="filter-product col-md-6">
            <SearchProductMultiselect placeholder="Buscar Producto" name="name" />
        </div>
        <div class="filter-from-date col-md-2">
            <div class="form-group" style="display: flex">
                <GainsFilterFromDate title="Desde" />
            </div>
        </div>
        <div class="filter-to-date col-md-2">
            <GainsFilterToDate title="Hasta" />
        </div>
        <div class="filter-button col-md-2">
            <button
                class="btn btn-primary pull-right"
                @click.prevent="loadData()"
                :class="{ 'spinner spinner-inverse spinner-sm': spinner }"
            >
                Buscar
            </button>
        </div>
        
    </div>
</template>

<script>

import { mapGetters } from "vuex";
import GainsFilterToDate from "./gains-filter-to-date.vue";
import GainsFilterFromDate from "./gains-filter-from-date.vue";
import gainsMixin from "../../../mixins/Gains/gains-mixin";
import SearchProductMultiselect from "../admin/products/List/Search-Product-Multiselect.vue";
export default {

    name: "GainsFilter",

    components: { GainsFilterFromDate, GainsFilterToDate, SearchProductMultiselect },

    mixins: [gainsMixin],
    
    data() {
        return {
            spinner: false,
        };
    },

    computed: {
        ...mapGetters(["GainsFromDateGetter", "GainsToDateGetter", "ListProductsGetter", "GainsPaginationGetter"]),
    },

    
};
</script>

<style scoped>
.filter-container {
  display: flex;
  margin-bottom: 2rem;
}
</style>
