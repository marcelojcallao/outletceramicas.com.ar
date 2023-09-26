<template>
    <div>
        <div class="btn-group" data-toggle="buttons" >
            <label class="btn btn-default btn-thick"
                :class="{'active-yes': active}"
            >
            <input type="radio" :value="true" v-model="active" > SÍ
            </label>
            <label class="btn btn-default btn-thick"
                :class="{'active-no': !active}"
            >
            <input type="radio" :value="false" v-model="active"> NO
            </label>
        </div>
    </div>
</template>

<script>
    export default {
        
        props: {
            data: {
                required: true
            }, 
            index: {
                required: true
            }
        },

        name: 'NotaCreditoTakeit',

        data() {
            return {
                active: false
            }
        },

        watch: {

            active(n){
                
                if (n) {

                    const invoice = {
                        id: this.data.id,
                        voucher: this.data.voucher,
                        long_number: this.data.long_number,
                        date: this.data.date,
                        total: this.data.total
                    }

                    this.$store.dispatch('toPayDetailAddNotaDeCredito', invoice);
                } else {
                    this.$store.dispatch('toPayDetailRemoveNotaDeCredito', this.data.id);
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    .active-yes {
        background-color: #4caf50!important;
        color: aliceblue !important;
        border: 2px solid #1b5e20!important;
    }
    .active-no {
        background-color: #f06292!important;
        color: aliceblue !important;
        border: 2px solid #c62828!important
    }
</style>