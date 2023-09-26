
<template>
    <span class="height">
        <a :class="(category.parent_id == 0)?'isParent':''" href="#">{{category.name}} - {{category.code}}</a> 
        <span v-if="!isEditable">
                <span>
                    <button type="button" 
                        @click="isEditable=!isEditable"
                        class="btn btn-default btn-xs" 
                    >Cambiar nombre</button>
                    <button type="button" 
                        @click="deleteCategory"
                        class="btn btn-xs" 
                        :class="[(category.status)?'btn-danger':'btn-success', {'spinner spinner-inverse spinner-sm' : spinner_status}]"
                    >{{(category.status)?'Desactivar':'Activar'}}</button>
                </span>
        </span>
        <span v-if="isEditable" class="td-flex">
            <input type="text" 
                v-model="category_name" 
                @focus="$event.target.select()"
            >
            <button type="button" 
                class="btn btn-primary btn-xs" 
                :class="{'spinner spinner-inverse spinner-sm' : spinner}"
                @click="update_name()"
            >
            Guardar</button>
            <button type="button" 
                class="btn btn-danger btn-xs"
                @click="isEditable=!isEditable"
            >
            Cancelar</button>
        </span> 
    </span>
</template>

<script>

import { mapGetters } from 'vuex';
import categories_mixin from "./../../../../../mixins/Categories/prepare"
export default {

    name : 'Category',

    props : {
        category : {
            type : Object,
            required : true
        }
    },

    mixins : [categories_mixin],

    data(){
        return {
            isEditable : false,
            spinner : false,
            spinner_status : false,
            category_name : this.category.name,
            //status : this.category.status
        }
    },

    methods : {

        async update_name(){

            this.spinner = true;

            const payload = {
                id : this.category.id,
                name : this.category_name,
            }

            const {data} = await this.$store.dispatch('updateCategoryName', payload)
                .catch((err) => {
                    console.log(err)
                })
                .finally(() => {
                    this.spinner = false
                    this.isEditable = false
                })
            
            if (data) {

                this.category.name = data.name;

                const Toast = Vue.swal.mixin({
                        toast: true,
                        position: 'top-end',
                        timer: 3000,
                        timerProgressBar: true,
                    });

                    Toast.fire({
                        icon: 'success',
                        title: 'Nombre de categoría actualizado correctamente.'
                    });
            }
        },

        async deleteCategory(){
            
            this.spinner_status = true;

            const payload = {
                category_id : this.category.id,
                code : this.category.code,
                status : !this.category.status,
            }

            const {data} = await this.$store.dispatch('setCategoryStatus', payload)
                .catch((err) => {
                    console.log(err)
                })
                .finally(() => {
                    this.spinner_status = false
                    this.isEditable = false
                })
            
            if (data) {

                //this.status = payload.status;

                const Toast = Vue.swal.mixin({
                        toast: true,
                        position: 'top-end',
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    
                const status = (payload.status)
                    ? 'activa' 
                    : 'inactiva';

                Toast.fire({
                    icon: 'success',
                    title: `La Categoría está ${status}.`
                });
                
                
                data.forEach(element => {
                    
                    this.OriginalCategoriesListGetter.forEach(category => {
                        if (category.code == element.code) {
                            category.name = element.name;
                            category.status = element.active;
                        }
                    })
                });

                this.transform_categories(this.OriginalCategoriesListGetter);

            }
        },
        
    },

    computed : {

        ...mapGetters(['CategoriesList', 'OriginalCategoriesListGetter'])
    }
}
</script>

<style scoped>
    .height{
        margin-top: 1rem;
        margin-bottom: 1rem;
    }
    a {
        margin-right: 2rem;
    }
    .isParent {
        text-decoration: none;
        font-weight: bold;
        color: black;
    }
</style>