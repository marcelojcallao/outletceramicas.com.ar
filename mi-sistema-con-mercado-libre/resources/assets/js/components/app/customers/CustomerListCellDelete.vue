<template>
    <div>
        <button 
            @click="delete_customer" 
            v-tooltip="'Eliminar cliente'"
            class="btn btn-outline-danger btn-icon sq-32"
            :class="{'btn btn-danger spinner spinner-inverse spinner-sm' : spinner}"
            type="button">
            <span class="icon icon-trash"></span>
        </button>
    </div>
</template>
<script>
    import {mapGetters} from 'vuex';
    import {Event} from 'vue-tables-2';
    export default {
        
        props: ['data', 'index'],

        data() {
            return {
                spinner : false
            }
        },

        methods : {
            
            async delete_customer()
            {
                Vue.swal.fire({
                    title: 'Eliminar Cliente',
                    text: 'Al eliminar el cliente se perderán todos sus datos, ¿Está seguro?',
                    icon : 'question',
                    showDenyButton: true,
                    confirmButtonText: 'Aceptar',
                    denyButtonText: `No, cerrar`,
                }).then( async (result) => {
                    if (result.isConfirmed) {
                        
                        this.spinner = true;

                        const customer = await this.$store.dispatch('customer_delete', this.data)

                        if (customer) {
                            
                            Vue.swal.fire('Eliminado satisfactoriamente', '', 'success');

                            Event.$emit('delete_customer', customer.id);
                        }
                    }
                }).catch((error)=>{
                    Vue.swal.fire({
                        title: 'Eliminar Cliente',
                        text: error.response.data.message,
                        icon : 'info',
                        confirmButtonText: 'Aceptar',
                    })
                }).finally(()=> this.spinner = false);
            }
            
        },
    }
</script>