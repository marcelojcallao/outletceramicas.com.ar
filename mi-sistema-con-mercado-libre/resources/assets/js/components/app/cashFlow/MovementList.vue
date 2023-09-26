<template>
    <table id="movement-list" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th class="number_h">#</th>
                <th class="date_h">Fecha</th>
                <th class="type_h">Tipo</th>
                <th class="description_h">Descripción</th>
                <th class="import_h">Importe</th>
                <th class="action_h">Editar</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(daily, index) in DailyMovementListGetter" :key="index" :class="(daily.type === 'ENTRADA') ? 'in' : 'out'">
                <td class="number_r">{{index + 1}}</td>
                <td class="date_r">{{daily.date}}</td>
                <td class="type_r">{{daily.type}}</td>
                <td class="description_r">{{daily.description}}</td>
                <td class="import_r">{{daily.import | currency}}</td>
                <td class="action_r">
                    <button
                        class="btn btn-danger"
						:class="{'spinner spinner-inverse spinner-sm' : spinner && id_to_delete == daily.id}"
                        v-tooltip="'Eliminar movimiento'"
						@click="deleteDaily(daily)"
                    >
                        <span class="icon icon-trash"></span>
                    </button></td>
            </tr>
        </tbody>
    </table>
</template>

<script>
import { mapGetters } from 'vuex';
export default {

    computed: {

        ...mapGetters(['DailyMovementListGetter']),
    },

	data(){
		return {
			spinner: false,
			id_to_delete: null
		}
	},

	methods: {

		async deleteDaily({id}){

			this.spinner = true;

			this.id_to_delete = id;

			await this.sleep(250);

			const { data } = await this.$store.dispatch('deleteDailyMovement', {id})
			.catch( (err) => {
				console.log("🚀 ~ file: MovementList.vue:58 ~ deleteDaily ~ err:", err)

			})
			.finally(() => {
				this.spinner = false;
				this.id_to_delete = null
			});

			if ( data ) {
				this.$store.dispatch('deleteDailyMovementFromList', data.id);
			}

		}

	},

	watch: {

		DailyMovementListGetter(listMovements){
			const saldo = listMovements.reduce((acc, item) => {
				return acc + item.import
			}, 0)
			this.$store.dispatch('setDailyMovementSaldo', saldo);
		}

	},

	async created(){

		const { data } = await this.$store.dispatch('getAllPettyCash');

        if (data) {
            this.$store.dispatch('setDailyMovementList', data.petty_cash);
            this.$store.dispatch('setDailyMovementSaldo', data.saldo);
        }
	},

}
</script>

<style scoped>
    #movement-list{
        margin-top: 3rem;
        padding: 1rem;
        width: 100%;
    }
    .number_h{
        width: 5%;
        text-align: center;
    }
    .date_h{
        width: 10%;
        text-align: center;
    }
    .type_h{
        width: 10%;
        text-align: center;
    }
    .description_h{
        width: 50%;
        text-align: center;
    }
    .import_h{
        width: 15%;
        text-align: center;
    }
    .action_h{
        width: 10%;
        text-align: center;
    }

    .number_r{
        width: 5%;
        text-align: center;
    }
    .date_r{
        width: 10%;
        text-align: center;
    }
    .type_r{
        width: 10%;
        text-align: left;
    }
    .description_r{
        width: 50%;
        text-align: left;
    }
    .import_r{
        width: 15%;
        text-align: right;
    }
    .action_r{
        width: 10%;
        text-align: center;
    }
    .out{
        color: red;
        background-color: pink;
    }
    .in{
        background-color: lightgreen;
    }
</style>
