import { Workbook } from 'exceljs';
import * as fs from 'file-saver';

class ExcelInvoices {

    constructor(){
        this.WB = new Workbook()
    }

    downloadExcel(data){

        this.WB.creator = 'Diego Adrian';
        this.createInvoicesTable(data);
        this.WB.xlsx.writeBuffer().then((data)=>{
            const blob = new Blob([data]);
            fs.saveAs(blob, 'www.xlsx')
        })
    }

    createInvoicesTable(invoicesData){

        const sheet = this.WB.addWorksheet('Comprobantes');

        sheet.getColumn('B').width = 31;
        sheet.getColumn('C').width = 31;
        sheet.getColumn('D').width = 31;
        sheet.getColumn('E').width = 31;
        sheet.getColumn('F').width = 31;
        sheet.getColumn('G').width = 31;
        sheet.getColumn('H').width = 31;

        sheet.columns.forEach(colum => {
            colum.alingment = {vertical: 'middle', wrapText: true}
        });

        const titleCell = sheet.getCell('C5');

        titleCell.value = 'Comprobantes de Venta';
        titleCell.style.font = { bold: true, size: 21};

        const headerRow = sheet.getRow(7);
        
        
        headerRow.values = [
            '',
            'CLIENTE',
            'COMPROBANTE',
            'NÚMERO',
            'FECHA',
            'NETO',
            'IVA',
            'TOTAL',
            'CAE'
        ];

        headerRow.font = { bold: true, size: 14};

        const rowsToInsert = sheet.getRows(8, invoicesData.length);

        for (let index = 0; index < rowsToInsert.length; index++) {
            const itemData = invoicesData[index];
            const row = rowsToInsert[index];
            
            row.values = [
                '',
                itemData.customer_name,
                itemData.name,
                itemData.number,
                itemData.date,
                itemData.neto,
                '',
                '',
                itemData.cae
            ]
        }

        sheet.eachRow((row, rowNum) => {

            row.eachCell(function(cell, colNumber) {
                console.log("🚀 ~ file: ExcelInvoices.js:80 ~ ExcelInvoices ~ row.eachCell ~ cell:", cell)
                
            });
            
        })
        
    }
}

export default ExcelInvoices;