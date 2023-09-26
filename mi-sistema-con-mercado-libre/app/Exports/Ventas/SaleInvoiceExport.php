<?php
namespace App\Exports\Ventas;

use App\Exports\ExcelBase;
use  App\Src\Models\SaleInvoice;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class SaleInvoiceExport implements  FromCollection,
WithHeadings, 
ShouldAutoSize, 
{
    use Exportable;
    
    public function __construct($from, $to)
    {
        $this->from = $from;

        $this->to = $to;

        $headings = [
            '#',
            'CLIENTE',
            'COMPROBANTE',
            'FECHA',
            'NUMERO',
            'IMPORTE',
        ];

    }

    /* public function map($invoice): array
    {
        return [
            $invoice->id,
            $invoice->cbte_fch,
            $invoice->created_at,
        ];
    } */
    public function collection()
    {
        return SaleInvoice::all();
    }
    /* public function query()
    {
        return  SaleInvoice::query()
            ->whereBetween('cbte_fch', [$this->from, $this->to])
            ->orderBy('cbte_fch');
        
    } */
}
