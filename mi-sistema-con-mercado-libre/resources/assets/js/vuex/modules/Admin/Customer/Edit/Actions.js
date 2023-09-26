export const customer_show = async (context, customer_id)  => {

    try {

        const response = await axios.post('/api/customer/show',
            {
                customer_id : customer_id
            }
        );
        
        return response.data;

    } catch (error) {
        console.log(' error en show');
        throw error;
    }
}

export const customer_update = async (context, customer)  => {

    try {

        const response = await axios.put('/api/customer/update',
            {
                customer : customer
            }
        );
        
        return response.data;

    } catch (error) {
        console.log(' error en show');
        throw error;
    }
}

export const customer_delete = async (context, customer)  => {

    try {

        const response = await axios.post('/api/customer/delete',
            {
                customer : customer,
            }
        );
        
        return response.data;

    } catch (error) {
        console.log(' error en show');
        throw error;
    }
}

export const editCustomerSetCustomerId = ({commit}, customer_id) => {
    commit('EDIT_CUSTOMER_SET_EDIT_CUSTOMER_ID', customer_id);
}
