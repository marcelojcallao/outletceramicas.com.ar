export const SET_SHEET_METAL_CUTTINGS = (state, value) => state.sheet_metal_cuttings = value;

export const NEW_SHEET_METAL_CUTTING_SET_ID = (state, value) => state.new_sheet_metal_cutting.id = value;

export const NEW_SHEET_METAL_CUTTING_SET_QUANTITY = (state, value) => state.new_sheet_metal_cutting.quantity = (value == '')?'':parseInt(value);

export const NEW_SHEET_METAL_CUTTING_SET_MTS = (state, value) => state.new_sheet_metal_cutting.mts = (value == '')?'':parseFloat(value);