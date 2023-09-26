import state from './State';

import * as search_actions from './Search/Actions';
import * as list_actions from './List/Actions';
import * as new_actions from './New/Actions';
import * as edit_actions from './Edit/Actions';

const actions = {
    ...search_actions,
    ...list_actions,
    ...new_actions,
    ...edit_actions
}

import * as list_getters from './List/Getters';
import * as new_getters from './New/Getters';
import * as edit_getters from './Edit/Getters';

const getters = {
    ...list_getters,
    ...new_getters,
    ...edit_getters
}

import * as list_mutations from './List/Mutations';
import * as new_mutations from './New/Mutations';
import * as edit_mutations from './Edit/Mutations';

const mutations = {
    ...list_mutations,
    ...new_mutations,
    ...edit_mutations
}

export default {
    actions,
    getters,
    mutations,
    state
}