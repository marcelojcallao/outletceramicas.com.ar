import { read, writeFileXLSX } from "xlsx";
import { set_cptable } from "xlsx";
import * as XLSX from 'xlsx/xlsx.mjs';
import * as cptable from 'xlsx/dist/cpexcel.full.mjs';
set_cptable(cptable);

class CriticalStockProduct {

    constructor(data, headers, file_name){
        this.data = data;
        this.file_name = file_name;
        this.headers = headers
    }

    max_width(ws){
        const a = ws["!ref"].split(':');
        const address = XLSX.utils.decode_cell(a[1]);
        //const range = XLSX.utils.decode_range(ws["!ref"]);
        const aa = {
            c : address.c,
            r : address.r +1 
        }
        const sum_range = XLSX.utils.encode_range({s:{c:6,r:8},e:address});
        const a1_addr = XLSX.utils.encode_cell(aa);
        XLSX.utils.sheet_set_array_formula(ws, a1_addr, `SUM(${sum_range})`);

        ws["!cols"] = [
            {'width' : 5}, 
            {'width' : 50}, 
            {'width' : 25}, 
            {'width' : 20}, 
            {'width' : 20}, 
        ]
    }

    add_text(ws, cell, text){
        ws[cell] = {
            t : 's',
            v : text
        }
    }

    create_excel(){
        const worksheet = XLSX.utils.json_to_sheet(this.data, { origin: "B2" });
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, this.file_name);
        
        XLSX.utils.sheet_add_aoa(worksheet, [this.headers], { origin: "B2" });
        
        /* calculate column width */
        
        this.max_width(worksheet)
        XLSX.writeFile(workbook, this.file_name);
    }
}

export default CriticalStockProduct;