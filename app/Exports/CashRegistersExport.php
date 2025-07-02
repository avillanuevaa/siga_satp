<?php
namespace App\Exports;
use App\Models\CashRegister;
use Maatwebsite\Excel\Excel;
use App\Models\CashRegisterDetail;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Style\Border;
class CashRegistersExport implements FromCollection, WithEvents, WithMapping, WithCustomStartCell
{
    private $cash_register_id;
    public function __construct(int $cash_register_id)
    {
        $this->cash_register_id = $cash_register_id;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect([]);
    }
    public function map($data): array
    {
        return [
            $data->issue_date,
        ];
    }
    public function startCell(): string
    {
        return 'A12';
    }
    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function (BeforeWriting $event) {
                $templateFile = new LocalTemporaryFile(
                    storage_path('app/public/templates/xlsx/1_Reportes_Caja_Chica.xlsx')
                );
                $event->writer->reopen($templateFile, Excel::XLSX);
                $sheet1 = $event->writer->getSheetByIndex(0);
                $this->HojaCajaChicaDetalle($sheet1);
                foreach (range('A', 'S') as $col) {
                    $sheet1->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }
                $sheet1->getDelegate()->getColumnDimension('G')->setAutoSize(false);
                $sheet1->getDelegate()->getColumnDimension('G')->setWidth(50);
                $sheet1->getDelegate()->getColumnDimension('H')->setAutoSize(false);
                $sheet1->getDelegate()->getColumnDimension('H')->setWidth(150);
                $sheet1->getDelegate()->getColumnDimension('A')->setAutoSize(false);
                $sheet1->getDelegate()->getColumnDimension('A')->setWidth(10);
                $highestRow = $sheet1->getDelegate()->getHighestRow();
                for ($row = 1; $row <= $highestRow; $row++) {
                    $sheet1->getDelegate()->getRowDimension($row)->setRowHeight(-1);
                }
                $sheet1->getDelegate()
                    ->getStyle("A1:S{$highestRow}")
                    ->getAlignment()
                    ->setWrapText(false);
                $sheet2 = $event->writer->getSheetByIndex(1);
                $this->HojaCajaChicaBienes($sheet2);
                foreach (range('A', 'R') as $col) {
                    $sheet2->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }
                // Hoja 3: Totales
                $sheet3 = $event->writer->getSheetByIndex(2);
                $this->HojaCajaChicaTotales($sheet3);
                foreach (range('A', 'H') as $col) {
                    $sheet3->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }
                $event->writer->getSheetByIndex(0)->export($event->getConcernable());
                return $event->getWriter()->getSheetByIndex(0);
            },
        ];
    }
    private function HojaCajaChicaDetalle($sheet)
    {
        $header = CashRegister::with(['user.person'])->find($this->cash_register_id);
        $details = CashRegisterDetail::where('cash_register_id', $this->cash_register_id)
            ->join('offices', 'cash_register_details.cost_center_code', '=', 'offices.code_ue')
            ->select('cash_register_details.*', DB::raw("CONCAT(offices.code_ue, ' - ', offices.name) AS code_ue_name"))
            ->orderByRaw("cash_register_details.issue_type, CASE WHEN cash_register_details.issue_type = '89' THEN cash_register_details.issue_number ELSE cash_register_details.issue_date END, cash_register_details.id")
            ->get();
        // Título y cabecera
        $sheet->setCellValue('A1', "REPORTE DE CAJA CHICA N° {$header->number}-" . date('Y', strtotime($header->opening_date)));
        $sheet->setCellValue('E6', $header->number);
        $sheet->setCellValue('E8', $header->opening_date);
        $sheet->setCellValue('H6', $header->user->person->name . ' ' . $header->user->person->lastname);
        $sheet->setCellValue('H8', $header->surrender_report);
        $sheet->setCellValue('L6', $header->closing_date);
        $sheet->setCellValue('R6', $header->amount_to_pay);
        // Detalles
        $index    = 1;
        $startRow = 12;
        $sumTotal = 0;
        $dataEndRow = $startRow - 1; // Para almacenar la última fila de datos

        foreach ($details as $value) {
            $sheet->setCellValue("A{$startRow}", $index);
            $sheet->setCellValue("B{$startRow}", $value->issue_date);
            $sheet->setCellValue("C{$startRow}", $value->issue_type);
            $sheet->setCellValue("D{$startRow}", $value->issue_serie);
            $sheet->setCellValue("E{$startRow}", $value->issue_number);
            $sheet->setCellValue("F{$startRow}", $value->supplier_number);
            $sheet->setCellValue("G{$startRow}", $value->supplier_name);
            $sheet->setCellValue("H{$startRow}", $value->expense_description);
            $sheet->setCellValue("I{$startRow}", $value->code_ue_name);
            $sheet->setCellValue("J{$startRow}", $value->goal_description);
            $sheet->setCellValue("K{$startRow}", $value->classifier_descripcion);
            $sheet->setCellValue("L{$startRow}", '');
            $sheet->setCellValue("M{$startRow}", '');
            $sheet->setCellValue("N{$startRow}", $value->taxed_base);
            $sheet->setCellValue("O{$startRow}", $value->igv);
            $sheet->setCellValue("P{$startRow}", $value->untaxed_base);
            $sheet->setCellValue("Q{$startRow}", $value->impbp);
            $sheet->setCellValue("R{$startRow}", $value->other_concepts);
            $sheet->setCellValue("S{$startRow}", $value->total);
            $sumTotal += $value->total;
            $dataEndRow = $startRow; // Actualizar la última fila de datos
            $startRow++;
            $index++;
        }

        // Aplicar bordes solo al rango de datos (sin incluir la fila del total)
        if ($dataEndRow >= 12) {
            $styleBorder = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ];
            $sheet->getDelegate()->getStyle("A12:S{$dataEndRow}")->applyFromArray($styleBorder);
        }

        // Saltar una fila vacía para separar los datos del total
        $startRow++;

        // Agregar la fila del total separada con fórmula SUBTOTAL
        $sheet->getDelegate()->mergeCells("A{$startRow}:R{$startRow}");
        $sheet->setCellValue("A{$startRow}", 'TOTAL');
        // Usar SUBTOTAL(109, rango) para sumar solo las celdas visibles (función 109 = SUMA con filtros)
        if ($dataEndRow >= 12) {
            $sheet->setCellValue("S{$startRow}", "=SUBTOTAL(109,S12:S{$dataEndRow})");
        } else {
            $sheet->setCellValue("S{$startRow}", 0);
        }

        // Aplicar estilo a la fila del total
        $styleRange = "A{$startRow}:S{$startRow}";
        $sheet->getDelegate()->getStyle($styleRange)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFFF00');
        $sheet->getDelegate()->getStyle($styleRange)->getFont()->setBold(true);

        // Aplicar bordes a la fila del total
        $sheet->getDelegate()->getStyle($styleRange)->applyFromArray($styleBorder);
    }
    private function HojaCajaChicaBienes($sheet)
    {
        $header = CashRegister::where('id', $this->cash_register_id)->with(["user","user.person"])->first();
        $details = DB::table('cash_register_detail_warehouses AS T1')
            ->select('T1.*', 'T2.*')
            ->join('cash_register_details AS T2', 'T1.cash_register_detail_id', '=', 'T2.id')
            ->where('T2.cash_register_id', $this->cash_register_id)
            ->orderByRaw("T2.issue_type, CASE WHEN T2.issue_type = '89' THEN T2.issue_number ELSE T2.issue_date END")
            ->get();
        //CABECER
        $sheet->setCellValue('D4', $header->number);
        $sheet->setCellValue('D6', $header->opening_date);
        $sheet->setCellValue('I4', $header->user->person->name . ' ' . $header->user->person->lastname);
        $sheet->setCellValue('I6', $header->surrender_report);
        $sheet->setCellValue('M4', $header->closing_date);
        $sheet->setCellValue('M6', $header->amount_to_pay);
        //DETALLES
        $index = 1;
        $startRow = 10;
        foreach ($details as $key => $value) {
            $sheet->setCellValue('A'.$startRow, $index);
            $sheet->setCellValue('B'.$startRow, $value->package);
            $sheet->setCellValue('C'.$startRow, $value->issue_date);
            $sheet->setCellValue('D'.$startRow, $value->issue_type);
            $sheet->setCellValue('E'.$startRow, $value->issue_serie);
            $sheet->setCellValue('F'.$startRow, $value->issue_number);
            $sheet->setCellValue('G'.$startRow, $value->supplier_number);
            $sheet->setCellValue('H'.$startRow, $value->supplier_name);
            $sheet->setCellValue('I'.$startRow, $value->expense_description);
            $sheet->setCellValue('J'.$startRow, $value->cost_center_description);
            $sheet->setCellValue('K'.$startRow, $value->goal_description);
            $sheet->setCellValue('L'.$startRow, $value->classifier_descripcion);
            $sheet->setCellValue('M'.$startRow, $value->detail);
            $sheet->setCellValue('N'.$startRow, $value->measure);
            $sheet->setCellValue('O'.$startRow, $value->quantity);
            $sheet->setCellValue('P'.$startRow, $value->unit_value);
            $sheet->setCellValue('Q'.$startRow, $value->total);
            $sheet->setCellValue('R'.$startRow, $value->observation ?? '');
            $startRow++;
            $index++;
        }
    }
    private function HojaCajaChicaTotales($sheet)
    {
        $details = CashRegisterDetail::where('cash_register_id', $this->cash_register_id)->get();
        $document_types = CashRegisterDetail::select(
            "issue_type" ,
            DB::raw("(sum(taxed_base)) as taxed_base"),
            DB::raw("(sum(igv)) as igv"),
            DB::raw("(sum(untaxed_base)) as untaxed_base"),
            DB::raw("(sum(impbp)) as impbp"),
            DB::raw("(sum(other_concepts)) as other_concepts"),
            DB::raw("(sum(total)) as total")
        )
            ->orderBy('issue_type')
            ->where('cash_register_id', $this->cash_register_id)
            ->groupBy(DB::raw("issue_type"))
            ->get();
        //DETALLES
        $total_taxed_base = 0;
        $total_igv = 0;
        $total_untaxed_base = 0;
        $total_impbp = 0;
        $total_other = 0;
        $total_total = 0;
        foreach ($details as $key => $value) {
            $total_taxed_base += $value->taxed_base;
            $total_igv += $value->igv;
            $total_untaxed_base += $value->untaxed_base;
            $total_impbp += $value->impbp;
            $total_other += $value->other_concepts;
            $total_total += $value->total;
        }
        $sheet->setCellValue('C4', $total_taxed_base);
        $sheet->setCellValue('D4', $total_igv);
        $sheet->setCellValue('E4', $total_untaxed_base);
        $sheet->setCellValue('F4', $total_impbp);
        $sheet->setCellValue('G4', $total_other);
        $sheet->setCellValue('H4', $total_total);
        //DETALLES
        $startRow = 8;
        foreach ($document_types as $key => $value) {
            $sheet->setCellValue('B'.$startRow, $value->issue_type);
            $sheet->setCellValue('C'.$startRow, $value->taxed_base);
            $sheet->setCellValue('D'.$startRow, $value->igv);
            $sheet->setCellValue('E'.$startRow, $value->untaxed_base);
            $sheet->setCellValue('F'.$startRow, $value->impbp);
            $sheet->setCellValue('G'.$startRow, $value->other_concepts);
            $sheet->setCellValue('H'.$startRow, $value->total);
            $startRow++;
        }
    }
}
