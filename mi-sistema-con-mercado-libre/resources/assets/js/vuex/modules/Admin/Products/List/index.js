import * as list_actions from './Actions';
import * as sheet_metal_cuttings_actions from './SheetmetalCuttings/Actions';

import * as list_mutations from './Mutations';
import * as sheet_metal_cuttings_mutations from './SheetmetalCuttings/Mutations';

import state from './State';

import * as list_getters from './Getters';
import * as sheet_metal_cuttings_getters from './SheetmetalCuttings/Getters';

const getters = {
    ...list_getters,
    ...sheet_metal_cuttings_getters,
} 
const mutations = {
    ...list_mutations,
    ...sheet_metal_cuttings_mutations,
} 
const actions = {
    ...list_actions,
    ...sheet_metal_cuttings_actions,
} 

export default {
    actions,
    getters,
    mutations,
    state
}