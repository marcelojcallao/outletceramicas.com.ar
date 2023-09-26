<template>
    <div>
        <div class="insert">
            <label>Ingreso de recortes de chapas</label>
            <div class="flex">
                <div class="item-1">
                    <label for="">Chapa</label>
                    <SearchSheetmetalCutting name="Chapas"/>
                </div>
                <div class="item-2">
                    <label for="">Cantidad</label>
                    <input type="text" class="form-control" v-model="quantity">
                </div>
                <div class="item-3">
                    <label for="">Metros</label>
                    <input type="text" class="form-control" v-model="mts">
                </div>
                <div class="item-4">
                    <button
                        class="btn btn-primary"
                        :class="{'btn btn-primary spinner spinner-inverse spinner-sm' : spinner}"
                        @click="save"
                    >
                        Guardar
                    </button>
                </div>
                <div class="item-5">
                    <button
                        class="btn btn-danger"
                        :class="{'btn btn-primary spinner spinner-inverse spinner-sm' : spinner}"
                        @click="delete_sheet_metal_cuttings"
                    >
                        Eliminar todos los recortes
                    </button>
                </div>
            </div>
        </div>
        <div class="search">
            <label for="">Seleccione una chapa para ver sus recortes</label>
                <SearchByNameSheetMetalCutting name="Chapas"/>
        </div>
    </div>
</template>

<script>
import { Event } from 'vue-tables-2';
import {mapGetters} from 'vuex';
import SearchSheetmetalCutting from './SearchSheetMetalCutting.vue';
import SearchByNameSheetMetalCutting from '../delete/SearchByNameSheetMetalCutting.vue';
export default {

    name : 'InsertSheetmetalCutting',

    components : {SearchSheetmetalCutting, SearchByNameSheetMetalCutting},

    data(){
        return {
            spinner : false,
            spinner_delete : false,
        }
    },

    computed : {

        ...mapGetters([
            'NewSheetMetalCuttingQuantityGetters',
            'NewSheetMetalCuttingMtsGetters',
            'NewSheetMetalCutting'
        ]),

        quantity : {
            get(){
                return this.NewSheetMetalCuttingQuantityGetters;
            },
            set(value){
                this.$store.dispatch('newSheetMetalCuttingSetQuantity', value);
            }
        },

        mts : {
            get(){
                return this.NewSheetMetalCuttingMtsGetters;
            },
            set(value){
                this.$store.dispatch('newSheetMetalCuttingSetMts', value);
            }
        }
    },

    methods : {

        async save(){

            this.spinner = true;

            if (this.NewSheetMetalCuttingMtsGetters > 13) {
                this.spinner = false;
                Vue.swal.fire({
                    title: 'Recortes de Chapas',
                    text : 'Los recortes de chapa no pueden tener 13 o más metros.',
                    icon : 'error',
                    width : '35%',
                    padding : '2rem',
                    backdrop : true,
                    time : 2000
                });
                return false;
            }
            const new_shett_metal_cutting = await this.$store.dispatch('saveSheetMetalCutting', this.NewSheetMetalCutting)
            .catch((e => {
                if (e.response.status == 422) {
                
                        Vue.swal.fire({
                            title: 'Recortes de Chapas',
                            text : 'No se pudo guardar el recorte, verifique que los campos estén completos.',
                            icon : 'error',
                            width : '35%',
                            padding : '2rem',
                            backdrop : true,
                            time : 2000
                        });
                }
            }))
            .finally(() => this.spinner = false);

            if (new_shett_metal_cutting) {
                const Toast = Vue.swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });

                    Toast.fire({
                        icon: 'success',
                        title: 'Recorte guardado correctamente.'
                    });

                this.$store.dispatch('setSheetMetalCutting', new_shett_metal_cutting.data)
                this.$store.dispatch('newSheetMetalCuttingSetQuantity', '');
                this.$store.dispatch('newSheetMetalCuttingSetMts', '');
            }
            
        },

        async delete_sheet_metal_cuttings(){

            const result = await Vue.swal.fire({
                    title: 'ATENCIÓN',
                    text: `¿Desea eliminar TODOS!!! los recortes de chapas?`,
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Continuar',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Cancelar'
                });

            if (result.isConfirmed) await this.removes_sheet_metal_cuttings();
            
        }, 


        async removes_sheet_metal_cuttings(){

            this.spinner_delete = true;

            const response = await this.$store.dispatch('deleteSheetMetalCuttings')
                .catch(e => {
                    Vue.swal.fire({
                        title: 'Error al eliminar los recortes de chapas',
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Continuar',
                    });
                })
                .finally(() => this.spinner_delete = false)

            if (response.status === 200) {

                Event.$emit('sheet_metal_cuttings_deleted');

                const Toast = Vue.swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                });

                Toast.fire({
                    icon: 'success',
                    title: 'Recortes de chapas eliminados correctamente.'
                });
                
            }
        }
    }
}
</script>

<style scoped>
    .flex {
        display: flex;
        flex-direction: row;
        margin-bottom: 2rem;
    }
    [class^=item]{
        padding: 10px;
    }
    .item-1{
        width: 100%;
    }
    .item-4, .item-5{
        padding-top: 3.1rem;
    }
    .search{
        margin: 2rem;
    }
</style>