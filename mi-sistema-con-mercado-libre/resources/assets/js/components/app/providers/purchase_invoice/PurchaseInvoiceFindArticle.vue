<template>
    <div class="center--vertical" :class="{'has-error' : PurchaseInvoiceArticlesErrorGetter}">
        <multiselect placeholder="Buscar producto" 
            id="find_article"
            track-by="name" label="name"
            :loading="show_spinner"
            v-model="selectedArticle"
            :options="products"
            :searchable="true"
            :internal-search="false" 
            :clear-on-select="false" 
            @search-change="asyncFind"
            @select="selectArticle"
            >
            <span slot="noOptions">
                    Lista Vacía
            </span>
            <span slot="noResult">
                    La búsqueda no arrojó resultados
            </span>
        </multiselect>
        <p class="text-danger" v-if="PurchaseInvoiceArticlesErrorGetter">{{PurchaseInvoiceArticlesErrorGetter[0]}}</p>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import Multiselect from 'vue-multiselect';
import auth from './../../../../mixins/auth';
import { Event } from 'vue-tables-2';
export default {
    props : ['index'],
    components : {
        Multiselect
    },
    mixins : [auth],
    data() {
        return {
            show_spinner : false,
            products : [],
            selectedArticle : null
        }
    },

    computed : {
        ...mapGetters([
            'PurchaseInvoiceArticlesErrorGetter',
        ])
    },

    methods : {

        async asyncFind (query) {

            if (query != '') {
                
                const { data:products} = await axios.post('/api/products/find_by_name', { product_name : query });

                if (products) {

                    products.forEach(product => {
                        product.$isDisabled = false;
                    })

                    this.products = products;
                }
                
            }
        },

        selectArticle(el){

            this.$store.commit('PURCHASE_INVOICE_SET_ARTICLE_IS_SELECTED', {index:this.index, value:true});
            
            const payload = {
                index:this.index, 
                value:el.id,
                name : el.name,
                accounting_account_id : el.accounting_account_id,
            }
            this.$store.commit('PURCHASE_INVOICE_SET_ID', payload);
            this.$store.commit('PURCHASE_INVOICE_SET_MEASURE_UNITY', {index:this.index, value:el.measure_unity});
        },
    },

    mounted(){

        Event.$on('purchase_invoice_set_initial_status', () => this.selectedArticle = null)
    }

}
</script>
<style  scoped>
    .center--vertical {
        vertical-align: middle;
    }
</style>