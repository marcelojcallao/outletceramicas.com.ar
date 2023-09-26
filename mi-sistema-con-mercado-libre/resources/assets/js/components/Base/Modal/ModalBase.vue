<template >
    <div class="modal fade "  
        ref="product_modal"
        id="myModal" 
        tabindex="-1" 
        role="dialog" aria-labelledby="myModalLabel"
        data-keyboard="false"
        data-backdrop="false"
    >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{title}}</h4>
                    <slot name="modal_header"></slot>
                </div>
                <div class="modal-body">
                    <slot name="modal_body"></slot>
                </div>
                <div class="modal-footer">
                    <button type="button" id="b-ok" class="btn btn-primary" data-dismiss="modal">{{name_ok_button}}</button>
                    <button 
                        @click="cancel"
                        type="button" id="b-cancel" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <slot name="modal_footer"></slot>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {

        props : {
            title : {
                type : String,
                required : true
            },
            name_ok_button : {
                type : String,
                required : true
            }

        }    
    }
</script>