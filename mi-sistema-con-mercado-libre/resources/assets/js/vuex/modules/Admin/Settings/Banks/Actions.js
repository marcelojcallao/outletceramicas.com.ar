export const banksSetList = ({ commit }, value) => commit('BANKS_SET_LIST', value);

export const banksSetBank = ({ commit }, value) => commit('BANKS_SET_BANK', value);

export const getBanks = async (_) => {

    try {
        
        const response = await axios.get('/api/bank/index');

        return response;
        
    } catch (error) {
        console.log("🚀 ~ file: Actions.js:12 ~ getBanks ~ error:", error)
        throw error
    }
}