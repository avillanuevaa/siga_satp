<?php

namespace App\Exports;

use App\Models\Settlement;
use App\Models\Office;
use App\Models\SettlementClassifier;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Carbon\Carbon;

class SettlementViaticExport implements FromCollection, WithEvents, WithMapping
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
    // return SettlementViaticDetail::all();
    // return SettlementViatic::all();
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
        $templateFile = new LocalTemporaryFile(storage_path('app/public/templates/xlsx/1_Liquidacion_Viaticos.xlsx'));
        $event->writer->reopen($templateFile, Excel::XLSX);
        $sheet1 = $event->writer->getSheetByIndex(0);

        $this->HojaLiquidacionViatico($sheet1);

        // $this->calledByEvent = true; // set the flag
        $event->writer->getSheetByIndex(0)->export($event->getConcernable()); // call the export on the first sheet

        return $event->getWriter()->getSheetByIndex(0);
      },
    ];
  }

  private function HojaLiquidacionViatico($sheet)
  {
    $header = Settlement::where('id', $this->settlement_id)->with(["requestFile"])->with(["person", "person.office"])->first();
    $details = SettlementClassifier::where('settlement_id', $this->settlement_id)->get();

    //CABECERA
    $fecha = Carbon::parse($header->request_date);
    $mfecha = $fecha->month;
    $dfecha = $fecha->day;
    $afecha = $fecha->year;
    $fechaActual = Carbon::now();
    $anioActual = $fechaActual->year;

    $correlative = substr(str_repeat(0, 4) . $header->requestFile->number_correlative, -4);

    $sheet->setCellValue('R3', 'PV-' . $correlative . '-' . $anioActual);
    $sheet->setCellValue('R6', $dfecha);
    $sheet->setCellValue('T6', $mfecha);
    $sheet->setCellValue('V6', $afecha);
    $sheet->setCellValue('C8', $header->person->name . ' ' . $header->person->lastname);
    $sheet->setCellValue('N8', $header->person->office[0]->name);
    $sheet->setCellValue('C11', $header->person->document_number);
    $sheet->setCellValue('H11', $header->person->cellphone);


    if ($header->viatic_type == '1') {
      $sheet->setCellValue('M14', ' X');
    } elseif ($header->viatic_type == '2') {
      $sheet->setCellValue('U14', 'X');
    }

    $sheet->setCellValue('H18', $header->reason);
    $sheet->setCellValue('H16', 'PV-' . $correlative . '-' . $anioActual);
    $sheet->setCellValue('H21', $header->requestFile->reference_document);
    $sheet->setCellValue('H23', $header->destination);
    $sheet->setCellValue('U23', $header->number_days);

    if ($header->means_of_transport == '1') {
      $sheet->setCellValue('K25', ' X');
    } elseif ($header->means_of_transport == '2') {
      $sheet->setCellValue('P25', 'X');
    } elseif ($header->means_of_transport == '3') {
      $sheet->setCellValue('W25', 'X');
    }

    $sheet->setCellValue('H27', $header->departure_date);
    $sheet->setCellValue('R27', $header->return_date);

    $sheet->setCellValue('K30', $header->number_days);

    $total_viatic = 0;
    $total_ticket = 0;
    foreach ($details as $key => $value) {
      if ($value->code_classify == '23 2 1 1 2') {
        $total_viatic = $value->goal_one + $value->goal_two + $value->oal_three;
      }
      if ($value->code_classify == '23 2 1 1 1') {
        $total_ticket = $value->goal_one + $value->goal_two + $value->oal_three;
      }
    }
    $sheet->setCellValue('U30', $total_viatic);
    $sheet->setCellValue('U32', $total_ticket);
  }
}
