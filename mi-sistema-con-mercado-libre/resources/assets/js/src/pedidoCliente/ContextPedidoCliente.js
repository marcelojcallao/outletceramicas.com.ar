class ContextPedidoCliente {

    constructor(){
        this.state = null;
    }

    setState(state){
        this.state = state;
    }

    async executeAction(){
        return await this.state.executeAction();
    }
}

export default ContextPedidoCliente;
