import InterfaceStatus from './InterfaceStatus';

class DespachadoState extends InterfaceStatus {

    constructor(){
        super();
        this.status = 8;
    }

    async executeAction(){
        
        let pedido_updated = await this.statusUpdate(this.status);

        return pedido_updated;
    }
}

export default DespachadoState;