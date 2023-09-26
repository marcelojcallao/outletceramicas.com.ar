<script>
    //import Form_Product_base from './Product-form-base.vue';
    import {Event} from 'vue-tables-2';
    import Form_Product_base from './../new/Product-form-base.vue'
    import sleep_mixin from './../../../../../mixins/sleep-mixin';
    import new_product_mixin from './../../../../../mixins/Products/NewProduct';
    import edit_product_mixin from './../../../../../mixins/Products/EditProduct';
    import dropzone_upload_image_mixin from './../../../../../mixins/Products/Dropzone-Upload-Image';
    export default {

        extends : Form_Product_base,

        mixins : [
            new_product_mixin,
            edit_product_mixin,
            dropzone_upload_image_mixin,
            sleep_mixin
        ],

        props : ['product_id'],

        data(){
            return {
                msg : 'Buscando datos...',
                loading : false
            }
        },

        methods : {

            async delete_picture(id)
            {
                let payload = {
                    token : this.User.token,
                    picture_id : id
                }

                let img = await this.$store.dispatch('delete_picture', payload)
                    .catch(err => console.log(err));

                if (img) {

                    this.pictures.forEach((pic, index) => {
                        if (pic.id == id) {
                            this.pictures.splice(index, 1);
                        }
                    })
                }
            },

            async upload_product(type)
            {
                this.loading = true;

                await this.sleep(500);

                if(this.$refs.dropzoneProductImage.getQueuedFiles() && this.$refs.dropzoneProductImage.getQueuedFiles().length == 0)
                {
                    let product = await this.update_product();

                    if (product) {
                        this.loading = false;
                        this.message('Se actualizó correctamente');
                        this.set_initial_status();

                        return;
                    }
                }
                //dropzone
                this.$refs.dropzoneProductImage.setOption("url", `/api/products/update`);
                const product = await this.upload();

                if (product) {
                    this.loading = false;
                    this.message('Se actualizó correctamente');
                    this.set_initial_status();
                    return;
                }
            }
        },

        async mounted(){

            this.loading = true;

            let payload = {
                token : this.User.token,
                product_id : this.product_id
            }

            const product = await this.$store.dispatch('find_product_by_id', payload)
            .catch(e => {
                console.log(e);
            })
            .finally(() => this.loading = false);

            if (product) {
                const code = product.data.code.split('-');

                product.data.code.split('-').forEach(cod => this.$store.commit('NEW_PRODUCT_SET_CODE', cod));

                setTimeout(() => {
                    this.pictures = product.data.pictures;
                    this.$store.commit('SET_CATEGORIES_TO_SELECTED_CATEGORIES_FROM_ROOT', product.data.selected_categories_from_root);
                    this.$store.commit('EDIT_PRODUCT_SET_CHILD_CATEGORIES', product.data.categories_path);
                    this.$store.commit('NEW_PRODUCT_SET_SUPPLIER_DATA', product.data.supplier);
                    this.$store.commit('NEW_PRODUCT_SET_PRODUCT_CODE_ON_SUPPLIER', product.data.code_on_supplier);
                    this.$store.commit('NEW_PRODUCT_SET_PRODUCT_NAME_ON_SUPPLIER', product.data.name_on_supplier);
                    this.$store.commit('NEW_PRODUCT_SET_NAME', product.data.name);
                    //this.$store.commit('NEW_PRODUCT_SET_CODE', product.data.code);
                    this.$store.commit('NEW_PRODUCT_SET_DESCRIPTION', product.data.description);
                    this.$store.commit('NEW_PRODUCT_SET_STOCK', product.data.stock);
                    this.$store.commit('NEW_PRODUCT_SET_CRITICAL_STOCK', product.data.critical_stock);
                    this.$store.commit('EDIT_PRODUCT_SET_ID', product.data.id);
                    this.$store.commit('EDIT_PRODUCT_SET_PRICE', product.data.price);
                    //this.$store.commit('NEW_PRODUCT_SET_PRICE_BASE', product.data.price[0].price); //price es costo
                    this.$store.commit('NEW_PRODUCT_SET_MTS_BY_UNITY', product.data.mts_by_unity);
                    this.$store.commit('EDIT_PRODUCT_SET_IMAGES', product.data.pictures);
                    this.$store.commit('NEW_PRODUCT_SET_PUBLISH_ON_WEB_STORE', (product.data.publish_on_web==1)?true:false);
                    this.$store.commit('NEW_PRODUCT_SET_SEE_PRICE_ON_WEB_STORE', (product.data.see_price_on_the_web==1)?true:false);
                    this.$store.commit('NEW_PRODUCT_SET_IS_CHP', (product.data.isCHP==1)?true:false);
                    this.$store.commit('EDIT_PRODUCT_SET_IS_EDITION_PRODUCT', true);
                    this.$store.commit('PRODUCT_SET_APPLY_DISCOUNT', product.data.apply_discount);
                    this.$store.commit('PRODUCT_SET_APPLY_DISCOUNT_FROM', product.data.apply_discount_from);
                    this.$store.commit('PRODUCT_SET_APPLY_DISCOUNT_PERCENTAGE', product.data.apply_discount_percentage);
                    this.$store.commit('PRODUCT_SET_APPLY_DISCOUNT_PAY_METHOD', product.data.apply_discount_pay_method);
                    console.log("🚀 ~ file: EditProduct.vue:127 ~ setTimeout ~ product.data:", product.data)
                }, 250);
            }

            Event.$on('image_remove', (image_id) => {

                const index = this.pictures.findIndex(picture => {
                    return picture.id == image_id
                });

                this.pictures.splice(index, 1);

                this.success_message('Imagen eliminada correctamente', 'Producto');
            })

        },


    }
</script>
