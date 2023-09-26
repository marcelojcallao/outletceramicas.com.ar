export const getPaymentTypes = async({commit}, token) =>  {
    try {
        //window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
        
        const response = await axios.get('/api/pay_methods/index');

        /* commit('SET_PAYMENT_TYPES', response.data); */

        return response

    } catch (e) {
        console.log('error en getAddressType action');
        console.log(e);
        throw e;
    }
}

export const updatePaymentType = async (_, payload) => {

    try {
        const response = await axios.post('/api/pay_methods/updatePayment', payload);

        return response;
        
    } catch (error) {
        console.log("🚀 ~ file: Actions.js:26 ~ updatePaymentType ~ error:", error)
        throw error;
    }
}

export const enablePaymentType = async (_, payload) => {

    try {
        const response = await axios.post('/api/pay_methods/enable', payload);

        return response;
        
    } catch (error) {
        console.log("🚀 ~ file: Actions.js:26 ~ enable ~ error:", error)
        throw error;
    }
}

export const deletePaymentType = async (_, payload) => {

    try {
        const response = await axios.post('/api/pay_methods/delete_payment_type', payload);

        return response;
        
    } catch (error) {
        console.log("🚀 ~ file: Actions.js:26 ~ enable ~ error:", error)
        throw error;
    }
}

export const storePaymentType = async (_, payload) => {

    try {
        const response = await axios.post('/api/pay_methods/store', payload);

        return response;
        
    } catch (error) {
        console.log("🚀 ~ file: Actions.js:26 ~ enable ~ storePaymentType:", error)
        throw error;
    }
}

export const updatePaymentTypeValue = ({commit}, value) => {
    commit('SET_PAYMENT_TYPE', value);
}
export const removePaymentType = ({commit}, value) => commit('DELETE_PAYMENT_TYPE', value);

export const addPaymentToList = ({commit}, value) => commit('ADD_PAYMENT_TO_LIST', value);
