import axios from 'axios';

const URL = 'https://cont1-virtual1.certisend.com/web/container/api/v1/database/data/ar/georef/province';

const TokenSusc = 'd5a12ca4dc0a2c5d81e8c12c803ac104';
const TokenAPI = 'b42dfc49820e09e2f9c2e85ea322e01d';

export const getState = () => {

    return axios.get(URL, {
        TOKENSUSC : TokenSusc,
        TOKENAPI : TokenAPI,
        province : 'bue'
    }); 
}