export const systemCheckingAccountSetBank = ({ commit }, value) => commit('SYSTEM_CHECKING_ACCOUNT_SET_BANK', value);

export const systemCheckingAccountSetCode = ({ commit }, value) => commit('SYSTEM_CHECKING_ACCOUNT_SET_CODE', value);

export const systemCheckingAccountSetList = ({ commit }, value) => commit('SYSTEM_CHECKING_ACCOUNT_SET_LIST', value);

export const checkingAccountStore = async (_, checkingAccount) => {

    try {
        
        const response = await axios.post('/api/checkingAccount/store', checkingAccount);

        return response;
        
    } catch (error) {
        console.log("🚀 ~ file: Actions.js:12 ~ getBanks ~ error:", error)
        throw error
    }
}

export const getCheckingAccounts = async (_) => {

    try {
        
        const response = await axios.get('/api/checkingAccount/index');

        return response;
        
    } catch (error) {
        console.log("🚀 ~ file: Actions.js:12 ~ getBanks ~ error:", error)
        throw error
    }
}