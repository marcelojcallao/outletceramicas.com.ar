import {mapGetters} from 'vuex';

export default {

    data(){
        return {
            benefit : null,
            spinner_up : false,
            spinner_down : false,
        }
    },

    computed : {

        Benefit(){
            return this.benefit;
        }
    },

    methods : {

        numbers(event){
            var value = event.target.value;

            if(isNaN(value)){
            
                value = value.replace(/[^0-9\,]/g,'');
               
                if(value.split(',').length > 2){
                    value = value.replace(/\,+$/,"");
                } 
            }

            event.target.value = value;
            
            this.http_benefit(value);
        },

        async update_benefit(type){
            
            if (type == 1) {
                
                (this.benefit == 0) ? 0 : this.benefit--;
                this.spinner_down = true;
            }

            if (type == 2) {
                
                this.benefit++;
                this.spinner_up = true;
            }

            this.sleep(50);

            this.http_benefit(this.benefit);
            
        },

        async http_benefit(benefit){

            let payload = {
                token : this.User.token,
                index : this.index,
                price_list_id : this.data.id,
                benefit : benefit,
            }

            let pl = await this.$store.dispatch('PriceListEditBenefit', payload)
                .catch(err => {
                    console.log(err, 'esaaaaaaaaaaa');
                }).finally(()=> {
                    this.spinner_up = false
                    this.spinner_down = false
                });
            
            if (pl) {
                this.$store.commit('NEW_PRODUCT_SET_PRICE_BY_BENEFIT', payload);
            }
        }
        
    },

    beforeMount(){
        this.benefit = parseFloat(this.data.benefit);
    }
    
}