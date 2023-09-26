
class SheetMetal {

    static roundedMts = (value) => {

        const VALUE = 0.5;
    
        if (String(value).indexOf('.') > 0){
    
            const integer = parseInt(Math.trunc(value));
    
            const roundedValue = integer + parseFloat(VALUE);
            
            if(roundedValue >= value)
            {
                let result = roundedValue - parseFloat(value)
                
                return result.toFixed(2);
            }
    
            const res = Math.ceil(value) - value;
    
            return parseFloat(res);
        }
    
        return 0;
    }
}

export default SheetMetal;