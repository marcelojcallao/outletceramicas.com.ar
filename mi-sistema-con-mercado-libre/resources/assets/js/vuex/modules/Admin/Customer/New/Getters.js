export const NewCustomerDniGetter = (state) => {
    return state.new_customer.dni;
}

export const NewCustomerGetNumber = (state) => state.customer_complete_data.number;

export const NewCustomerGetName = (state) => state.customer_complete_data.name;

export const NewCustomerGetPurchaserDocument = (state) => state.customer_complete_data.purchase_document;

export const NewCustomerGetInscription = (state) => state.customer_complete_data.inscription;

export const NewCustomerAddressGetState = (state) => state.customer_complete_data.address.state;

export const NewCustomerAddressGetCity = (state) => state.customer_complete_data.address.city;

export const NewCustomerAddressGetAddress = (state) => state.customer_complete_data.address.address;

export const NewCustomerAddressGetCP = (state) => state.customer_complete_data.address.cp;

export const NewCustomerAddressGetBetween = (state) => state.customer_complete_data.address.between;

export const NewCustomerAddressGetObs = (state) => state.customer_complete_data.address.obs;

export const NewCustomerGetContactName = (state) => state.customer_complete_data.contact.name;

export const NewCustomerGetContactEmail = (state) => state.customer_complete_data.contact.email;

export const NewCustomerGetContactCellPhone = (state) => state.customer_complete_data.contact.cell_phone;

export const NewCustomerGetContactPhone = (state) => state.customer_complete_data.contact.phone_1;

export const NewCustomerCompleteData = (state) => state.customer_complete_data;



