export const setSheetMetalCutting = ({commit}, value) => commit('SET_SHEET_METAL_CUTTINGS', value);

export const newSheetMetalCuttingSetId = ({commit}, value) => commit('NEW_SHEET_METAL_CUTTING_SET_ID', value);

export const newSheetMetalCuttingSetQuantity = ({commit}, value) => commit('NEW_SHEET_METAL_CUTTING_SET_QUANTITY', value);

export const newSheetMetalCuttingSetMts = ({commit}, value) => commit('NEW_SHEET_METAL_CUTTING_SET_MTS', value);

export const getSheetMetalCuttings = async (_) => {

    try {
        const sheet_metal_cuttings_list = await axios.get('/api/products/sheet_metal_cuttings');

        return sheet_metal_cuttings_list;

    } catch (e) {
        
        throw e;
    }
}

export const saveSheetMetalCutting = async (_, payload) => {

    try {
        const new_sheet_metal_cutting = await axios.post('/api/products/sheet_metal_store',{new_sheet_metal : payload});

        return new_sheet_metal_cutting;

    } catch (e) {
        
        throw e;
    }
}
export const deleteAllSheetMetalCuttings = async (_) => {

    try {
        const response = await axios.post('/api/products/delete_sheet_metal_cuttings');

        return response;

    } catch (e) {
        
        throw e;
    }
}
export const deleteSheetMetalCutting = async (_, { product_id, mts }) => {

    try {
        const response = await axios.post('/api/products/delete_single_sheet_metal_cutting', { product_id, mts });

        return response;

    } catch (e) {
        
        throw e;
    }
}
export const getSheetMetalCutting = async (_, id) => {

    try {
        const response = await axios.post('/api/products/get_sheet_metal_cuttings', {id});

        return response;

    } catch (e) {
        
        throw e;
    }
}