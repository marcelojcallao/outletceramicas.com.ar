import InterfaceStatus from './InterfaceStatus';

class RetiradoState extends InterfaceStatus {

    constructor(){
        super();
        this.status = 7;
    }

    async executeAction(){
        
        let pedido_updated = await this.statusUpdate(this.status);

        return pedido_updated;
    }
}

export default RetiradoState;