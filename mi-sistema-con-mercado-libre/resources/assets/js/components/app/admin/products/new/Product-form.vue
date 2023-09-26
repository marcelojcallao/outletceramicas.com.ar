
<script>

    import toast_mixin from './../../../../../mixins/toast-mixin';
    import sleep_mixin from './../../../../../mixins/sleep-mixin';
    import new_product_mixin from './../../../../../mixins/Products/NewProduct';
    import dropzone_upload_image_mixin from './../../../../../mixins/Products/Dropzone-Upload-Image';
    import product_form_base from './Product-form-base.vue';
    export default {

        extends : product_form_base,

        mixins : [new_product_mixin, dropzone_upload_image_mixin, sleep_mixin, toast_mixin],

        methods :{
            
            async getEnablePriceList(){

                const response = await this.$store.dispatch('enablePriceLists', this.User.token)
                .catch((err) => {
                    console.log(err);
                })

                if (response) {
                    //this.$store.commit('SET_ENABLE_PRICE_LISTS', response.data);
                    return response.data;
                }
            },

            async upload_product()
            {   
                this.loading = true;

                await this.sleep(500);

                if(this.$refs.dropzoneProductImage.getQueuedFiles().length == 0)
                {
                    let product = await this.store_product()

                    if (product) {
                        this.loading = false;
                        this.message('Se guardó correctamente');
                        this.set_initial_status();
                        return;
                    }
                        
                }
                //dropzone
                this.$refs.dropzoneProductImage.setOption("url", `/api/products/store`);
                let product = await this.upload();
                
                if (product) {
                    this.loading = false;
                    this.message('El Producto se guardó correctamente');
                    this.set_initial_status();
                    return;
                }
            }
        },

        async beforeMount(){

            const price_lists = await this.getEnablePriceList();

            this.add_price_list_formated_to_product(price_lists);

        },

    }
</script>