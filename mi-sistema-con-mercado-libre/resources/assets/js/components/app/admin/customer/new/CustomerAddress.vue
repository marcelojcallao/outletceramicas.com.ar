<template>
    <div class="card margin-top">
        <div class="card-header title bg-default">
            <h4>DOMICILIO</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" >Provincia</label>
                            <div class="">
                                <multiselect placeholder="Provincia" 
                                    track-by="name" label="name"
                                    :value="NewCustomerAddressGetState"
                                    :options="Provinces"
                                    :clear-on-select="false" 
                                    @input="setProvince"
                                    selectLabel="Seleccionar"
                                    selectedLabel="Seleccionado"
                                    deselectLabel="Remover"
                                    >
                                    <span slot="noOptions">
                                            Lista Vacía
                                    </span>
                                    <span slot="noResult">
                                            La búsqueda no arrojó resultados
                                    </span>
                                </multiselect>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label" >Localidad</label>
                            <div class="">
                                <input v-model="city" class="form-control" type="text">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row margin-top">
                <div class="col-md-12">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" >Cód. Postal</label>
                            <div class="">
                                <input v-model="cp" class="form-control" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="form-group">
                            <label class="control-label" >Calle</label>
                            <div class="">
                                <input v-model="address" class="form-control" type="text">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label" >Entre calles</label>
                        <div class="">
                            <input v-model="between" class="form-control" type="text">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label" >Observaciones</label>
                        <div class="">
                            <textarea v-model="obs" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect';
import {mapGetters, mapActions} from 'vuex';

    export default {
        components : {
            Multiselect
        },

        computed : {

            ...mapGetters(
                [
                    'GetAddressType',
                    'Provinces',
                    'GetPersonWSPUC',
                    'GetCustomersData',
                    'NewCustomerAddressGetState',
                    'NewCustomerAddressGetCity',
                    'NewCustomerAddressGetAddress',
                    'NewCustomerAddressGetCP',
                    'NewCustomerAddressGetObs',
                    'NewCustomerAddressGetBetween',
                ]
            ),

            city : {
                get()
                {
                    return this.NewCustomerAddressGetCity;
                },

                set(city)
                {
                    this.$store.dispatch('newCustomerAddressSetCity', city);
                }
            },

            cp : {
                get()
                {
                    return this.NewCustomerAddressGetCP;
                },

                set(cp)
                {
                    this.$store.dispatch('newCustomerAddressSetCp', cp);
                }
            },

            address : {
                get()
                {
                    return this.NewCustomerAddressGetAddress;
                },

                set(address)
                {
                    this.$store.dispatch('newCustomerAddressSetAddress', address);
                }
            },

            between : {
                get()
                {
                    return this.NewCustomerAddressGetBetween;
                },

                set(value)
                {
                    this.$store.dispatch('newCustomerAddressSetBetween', value);
                }
            },

            obs : {
                get()
                {
                    return this.NewCustomerAddressGetObs;
                },

                set(value)
                {
                    this.$store.dispatch('newCustomerAddressSetObs', value);
                }
            }
        },

        methods : {
            ...mapActions([
                'setProviderAddressType',
                'setProviderProvince'
            ]),

            setProvince(e){
                this.$store.dispatch('newCustomerAddressSetState', e)
            }
        },

        async mounted  () {
            
            await this.$store.dispatch('getAddressType', this.User.token);
            
            const provinces = await this.$store.dispatch('getProvinces', this.User.token);

            if (provinces) {
                this.$store.commit('SET_PROVINCES', provinces.data);
            }
        }
    }
</script>