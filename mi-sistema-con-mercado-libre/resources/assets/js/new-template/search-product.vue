<template>
    <div class="input-group">
        <input type="search" class="form-control" placeholder="Buscar..." v-model="product_name">
        <span class="input-group-btn">
            <button 
                class="btn btn-secondary search-submit" 
                type="button"
                @click="searchProduct"
                    ><i class="fa fa-search"></i>
            </button>
        </span>
    </div>
</template>

<script>
export default {

    data(){
        return {
            product_name : null
        }
    },

    methods : {

        searchProduct(){
            this.$store.commit('SET_PRODUCT_NAME', this.product_name);
        }
    }
}
</script>

<style>

</style>