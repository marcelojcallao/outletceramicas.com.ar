import InterfaceStatus from './InterfaceStatus';

class EntregadoState extends InterfaceStatus {

    constructor(){
        super();
        this.status = 9;
    }

    async executeAction(){
        
        this.Store.commit('SET_WHO_DISPATCH_SPINNER', true);

        let whoDispatch = this.Store.getters.WhoDispatchGetter;

        let payload = {
            order_id : this.data.id,
            who : whoDispatch
        }

        let who = await this.Store.dispatch('who_dispatch_update', payload)
        .catch(e => console.log(e));

        if(who){
            this.Store.commit('SET_OPEN_WHO_DISPATCH_INPUT', false);
        }

        let pedido_updated = await this.statusUpdate(this.status);

        this.Store.commit('SET_WHO_DISPATCH_SPINNER', false);

        return pedido_updated;
    }
}

export default EntregadoState;