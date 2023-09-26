<template>
    <li class="file">
        <a class="file-link" @click.prevent="" :title="image.name" :download="image.name">
            <div class="file-thumbnail" :style="{ 'background-image': 'url(' + image.url + ')' }"></div>
            <div v-if="loading" class="spinner spinner-primary"></div>
            <div class="file-info" v-else>
                <span class="file-name">{{image.name}}</span>
            </div>
        </a>
        <button 
            class="file-delete-btn delete" 
            title="Eliminar imagen" 
            type="button"
            @click="image_remove"
            >
            <span class="icon icon-remove"></span>
        </button>
    </li>
</template>

<script>
import {Event} from 'vue-tables-2';

export default {

    name : 'imageProduct',

    props : {
        image : {
            required : true
        }
    },

    data(){
        return{
            loading : true
        }
    },

    methods : {

        random_number(min, max){
            return Math.floor(Math.random() * (max - min)) + min;
        },

        async image_remove(){

            this.loading = true;

            const image = await this.$store.dispatch('image_remove', this.image)
                .catch((err) => {
                    this.error_message(err.response.data, 'Producto')
                })
                .finally(()=> this.loading = false);
            
            if (image) {
                
                Event.$emit('image_remove', image.id)
                
            }
        }
    },

    mounted(){

        setTimeout(() => {
            this.loading = false;
        }, this.random_number(750, 2500));
    }
}
</script>

<style scoped>
    
</style>