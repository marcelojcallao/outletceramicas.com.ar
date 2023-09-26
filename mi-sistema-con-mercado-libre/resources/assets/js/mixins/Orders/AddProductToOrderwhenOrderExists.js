import {mapGetters} from 'vuex';
import collect from 'collect.js';
import DeleteRow from '../../components/app/admin/orders/New/Body/Row/DeleteRowButton';
import MultiselectIva from '../../components/app/admin/orders/New/Body/Row/MultiselectIva';
import MultiselectProduct from '../../components/app/admin/orders/New/Body/Row/MultiselectProduct';
import MultiselectPriceList from '../../components/app/admin/orders/New/Body/Row/MultiselectPriceList';
export default {

    components : {
        DeleteRow,
        MultiselectIva,
        MultiselectProduct,
        MultiselectPriceList
    },

    data() {
        return {
            expresions : {
                quantity : /^[0-9]+$/,
            },
            stock : 0
        }
    },

    methods: {

        isNumber($event) {
            const char = String.fromCharCode($event.keyCode);
            if (this.expresions.quantity.test(char)) return true;
            else $event.preventDefault();
        },

        isDecimal($event) {
            let keyCode = ($event.keyCode ? $event.keyCode : $event.which);

            // only allow number and one dot
            if ((keyCode < 48 || keyCode > 57) && (keyCode !== 46 || $event.target.value.indexOf('.') != -1)) { // 46 is dot
                $event.preventDefault();
            }

            // restrict to 2 decimal places
            if($event.target.value!=null && $event.target.value.indexOf(".")>-1 && ($event.target.value.split('.')[1].length > 1)){
                $event.preventDefault();
            }

        },

        rounded(value){

            const VALUE = 0.5;

            if (String(value).indexOf('.') > 0){

                let parteEntera = parseInt(Math.trunc(value));

                let subido = parteEntera + parseFloat(VALUE);
                
                if(subido >= value)
                {
                    let result = subido - parseFloat(value)
                    
                    return result.toFixed(2);
                }

                return Math.ceil(value) - value;
            }

            return 0;
        },

    },

    watch : {
        
    },

    computed : {

        isUserOfSale()
        {
            if (this.User.type_user_id == 3) {
                return true;
            }

            return false;
        },

        IvaImport(){
            return this.NewOrderIvaImportGetter(this.index);
        },

        unit_price : {
            
        },

        quantity : {
            
        },

        descuento : {
            
        },

        total : {
            
        },

        Code()
        {
            return '';
        },

        IsCHP(){
            
        },

        RoundedMts(){
            
        }
    },

}