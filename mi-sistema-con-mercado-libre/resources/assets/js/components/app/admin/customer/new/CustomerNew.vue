
<script>
import CustomerBase from "./CustomerBase.vue"
import customer_new_mixin from "./../../../../../mixins/Customer/new";
export default {

    name : 'CustomerNew',

    extends : CustomerBase,

    mixins : [customer_new_mixin],
}
</script>

<style>

</style>