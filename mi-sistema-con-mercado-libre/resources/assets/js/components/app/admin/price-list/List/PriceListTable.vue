<template>
    <div>
        <label class="control-label">Listado</label>
        <v-client-table
                ref="price_list_table"
                id="price_list_table"
                :columns="columns"
                :data="rows"
                :options="options"
            >
        </v-client-table>
    </div>
</template>

<script>
    import {Event} from 'vue-tables-2';
    import {mapGetters, mapActions} from 'vuex';
    import benefitComponent from './components/benefit.vue';
    import StatusButton from './../../../../Base/Button/StatusButton.vue';
	import EditPriceList from './components/EditPriceList.vue';
	import EditName from './components/EditName.vue';
    export default {

        data(){
            return {
                columns : [
                    'id',
                    'name',
                    'benefit',
                    'enable',
                    'edit',
                ],
                rows : [],
                options : {
                    orderBy: { column: false },
                    sortable: [],
                    uniqueKey : 'id',
                    perPage : 31,
                    pagination: { dropdown:false },
                    headings: {
                        id : '#',
                        name : 'Nombre',
                        benefit : 'Beneficio',
                        enable : 'Estado',
                        edit : 'Editar',
                    },
                    templates: {
						name: EditName,
						benefit : benefitComponent,
						enable : StatusButton,
						edit : EditPriceList
                    },
                    columnsClasses: {
                        id : 'col-md-2 text-center',
                        name : 'col-md-3 text-left',
                        benefit : 'col-md-3 text-center',
                        enable : 'col-md-2 text-center',
                        edit : 'col-md-2 text-center',

                    },

                    toggleGroups : true,
                    showChildRowToggler : true,
                    filterable: false,
                }
            }
        },

        methods : {

            ...mapActions([
                'priceListGetListAction'
            ]),
        },

        computed : {

            ...mapGetters([
                'PriceListListGetter'
            ])
        },

        watch : {

            PriceListListGetter(n){
                this.rows = n;
            }
        },

        async mounted(){

            const {data} = await this.priceListGetListAction(this.User.token)
                .catch(err => console.log('Estoy en pricelist table mounted'));

            if (data) {
                console.log("🚀 ~ mounted ~ data:", data)
                this.$store.commit('PRICE_LIST_SET_LIST', data);
            }

            Event.$on('PriceListWasCreated', new_price_list => {
                this.$refs.price_list_table.tableData.unshift(new_price_list)
                //console.log(new_price_list);
                console.log(new_price_list);
            });

			Event.$on('update_price_list_name', (event)=>{

				const index = this.$refs.price_list_table.tableData.findIndex((row, index) => row.id === event.id);
				this.$refs.price_list_table.tableData[index].name = event.name;
			})

			Event.$on('update_price_list_enable', (event)=>{

				const index = this.$refs.price_list_table.tableData.findIndex((row, index) => row.id === event.id);
				this.$refs.price_list_table.tableData[index].enable = event.enable;
			})

        }
    }
</script>

<style>
    div label {
        font-size: 2rem;
    }
	#price_list_table table thead tr th:nth-child(1){
		width: 3%;
	}
	#price_list_table table thead tr th:nth-child(2){
		width: 50%;
	}
	#price_list_table table thead tr th:nth-child(3){
		width: 8%;
	}
	#price_list_table table thead tr th:nth-child(4){
		width: 8%;
	}
	#price_list_table table thead tr th:nth-child(5){
		width: 13%;
	}
	#price_list_table table thead tr th:nth-child(6){
		width: 18%;
	}
</style>
