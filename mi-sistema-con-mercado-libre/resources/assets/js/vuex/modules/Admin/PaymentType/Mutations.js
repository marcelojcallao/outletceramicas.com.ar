export const SET_PAYMENT_TYPES = (state, value) => {
    state.payment_types = value;
}

export const SET_PAYMENT_TYPE = (state, value) => {
    state.payment_type = value;
}

export const DELETE_PAYMENT_TYPE = (state, value) => {

    const index = state.payment_types.findIndex( payment => payment.id == value);

    state.payment_types.splice(index, 1);
}

export const ADD_PAYMENT_TO_LIST = (state, value) => state.payment_types.unshift(value);