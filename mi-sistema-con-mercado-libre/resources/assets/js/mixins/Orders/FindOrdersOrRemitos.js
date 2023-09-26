
export default {
    
    data() {
        return {
            code : null,
            codes: [],
            show_spinner_pedido : false
        }
    },

    methods: {
        
        async asyncFindPedidos (query) {
            
            this.show_spinner_pedido = true;
            
            const { data } = await axios.post('/api/pedido_cliente/findCode', 
                {
                    code : query
                })

            this.codes = data;
            
            this.show_spinner_pedido = false;
        },

        selectCode(el){
            this.code = el.code;
        },

        removeCode(){
            this.code = null
        }

    },

    mounted() {

        if (typeof this.loadData == 'function') {
            this.loadData();
        }
    },
}