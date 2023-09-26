<template>
    <div class="card margin-top">
        <div class="card-header title bg-default">
            <h4>CONTACTO</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" >Nombre</label>
                            <div class="">
                                <input v-model="name" class="form-control" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" >Email</label>
                            <div class="">
                                <input v-model="email" class="form-control" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" >Celular</label>
                            <div class="">
                                <input v-model="cell_phone" class="form-control" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" >Teléfono</label>
                            <div class="">
                                <input v-model="phone" class="form-control" type="text">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import Multiselect from 'vue-multiselect';
    export default {
        components : {
            Multiselect
        },

        computed : {

            ...mapGetters(
                [
                    'NewCustomerGetContactName',
                    'NewCustomerGetContactEmail',
                    'NewCustomerGetContactCellPhone',
                    'NewCustomerGetContactPhone'
                ]
            ),

            name : {
                get()
                {
                    return this.NewCustomerGetContactName;
                },

                set(value)
                {
                    this.$store.dispatch('newCustomerSetContactName', value);
                }
            },

            email : {
                get()
                {
                    return this.NewCustomerGetContactEmail;
                },

                set(value)
                {
                    this.$store.dispatch('newCustomerSetContactEmail', value);
                }
            },

            cell_phone : {
                get()
                {
                    return this.NewCustomerGetContactCellPhone;
                },

                set(value)
                {
                    this.$store.dispatch('newCustomerSetContactCellPhone', value);
                }
            },

            phone : {
                get()
                {
                    return this.NewCustomerGetContactPhone;
                },

                set(value)
                {
                    this.$store.dispatch('newCustomerSetContactPhone', value);
                }
            },
            
        },

        methods : {
            ...mapActions([
                'setProviderAddressType',
                'setProviderProvince'
            ])
        },
    }
</script>

<style lang="scss" scoped>

</style>