<?php

namespace App\Exports;

use App\Models\Settlement;
use App\Models\Office;
use Maatwebsite\Excel\Excel;
use App\Models\SettlementClassifier;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Carbon\Carbon;

class SettlementOrderExport implements FromCollection, WithEvents, WithMapping, WithCustomStartCell
{
  private $settlement_id;

  public function __construct(int $settlement_id)
  {
    $this->settlement_id = $settlement_id;
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    // return SettlementOrderDetail::all();
    // return SettlementOrder::all();
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
        $templateFile = new LocalTemporaryFile(storage_path('app/public/templates/xlsx/1_Liquidacion_Encargo.xlsx'));
        $event->writer->reopen($templateFile, Excel::XLSX);
        $sheet1 = $event->writer->getSheetByIndex(0);

        $this->HojaLiquidacionEncargo($sheet1);

        // $this->calledByEvent = true; // set the flag
        $event->writer->getSheetByIndex(0)->export($event->getConcernable()); // call the export on the first sheet

        return $event->getWriter()->getSheetByIndex(0);
      },
    ];
  }

  public function startCell(): string
  {
    return 'A23';
  }

  private function HojaLiquidacionEncargo($sheet)
  {
    $header = Settlement::where('id', $this->settlement_id)->with(["requestFile"])->with(["person", "person.office"])->first();
    $details = SettlementClassifier::where('settlement_id', $this->settlement_id)->get();

    //CABECERA
    $fecha = Carbon::parse($header->authorization_date);
    $mfecha = $fecha->month;
    $dfecha = $fecha->day;
    $afecha = $fecha->year;

    $fechaActual = Carbon::now();
    $anioActual = $fechaActual->year;

    $correlative = substr(str_repeat(0, 4) . $header->number_correlative, -4);

    $sheet->setCellValue('H3', 'E-' . $correlative . '-' . $anioActual);
    $sheet->setCellValue('H6', $dfecha);
    $sheet->setCellValue('I6', $mfecha);
    $sheet->setCellValue('J6', $afecha);
    $sheet->setCellValue('A9', $header->person->name . ' ' . $header->person->lastname);
    $sheet->setCellValue('A12', $header->requestFile->reference_document);
    $sheet->setCellValue('C9', $header->person->office[0]->name);
    $sheet->setCellValue('H9', $header->person->document_number);
    $sheet->setCellValue('D15', $header->request_amount);
    $sheet->setCellValue('C12', $header->authorization_detail);
    $sheet->setCellValue('F12', $header->budget_certificate);
    $sheet->setCellValue('H12', $header->approved_amount);
    $sheet->setCellValue('A16', $header->reason);
    $sheet->setCellValue('A19', $header->requestFile->justification);

    //DETALLES
    $index = 1;
    $startRow = 23;
    foreach ($details as $key => $value) {
      $sheet->setCellValue('A' . $startRow, $value->code_classify);
      $sheet->setCellValue('B' . $startRow, $value->name_classify);
      $sheet->setCellValue('C' . $startRow, $value->issue_type);
      $sheet->setCellValue('D' . $startRow, $value->goal_one);
      $sheet->setCellValue('E' . $startRow, $value->goal_two);
      $sheet->setCellValue('F' . $startRow, $value->goal_three);
      $sheet->setCellValue('G' . $startRow, $value->goal_one + $value->goal_two + $value->goal_three);
      $startRow++;
      $index++;
    }
  }
}
