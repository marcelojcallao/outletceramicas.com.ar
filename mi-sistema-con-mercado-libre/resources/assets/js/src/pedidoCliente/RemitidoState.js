import InterfaceStatus from './InterfaceStatus';
import PreparePedidoClientePdf from './Services/PreparePedidoClientePdf';

class RemitidoState extends InterfaceStatus {

    constructor(){
        super();
        this.status = 3;
        this.new_remito = '';

    }

    control_stock(){

        let stock = [];

        this.data.items.map((item) => {

            if (item.isCHP) {

                const product_id = item.product_id;

                const disponibleStock = this.Store.getters.CheckStockDisponibleStock;

                const quantity = parseInt( item.quantity );

                let cada_chapa_mide = parseFloat( item.real_mts ) / quantity;

                const indexDisponibleStock = disponibleStock.findIndex( product => product[0].product_id === product_id);

                const sheet_metal_cuttings = disponibleStock[indexDisponibleStock];

                for (let i = 0; i < quantity; i++) {

                    sheet_metal_cuttings.sort((a,b) => {

                        if (a.mts < b.mts) {
                            return -1;
                        }
                        if (a.mts > b.mts) {
                            return 1;
                        }

                        return 0;
                    });

                    sheet_metal_cuttings.map(smc => console.log(' metros ', smc.mts));

                    const index = sheet_metal_cuttings.findIndex( smc => parseFloat(smc.mts) >= parseFloat(cada_chapa_mide) );

                    if ( index > -1 ) {
                        //console.log("🚀 ~ file: chapa", sheet_metal_cuttings[ index ].mts, 'quantity ', sheet_metal_cuttings[ index ].quantity)

                        sheet_metal_cuttings[ index ].quantity = sheet_metal_cuttings[ index ].quantity - 1;
                        //console.log("🚀 ~ file: chapa", sheet_metal_cuttings[ index ].mts, 'quantity ', sheet_metal_cuttings[ index ].quantity)

                        const recorte_sobrante_mts = parseFloat(sheet_metal_cuttings[ index ].mts) - parseFloat(cada_chapa_mide);
                        //console.log("🚀 ~ file: RemitidoState.js:63 ~ RemitidoState ~ this.data.items.map ~ recorte_sobrante_mts:", recorte_sobrante_mts)

                        if (sheet_metal_cuttings[ index ].quantity < 1) {
                            sheet_metal_cuttings.splice( index, 1 );
                        }

                        if (parseFloat(recorte_sobrante_mts) > 0) {

                            const ind = sheet_metal_cuttings.findIndex( ( element ) => {
                                return String( element.mts ) == recorte_sobrante_mts.toFixed(2)
                            });

                            if ( ind > -1 ) {
                                sheet_metal_cuttings[ ind ].quantity = parseInt(sheet_metal_cuttings[ ind ].quantity) + 1;
                            }else{
                                const mts = parseFloat(recorte_sobrante_mts.toFixed(2));

                                sheet_metal_cuttings.push({ quantity: 1, mts: mts, product_id: item.product_id} );
                            }

                        }
                    }

                }

                item.sheet_metal_cuttings = sheet_metal_cuttings;

                const payload = {
                    index: indexDisponibleStock,
                    stock: sheet_metal_cuttings
                }

                this.Store.dispatch('chekStockAddUpdatedStock', payload);

            }else{

                for (let i = 0; i < item.quantity; i++){
                    item.stock = item.stock - 1;
                }
            }

        });


    }

    async executeAction(){

        const remito_payload = {
            customer_id : this.data.customer_id,
            pedido_cliente_id : this.data.id,
            delivery_date : this.data.delivery_date,
            items : this.data.items,
            payment_type_id : this.data.payment_data.id,
            total : this.data.total_neto,
        }
        this.control_stock();

        const remito = await this.Store.dispatch('store_remito', remito_payload);

        if (remito) {

            this.remito = remito.data;

            const payload = {
                id : this.data.id,
                code : this.data.code,
                status : this.status
            }

            this.Store.commit('SET_PEDIDO_CODE', payload);

            const pedido_updated = await this.statusUpdate(this.status);

            this.printPdf();

            return pedido_updated;
        }

    }

    printPdf()
    {
        const id = this.data.id;

        this.data.id = this.remito.code;
        console.log("🚀 ~ file: RemitidoState.js:144 ~ RemitidoState ~ this.data:", this.data)

        const pdf = PreparePedidoClientePdf.prepare(this.Store.getters.GetCompany, this.data, this.Store);

        pdf.generatePdf(['ORIGINAL', 'DUPLICADO']);

        this.data.id = id;

        pdf.print();
    }

}

export default RemitidoState;
