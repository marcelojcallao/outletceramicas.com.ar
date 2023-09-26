import RemitidoState from './RemitidoState';
import FacturadoState from './FacturadoState';
import PresupuestoState from './PresupuestoState';
import PreparadoState from './PreparadoState';
import RetiradoState from './RetiradoState';
import DespachadoState from './DespachadoState';
import EntregadoState from './EntregadoState';

const statusses = [
    {
        status_id : 3,
        status : RemitidoState
    },
    {
        status_id : 4,
        status : PresupuestoState
    },
    {
        status_id : 5,
        status : FacturadoState
    },
     {
        status_id : 6,
        status : PreparadoState
    },
    {
        status_id : 7,
        status : RetiradoState
    },
    {
        status_id : 8,
        status : DespachadoState
    },
    {
        status_id : 9,
        status : EntregadoState
    }, 
];

class FactoryStatus {

    constructor() {
        this.current_status = null;
        this.state = null;
    }

    setCurrentStatus(status){
        this.current_status = status;
    }

    getInstance(){
        
        statusses.forEach(status => {

            if (status.status_id == this.current_status + 1) {
                this.state = status.status;
            }
            
        });

        return this.state;
    }
}

export default FactoryStatus;