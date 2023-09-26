<template>
    <div class="card margin-top">
        <div class="card-header title bg-default">
            <h4>DATOS</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-error' : false}">
                            <label class="control-label" >Nombre O Razón Social</label>
                            <input v-model="name" class="form-control" type="text">
                            <!-- <p class="text-danger" v-if="'ProviderErrorNameGetter'">{{'ProviderErrorNameGetter'[0]}}</p> -->
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-error' : false}">
                            <label class="control-label" >Documento Tipo</label>
                            <div class="">
                                <multiselect placeholder="Documento tipo" 
                                    track-by="name" label="name"
                                    :options="PurchaserDocuments"
                                    :loading="purchaser_spinner"
                                    :value="NewCustomerGetPurchaserDocument"
                                    @input="setPurchaseDocument"
                                    :clear-on-select="false" 
                                    >
                                    <span slot="noOptions">
                                            Lista Vacía
                                    </span>
                                    <span slot="noResult">
                                            La búsqueda no arrojó resultados
                                    </span>
                                </multiselect>
                            <!-- <p class="text-danger" v-if="'ProviderErrorPurchaseDocumentGetter'">{{'ProviderErrorPurchaseDocumentGetter'[0]}}</p> -->
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-2">
                        <div class="form-group" :class="{'has-error' : ProviderErrorNumberGetter}">
                            <label class="control-label" >Número</label>
                            <input  v-model="number" class="form-control" type="text">
                            <p class="text-danger" v-if="ProviderErrorNumberGetter">{{ProviderErrorNumberGetter[0]}}</p>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="row margin-top">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-error' : false}">
                            <label class="control-label" >Inscripción</label>
                            <div class="">
                                <multiselect placeholder="Inscripción" 
                                    track-by="name" label="name"
                                    :value="NewCustomerGetInscription"
                                    :options="GetInscriptions"
                                    :loading="inscription_spinner"
                                    :clear-on-select="false" 
                                    @input="setInscription"
                                    >
                                    <span slot="noOptions">
                                            Lista Vacía
                                    </span>
                                    <span slot="noResult">
                                            La búsqueda no arrojó resultados
                                    </span>
                                </multiselect>
                                <!-- <p class="text-danger" v-if="'ProviderErrorInscriptionGetter'">{{'ProviderErrorInscriptionGetter'[0]}}</p> -->
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" >Cuenta Contable</label>
                            <div class="">
                                <multiselect placeholder="Inscripción" 
                                    track-by="name" label="name"
                                    :value="ProviderAccountingAccount"
                                    :options="AccountingAccountsGetter"
                                    :clear-on-select="false" 
                                    :disabled="true"
                                    @input="setProviderAccountingAccount"
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
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect';
import {mapGetters} from 'vuex';
    export default {
        components : {Multiselect},
        data(){
            return {
                inscriptions : [],
                purchaser_spinner : true,
                inscription_spinner : true,
            }
        },

        methods : {

            setPurchaseDocument(e){
                this.$store.dispatch('newCustomerSetPurchaseDocument', e)
            },

            async get_inscriptions  () {
                
                try {
                    window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.User.token;
                    
                    let response = await axios.get('/api/inscription/index');

                    this.$store.commit('SET_INSCRIPTIONS', response.data);

                } catch (e) {
                    console.log('error en get_inscriptions method');
                    console.log(e);
                }
            },

            async get_purchase_documents  () {
                
                try {
                    window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.User.token;
                    
                    let response = await axios.get('/api/purchase_document/index');

                    this.$store.commit('SET_PURCHASER_DOCUMENT', response.data);

                } catch (e) {
                    console.log('error en get_purchase_documents method');
                    console.log(e);
                }
            },

            setInscription(e){
                this.$store.dispatch('newCustomerSetInscription', e);
            }
        },

        computed : {
            ...mapGetters([
                'GetInscriptions',
                'NewCustomerGetName',
                'NewCustomerGetPurchaserDocument',
                'NewCustomerGetInscription',
                'PurchaserDocuments'
                
            ]),

            name : {
                get()
                {
                    return this.NewCustomerGetName;
                },

                set(value)
                {
                    this.$store.commit('NEW_CUSTOMER_SET_NAME', value);
                }
            },

            number : {
                get()
                {
                    return this.ProviderNumber;
                },

                set(value)
                {
                    this.$store.commit('SET_PROVIDER_NUMBER', value);
                }
            },
        },

        watch : {

            NewCustomerGetName(n){

            }

        },

        async mounted(){
            await this.get_inscriptions();
            this.inscription_spinner = false
            await this.get_purchase_documents();
            this.purchaser_spinner = false
        }
    }
</script>

<style scoped>
    .margin-top{
        margin-top: 15px;
    }
</style>