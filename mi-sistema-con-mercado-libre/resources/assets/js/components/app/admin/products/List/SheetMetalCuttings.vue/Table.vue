<template>
    <div>
        <InsertSheetmetalCutting />
        <v-client-table
            ref="sheet_metal_cuttings_table"
            :columns="columns"
            :data="rows"
            :options="options"
        >

        </v-client-table>
        <div class="col-md-12 text-center">
            <paginate
                :page-count="pageCount"
                :click-handler="loadData"
                :prev-text="'Ant.'"
                :next-text="'Sig.'"
                :container-class="'pagination'">
            </paginate>
        </div>
        <BlockUI :message="msg" v-if="loading"></BlockUI>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import { Event } from 'vue-tables-2';
import DeleteSheetMetalCutting from './delete/DeleteSheetMetalCutting.vue';
import InsertSheetmetalCutting from "./Insert/InsertSheetMetalCutting.vue"
import Paginate from 'vuejs-paginate';
    export default {

        components : {Paginate, InsertSheetmetalCutting},

        data(){
            return {
                msg : 'Buscando datos...',
                loading : false,
                pageCount : 1,
                page : 1,
                columns : [
                    'number',
                    'name',
                    'quantity',
                    'mts',
                    'remove'
                ],
                rows : [],
                options: {
                    orderBy: { column: false },
                    sortable: [],
                    uniqueKey : 'id',
                    perPage : 31,
                    pagination: { dropdown:false },
                    headings: {
                        number : '#',
                        name : 'Producto',
                        quantity : 'Cantidad',
                        mts : 'Metros',
                        remove: 'Eliminar'
                    },
                    templates: {
                        remove: DeleteSheetMetalCutting
                    },
                    columnsClasses: {
                        number : 'col-md-1 text-center',
                        name : 'col-md-6 text-left',
                        quantity : 'col-md-2 text-center',
                        mts : 'col-md-2 text-center',
                        remove : 'col-md-1 text-center',
                    },
                    filterable: false,
                }
            }
        },

        computed : {
            ...mapGetters([
                'SheetMetalCuttingsGetters'
            ])
        },

        watch : {

            SheetMetalCuttingsGetters(n)
            {
                this.rows = n;
            }

        },

        methods : {

            async loadData(page=1){

                this.loading = true;

                const payload = {
                    page : page,
                    token : this.User.token
                }

                const response = await this.$store.dispatch('loadData', payload)
                .finally(() => this.loading = false);

                if (response) {
                    this.pageCount = response.data.pagination.last_page;
                    this.$store.commit('ADD_PRODUCTS_TO_LIST_PRODUCTS', response.data.data);
                }

            }
        },

        async mounted(){

            this.loading = true;

            const sheet_metal_cuttings_stock = await this.$store.dispatch('getSheetMetalCuttings')
                .catch((err) => {
                    console.log(err)
                })
                .finally(() => this.loading = false);

            if (sheet_metal_cuttings_stock) {
                this.$store.dispatch('setSheetMetalCutting', sheet_metal_cuttings_stock.data)
            }

            Event.$on('sheet_metal_cuttings_deleted', () => {
                this.$refs.sheet_metal_cuttings_table.tableData.map((smc, index) => {
                    this.$refs.sheet_metal_cuttings_table.tableData.splice(index, 1);
                });

            })

        }
    }
</script>
