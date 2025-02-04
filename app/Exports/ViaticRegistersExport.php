<?php

namespace App\Exports;

use App\Models\ViaticRegister;
use Maatwebsite\Excel\Excel;
use App\Models\ViaticRegisterDetail;
use App\Models\Parameter;
use App\Models\Settlement;
use App\Models\SettlementClassifier;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Carbon\Carbon;

class ViaticRegistersExport implements FromCollection, WithEvents, WithMapping, WithCustomStartCell
{
  private $viatic_register_id;
  private $type_report;

  public function __construct(int $viatic_register_id, int $type_report)
  {
    $this->viatic_register_id = $viatic_register_id;
    $this->type_report = $type_report;
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    // return ViaticRegisterDetail::all();
    // return ViaticRegister::all();
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
        if($this->type_report==1){
          $templateFile = new LocalTemporaryFile(storage_path('app/public/templates/xlsx/1_Reportes_Viaticos.xlsx'));
          // $path = storage_path() . '/' . 'app' . '/templates/xlsx/1_Reportes_Caja_Chica.xlsx';

          $event->writer->reopen($templateFile, Excel::XLSX);
          $sheet1 = $event->writer->getSheetByIndex(0);
          $sheet2 = $event->writer->getSheetByIndex(1);

          $this->HojaRendicionViaticos($sheet1);
          $this->HojaRencidionPPtal($sheet2);

          // $this->calledByEvent = true; // set the flag
          $event->writer->getSheetByIndex(0)->export($event->getConcernable()); // call the export on the first sheet

          return $event->getWriter()->getSheetByIndex(0);
        }elseif($this->type_report==2){
          $templateFile = new LocalTemporaryFile(storage_path('app/public/templates/xlsx/1_Informe_Viaticos.xlsx'));

          $event->writer->reopen($templateFile, Excel::XLSX);
          $sheet1 = $event->writer->getSheetByIndex(0);
          $sheet2 = $event->writer->getSheetByIndex(1);
          $sheet3 = $event->writer->getSheetByIndex(2);

          $this->HojaInformeFinalViaticos($sheet1);
          $this->HojaInformeFinalViaticos_informe_comision($sheet2);
          $this->HojaInformeFinalViaticos_dj($sheet3);

          // $this->calledByEvent = true; // set the flag
          $event->writer->getSheetByIndex(0)->export($event->getConcernable()); // call the export on the first sheet

          return $event->getWriter()->getSheetByIndex(0);
        }

      },
    ];
  }

  public function startCell(): string
  {
    return 'A12';
  }

  private function HojaRendicionViaticos($sheet)
  {
    $header = ViaticRegister::where('id', $this->viatic_register_id)->with("settlement")->with(["user", "user.person.office"])->first();

    //CABECERA
    $fecha = Carbon::parse($header->authorization_date);
    $mfecha = $fecha->month;
    $dfecha = $fecha->day;
    $afecha = $fecha->year;

    $fechaActual = Carbon::now();
    $anioActual = $fechaActual->year;

    $correlative = substr(str_repeat(0, 4) . $header->number, -4);

    $sheet->setCellValue('R3', 'PV-' . $correlative . '-' . $anioActual);
    $sheet->setCellValue('R6', $dfecha);
    $sheet->setCellValue('T6', $mfecha);
    $sheet->setCellValue('V6', $afecha);
    $sheet->setCellValue('C8', $header->user->person->name . ' ' . $header->user->person->lastname);
    $sheet->setCellValue('N8', $header->user->person->office[0]->name);

    if($header->settlement->viatic_type == 1){
      $sheet->setCellValue('M11', 'X');
    }else if($header->settlement->viatic_type == 2){
      $sheet->setCellValue('U11', 'X');

    }

    $sheet->setCellValue('H13', $header->number);
    $sheet->setCellValue('R13', $header->settlement->destination);

    if($header->settlement->means_of_transport == '1'){
      $sheet->setCellValue('K15', ' X');
    }elseif ($header->means_of_transport == '2'){
        $sheet->setCellValue('P15','X');
    }elseif ($header->means_of_transport == '3'){
        $sheet->setCellValue('W15','X');
    } 


    $sheet->setCellValue('G17', $header->settlement->number_days);
    $sheet->setCellValue('L17', $header->settlement->departure_date);
    $sheet->setCellValue('S17', $header->settlement->return_date);

    $details_tickets_and_transportation_expenses = ViaticRegisterDetail::where('viatic_register_id', $this->viatic_register_id)->where('classifier_code', '23 2 1 1 1')->get();
    //DETALLES
    $index = 1;
    $startRow = 21;
    // 23 2 1 1 1 -- classifer pasajes y gastos de transportes max 4
    foreach ($details_tickets_and_transportation_expenses as $key => $value) {
      $sheet->setCellValue('C' . $startRow, $index);
      $sheet->setCellValue('D' . $startRow, $value->issue_date);
      $issue_description = Parameter::where('nParClase', '1006')->where('cParValor', $value->issue_type)->first();
      $sheet->setCellValue('F' . $startRow, $issue_description->cParNombre);
      $sheet->setCellValue('H' . $startRow, $value->issue_serie . '-' . $value->issue_number);
      $sheet->setCellValue('L' . $startRow, $value->supplier_name);
      $sheet->setCellValue('S' . $startRow, $value->total);
      $startRow++;
      $index++;
    }

    // 23 2 1 1 2 -- viaticos y asignaciones
    $details_viatic = ViaticRegisterDetail::where('viatic_register_id', $this->viatic_register_id)->where('classifier_code', '23 2 1 1 2')->get();
    $startRow = 26;
    foreach ($details_viatic as $key => $value) {
      $sheet->setCellValue('C' . $startRow, $index);
      $sheet->setCellValue('D' . $startRow, $value->issue_date);
      $issue_description = Parameter::where('nParClase', '1006')->where('cParValor', $value->issue_type)->first();
      $sheet->setCellValue('F' . $startRow, $issue_description->cParNombre);
      $sheet->setCellValue('H' . $startRow, $value->issue_serie . '-' . $value->issue_number);
      $sheet->setCellValue('L' . $startRow, $value->supplier_name);
      $sheet->setCellValue('S' . $startRow, $value->total);
      $startRow++;
      $index++;
    }

    //FOOTER

    $sheet->setCellValue('T37', $header->affidavit_amount_undocumented_expenses);
    $sheet->setCellValue('T41', $header->affidavit_amount_lost_documents);
    $sheet->setCellValue('T46', $header->settlement->approved_amount);

  }

  private function HojaRencidionPPtal($sheet)
  {
    $header = ViaticRegister::where('id', $this->viatic_register_id)->with("settlement")->with(["user", "user.person"])->first();
    $details = ViaticRegisterDetail::where('viatic_register_id', $this->viatic_register_id)
                                    ->join('offices', 'viatic_register_details.cost_center_code', '=', 'offices.code_ue')
                                    ->select('viatic_register_details.*', DB::raw("CONCAT(offices.code_ue, ' - ', offices.name) AS code_ue_name"))
                                    ->get();

    //TITULO
    $sheet->setCellValue('A1', "REPORTE DE VIATICOS N° " . $header->number . "-" . date('Y',strtotime($header->opening_date)));

    //CABECER
    $sheet->setCellValue('E4', $header->number);
    $sheet->setCellValue('D6', $header->opening_date);
    // $sheet->setCellValue('D8', $header->opening_date); // memorando pendiente
    $sheet->setCellValue('H4', $header->user->person->name . ' ' . $header->user->person->lastname . ' (' . $header->user->person->document_number . ')');
    $sheet->setCellValue('H6', $header->surrender_report);
    $sheet->setCellValue('H8', $header->order_pay_electronic_date);
    $sheet->setCellValue('L4', $header->closing_date);
    $sheet->setCellValue('L6', $header->siaf_number);
    $sheet->setCellValue('L8', $header->voucher_number);
    $sheet->setCellValue('Q4', $header->approved_amount);
    $sheet->setCellValue('Q6', $header->amount_to_pay);
    $sheet->setCellValue('Q8', $header->amount_to_returned);

    //DETALLES
    $index = 1;
    $startRow = 12;
    foreach ($details as $key => $value) {
      $sheet->setCellValue('A' . $startRow, $index);
      $sheet->setCellValue('B' . $startRow, $value->issue_date);
      $sheet->setCellValue('C' . $startRow, $value->issue_type);
      $sheet->setCellValue('D' . $startRow, $value->issue_serie);
      $sheet->setCellValue('E' . $startRow, $value->issue_number);
      $sheet->setCellValue('F' . $startRow, $value->supplier_number);
      $sheet->setCellValue('G' . $startRow, $value->supplier_name);
      $sheet->setCellValue('H' . $startRow, $value->expense_description);
      $sheet->setCellValue('I' . $startRow, $value->code_ue_name);
      $sheet->setCellValue('J' . $startRow, $value->cost_center_code);
      $sheet->setCellValue('K' . $startRow, $value->classifier_code);
      $sheet->setCellValue('L' . $startRow, $value->taxed_base);
      $sheet->setCellValue('M' . $startRow, $value->igv);
      $sheet->setCellValue('N' . $startRow, $value->untaxed_base);
      $sheet->setCellValue('O' . $startRow, $value->impbp);
      $sheet->setCellValue('P' . $startRow, $value->other_concepts);
      $sheet->setCellValue('Q' . $startRow, $value->total);
      $startRow++;
      $index++;
    }
  }

  private function HojaInformeFinalViaticos($sheet)
  {
    $month_array = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    $header = ViaticRegister::where('id', $this->viatic_register_id)->with("settlement")->with(["user", "user.person.office"])->first();

    //CABECERA
    $fechaActual = Carbon::now();
    $mfecha = $fechaActual->month;
    $dfecha = $fechaActual->day;
    $afecha = $fechaActual->year;

    $correlative = substr(str_repeat(0, 4) . $header->number, -4);

    $sheet->setCellValue('E3', "INFORME N° {$header->settlement->authorization_detail}");
    $sheet->setCellValue('E6', $header->service_commission_a);
    $sheet->setCellValue('E8', $header->service_commission_from);
    $sheet->setCellValue('E10', "Rendición de Encargo N° {$correlative}-{$header->year} por S/ {$header->settlement->approved_amount}");
    
    // Memorando N° 008-2022-SATP de fecha 18.07.2022
    // Expediente SIAF 1248-2022 de fecha 19.07.2022
		// Comprobante de Pago N° 00000125 de fecha 19.07.2022
    // Orden de Pago Electrónica de fecha 20.07.2022
    $siaf_number = substr(str_repeat(0, 4) . $header->siaf_number, -4);
    $voucher_number = substr(str_repeat(0, 8) . $header->voucher_number, -8);
    $authorization_date = Carbon::createFromFormat('Y-m-d', $header->settlement->authorization_date)->format('d.m.Y');
    $siaf_date = Carbon::createFromFormat('Y-m-d', $header->siaf_date); 
    $voucher_date = Carbon::createFromFormat('Y-m-d', $header->voucher_date)->format('d.m.Y');
    $order_pay_electronic_date = Carbon::createFromFormat('Y-m-d', $header->order_pay_electronic_date)->format('d.m.Y');
    $ref_memo = "Memorando N° {$header->settlement->authorization_detail} de fecha {$authorization_date} ";
    $ref_siaf = "Expediente SIAF {$siaf_number}-{$siaf_date->format('Y')} de fecha {$siaf_date->format('d.m.Y')}";
    $ref_payment = "Comprobante de Pago N° {$voucher_number} de fecha {$voucher_date}";
    $ref_pay_electronic = "Orden de Pago Electrónica de fecha {$order_pay_electronic_date}";
    $sheet->setCellValue('E12', "{$ref_memo}\n{$ref_siaf}\n{$ref_payment}\n{$ref_pay_electronic}");
    //Piura, 10 de Agosto 2022
    $sheet->setCellValue('E14', "Piura, {$dfecha} de {$month_array[$mfecha - 1]} {$afecha}");
    $sheet->setCellValue('B20', "INFORME N° {$header->settlement->reason}");

    // 23 2 1 1 1 -- classifer pasajes y gastos de transportes
    $details_tickets_and_transportation_expenses = ViaticRegisterDetail::where('viatic_register_id', $this->viatic_register_id)->where('classifier_code', '23 2 1 1 1')->get();
    $sheet->setCellValue('I25', "{$details_tickets_and_transportation_expenses->sum('total')}");

    // 23 2 1 1 2 -- viaticos y asignaciones
    $details_viatic = ViaticRegisterDetail::where('viatic_register_id', $this->viatic_register_id)->where('classifier_code', '23 2 1 1 2')->get();
    $sheet->setCellValue('I26', "{$details_viatic->sum('total')}");

    // 23 2 1 1 1 -- classifer pasajes y gastos de transportes - settlement
    $details_tickets_and_transportation_expenses_settlement = SettlementClassifier::where('settlement_id', $header->settlement_id)->where('code_classify', '23 2 1 1 1')->get();
    $details_tickets_and_transportation_expenses_settlement_sum = $details_tickets_and_transportation_expenses_settlement->sum('goal_one')+ $details_tickets_and_transportation_expenses_settlement->sum('goal_two') + $details_tickets_and_transportation_expenses_settlement->sum('goal_three');
    $result_1 = $details_tickets_and_transportation_expenses_settlement_sum - $details_tickets_and_transportation_expenses->sum('total');
    $sheet->setCellValue('I34', "{$result_1}");

    // 23 2 1 1 2 -- viaticos y asignaciones
    $details_viatic_settlement = SettlementClassifier::where('settlement_id', $header->settlement_id)->where('code_classify', '23 2 1 1 2')->get();
    $details_viatic_settlement_sum = $details_viatic_settlement->sum('goal_one') + $details_viatic_settlement->sum('goal_two') + $details_viatic_settlement->sum('goal_three');
    $result_2 = $details_viatic_settlement_sum - $details_viatic->sum('total');
    $sheet->setCellValue('I35', "{$result_2}");

  }

  private function HojaInformeFinalViaticos_informe_comision($sheet)
  {
    $month_array = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    $header = ViaticRegister::where('id', $this->viatic_register_id)->with("settlement")->with(["user", "user.person.office"])->first();

    //CABECERA
    $fechaActual = Carbon::now();
    $mfecha = $fechaActual->month;
    $dfecha = $fechaActual->day;
    $afecha = $fechaActual->year;

    $correlative = substr(str_repeat(0, 4) . $header->number, -4);

    $sheet->setCellValue('E3', "INFORME N° {$header->settlement->authorization_detail}");
    $sheet->setCellValue('E6', $header->service_commission_a);
    $sheet->setCellValue('E8', $header->service_commission_from);
    //Piura, 10 de Agosto 2022
    $sheet->setCellValue('E10', "Piura, {$dfecha} de {$month_array[$mfecha - 1]} {$afecha}");

    $sheet->setCellValue('B14', "{$header->service_commission_activities_performed}");
    $sheet->setCellValue('B19', "{$header->service_commission_results_obtained}");

  }

  private function HojaInformeFinalViaticos_dj($sheet)
  {
    $header = ViaticRegister::where('id', $this->viatic_register_id)->with("settlement")->with(["user", "user.person.office"])->first();

    $sheet->setCellValue('B8', "{$header->affidavit_description_lost_documents}");
    $sheet->setCellValue('J8', "{$header->affidavit_amount_lost_documents}");
    $sheet->setCellValue('J12', "{$header->affidavit_amount_undocumented_expenses}");

  }
}
