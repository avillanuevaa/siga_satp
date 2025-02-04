<?php

namespace App\Exports;

use App\Models\OrderRegister;
use Maatwebsite\Excel\Excel;
use App\Models\OrderRegisterDetail;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class OrderRegistersExport implements FromCollection, WithEvents, WithMapping, WithCustomStartCell
{
  private $order_register_id;

  public function __construct(int $order_register_id)
  {
    $this->order_register_id = $order_register_id;
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    // return OrderRegisterDetail::all();
    // return OrderRegister::all();
    return collect([]);
  }

  public function map($data): array
  {
    return [
      $data->issue_date,
    ];
  }

  public function registerEvents(): array
  {
    return [
      BeforeWriting::class => function (BeforeWriting $event) {
        // $path = storage_path() . '/' . 'app' . '/templates/xlsx/1_Reportes_Caja_Chica.xlsx';
        $templateFile = new LocalTemporaryFile(storage_path('app/public/templates/xlsx/1_Reportes_Encargos.xlsx'));
        $event->writer->reopen($templateFile, Excel::XLSX);
        $sheet1 = $event->writer->getSheetByIndex(0);
        $sheet2 = $event->writer->getSheetByIndex(1);

        $this->HojaEncargoDetalle($sheet1);
        $this->HojaEncargoBienes($sheet2);

        // $this->calledByEvent = true; // set the flag
        $event->writer->getSheetByIndex(0)->export($event->getConcernable()); // call the export on the first sheet

        return $event->getWriter()->getSheetByIndex(0);
      },
    ];
  }

  public function startCell(): string
  {
    return 'A12';
  }

  private function HojaEncargoDetalle($sheet)
  {
    $header = OrderRegister::where('id', $this->order_register_id)->with(["user", "user.person"])->first();
    $details = OrderRegisterDetail::where('order_register_id', $this->order_register_id)->get();

    //TITULO
    $sheet->setCellValue('A1', "REPORTE DE ENCARGOS N° " . $header->number . "-" . date('Y',strtotime($header->opening_date)));

    //CABECERA
    $sheet->setCellValue('E4', $header->number);
    $sheet->setCellValue('D6', $header->opening_date);
    $sheet->setCellValue('H4', $header->user->person->name . ' ' . $header->user->person->lastname.' ('.$header->user->person->document_number.')');
    $sheet->setCellValue('H6', $header->surrender_report);
    $sheet->setCellValue('H8', $header->order_pay_electronic_date);
    $sheet->setCellValue('N4', $header->closing_date);        
    $sheet->setCellValue('S4', $header->approved_amount);
    $sheet->setCellValue('S6', $header->amount_to_pay);
    $sheet->setCellValue('S8', $header->amount_to_returned);
    $sheet->setCellValue('N6', $header->siaf_number);
    $sheet->setCellValue('N8', $header->voucher_number);

    //DETALLES
    $index = 1;
    $startRow = 12;
    foreach ($details as $key => $value) {
      $sheet->setCellValue('A'.$startRow, $index);
      $sheet->setCellValue('B'.$startRow, $value->issue_date);
      $sheet->setCellValue('C'.$startRow, $value->issue_type);
      $sheet->setCellValue('D'.$startRow, $value->issue_serie);
      $sheet->setCellValue('E'.$startRow, $value->issue_number);
      $sheet->setCellValue('F'.$startRow, $value->supplier_number);
      $sheet->setCellValue('G'.$startRow, $value->supplier_name);
      $sheet->setCellValue('H'.$startRow, $value->expense_description);
      #$sheet->setCellValue('I'.$startRow, $value->cost_center_description);
      $sheet->setCellValue('J'.$startRow, $value->goal_description);
      $sheet->setCellValue('K'.$startRow, $value->classifier_code);
      if($value->enter_to_warehouse=='0'){
          $sheet->setCellValue('L'.$startRow,'NO');
      }elseif($value->enter_to_warehouse=='1'){
          $sheet->setCellValue('L'.$startRow,'SI');
      }
      $sheet->setCellValue('M'.$startRow, '');
      $sheet->setCellValue('N'.$startRow, $value->taxed_base);
      $sheet->setCellValue('O'.$startRow, $value->igv);
      $sheet->setCellValue('P'.$startRow, $value->untaxed_base);
      $sheet->setCellValue('Q'.$startRow, $value->impbp);
      $sheet->setCellValue('R'.$startRow, $value->other_concepts);
      $sheet->setCellValue('S'.$startRow, $value->total);

      $startRow++;
      $index++;
    }
  }

  private function HojaEncargoBienes($sheet)
  {
    $header = OrderRegister::where('id', $this->order_register_id)->with(["user", "user.person"])->first();
    $details = DB::table('order_register_detail_warehouses AS T1')
      ->select('T1.*', 'T2.*')
      ->join('order_register_details AS T2', 'T1.order_register_detail_id', '=', 'T2.id')
      ->where('T2.order_register_id', $this->order_register_id)
      ->get();

    //CABECER
    $sheet->setCellValue('D4', $header->number);
    $sheet->setCellValue('C6', $header->opening_date);
    $sheet->setCellValue('H4', $header->user->person->name . ' ' . $header->user->person->lastname.' ('.$header->user->person->document_number.')');
    $sheet->setCellValue('H6', $header->surrender_report);
    $sheet->setCellValue('H8', $header->order_pay_electronic_date);
    $sheet->setCellValue('M4', $header->closing_date);        
    $sheet->setCellValue('Q4', $header->approved_amount);
    $sheet->setCellValue('Q6', $header->amount_to_pay);
    $sheet->setCellValue('Q8', $header->amount_to_returned);
    $sheet->setCellValue('M6', $header->siaf_number);
    $sheet->setCellValue('M8', $header->voucher_number);

    //DETALLES
    $index = 1;
    $startRow = 12;
    foreach ($details as $key => $value) {
      $sheet->setCellValue('A'.$startRow, $index);
      $sheet->setCellValue('B'.$startRow, $value->package);
      $sheet->setCellValue('C'.$startRow, $value->issue_date);
      $sheet->setCellValue('D'.$startRow, $value->issue_type);
      $sheet->setCellValue('E'.$startRow, $value->issue_serie);
      $sheet->setCellValue('F'.$startRow, $value->issue_number);
      $sheet->setCellValue('G'.$startRow, $value->supplier_number);
      $sheet->setCellValue('H'.$startRow, $value->supplier_name);
      $sheet->setCellValue('I'.$startRow, $value->observation);
      #$sheet->setCellValue('J'.$startRow, $value->cost_center_description);
      $sheet->setCellValue('K'.$startRow, $value->goal_description);
      $sheet->setCellValue('L'.$startRow, $value->classifier_code);
      if($value->lesser_package=='0'){
          $sheet->setCellValue('M'.$startRow, 'NO');
      }
      elseif($value->lesser_package=='1'){
          $sheet->setCellValue('M'.$startRow, 'SI');
      }
      $sheet->setCellValue('N'.$startRow, $value->measure);
      $sheet->setCellValue('O'.$startRow, $value->quantity);
      $sheet->setCellValue('P'.$startRow, $value->unit_value);
      $sheet->setCellValue('Q'.$startRow,$value->quantity*$value->unit_value);
      $startRow++;
      $index++;
    }
  }

}
