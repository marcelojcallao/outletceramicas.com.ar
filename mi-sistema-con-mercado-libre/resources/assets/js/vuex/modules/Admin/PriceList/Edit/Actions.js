export const PriceListEditBenefit = async (context, payload) => {
    try {

        let price_list = await axios.post('/api/price_list/update_benefit', {
            price_list_id : payload.price_list_id,
            benefit : payload.benefit,
        })
        
        return price_list;

    } catch (e) {
        console.log(e, 'update benefit');
        throw e;
    }
}