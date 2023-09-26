<template>
    <div class="btn-group dropdown"
        :class="{' open' : OpenChangeInvoiceDateGetter}"
    >
        <button
            :disabled="! DisabledButton"
            class="btn btn-icon sq-32 dropdown-toggle"
            type="button"
            @click="dropdown_open"
            v-tooltip="button.tooltip"
            :class="[
                ( button.status_id == PedidoListChildRowReactivityData.status_id + 1 ) ? {'spinner spinner-inverse spinner-sm' : spinner} : '',
                ( DisabledButton ) ? 'btn-color' : ''
            ]"
        >
            <span class="material-icons">{{button.icon}}</span>
        </button>
        <ul class="dropdown-menu form-width">
            <li style="margin-bottom: 3rem;">
                <div  style="padding-bottom: 10px">
                        <p class="name">Productos a facturar</p>
						<Comments />
                </div>
            </li>
            <li>
                <span class="text-center">
                    <tableProducts  />
                </span>
            </li>
        </ul>
    </div>
</template>
<script>
    import { mapGetters } from 'vuex';
    import tableProducts from './table-products.vue';
import Comments from './Comments.vue';
    export default {

        props : {
            button: {
                required: true
            }
        },

        components : { tableProducts, Comments },

        data(){
            return {
                spinner : null,
                my_status: null,
                status_order: null,
                disabledDates : {
                    to: this.$moment().subtract(5, 'days').toDate(), // Disable all dates up to specific date
                    from: this.$moment().add(5, 'days').toDate(), // Disable all dates after specific date
                },
            }
        },

        methods : {

            dropdown_open()
            {
                const open = ! this.OpenChangeInvoiceDateGetter;
                const { items } = this.PedidoListChildRowReactivityData;

                items.forEach( (item) => this.$store.dispatch('setItemsToFacturar', JSON.parse(JSON.stringify(item))))

                this.$store.dispatch('setOpenChangeInvoiceDate', open);
            },
        },

        computed : {

            ...mapGetters([
                'OpenChangeInvoiceDateGetter',
                'PedidoListChildRowReactivityData'
            ]),

            DisabledButton(){
                if (( parseInt(this.my_status) - parseInt(this.status_order) ) == 1) {
                    return true;
                }

                return false;
            }

        },

        watch : {

            PedidoListChildRowReactivityData :
            {
                handler(n)
                {
                    this.status_order = n.status_id;
                },
                deep : true
            }
        },

        beforeMount() {
            this.status_order = this.PedidoListChildRowReactivityData.status_id;
            this.my_status = this.button.status_id;
        },

    }
</script>

<style scoped>
    .dropdown-menu{
        min-width : 360px !important;
    }
    ul.dropdown-menu {
        right: -281px;
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
    ul.dropdown-menu {
        width: 1000px;
        right: -901px;
    }
    .table-custom-header{
        background-color: #606470;
        color: beige;
        font-size: 12px;
        height: 1rem;
        width: auto;
    }
    .input-inside-td{
        padding: 2px 5px;
    }
	ul li div {
		display: flex;
		justify-content: space-between;
		height: 3rem;
		padding: 1rem;
	}
</style>
