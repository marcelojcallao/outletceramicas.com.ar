export const NEW_CUSTOMER_SET_DNI = (state, value) => {
    state.new_customer.dni = value;
}

/** Formulario de datos extendido */
export const NEW_CUSTOMER_SET_NUMBER = (state, number) => state.customer_complete_data.number = number;

export const NEW_CUSTOMER_SET_NAME = (state, name) => state.customer_complete_data.name = name;

export const NEW_CUSTOMER_SET_PURCHASER_DOCUMENT = (state, purchase_document) => state.customer_complete_data.purchase_document = purchase_document;

export const NEW_CUSTOMER_SET_INSCRIPTION = (state, inscription) => state.customer_complete_data.inscription = inscription;

export const NEW_CUSTOMER_SET_ADDRESS_STATE = (state, st) => {
    state.customer_complete_data.address.state = st;
}

export const NEW_CUSTOMER_SET_ADDRESS_CITY = (state, city) => state.customer_complete_data.address.city = city;

export const NEW_CUSTOMER_SET_ADDRESS_ADDRESS = (state, address) => state.customer_complete_data.address.address = address;

export const NEW_CUSTOMER_SET_ADDRESS_CP = (state, cp) => state.customer_complete_data.address.cp = cp;

export const NEW_CUSTOMER_SET_ADDRESS_BETWEEN = (state, between) => state.customer_complete_data.address.between = between;

export const NEW_CUSTOMER_SET_ADDRESS_OBS = (state, obs) => state.customer_complete_data.address.obs = obs;

export const NEW_CUSTOMER_SET_INITIAL_STATUS = (state) => {
    //state.customer_complete_data.id = null;
    state.customer_complete_data.name = null;
    state.customer_complete_data.status = false;
    state.customer_complete_data.tipo_persona = false;
    state.customer_complete_data.tipo_clave = false;
    state.customer_complete_data.inscription = {};
    state.customer_complete_data.purchase_document = {};

    if (! state.customer_complete_data.address) {

        state.customer_complete_data.address = {
            type : null,
            state : null,
            city : null,
            address : null,
            cp : null,
            between : null,
            obs : null
        }
    }else{

        state.customer_complete_data.address.type = null;
        state.customer_complete_data.address.state = null;
        state.customer_complete_data.address.city = null;
        state.customer_complete_data.address.address = null;
        state.customer_complete_data.address.cp = null;
        state.customer_complete_data.address.between = null;
        state.customer_complete_data.address.obs = null;
    }

    state.customer_complete_data.contact.name = null;
    state.customer_complete_data.contactemail = null;
    state.customer_complete_data.contact.cell_phone = null;
    state.customer_complete_data.contact.phone_1 = null;
    state.customer_complete_data.afip_data = {};
};

export const NEW_CUSTOMER_SET_CONTACT_NAME = (state, name) => state.customer_complete_data.contact.name = name;

export const NEW_CUSTOMER_SET_CONTACT_EMAIL = (state, email) => state.customer_complete_data.contact.email = email;

export const NEW_CUSTOMER_SET_CONTACT_CELL_PHONE = (state, cell_phone) => state.customer_complete_data.contact.cell_phone = cell_phone;

export const NEW_CUSTOMER_SET_CONTACT_PHONE = (state, phone) => state.customer_complete_data.contact.phone_1 = phone;

export const NEW_CUSTOMER_SET_AFIP_DATA = (state, afip_data) => state.customer_complete_data.afip_data = afip_data;


