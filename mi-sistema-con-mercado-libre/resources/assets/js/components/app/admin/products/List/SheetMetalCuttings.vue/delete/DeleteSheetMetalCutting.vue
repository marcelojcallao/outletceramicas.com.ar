<script>
import { Event } from 'vue-tables-2';
    import DeleteRowBase from '../../../../../../Base/Button/DeleteRowBase.vue';
    export default {

        name: 'DeleteSheetMetalCutting',

        extends: DeleteRowBase,

        methods: {

            async delete_item(){

                const { data } = await this.$store.dispatch('deleteSheetMetalCutting', { product_id: this.data.product_id, mts: this.data.mts })

                if (data) {

                    this.$store.dispatch('setSheetMetalCutting', data)

                    const Toast = Vue.swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    });

                    Toast.fire({
                        icon: 'success',
                        title: 'El recorte se ha eliminado correctamente'
                    });
                }
            }
        },

        computed: {

            IsDisabled(){
                return false;
            }
        },

    }
</script>

<style lang="scss" scoped>

</style>