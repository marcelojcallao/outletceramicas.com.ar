export const get_products_for_web = async (_, category) => {

    try {
        
        let url = '';

        if (category == null || category == 'undefined') {
            url = '/get_products';
        }else{
            url = `/get_products?by_category=${category}`;
        }
        
        const response = await axios.get(url);

        return response;

    } catch (error) {
        throw error;
    }
}
/* export const loadData = async (_, page) => {

    try {
        
        let url = '/get_products?page=' + page;

                
        const response = await axios.get(url);

        return response;

    } catch (error) {
        throw error;
    }
} */