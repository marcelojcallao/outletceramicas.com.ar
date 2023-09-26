
export const customer_store_basic_data = async (context, customer)  => {

    try {

        const response = await axios.post('/api/customer/store_basic_data',
            {
                customer : customer
            }
        );
        
        return response.data;

    } catch (error) {
        console.log(' error en store_basic_data_customer');
        throw error;
    }
}

export const customer_store = async (context, customer)  => {

    try {

        const response = await axios.post('/api/customer/store',
            {
                customer : customer
            }
        );
        
        return response.data;

    } catch (error) {
        console.log(' error en store');
        throw error;
    }
}
export const save_customer_address = async (context, payload)  => {

    try {

        const response = await axios.post('/api/customer/save_customer_address',
            {
                customer_id : payload.customer_id,
                address: payload.address
            }
        );
        
        return response.data;

    } catch (error) {
        console.log(' error en save_customer_address');
        throw error;
    }
}

export const newCustomerSetNumber = ({commit}, number) => {
    commit('NEW_CUSTOMER_SET_NUMBER', number)
}

export const newCustomerSetName = ({commit}, name) => {
    commit('NEW_CUSTOMER_SET_NAME', name)
}

export const newCustomerSetPurchaseDocument = ({commit}, purchaser_document) => {
    commit('NEW_CUSTOMER_SET_PURCHASER_DOCUMENT', purchaser_document)
}

export const newCustomerSetInscription = ({commit}, inscription) => {
    commit('NEW_CUSTOMER_SET_INSCRIPTION', inscription)
}

export const newCustomerAddressSetState = ({commit}, state) => {
    commit('NEW_CUSTOMER_SET_ADDRESS_STATE', state)
}

export const newCustomerAddressSetCity = ({commit}, city) => {
    commit('NEW_CUSTOMER_SET_ADDRESS_CITY', city)
}

export const newCustomerAddressSetAddress = ({commit}, address) => {
    commit('NEW_CUSTOMER_SET_ADDRESS_ADDRESS', address)
}

export const newCustomerAddressSetCp = ({commit}, cp) => {
    commit('NEW_CUSTOMER_SET_ADDRESS_CP', cp)
}

export const newCustomerAddressSetBetween = ({commit}, between) => {
    commit('NEW_CUSTOMER_SET_ADDRESS_BETWEEN', between)
}

export const newCustomerAddressSetObs = ({commit}, obs) => {
    commit('NEW_CUSTOMER_SET_ADDRESS_OBS', obs)
}

export const newCustomerSetInitialStatus = ({commit}) => {
    commit('NEW_CUSTOMER_SET_INITIAL_STATUS')
}

export const newCustomerSetContactName = ({commit}, name) => {
    commit('NEW_CUSTOMER_SET_CONTACT_NAME', name)
}

export const newCustomerSetContactEmail = ({commit}, email) => {
    commit('NEW_CUSTOMER_SET_CONTACT_EMAIL', email)
}

export const newCustomerSetContactCellPhone = ({commit}, cell_phone) => {
    commit('NEW_CUSTOMER_SET_CONTACT_CELL_PHONE', cell_phone)
}

export const newCustomerSetContactPhone = ({commit}, phone) => {
    commit('NEW_CUSTOMER_SET_CONTACT_PHONE', phone)
}

export const newCustomerSetAfipData = ({commit}, afip_data) => {
    commit('NEW_CUSTOMER_SET_AFIP_DATA', afip_data)
}

