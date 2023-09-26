export const setItemsToFacturar = ({commit}, value) => commit('SET_ITEMS_TO_FACTURAR', value);

export const setItemsToFacturarInitialValue = ({commit}, _) => commit('SET_ITEMS_TO_FACTURAR_INITIAL_VALUE');

export const setIsSendingToAfip = ({commit}, value) => commit('IS_SENDING_TO_AFIP', value);

export const setItemsToFacturarUnitPrice = ({commit}, {index, value}) => commit('SET_ITEMS_TO_FACTURAR_UNIT_PRICE', {index, value});

export const setItemsToFacturarNetoImport = ({commit}, {index, value}) => commit('SET_ITEMS_TO_FACTURAR_NETO_IMPORT', {index, value});

export const setCommentsToInvoice = ({commit}, value) => commit('SET_COMMENTS_TO_INVOICE', value);
