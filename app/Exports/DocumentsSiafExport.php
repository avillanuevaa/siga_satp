<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\DocumentSiaf;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class DocumentsSiafExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithTitle, WithMapping, WithCustomStartCell
{
    private $headings_ple = [
        'N°',
        'SIAF',
        'TIPO',
        'FECHA',
        'COMPROBANTE PAGO',
        '',
        '',
        'TD',
        'RUC',
        'PROVEEDOR',
        'BASE IMPONIBLE',
        'IMPUESTO BOLSAS P.',
        'IGV',
        'OTROS CARGOS',
        'TOTAL',
        'DOC.PAGO',
        'FECHA DE PAGO',
        'E',
        'COMPROBANTE DE PAGO QUE MOD.',
        '',
        '',
        '',
        ''
    ];

    private $headings_plame = [
        'N°',
        'SIAF',
        'TIPO',
        'FECHA',
        'COMPROBANTE PAGO',
        '',
        '',
        'TD',
        'RUC',
        'PROVEEDOR',
        'TOTAL HONORARIO',
        'VALOR RETENCION.',
        'NETO RECIBIDO',
        'DOC.PAGO',
        'FECHA DE PAGO',
        'E',
        'COMPROBANTE DE PAGO QUE MOD.',
        '',
        '',
        '',
        ''
    ];
    private $month;
    private $year;
    private $type;
    private $row = 0;

    public function __construct(int $month, int $year, int $type)
    {
        $this->month = $month;
        $this->year = $year;
        $this->type = $type;
    }

    public function collection()
    {
        if ($this->type == 1) {
            return DocumentSiaf::getExcelPlebyMonth($this->month, $this->year)->get();
        } elseif ($this->type == 2) {
            return DocumentSiaf::getExcelPlamebyMonth($this->month, $this->year)->get();
        } elseif ($this->type == 3) {
            return DocumentSiaf::getExcelPending($this->year)->get();
        }
    }

    public function map($data): array
    {
        $Estado = "";
        if ($data->type_new == "03" || $data->igv == "0.00") {
            $Estado = "0";
        } else if (Carbon::createFromFormat('Y-m-d', $data->date)->format('n') == $this->month) {
            $Estado = "1";
        } else {
            $ts1 = strtotime($data->date);
            $ts2 = strtotime($data->payment_date);
            if ($data->date < $data->payment_date) {
                $year1 = date('Y', $ts1);
                $year2 = date('Y', $ts2);

                $month1 = date('m', $ts1);
                $month2 = date('m', $ts2);

                $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
                if ($diff <= 12) {
                    $Estado = "6";
                } else {
                    $Estado = "7";
                }
            }
        }
        if ($this->type == 1 || $this->type == 3) {
            // $taxable_basis = ($data->taxable_basis > "0.00") ? $data->taxable_basis : ( ($data->type_new == '03') ? $data-> amount : $data->taxable_basis);
            $taxable_basis = ($data->type_new == '03' || $data->igv == "0.00" ) ? $data->amount : ( $data->taxable_basis + $data->untaxed_basis);
            return [
                ++$this->row,
                $data->siaf,
                ucfirst(mb_strtolower($data->NombreTipo, 'UTF-8')),
                $data->date,
                $data->type_new,
                $data->serie,
                $data->number,
                '06', //tipo doc ruc
                $data->ruc,
                $data->business_name,
                $taxable_basis, //$data->taxable_basis,
                $data->impbp,
                $data->igv,
                $data->other_concepts,
                $data->amount,
                $data->doc_code . '-' . $data->num_doc . '-0', //verificar el 0
                $data->payment_date,
                $Estado, //'6', //estado siempre 6
                $data->doc_modify_date_of_issue,
                $data->doc_modify_type,
                $data->doc_modify_serie,
                $data->doc_modify_number,
            ];
        } elseif ($this->type == 2) {
            return [
                ++$this->row,
                $data->siaf,
                'Recibo por honorarios', //falta join
                $data->date,
                $data->type_new,
                $data->serie,
                $data->number,
                '06', //tipo doc ruc
                $data->ruc,
                $data->last_name . ' ' . $data->mother_last_name . ' ' . $data->name,
                $data->total_honorary,
                $data->retention * -1,
                $data->net_honorary,
                $data->doc_code . '-' . $data->num_doc . '-0', //verificar el 0
                $data->payment_date,
                $Estado, //'6', //estado siempre 6
                $data->doc_modify_date_of_issue,
                $data->doc_modify_type,
                $data->doc_modify_serie,
                $data->doc_modify_number,
            ];
        }
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function headings(): array
    {
        if ($this->type == 1 || $this->type == 3) {
            return $this->headings_ple;
        } elseif ($this->type == 2) {
            return $this->headings_plame;
        }
    }

    public function title(): string
    {
        return 'Compras';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                if ($this->type == 1 || $this->type == 3) {
                    $cellRangeTitle = 'A1:U1';
                    $cellRangeHeaders = 'A2:U2';
                    $event->sheet->setCellValue('A1', 'COMPRAS');
                    $event->sheet->mergeCells($cellRangeTitle);
                    $event->sheet->mergeCells('E2:G2');
                    $event->sheet->mergeCells('S2:V2');
                } elseif ($this->type == 2) {
                    $cellRangeTitle = 'A1:S1';
                    $cellRangeHeaders = 'A2:S2';
                    $event->sheet->setCellValue('A1', 'COMPRAS');
                    $event->sheet->mergeCells($cellRangeTitle);
                    $event->sheet->mergeCells('E2:G2');
                    $event->sheet->mergeCells('Q2:S2');
                }
                $event->sheet->getDelegate()->getStyle($cellRangeTitle)->getFont()->setSize(14)->setBold(true);
                $event->sheet->getDelegate()->getStyle($cellRangeTitle)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle($cellRangeHeaders)->getFont()->setSize(12)->setBold(true);
            },
        ];
    }
}
