<script>
    import Editable_td_base from './../../../../../../Base/Table/Editable-td-base'
    export default {
        
        extends : Editable_td_base,
        
        props : {
            index : {
                type : Number
            },
             value : {
                required : true
            },
            /**
             * TODO
             * /es una accion tengo que cambiar
             */
            mutation : { 
                type : String,
                required : true
            }
        },

        data(){
            return {
                spinner : false,
                new_value : null,
                isEditable : false
            }
        },

        methods : {
            editable(bool){
                this.isEditable = bool;
            },

            update_value(){
                
                this.spinner = true;

                this.sleep(1500);

                let payload = {
                    value : this.new_value,
                    index : this.index
                }

                this.$store.dispatch(this.mutation, payload); 

                this.spinner = false;

                this.isEditable = false;
            },

            cancel(){
                this.new_value = '';
                this.isEditable = false;
            }
        },

        mounted(){
            this.new_value = this.value;
        }
    }
</script>

<style scoped>
    .td-flex{
        display: flex;
        justify-content: center;
    }
    span input {
        width: 4rem;
    }
</style>