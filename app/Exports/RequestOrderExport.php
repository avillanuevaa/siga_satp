<?php

namespace App\Exports;

use App\Models\RequestFile;
use App\Models\Office;
use Maatwebsite\Excel\Excel;
use App\Models\RequestFileClassifier;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Carbon\Carbon;

class RequestOrderExport implements FromCollection, WithEvents, WithMapping, WithCustomStartCell
{
    private $request_id;

    public function __construct(int $request_id)
    {
        $this->request_id = $request_id;
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

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function (BeforeWriting $event) {
               
                $templateFile = new LocalTemporaryFile(storage_path('app/public/templates/xlsx/1_Solicitudes_Encargo.xlsx'));
                $event->writer->reopen($templateFile, Excel::XLSX);
                $sheet1 = $event->writer->getSheetByIndex(0);
                
                $this->HojaSolicitudEncargo($sheet1);
                

                $this->calledByEvent = true; // set the flag
                $event->writer->getSheetByIndex(0)->export($event->getConcernable()); // call the export on the first sheet

                return $event->getWriter()->getSheetByIndex(0);
            },
        ];
    }

    public function startCell(): string
    {
        return 'A26';
    }

    private function HojaSolicitudEncargo($sheet)
    {
        $header = RequestFile::where('id', $this->request_id)->with(["person","person.office"])->first();
        $details = RequestFileClassifier::where('request_id', $this->request_id)->get();
        $management = Office::with('person','person.office')->where('id','7')->first();

        if( $header->request_type==1){
            //CABECERA
            $fecha = Carbon::parse($header->request_date);
            $mfecha = $fecha->month;
            $dfecha = $fecha->day;
            $afecha = $fecha->year;

            $sheet->setCellValue('H3', $header->number_correlative);
            $sheet->setCellValue('H6', $dfecha);
            $sheet->setCellValue('I6', $mfecha);
            $sheet->setCellValue('J6', $afecha);
            $sheet->setCellValue('B8', $management->person[0]->name.' '.$management->person[0]->lastname);
            $sheet->setCellValue('A12', $header->person->name . ' ' . $header->person->lastname);
            $sheet->setCellValue('C12', $header->person->office[0]->name);
            $sheet->setCellValue('H12', $header->person->document_number);
            $sheet->setCellValue('D15', $header->request_amount);
            $sheet->setCellValue('A15', $header->reference_document);
            $sheet->setCellValue('A19', $header->purpose);
            $sheet->setCellValue('A22', $header->justification);

            //DETALLES
            $index = 1;
            $startRow = 26;
            foreach ($details as $key => $value) {
                $sheet->setCellValue('A'.$startRow, $value->code_classify);
                $sheet->setCellValue('B'.$startRow, $value->name_classify);
                $sheet->setCellValue('C'.$startRow, $value->issue_type);
                $sheet->setCellValue('D'.$startRow, $value->goal_one);
                $sheet->setCellValue('E'.$startRow, $value->goal_two);
                $sheet->setCellValue('F'.$startRow, $value->goal_three);
                $sheet->setCellValue('G'.$startRow, $value->goal_one+$value->goal_two+$value->goal_three );
                $startRow++;
                $index++;
            }
        }
        elseif($header->request_type==2){
            //CABECERA
            $fecha = Carbon::parse($header->request_date);
            $mfecha = $fecha->month;
            $dfecha = $fecha->day;
            $afecha = $fecha->year;

            $sheet->setCellValue('R3', $header->number_correlative);
            $sheet->setCellValue('R6', $dfecha);
            $sheet->setCellValue('T6', $mfecha);
            $sheet->setCellValue('V6', $afecha);
            $sheet->setCellValue('C12', $header->person->name . ' ' . $header->person->lastname);
            $sheet->setCellValue('C12', $header->person->office[0]->name);
            $sheet->setCellValue('H12', $header->person->document_number);
            $sheet->setCellValue('D15', $header->request_amount);
            $sheet->setCellValue('A15', $header->reference_document);
            $sheet->setCellValue('A19', $header->purpose);
            $sheet->setCellValue('A22', $header->justification);

        }
    }
}