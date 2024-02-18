export const PriceListEditBenefit = async (context, payload) => {
    try {

        const price_list = await axios.post('/api/price_list/update_benefit', {
            price_list_id : payload.price_list_id,
            benefit : payload.benefit,
        })

        return price_list;

    } catch (e) {
        console.log(e, 'update benefit');
        throw e;
    }
}
export const PriceListEditName = async (context, payload) => {
    try {

        const price_list = await axios.post('/api/price_list/update_name', {
            price_list_id : payload.price_list_id,
            name : payload.name,
        })

        return price_list;

    } catch (e) {
        console.log(e, 'update name');
        throw e;
    }
}
export const PriceListEditEnable = async (context, payload) => {
    try {

        const price_list = await axios.post('/api/price_list/enable', {
            price_list_id : payload.price_list_id,
            enable : payload.enable,
        })

        return price_list;

    } catch (e) {
        console.log(e, 'update name');
        throw e;
    }
}
