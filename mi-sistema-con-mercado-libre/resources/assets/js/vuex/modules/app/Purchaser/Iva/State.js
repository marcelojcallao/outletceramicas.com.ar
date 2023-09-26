const state = {

    vouchers : [],

    vouchers_by_inscription: [],

    invoice : {
        supplier : null,
        type : null,
        subsidiary : null,
        number : null,
        date : null,
        imputation_date: null,
        money : {
            code: "PES",
            description: "name",
            id: 1,
            name: "PESOS",
            symbol: "$",
        },
        products : [
            {
                product : false,
                id : null,
                name : null,
                accounting_account_id : null,
                unit_price : 0,
                measure_unity : null,
                neto_import : 0,
                quantity : 1,
                iva : {
                    code : 5,
                    id : 6,
                    name : "21%",
                    percentage : 21
                },
                iva_import : 0,
                discount_import : 0,
                total_import : 0,
            }
        ],
        taxes : [
            {
                tax: {
                    id: null,
                    name: null
                },
                import: 0
            }
        ],
        total : 0,
    }
}

export default state;