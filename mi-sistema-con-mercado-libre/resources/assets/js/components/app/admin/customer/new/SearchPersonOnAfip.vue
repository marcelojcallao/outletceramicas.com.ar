<template>
    <div class="row">
        <div class="col-md-12">
            <form class="form form-inline">
                <label >BUSCAR EN AFIP </label>
                <div class="form-group">
                    <div class="input-with-icon">
                        <vue-autonumeric
                            :disabled="spinner"
                            placeholder="CUIT"
                            class="form-control text-right"
                            :options="options"
                            v-model="number"
                        ></vue-autonumeric>
                    </div>
                </div>
                <button
                    v-tooltip="'Buscar contribuyente en Afip'"
                    @click="getPersonFromAfip"
                    :disabled="spinner"
                    class="btn btn-primary btn-icon sq-32"
                    :class="{'btn btn-primary spinner spinner-inverse spinner-sm' : spinner}"
                    type="button">
                    <span class="icon icon-search"></span>
                </button>
                <fade-transition>
                    <select class="form-control" v-model="personaSelected" v-if="IsPersonArray" :disabled="spinner">
                        <option >Selecciona contribuyente</option>
                        <option v-for="(persona, index) in this.person.idPersonaListReturn.idPersona" v-bind:value="persona" :key="index">{{ persona }}</option>
                    </select>
                </fade-transition>
            </form>
        </div>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect';
