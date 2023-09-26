<?php

namespace App\Exports;

use App\Src\Traits\ZeroLeftTrait;
use App\Src\Traits\DateFormatTrait;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ExcelBase
{
    use DateFormatTrait, ZeroLeftTrait;
    
    const ARGENTINA_FORMAT_CURRENCY = '_ $ * #,##0.00_ ;_ $ * -#,##0.00_ ;_ $ * "-"??_ ;_ @_ ';
    
    protected $lastRowNumber = 0;
    
    protected $firstRowNumber = 8;
    
    protected $lastColumnLetter;

    protected $row = 1;
    
    protected $startCell =  'B8';

    protected $headings = [];

    protected $from;

    protected $to;

    public function parseNumberToColumn($position=0)
    {
        $alphabet = range('A', 'Z');

        return collect($alphabet)->map(function($a, $index) use($position) {
            if ($index == $position) {
                return $a;
            }
        })->filter()->values()->toArray()[0];

    }

    public function startCell(): string
    {
        return $this->startCell;
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo de la empresa');
        $drawing->setPath(public_path('/images/logos/logo.png'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('B2');

        return $drawing;
    }

    public function headings(): array
    {
        return [
            $this->headings
        ];
    }

}
