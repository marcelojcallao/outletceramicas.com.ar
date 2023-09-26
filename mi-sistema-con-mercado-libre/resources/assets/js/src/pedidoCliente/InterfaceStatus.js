class InterfaceStatus {

    constructor(){
        this.data = null;
        this.new_status = null;
        this.Store = null;
        this.InstanceVue = null;
        this.Moment = null;
    }

    async executeAction(){}

    async statusUpdate(new_status){

        const pedido = await axios.put('/api/pedido_cliente/status_update',
        {
            pedido : this.data,
            new_status : new_status
        })
        .catch((err) => {
            console.log('error en RemitidoState' + err);
            return false;
        });

        if (pedido) {
            return pedido;
        }
    }

    setMoment(moment){
        this.Moment = moment;
    }

    setStore(store){
        this.Store = store;
    }

    setData(data){
        this.data = data;
        console.log("🚀 ~ file: InterfaceStatus.js:40 ~ InterfaceStatus ~ setData ~ this.data:", this.data)

    }
}

export default InterfaceStatus;
