export const checkStockSetProduct = ({ commit }, product) => commit('CHECK_STOCK_SET_PRODUCT', product);

export const checkStockSetMtsByUnity = ({ commit }, value) => commit('CHECK_STOCK_SET_MTS_BY_UNITY', value);

export const checkStockSetRoundedMts = ({ commit }, value) => commit('CHECK_STOCK_SET_ROUNDED_MTS', value);

export const checkStockSetRealMts = ({ commit }, value) => commit('CHECK_STOCK_SET_REAL_MTS', value);

export const checkStockSetMtsToInvoiced = ({ commit }, value) => commit('CHECK_STOCK_SET_MTS_TO_INVOICED', value);

export const check_stock = async ({ dispatch, getters }, quantity) => {

    const critical_stock =  parseInt(getters.CheckStockProductGetter.critical_stock);

    const actual_stock = parseInt(getters.CheckStockProductGetter.stock) - parseInt(quantity);

    if (actual_stock < 0) {

        const text = `No hay stock para completar el pedido de éste producto. Si desea, puede utilizar el stock disponible. ¿Continuar?`;

        let result =  await Vue.swal.fire({
            title: 'PRODUCTO SIN STOCK',
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Continuar',
            cancelButtonText: 'Cancelar'
        })

        result.quantity = getters.CheckStockProductGetter.stock;

        return result

    } else if  (actual_stock <= critical_stock && getters.CheckStockProductGetter.stock > 0) {

            const unidades = ((getters.CheckStockProductGetter.stock - parseInt(quantity)) > 1) ? 'unidades' : 'unidad';
            const quedaran = ((getters.CheckStockProductGetter.stock - parseInt(quantity)) > 1) ? 'quedaran' : 'quedará';

            let result = await Vue.swal.fire({
                title: 'STOCK CRÍTICO',
                text: `Producto en stock crítico, actualmente hay ${getters.CheckStockProductGetter.stock} unidades. Luego de esta transacción ${quedaran} ${getters.CheckStockProductGetter.stock - parseInt(quantity)} ${unidades}.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Continuar',
                cancelButtonText: 'Cancelar'
            })

            result.quantity = quantity;
            return result;

    } else {

        const result = {
            isConfirmed: true,
            quantity
        };

        return result;

    }
}

export const check_sheet_metal_stock = async ({ dispatch, getters }, { product_id, quantity, mts_by_unity }) => {

    const stockToControl = getters.CheckStockDisponibleStock.filter(stock => stock[0].product_id === product_id);

    let sheet_metal_cuttings_suitable_for_this_sale = 0;

    let isLonger = mts_by_unity;

    let longer_sheet_metal = 0;

    stockToControl.map( smc => {

        smc.map( chapa => {
            const cuttings = parseFloat( chapa.mts ) / parseFloat( mts_by_unity );
            console.log("🚀 ~ file: Actions.js:98 ~ constcheck_sheet_metal_stock= ~ parseFloat( chapa.mts ) / parseFloat( mts_by_unity ):", chapa, parseFloat( chapa.mts ) , parseFloat( mts_by_unity ))
            console.log("🚀 ~ file: Actions.js:98 ~ constcheck_sheet_metal_stock= ~ cuttings:", cuttings)

            const integer = parseInt(Math.trunc(cuttings));

            console.log("🚀 ~ file: Actions.js:103 ~ constcheck_sheet_metal_stock= ~ integer:", integer)
            if (integer > 0) {

                sheet_metal_cuttings_suitable_for_this_sale += integer * parseInt(chapa.quantity);
            }
            console.log("🚀 ~ file: Actions.js:106 ~ constcheck_sheet_metal_stock= ~ sheet_metal_cuttings_suitable_for_this_sale:", sheet_metal_cuttings_suitable_for_this_sale)

            if ( parseFloat( chapa.mts ) > parseFloat( longer_sheet_metal ) ) {
                longer_sheet_metal = chapa.mts;
            }
        })

    });

    if ( parseFloat( isLonger )  >  parseFloat( longer_sheet_metal ) ) {

        const result = await Vue.swal.fire({
            title: 'PRODUCTO SIN STOCK',
            text: `No hay recortes disponibles de ${isLonger} metros, el recorte más largo en el stock es de ${longer_sheet_metal} metros.`,
            icon: 'error',
            showDenyButton: true,
            denyButtonColor: '#dd6b55',
            denyButtonText: 'Cerrar',
            showConfirmButton: false
        })

        return result;
    }

    if  (sheet_metal_cuttings_suitable_for_this_sale == 0 ) {

        const result = await Vue.swal.fire({
            title: 'PRODUCTO SIN STOCK',
            text: `Actualmente éste producto se encuentra sin stock.`,
            icon: 'error',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Cerrar',
        })

        return result;

    }

    if (sheet_metal_cuttings_suitable_for_this_sale > 0 && sheet_metal_cuttings_suitable_for_this_sale < quantity) {

        const result = await Vue.swal.fire({
            title: 'STOCK INSUFICIENTE',
            text: `No hay capacidad para éste producto, quedan ${ sheet_metal_cuttings_suitable_for_this_sale } unidades. Usted solicita ${ quantity } unidades.
                ¿Desea utilizar las unidades que quedan en stock?`,
            icon: 'error',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Continuar',
            showCancelButton: true,
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed) {
            dispatch('orderUpdateSetQuantityProduct', parseInt( sheet_metal_cuttings_suitable_for_this_sale ));
        }

        return result;
    }

    const result = {isConfirmed: true};

    return result;
}

export const setDisponibleStock = ({ commit }, value) => commit('CHECK_STOCK_ADD_DISPONIBLE_STOCK', value);

export const cleanDisponibleStock = ( { commit } ) => commit('CHECK_STOCK_CLEAN_DISPONIBLE_STOCK');

export const chekStockAddUpdatedStock = ( { commit }, payload) => commit('CHECK_STOCK_ADD_UPDATED_STOCK', payload);

export const getStockMovements = async (_, product_id) => {

	try{

        const response = await axios.post('/api/products/getStockMovements', {product_id});

        if (response) {
            return response;
        }

    } catch(e){
    console.log("🚀 ~ file: Actions.js:173 ~ getStockMovements ~ e:", e)

    }
}
