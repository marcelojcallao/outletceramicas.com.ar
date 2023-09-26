export const priceListGetListAction = async (context, token) => {

    try {
        window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;

        let list = await axios.post('/api/price_list/index')
        
        return list;

    } catch (e) {
        console.log("🚀 ~ file: Actions.js ~ line 11 ~ priceListGetListAction ~ e", e)
        
        throw e;
    }
}