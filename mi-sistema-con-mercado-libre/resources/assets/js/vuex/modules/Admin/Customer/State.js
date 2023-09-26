const state = {

    customer : {},
    customers : [],
    new_customer : { //basic form
        dni : null,
        data : null
    },
    customer_complete_data  : {
        id : null,
        name : null,
        status : false,
        tipo_persona : false,
        tipo_clave : false,
        inscription : {},
        purchase_document : {},
        number : null,
        address : {
            type : null,
            state : null,
            city : null,
            address : null,
            cp : null,
            between : '',
            obs : ''
        },
        contact : {
            name : null,
            email : null,
            cell_phone : null,
            phone_1 : null,
            phone_2 : null,
            phone_3 : null,
        },
        afip_data : {}
    },
    edit_customer_id : null
}

export default state;