
<template>
    <span class="box">
        <p>{{ data.name }}</p>
        <span v-if="!isEditable" class="push">
                <span>
                    <button type="button"
                        @click="isEditable=!isEditable"
                        class="btn btn-warning btn-xs"
                    >Cambiar nombre</button>
                    <button type="button"
						@click="update_enable"
                        class="btn btn-xs"
                        :class="[(data.enable)?'btn-danger':'success', {'spinner spinner-inverse spinner-sm' : spinner_enable}]"
                    >{{(data.enable)?'Desactivar':'Activar'}}</button>
                </span>
        </span>
        <span v-if="isEditable" class="push">
            <input type="text"
				class="width-input"
                v-model="name"
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
import { Event } from 'vue-tables-2'
export default {

    name : 'EditName',

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


    data(){
        return {
            isEditable : false,
            spinner : false,
            spinner_enable : false,
			name:''
        }
    },

    methods : {

		async update_name(){

			this.spinner = true

            const payload = {
                /* token : this.User.token,
                index : this.index, */
                price_list_id : this.data.id,
                name : this.name,
            }

            const pl = await this.$store.dispatch('PriceListEditName', payload)
                .catch(err => {
                    console.log(err, 'esaaaaaaaaaaa');
                }).finally(()=> {
                    this.spinner = false
                });

            if (pl) {

				Event.$emit('update_price_list_name', (pl.data))
                this.success_message(`El nombre de la lista de precios se actualizó correctamente, a partir de ahora es: ${pl.data.name} ` , 'Lista de precios');
				this.isEditable = false;
				this.name = ''
            }
		},

		async update_enable(){

			this.spinner_enable = true

            const payload = {
                /* token : this.User.token,
                index : this.index, */
                price_list_id : this.data.id,
                enable : !this.data.enable,
            }

            const pl = await this.$store.dispatch('PriceListEditEnable', payload)
                .catch(err => {
                    console.log(err, 'esaaaaaaaaaaa');
                }).finally(()=> {
                    this.spinner_enable = false
                });

            if (pl) {

				Event.$emit('update_price_list_enable', (pl.data))
                this.success_message(`La lista de precios ahora está: ${(pl.data.enable)?'Activa':'Inactiva'} ` , 'Lista de precios');
				this.data.enable = pl.data.enable
            }
		}

    },

}
</script>

<style scoped>
    .box {
		display: flex;
		justify-content: flex-end;
        margin-top: 1rem;
        margin-bottom: 1rem;
	}
	.push {
		margin-left: auto;
	}
	.width-input{
		width: 35rem;
	}
	.success {
		color: aliceblue;
		background-color: #2fba25;
	}

</style>
