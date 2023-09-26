const state = {

    order : {
        isNew: true,
        customer : {},
        date : null,
        delivery_date : null,
        flags : {
            hasState : true,
            hasCity : false,
            hasCp : false,
            hasStreet : false,
            hasNumber : false
        },
        address : {
            state : {
                id : 2,
                name : 'BUENOS AIRES'
            },
            city : {
                cp : null,
                name: null
            },
            street : null,
            number : null,
            between : null,
            obs : null
        },
        user_id : null,
        products : [
            {
                product : null,
                unit_price : 0,
                quantity : 1,
                iva : {
                    id: 6,
                    code: "5",
                    name: "21%",
                    percentage: "21",
                },
                isCHP : false,
                rounded_mts : 0,
                real_mts : 0,
                mts_to_invoiced : 0,
                mts_by_unity : 0,
                iva_import : 0,
                neto : 0,
                total : 0,
                price_lists : [],
                price_list : null,
                descuento: 0
            }
        ],
        payment : {
            id : '',
            name : '',
            percentage : '',
            value : ''
        },
        required_shipping : false,
        shipping : {
            percentage : 0,
            value : 0
        },
        delivery_service : 0,
        iva_import : 0,
        neto : 0,
        discount : 0,
        total : 0,
        status : null,
        type : null
    },
    current_product : null,
    multiselect_products : [],
    errors : false,
    enabledAddProductButton : true
}

export default state;