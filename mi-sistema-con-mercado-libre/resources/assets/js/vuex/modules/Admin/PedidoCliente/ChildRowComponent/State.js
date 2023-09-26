const state = {
    data : null,
    whoPrepared : null,
    whoPreparedSpinner : false,
    openWhoPreparedInput : false,
    whoDispatch : null,
    whoDispatchSpinner : false,
    openWhoDispatchInput : false,
    openChangeInvoiceDate : false,
    newProduct: 
    {
        product: null,
        product_name: null,
        code: null,
        unit_price: 0,
        quantity: 0,
        iva: 
        {
            id: 6,
            code: "5",
            name: "21%",
            percentage: "21"
        },
        isCHP: false,
        sheet_metal_cuttings: false,
        rounded_mts: 0,
        real_mts: 0,
        mts_to_invoiced: 0,
        mts_by_unity: 0,
        iva_import: 0,
        neto_import: 0,
        stock: 0,
        critical_stock: 0,
        discount: 0,
        total_raw: 0,
        price_lists: [],
        price_list: null
    },
}

export default state;