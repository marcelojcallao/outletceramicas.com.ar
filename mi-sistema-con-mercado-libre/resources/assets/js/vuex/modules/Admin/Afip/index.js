import state from './State';

import * as wspuc_actions from './Wspuc/Actions';
import * as wsfe_actions from './WsFe/Actions';

const actions = {
    ...wspuc_actions,
    ...wsfe_actions
}

import * as wsfe_getters from './WsFe/Getters';

const getters = {
    ...wsfe_getters
}

import * as wsfe_mutations from './WsFe/Mutations';

const mutations = {
    ...wsfe_mutations
}

export default {
    actions,
    getters,
    mutations,
    state
}