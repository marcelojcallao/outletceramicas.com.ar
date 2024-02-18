<template>
    <button
        v-tooltip="tooltip"
        @click="edit"
        type="button"
        class="btn btn-outline-primary btn-icon sq-32"
        :class="{'btn btn-primary spinner spinner-inverse spinner-sm' : spinner}"
		:disabled="disabled"
        >
        <span class="icon icon-save"></span>
    </button>
</template>
<script>
import {Event} from 'vue-tables-2';
import toast from './../../../../../../mixins/toast-mixin';
export default {

    props : {
        tooltip : {
            default() { return 'Guardar cambios' },
            type : String
        },
        index : {
            type : Number
        },

        data : {
            required : false,
        }
    },

	data() {
		return {
			spinner : false,
			benefit:null,
			disabled: true
		}
	},

	methods: {

		async edit(){

			this.spinner = true

            const payload = {
                token : this.User.token,
                index : this.index,
                price_list_id : this.data.id,
                benefit : this.benefit,
            }

            const pl = await this.$store.dispatch('PriceListEditBenefit', payload)
                .catch(err => {
                    console.log(err, 'esaaaaaaaaaaa');
                }).finally(()=> {
                    this.spinner = false
                    this.spinner = false
                });

            if (pl) {
                this.info_message(`La lista de precios ${pl.name} se actualizó correctamente` , 'Lista de precios');
            }
        }
	},

	mounted() {
		Event.$on('update_benefit', (event)=>{
			if (event.id === this.data.id) {
				this.disabled = false
				this.benefit = event.benefit

			}
		})

	}
}
</script>
<style scoped>

</style>
