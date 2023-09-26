
export const listOfEarnings = async (_, url) => {

    try {
        
        const response = await axios.post(url);

        return response;

    } catch (error) {
        throw error;
    }
}

export const gainsSetFromDate = ({ commit }, value) => commit('GAINS_SET_FROM_DATE', value);

export const gainsSetToDate = ({ commit }, value) => commit('GAINS_SET_TO_DATE', value);

export const gainsSetList = ({ commit }, value) => commit('GAINS_SET_LIST', value);

export const gainsSetPagination = ({ commit }, value) => commit('GAINS_SET_PAGINATION', value);

export const gainsSetTotalEarnings = ({ commit }, value) => commit('GAINS_SET_TOTAL_EARNINGS', value);
