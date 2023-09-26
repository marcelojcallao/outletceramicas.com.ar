<template>
    <div class="modal fade "  
    ref="address_modal"
    id="address_modal" 
    tabindex="-1" 
    role="dialog" aria-labelledby="myModalLabel"
    data-keyboard="false"
    data-backdrop="false"
    >
    
        <div class="modal-dialog" role="document">
            <div class="modal-content" id="modal-content">
                <div class="modal-header">
                    <button 
                        type="button" 
                        class="close" 
                        data-dismiss="modal" 
                        @click="close_modal"
                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Domicilio de entrega</h4>
                </div>
                <div class="modal-body">
                    <div class="grid">
                        <div class="item-1">PROVINCIA: (*)</div>
                        <div class="item-2">
                            <StateMultiselect name="address" :isAjax="false" />
                        </div>
                        <div class="item-3">LOCALIDAD: (*)</div>
                        <div class="item-4">
                            <CityMultiselect 
                                name="city" 
                                :isAjax="true" 
                                :disabled=" ! NewOrderDataGetter.flags.hasState "
                            /> 
                        </div>
                        <div class="item-15">
                            <button class="btn btn-primary btn-height">Agregar Localidad</button>
                        </div>
                        <div class="item-5">CÓDIGO POSTAL (*)</div>
                        <div class="item-6">
                            <input 
                                class="form-control" 
                                type="text" 
                                v-model="cp"
                                :disabled=" ! NewOrderDataGetter.flags.hasCity "
                            >
                        </div>
                        <div class="item-7">CALLE (*)</div>
                        <div class="item-8">
                            <input 
                                class="form-control" 
                                type="text"
                                v-model="street"
                                :disabled=" ! NewOrderDataGetter.flags.hasCp "
                            >
                        </div>
                        <div class="item-9">NÚMERO</div>
                        <div class="item-10">
                            <input 
                                class="form-control" 
                                type="text"
                                v-model="number"
                                :disabled=" ! NewOrderDataGetter.flags.hasStreet "
                            >
                        </div>
                        <div class="item-13">OBSERVACIONES</div>
                        <div class="item-14">
                            <textarea 
                                class="form-control"
                                v-model="obs"
                                name="" id="" cols="30" rows="4"></textarea>
                        </div>
                    </div>
                </div>
                <p>(*) Datos Obligatorios</p>
                <div class="modal-footer">
                    <button 
                        type="button" 
                        id="b-ok" 
                        class="btn btn-primary" 
                        data-dismiss="modal"
                        :disabled=" ! EnableButton "
                    >Guardar domicilio</button>
                    <button @click="close_modal"
                        type="button" id="b-cancel" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import CityMultiselect from './../../Base/Address/CityMultiselect';
import StateMultiselect from './../../Base/Address/StateMultiselect';
export default {

    components : {
        StateMultiselect,
        CityMultiselect
    },

    computed : {

        ...mapGetters([
            'NewOrderDataGetter'
        ]),

        cp : {
            get(){
                return this.NewOrderDataGetter.address.city.cp;
            },
            set(value){
                this.$store.commit('NEW_ORDER_ADDRESS_SET_CP', value);
            }
        },

        street : {
            get(){
                return this.NewOrderDataGetter.address.street;
            },
            set(value){
                this.$store.commit('NEW_ORDER_ADDRESS_SET_STREET', value);
            }
        },

        number : {
            get(){
                return this.NewOrderDataGetter.address.number;
            },
            set(value){
                this.$store.commit('NEW_ORDER_ADDRESS_SET_NUMBER', value);
            }
        },

        obs : {
            get(){
                return this.NewOrderDataGetter.address.obs;
            },
            set(value){
                this.$store.commit('NEW_ORDER_ADDRESS_SET_OBS', value);
            }
        },

        EnableButton(){
            if (this.NewOrderDataGetter.flags.hasState && 
                this.NewOrderDataGetter.flags.hasCity && 
                this.NewOrderDataGetter.flags.hasCp && 
                this.NewOrderDataGetter.flags.hasStreet && 
                this.NewOrderDataGetter.flags.hasNumber
            ) {
                return true;
            }
            return false;
        }
    },
    
    methods : {

        close_modal(){
            this.$store.commit('NEW_ORDER_SET_REQUIRED_SHIPPING', false);
            this.$store.commit('NEW_ORDER_ADDRESS_INITIAL_STATUS');
        }

    },

    mounted(){
        
        window.addEventListener('keydown', (e) =>{

            /* if (e.ctrlKey == true && e.key == 'F8') {
                $(this.$refs.address_modal).modal('show');

            } */

            if (e.ctrlKey == true && e.key == 'F10') {  
                $(this.$refs.address_modal).modal('hide');
            }

            
        });

    },
}
</script>

<style scoped>

.modal {
    position: absolute;
    top: -460px;
    left: -70px;
    z-index: 100;
}
/* .modal-dialog{
    width: 100%;
} */
div.modal-header{
    background-color: rgb(144, 220, 255);
    color: black
}
.modal-open .modal  {
    overflow: hidden;
}
#modal-content {
    position: absolute;
    left: -100px;
    top: 0px;
    box-shadow : 0 50px 100px 0 rgb(0 0 0 / 0.5);
    width: 125%;
}
.modal-content p{
    color: brown;
    margin-left: 2rem;
}
#b-ok:focus {
    background-color: blue;
}
#b-cancel:focus {
    background-color: crimson;
}
.grid{
    display : grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    margin: 0 2rem;
    gap: 1rem;
}
.item-2,  .item-6, .item-8, .item-10, .item-12, .item-14{
    grid-column-start: 2;
    grid-column-end: 5;
}
.item-1, .item-3, .item-5, .item-7, .item-9, .item-11, .item-13{
    font-weight: bold;
}
.item-4{
    grid-column-start: 2;
    grid-column-end: 4;
}
.item-15{
    grid-column-start: 4;
    grid-column-end: 5;
    vertical-align: middle;
}
.btn-height{
    height: 41px;
}
</style>