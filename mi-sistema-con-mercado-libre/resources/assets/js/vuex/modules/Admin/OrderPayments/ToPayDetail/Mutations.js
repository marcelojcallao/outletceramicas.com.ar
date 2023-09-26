export const TO_PAY_DETAIL_ADD_NOTA_DE_CREDITO = (state, value) => state.notas_de_credito.push(value);

export const TO_PAY_DETAIL_REMOVE_NOTA_DE_CREDITO = (state, id) => {

    const index = state.notas_de_credito.findIndex(nc => nc.id === id);

    state.notas_de_credito.splice(index, 1);
}