export default {

    methods : {

        numbers(event){
            var value = event.target.value;

            if(isNaN(value)){
            
                value = value.replace(/[^0-9\,]/g,'');
               
                if(value.split(',').length > 2){
                    value = value.replace(/\,+$/,"");
                } 
            }

            event.target.value = value;
        }
    },
}