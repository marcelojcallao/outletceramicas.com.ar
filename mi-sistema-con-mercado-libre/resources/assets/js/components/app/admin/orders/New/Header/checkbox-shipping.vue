<template>
    <div class="shipping">
        <label >¿Requiere envío?</label>
        <div class="required">
            <div class="--ckeckbox">
                <label>NO</label>
                <label class="switch switch-primary">
                    <input class="switch-input" 
                        type="checkbox" 
                        v-model="required_shipping"    
                    >
                    <span class="switch-track"></span>
                    <span class="switch-thumb"></span>
                </label>
                <label>SÍ</label>
            </div>
            <transition name="fade">
                <input 
                    v-if="required_shipping"
                    type="text"
                    @focus="$event.target.select()"
                    class="percentage"
                    v-model="percentage"
                    v-tooltip="'Ingresar porcentaje'"
                >
            </transition>
        </div>
    </div>
</template>

<script>
import {mapGetter, mapGetters} from 'vuex';
    export default {
        
        computed : {

            ...mapGetters([
                'NewOrderTotalsNeto',
                'NewOrderShippingGetter',
                'NewOrderDataGetter'
            ]),

            percentage : {
                get(){
                    return this.NewOrderShippingGetter.percentage;
                },
                set(value){
                    this.$store.commit('NEW_ORDER_SET_SHIPPING_PERCENTAGE', value);
                }
            },

            required_shipping : {
                get(){
                    return this.NewOrderDataGetter.required_shipping;
                },
                set(value){
                    
                    if (value) {
                        $('#address_modal').modal('show');
                    }
                    this.$store.commit('NEW_ORDER_SET_REQUIRED_SHIPPING', value);
                }
            }

        },

        watch : {

            /* NewOrderShippingGetter : {

                deep : true,

                handler(value){

                    let percentage = this.NewOrderTotalsNeto * parseInt(value.percentage) / 100;
                    
                    this.$store.commit('NEW_ORDER_SET_SHIPPING_VALUE', percentage);
                }
            },

            shipping(n)
            {
                if (!n) {
                    this.$store.commit('NEW_ORDER_SET_SHIPPING_PERCENTAGE', 0);
                }
            } */
        }
    }
</script>

<style lang="scss" scoped>
    .shipping .required{
        display: flex;
        justify-content: space-between;
    }
    .required .--checkbox{
        width: 131px;
        display: flex;
    }
    .shipping .percentage{
        width: 75px;
    }
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s
    }
    .fade-enter, .fade-leave-to {
        opacity: 0
    }
    input[type=text]{
        padding: 0 1rem;
    }
</style>