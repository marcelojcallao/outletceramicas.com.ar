export const set_remito = ({commit}, remito) => commit('SET_REMITO', remito);

export const store_remito = async (_, payload) => {

    try {
        const remito = await axios.post('/api/remitos/store', {remito:payload});

        return remito;

    } catch (e) {
        
        throw e;
    }
}