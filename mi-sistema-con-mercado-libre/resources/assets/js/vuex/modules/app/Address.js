import collect from 'collect.js';

export default {


    actions : {

        async save_address(context, data)
        {
            try {
                window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + data.token;
                
                let response = await axios.post('/api/address/store', 
                {
                    address : data.address
                });

                return response

            } catch (e) {
            console.log("🚀 ~ file: Address.js ~ line 21 ~ e", e)
            }
        },

        async update_address(context, data)
        {
            try {
                window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + data.token;
                
                let response = await axios.put('/api/address/update', 
                {
                    address : data.address
                });

                return response

            } catch (e) {
            console.log("🚀 ~ file: Address.js ~ line 38 ~ e", e)
            }
        }
    },


}
