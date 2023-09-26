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
		this.data.items.sort((a,b) => {

			if (a.mts_by_unity < b.mts_by_unity) {
				return -1;
			}
			if (a.mts_by_unity > b.mts_by_unity) {
				return 1;
			}

			return 0;
		});

        this.data.items.map((product, index) => {

            if (product.isCHP) {

				if(typeof product.sheet_metal_cuttings === 'object' && product.sheet_metal_cuttings !== null){
					product.sheet_metal_cuttings = Object.values(product.sheet_metal_cuttings)
				}
				/**
				 * CheckStockDisponibleStock
				 * es un array que guarda el stock disponible de cada producto en el pedido y ya esta ordenado
				 */
                //const disponibleStock = this.Store.getters.CheckStockDisponibleStock;

				if (product.sheet_metal_cuttings.length) {

					const quantity = parseInt( product.quantity );

					const cada_chapa_mide = parseFloat( product.real_mts ) / quantity;

					for (let i = 0; i < quantity; i++) {

						product.sheet_metal_cuttings.sort((a,b) => {

							if (a.mts < b.mts) {
								return -1;
							}
							if (a.mts > b.mts) {
								return 1;
							}

							return 0;
						});
						console.log("🚀 ~ file 61 : ", product.product_name, ' tiene un stock de 13 mts ' + product.stock)

						//busco el recorte de chapa en el stock diponible, pero además busco la chapa en el stock que más se
						//acerca en el metraje. Esto es para aprovechar los recortes y tener el menor desperdicio posible.
						//Como el stock ya esta ordenado de menor a mayor, cuando encuentra, encuentra la chapa inmediatamente mayor.
						const index = product.sheet_metal_cuttings.findIndex( smc => parseFloat(smc.mts) >= parseFloat(cada_chapa_mide) );

						if ( index > -1 ) {

							/* console.log("Chapa:", product.product_name, " ~Necesito una de: " + cada_chapa_mide + ".mts - La que encuentra más inmediata", product.sheet_metal_cuttings[ index ].mts, 'en el buble es la vuenta número: ' + (i+1))

							console.log("🚀 ~ stock anterior :", product.sheet_metal_cuttings[ index ].quantity, product.sheet_metal_cuttings[ index ]); */
							product.sheet_metal_cuttings[ index ].quantity = product.sheet_metal_cuttings[ index ].quantity - 1;
							console.log("🚀 ~ stock actual :", product.sheet_metal_cuttings[ index ].quantity, product.sheet_metal_cuttings[ index ]);

							const recorte_sobrante_mts = parseFloat(product.sheet_metal_cuttings[ index ].mts) - parseFloat(cada_chapa_mide);
							/* console.log("🚀 ~ recorte_sobrante_mts:", recorte_sobrante_mts)
							console.log("🚀 ~ file 78 : ", product.product_name, ' tiene un stock de 13 mts ' + product.stock) */
							//Acá se agregan los sobrantes del corte nuevo y se agregan al stock
							if (parseFloat(recorte_sobrante_mts) > 0) {

								const ind = product.sheet_metal_cuttings.findIndex( ( smc ) => {
									return ('' + smc.mts.toFixed(2)) === recorte_sobrante_mts.toFixed(2)
								});

								if ( ind > -1 ) {
									console.log("🚀 ~ recorte_sobrante_mts existe y hay:  :", product.sheet_metal_cuttings[ ind ].quantity )
									product.sheet_metal_cuttings[ ind ].quantity = parseInt(product.sheet_metal_cuttings[ ind ].quantity) + 1;
									console.log("🚀 ~ recorte_sobrante_mts más 1:  :", product.sheet_metal_cuttings[ ind ].quantity )
								}else{
									const mts = parseFloat(recorte_sobrante_mts.toFixed(2));
									console.log("🚀 ~ se agrega un recorte nuevo que no estaba en el stock:", { quantity: 1, mts: mts, product_id: product.product_id})

									product.sheet_metal_cuttings.push({ quantity: 1, mts: mts, product_id: product.product_id} );
								}
							}
						}
						product.sheet_metal_cuttings.sort((a,b) => {

							if (a.mts < b.mts) {
								return -1;
							}
							if (a.mts > b.mts) {
								return 1;
							}

							return 0;
						});
						product.sheet_metal_cuttings = product.sheet_metal_cuttings.filter((smc) => smc.quantity > 0);
					}
				}
				//acá se actualiza el nuevo stock a todos los mismos productos
				this.data.items.map(producto => {
					if (producto.product_id === product.product_id) {
						producto.sheet_metal_cuttings = product.sheet_metal_cuttings;
					}
				})

            }else{

                for (let i = 0; i < product.quantity; i++){
                    product.stock = product.stock - 1;
                }
            }
        });


    }

    async executeAction(){

        this.control_stock();

        const remito_payload = {
            customer_id : this.data.customer_id,
            pedido_cliente_id : this.data.id,
            delivery_date : this.data.delivery_date,
            items : this.data.items,
            payment_type_id : this.data.payment_data.id,
            total : this.data.total_neto,
        }

        remito_payload.items.forEach(item => {

			if (item.isCHP && item.sheet_metal_cuttings) {

				item.sheet_metal_cuttings.forEach((smc, index) => {
					if (smc.quantity < 0) {
						item.sheet_metal_cuttings.splice(index, 1);
					}
				})
			}
		});

        const remito = await this.Store.dispatch('store_remito', remito_payload)
		.catch((err) => {
			console.log("🚀 ~ file: RemitidoState.js:118 ~ RemitidoState ~ executeAction ~ err:", err)

		})

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
        console.log("🚀 ~ file: RemitidoState.js:186 ~ RemitidoState ~ this.data:", this.data)

        const pdf = PreparePedidoClientePdf.prepare(this.Store.getters.GetCompany, this.data, this.Store);

        pdf.generatePdf(['ORIGINAL', 'DUPLICADO']);

        this.data.id = id;

        pdf.print();
    }

}

export default RemitidoState;