import {mapGetters} from 'vuex';
import VueAutonumeric from '../../../../../../../../node_modules/vue-autonumeric/src/components/VueAutonumeric';
export default {
    components : {
        Multiselect, VueAutonumeric
    },
    data() {
        return {
            spinner : false,
            options : {
                decimalPlaces : 0,
                digitGroupSeparator: '',
                decimalCharacter: ',',
                decimalCharacterAlternative: '.',
                currencySymbolPlacement: 'p',
                roundingMethod: 'U',
                minimumValue: '0',
                modifyValueOnWheel  : false,
                showOnlyNumbersOnFocus : true,
            },
            hasError : false,
            errorMsg : null,
            person : null,
            personIsArray : false,
            personaSelected : null
        }
    },
    methods : {

        thisObjectHasThisProperty(object, property){
            return (object.hasOwnProperty(property)) ? true : false;
        },

        async getPersonFromAfip()
        {
            this.$store.dispatch('newCustomerSetInitialStatus');
            this.hasError = false;
            this.errorMsg = null;
            this.spinner = true;
            this.personIsArray = false;
            const payload = {
                    token : this.User.token,
                    cuit : this.NewCustomerGetNumber
                }

            const person = await this.getPerson(payload);

            if (person) {
                this.checkResponse(person);
                return person;
            }
        },

        async getPerson(payload){
            const person = await this.$store.dispatch('getPersonFromAfip', payload)
                .catch((err)=>{
                    this.error_message(err.response.data.message, 'AFIP' , 4000);
                }).finally(()=>this.spinner = false);

            console.log("🚀 ~ file: SearchPersonOnAfip.vue ~ line 94 ~ getPerson ~ person", person)

            return person;
        },

        checkResponse(person)
        {
            if (this.thisObjectHasThisProperty(person, 'idPersonaListReturn')) {
                if (this.thisObjectHasThisProperty(person.idPersonaListReturn, 'idPersona')) {
                    this.personIsArray = Array.isArray(person.idPersonaListReturn.idPersona);
                }
            }

            if (this.thisObjectHasThisProperty(person, 'idPersonaListReturn')) {

                if(this.thisObjectHasThisProperty(person.idPersonaListReturn, 'idPersona'))
                { // ingreso dni, devuelve cuil
                    if(this.personIsArray)
                    {
                        this.person = person

                    }else{
                        this.person = person;
                        this.$store.dispatch('newCustomerSetNumber', parseInt(person.idPersonaListReturn.idPersona))
                    }

                }

            }

            if (this.thisObjectHasThisProperty(person, 'personaReturn')) {

                this.personIsArray = false;

                if (this.thisObjectHasThisProperty(person.personaReturn, 'datosGenerales')) {

                    const tipoClave = person.personaReturn.datosGenerales.tipoClave

                    if (tipoClave === 'CUIT') {
                        const purchase_document = {"id":25,"afip_code":"80","name":"CUIT"};
                        this.$store.dispatch('newCustomerSetPurchaseDocument', purchase_document);
                    }

                    if (tipoClave === 'CUIL') {
                        const purchase_document = {"id":26,"afip_code":"86","name":"CUIL"};
                        this.$store.dispatch('newCustomerSetPurchaseDocument', purchase_document);
                    }

                    const keys = Object.keys(person.personaReturn);
                    console.log("🚀 ~ file: SearchPersonOnAfip.vue:147 ~ keys", keys, keys[0], keys[1], keys.length);

                    if (keys.length === 2 && keys[0] === 'datosGenerales' && keys[1] === 'metadata') {
                        const inscription = {"id":5,"name":"Consumidor Final","short":"CF"};
                        this.$store.dispatch('newCustomerSetInscription', inscription);
                    }

                    if (this.thisObjectHasThisProperty(person.personaReturn.datosGenerales, 'razonSocial')) {
                        const name = `${person.personaReturn.datosGenerales.razonSocial}`
                        this.$store.dispatch('newCustomerSetName', name);
                    }else{
                        /** cuando es monotributista o responsable inscripto pesona fisica*/
                        const name = `${person.personaReturn.datosGenerales.apellido} ${person.personaReturn.datosGenerales.nombre}`
                        this.$store.dispatch('newCustomerSetName', name);
                    }

                    if (this.thisObjectHasThisProperty(person.personaReturn.datosGenerales, 'domicilioFiscal')){

                        const id_provincia = person.personaReturn.datosGenerales.domicilioFiscal.idProvincia;

                        const index = this.Provinces.findIndex((provincia) => provincia.afip_code == id_provincia)

                        this.$store.dispatch('newCustomerAddressSetState', this.Provinces[index]);


                        if (parseInt(id_provincia) == 0) {
                            this.$store.dispatch('newCustomerAddressSetCity', 'CIUDAD AUTONOMA BUENOS AIRES');
                        }else{
                            const city = person.personaReturn.datosGenerales.domicilioFiscal.localidad;
                            this.$store.dispatch('newCustomerAddressSetCity', city);
                        }


                        const address = person.personaReturn.datosGenerales.domicilioFiscal.direccion;
                        this.$store.dispatch('newCustomerAddressSetAddress', address);

                        const cp = person.personaReturn.datosGenerales.domicilioFiscal.codPostal;
                        this.$store.dispatch('newCustomerAddressSetCp', cp);

                    }

                    if (this.thisObjectHasThisProperty(person.personaReturn, 'datosRegimenGeneral')){

                        const impuestos = person.personaReturn.datosRegimenGeneral.impuesto;
                        console.log(typeof impuestos);

                        if (typeof impuestos === 'object') {
                            if (impuestos.descripcionImpuesto === 'IVA') {
                                const inscription = {"id":1,"name":"IVA Responsable Inscripto","short":"IRI"};
                                this.$store.dispatch('newCustomerSetInscription', inscription);
                            }else{
                                const inscription = {"id":5,"name":"Consumidor Final","short":"CF"};
                                this.$store.dispatch('newCustomerSetInscription', inscription);
                            }
                        }

                        if (Array.isArray(impuestos)){

                            impuestos.forEach(impuesto => {
                                console.log("🚀 ~ file: SearchPersonOnAfip.vue:199 ~ impuesto", impuesto)
                                if (impuesto.descripcionImpuesto === 'IVA') {
                                    const inscription = {"id":1,"name":"IVA Responsable Inscripto","short":"IRI"};
                                    this.$store.dispatch('newCustomerSetInscription', inscription);
                                }

                                if (impuesto.descripcionImpuesto === 'IVA EXENTO') {
                                    const inscription = {"id":4,"name":"IVA Sujeto Exento","short":"ISE"};
                                    this.$store.dispatch('newCustomerSetInscription', inscription);
                                }
                            });

                        }
                    }

					if (this.thisObjectHasThisProperty(person.personaReturn, 'datosMonotributo')){
                        const inscription = {"id":6,"name":"Responsable Monotributo","short":"M"};
                        this.$store.dispatch('newCustomerSetInscription', inscription);
                    }
                }

                /** CUIL */
                if (this.thisObjectHasThisProperty(person.personaReturn, 'errorConstancia'))
                {
                    const errors = person.personaReturn.errorConstancia.error;

                    let message = '';

                    if (Array.isArray(errors)) {

                        for (const key in errors) {
                            if (errors.hasOwnProperty.call(errors, key)) {
                                    message += errors[key] + '  ';
                            }
                        }

                    }else{
                        message = errors;
                    }

                    message = `${message}. Ingrese el cliente manualmente como consumidor final y DNI.,
                    No utilice la lupa para buscar el DNI en AFIP.`;

                    if (errors != 'La clave ingresada no es una CUIT') {

                        Vue.swal.fire({
                            title: 'Ingreso de clientes',
                            text : message,
                            icon : 'error',
                            width : '35%',
                            padding : '2rem',
                            backdrop : true,
                            time : 2000
                        });
                    }

                    this.person = person.personaReturn.errorConstancia

                    if (errors == 'La clave ingresada no es una CUIT') {

                        this.$store.dispatch('newCustomerSetNumber', parseInt(person.personaReturn.errorConstancia.idPersona));

                        const name = `${person.personaReturn.errorConstancia.apellido} ${person.personaReturn.errorConstancia.nombre}`
                        this.$store.dispatch('newCustomerSetName', name);

                        const purchase_document = {"id":26,"afip_code":"86","name":"CUIL"};
                        this.$store.dispatch('newCustomerSetPurchaseDocument', purchase_document);

                        const inscription = {"id":5,"name":"Consumidor Final","short":"CF"};
                        this.$store.dispatch('newCustomerSetInscription', inscription);
                    }

                }
            }

            this.$store.dispatch('newCustomerSetAfipData', person);
        },
    },

    watch : {

        async personaSelected(n){

            this.spinner = true;

            const payload = {
                token : this.User.token,
                cuit : n
            }
            this.$store.dispatch('newCustomerSetNumber', payload.cuit);
            const person = await this.getPerson(payload);

            if (person) {
                this.checkResponse(person);
            }
        }
    },

    computed : {

        ...mapGetters(['NewCustomerGetNumber', 'Provinces']),

        number : {
            get(){
                return this.NewCustomerGetNumber;
            },
            set(number){
                this.$store.dispatch('newCustomerSetNumber', number)
            }
        },

        IsPersonArray(){

            return this.personIsArray;
        }
    },

}
</script>

<style scoped>
    .margin-top{
        margin-top: 15px;
    }
</style>
