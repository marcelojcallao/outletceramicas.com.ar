export const setSales = ( { commit }, value) => commit('SET_SALES', value);

export const sales_column_chart = async (_, {from, to}) => {

    try {
        
        const response = await axios.post('/api/gains/sales_column_chart', {from, to});

        return response;

    } catch (error) {
        throw error;
    }
}