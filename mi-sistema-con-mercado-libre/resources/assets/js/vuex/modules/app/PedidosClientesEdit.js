
export default {

    state : {
        pedido : null,
        update_delivery_date : null
        
    },

    actions : {

        async update_delivery_date(context, payload)
        {
            try {
                
                const response = await axios.post('/api/pedido_cliente/update_delivery_date', 
                {
                    date : payload.date,
                    pedido_id : payload.pedido_id,

                })

                return response.data;

            } catch (error) {
                console.log('Hubo un error en update_delivery_date');
                throw error;
            }
        },

        async delete_product(_, payload)
        {
            try {
                
                const response = await axios.post('/api/pedido_cliente/delete_product', 
                {
                    pedido_id : payload.pedido_id,
                    product_id : payload.product_id,

                })

                return response;

            } catch (error) {
                console.log("🚀 ~ file: PedidosClientesEdit.js ~ line 47 ~ error", error)
                
                throw error;
            }
        }
    },

    mutations : {

        UPDATE_DELIVERY_DATE(state, value)
        {
            state.update_delivery_date = value
        }
        
    },

    getters : {

        GetPedidoEdit(state)
        {
            return state.pedido;
        }
        
    }
}