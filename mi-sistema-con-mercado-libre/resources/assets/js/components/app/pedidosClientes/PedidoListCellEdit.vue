<template>
    <div>
        <button v-if="data.type == 'COTIZACION'"
            v-tooltip="'Aprobar Cotización'"
            :disabled="(data.status_id == 13) ? true : false"
            @click="changeToPedido"
            class="btn btn-outline-success btn-icon sq-32"
            :class="{'btn btn-default spinner spinner-inverse spinner-sm' : spinner}" >
            <span class="icon icon-check"></span>
        </button>
        <button v-else
            @click="pedido_cliente_delete"
            :disabled="(data.status_id > 4 || User.type_user_id == 3) ? true : false"
            v-tooltip="'Eliminar pedido'"
            class="btn btn-outline-danger btn-icon sq-32"
            :class="{'btn btn-danger spinner spinner-inverse spinner-sm' : spinner}" >
            <span class="icon icon-trash"></span>
        </button>
        <!-- <div class="btn-group dropup">
                  <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" type="button" aria-expanded="false">
                    <span class="icon icon-share-alt icon-lg icon-fw"></span>
                    Acción
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                      <a href="#">
                        <div class="media">
                          <div class="media-left">
                            <span class="icon icon-trash icon-lg icon-fw text-danger"></span>
                          </div>
                          <div class="media-body">
                            <span class="d-b">Eliminar Cotización</span>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="#">
                        <div class="media">
                          <div class="media-left">
                            <span class="icon icon-check icon-lg icon-fw text-default"></span>
                          </div>
                          <div class="media-body">
                            <span class="d-b">Aprobar Cotización</span>
                          </div>
                        </div>
                      </a>
                    </li>
                  </ul>
                </div> -->
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import {Event} from 'vue-tables-2';
    import auth from './../../../mixins/auth';
    import toast_mixin from './../../../mixins/toast-mixin';
    export default {
        props: ['data', 'index'],
        data() {
            return {
                token : null,
                spinner : false
            }
        },

        mixins : [auth, toast_mixin],
        
        methods : {

            async pedido_cliente_delete()
            {
                const message = `Va a eliminar el pedido N° ${this.data.code} del cliente ${this.data.customer}, ¿Está seguro? `;

                Vue.swal.fire({
                    title: 'Eliminar Pedido',
                    text: message,
                    icon : 'question',
                    showDenyButton: true,
                    confirmButtonText: 'Aceptar',
                    denyButtonText: `No, cerrar`,
                }).then( async (result) => {
                    if (result.isConfirmed) {
                        
                        const payload = {
                            token : this.User.token,
                            pedido_cliente_id : this.data.id
                        }

                        this.spinner = true;

                        const pedido = await this.$store.dispatch('pedido_delete', payload);

                        if (pedido) {
                            
                            Vue.swal.fire('Pedido eliminado satisfactoriamente', '', 'success');

                            Event.$emit('pedido_cliente_delete', this.data);
                        }
                    }
                }).catch((error)=>{
                    Vue.swal.fire({
                        title: 'Eliminar Pedido',
                        text: error.response.data.message,
                        icon : 'info',
                        confirmButtonText: 'Aceptar',
                    })
                }).finally(()=> this.spinner = false);
                
            },

            async changeToPedido(){

                this.spinner = true;

                const pedido = await this.$store.dispatch('changeToPedido', this.data.id)
                .catch((error)=>{
                    
                    const { message } = error.response.data;
                    
                    const errorMessage = message.split('cut_flag');
                    
                    const stockProduct = message.split('|');
                    
                    const stockProductToJSON = JSON.parse(stockProduct[1]);
                    
                    let html = '';
                        
                    errorMessage.forEach(element => {
                        if (element[1] != '|') {
                            html = `${html} <p> ${element} </p>`
                        }
                    });

                    const options = {
                        title: 'Atención, problemas con el Stock de productos',
                        html : html,
                        icon : 'warning',
                        width : '35%',
                        padding : '2rem',
                        backdrop : true,

                        showConfirmButton: (Object.keys(stockProductToJSON).length == 1 && stockProductToJSON[0].stock == 0) ? false : true,
                        confirmButtonText: 'Continuar con productos con stock disponible',
                        confirmButtonColor: '#00bbf0',

                        showCancelButton: true,
                        cancelButtonText: 'Yo me ocupo del stock',
                    }

                    Vue.swal.fire(options).then( async (result) => {
                
                        if (result.isConfirmed) {
                            
                            const payload = {
                                pedido_id: this.data.id,
                                products: stockProduct[1]
                            }

                            const Toast = Vue.swal.mixin({
                                    toast: true,
                                    position: 'bottom-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });

                            const updated_cotization = await this.$store.dispatch('update_items_of_pedido', payload)
                            .catch((err) => {

                                Toast.fire({
                                    icon: 'error',
                                    title: err.response.data
                                });
                            });

                            if (updated_cotization) {
                                console.log("🚀 ~ file: PedidoListCellEdit.vue ~ line 176 ~ updated_cotization", updated_cotization)
                                Event.$emit('cotizacionItemsHasChanged', updated_cotization);
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Cotización actualizada correctamente'
                                });
                            }
                        }
                    });
                    
                }).finally(()=>this.spinner=false);

                if (pedido) {
                    Event.$emit('changeToPedido', pedido.data);
                }
            }
        },

        computed : {
            ...mapGetters([
                'GetCompany',
            ])
        },
       
    }
</script>