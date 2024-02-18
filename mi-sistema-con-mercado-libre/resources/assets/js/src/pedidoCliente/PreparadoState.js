import InterfaceStatus from './InterfaceStatus';

class PreparadoState extends InterfaceStatus {

    constructor(){
        super();
        this.status = 6;
    }

    async executeAction(){

        this.Store.commit('SET_WHO_PREPARED_SPINNER', true);

        const whoPrepared = this.Store.getters.WhoPreparedGetter;

        const payload = {
            order_id : this.data.id,
            who : whoPrepared
        }

        const who = await this.Store.dispatch('who_prepared_update', payload)
        .catch(e => console.log(e));

        if(who){
            this.Store.commit('SET_OPEN_WHO_PREPARED_INPUT', false);
        }

        const pedido_updated = await this.statusUpdate(this.status);

        this.Store.commit('SET_WHO_PREPARED_SPINNER', false);

        return pedido_updated;
    }
}

export default PreparadoState;
