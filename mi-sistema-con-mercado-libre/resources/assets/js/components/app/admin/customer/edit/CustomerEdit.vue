
<script>
import CustomerBase from "./../new/CustomerBase.vue";
import customer_edit_mixin from "./../../../../../mixins/Customer/edit";
export default {

    name : 'CustomerNew',

    extends : CustomerBase,

    mixins : [customer_edit_mixin],

    async beforeMount(){
        console.log(this.customer_id)
        console.log(this.customer_id)
        const customer = await this.$store.dispatch('customer_show', this.customer_id);

        console.log(customer);

        if (customer) {
            this.$store.dispatch('editCustomerSetCustomerId', customer.id);
            this.$store.dispatch('newCustomerSetNumber', customer.number);
            this.$store.commit('NEW_CUSTOMER_SET_NAME', customer.name);
            this.$store.dispatch('newCustomerSetContactName', customer.contact);
            this.$store.dispatch('newCustomerSetContactEmail', customer.email);
            this.$store.dispatch('newCustomerSetContactCellPhone', customer.cell_phone);
            this.$store.dispatch('newCustomerSetContactPhone', customer.phone_1);
            this.$store.dispatch('newCustomerAddressSetCity', (customer.addresses) ? customer.addresses[0].city:'');
            this.$store.dispatch('newCustomerAddressSetCp', (customer.addresses) ? customer.addresses[0].cp:'');
            this.$store.dispatch('newCustomerAddressSetAddress', (customer.addresses) ? customer.addresses[0].domicilio:'');
            this.$store.dispatch('newCustomerAddressSetBetween', (customer.addresses) ? customer.addresses[0].between_streets:'');
            this.$store.dispatch('newCustomerAddressSetObs', (customer.addresses) ? customer.addresses[0].obs:'');
            this.$store.dispatch('newCustomerAddressSetState', {id : (customer.addresses) ? customer.addresses[0].state_id:'', name : (customer.addresses) ? customer.addresses[0].state:''});
            this.$store.dispatch('newCustomerSetPurchaseDocument', {id : customer.purchaser_document_id, name : customer.purchaser_document}); 
            this.$store.dispatch('newCustomerSetInscription', {id : customer.inscription_id, name : customer.inscription});

        }

    }
}
</script>

<style>

</style>