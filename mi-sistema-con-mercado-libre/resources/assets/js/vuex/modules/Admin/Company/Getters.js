const getters = {

    HasCompany(state){

        if(!  _.isEmpty(state.company) ){

            return true;
        }
        
        return false;
    },

    GetCompany(state)
    {
        return state.company;
    },

    AfipWsEnvironmetMultiselect(state)
    {
        return state.afip_ws_environment;
    },

    AfipWsEnvironmet(state)
    {
        return state.company.settings['afip_enviroment'];
    }

    
}

export default getters;