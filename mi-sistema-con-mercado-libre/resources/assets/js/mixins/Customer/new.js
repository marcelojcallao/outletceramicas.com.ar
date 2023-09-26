export default {

    methods : {

        async store () {

            this.spinner = true;
            
            this.sleep(500);
            
            let customer_data = this.NewCustomerCompleteData;

            let address_flag = false;

            for (const key in customer_data.address) {
                if (customer_data.address[key] != null ) {
                    address_flag = true;
                }
            }

            if (! address_flag) {
                customer_data.address = false;
            }

            const customer = await this.$store.dispatch('customer_store', customer_data)
                .catch((error) => {

                    const errors = error.response.data.errors;

                    let message = '';

                    for (const key in errors) {
                        if (errors.hasOwnProperty.call(errors, key)) {
                            message += errors[key][0] + '  ';
                        }
                    }

                    Vue.swal.fire({
                        title: 'Ingreso de clientes',
                        text : message,
                        icon : 'error',
                        width : '35%',
                        padding : '2rem',
                        backdrop : true,
                        time : 2000
                    });

                }).finally(()=>this.spinner = false);
            
            if (customer) {

                Vue.swal.fire({
                    title: 'Ingreso de clientes',
                    text : 'Se ingresó correctamente',
                    icon : 'success',
                    width : '35%',
                    padding : '2rem',
                    backdrop : true,
                    time : 2000
                });

                this.$store.dispatch('newCustomerSetInitialStatus');
            }
        },

    },

    beforeMount(){
        this.text_at_button = 'INGRESAR CLIENTE';
    }
    
}