export const setDailyMovementDate = ({ commit }, value) => commit('DAILY_MOVEMENT_DATE', value);

export const setDailyMovementType = ({ commit }, value) => commit('DAILY_MOVEMENT_TYPE', value);

export const setDailyMovementDescription = ({ commit }, value) => commit('DAILY_MOVEMENT_DESCRIPTION', value);

export const setDailyMovementImport = ({ commit }, value) => commit('DAILY_MOVEMENT_IMPORT', value);

export const setDailyMovementList = ({ commit }, value) => commit('DAILY_MOVEMENT_LIST', value);

export const setDailyMovementSaldo = ({ commit }, value) => commit('DAILY_MOVEMENT_SALDO', value);

export const deleteDailyMovementFromList = ({ commit }, value) => commit('DELETE_DAILY_MOVEMENT', value);

export const getAllPettyCash = async (_) => {

    try {
        const response = await axios.get('/api/cash/index');

        return response;

    } catch (e) {

        throw e;
    }
}

export const saveDailyMovement = async (_, movement) => {

    try {
        const response = await axios.post('/api/cash/store', movement);

        return response;

    } catch (e) {

        throw e;
    }
}
export const deleteDailyMovement = async (_, id) => {

    try {
        const response = await axios.post('/api/cash/delete', id);

        return response;

    } catch (e) {

        throw e;
    }
}
