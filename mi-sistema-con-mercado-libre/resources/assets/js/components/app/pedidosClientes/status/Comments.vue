<template>
    <div class="btn-group dropdown"
        :class="{' open' : open}"
    >
        <button
            class="btn btn-primary btn-icon sq-32 dropdown-toggle"
            type="button"
            @click="open=!open"
			v-tooltip="'Agregar comentarios a la factura'"
        >
		<span class="icon icon-edit"></span>
        </button>
        <ul class="dropdown-menu form-width">
            <li>
                <div  style="padding-bottom: 10px">
                    <p class="name">Observación</p>
                </div>
            </li>
            <li>
                <div class="comments">
					<textarea id="form-control-8" class="form-control" rows="3" v-model="comments"></textarea>
					<button @click="saveComment" class="btn btn-info" type="button">Guardar</button>
				</div>
            </li>
        </ul>
    </div>
</template>
<script>
    import { mapGetters } from 'vuex';
    export default {

        data(){
            return {
                open: false,
				spinner: false,
				comments: ''
            }
        },

        methods : {

			saveComment(){
				this.open = false;
				this.$store.dispatch('setCommentsToInvoice', this.comments);
			}

        },
    }
</script>

<style scoped>
    ul.dropdown-menu {
        right: 5rem;
    }
    div.media{
        display: flex;
    }
    div.media .name{
        margin-left: 1rem;
    }
    .btn-color{
        background-color: #bbe4e9;
        color : #606470
    }
    .name{
        padding: .5rem .5rem;
    }
	.form-width {
		min-width: 600px !important;
		max-width: 600px !important;
		min-height: 150px !important;
		max-height: 150px !important;
	}
	.comments {
		min-height: 120px;
		max-height: 120px;
	}
    ul.dropdown-menu {
        width: 15rem;
    }
	ul li div {
		display: flex;
		justify-content: space-between;
		height: 3rem;
		padding: 1rem;
	}
	ul li div textarea {
		margin-right: 1rem;
		resize: none;
	}
</style>
