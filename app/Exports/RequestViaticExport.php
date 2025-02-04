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
use Carbon\Carbon;

class RequestViaticExport implements FromCollection, WithEvents, WithMapping
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
               
                $templateFile = new LocalTemporaryFile(storage_path('app/public/templates/xlsx/1_Solicitudes_Viaticos.xlsx'));
                $event->writer->reopen($templateFile, Excel::XLSX);
                $sheet1 = $event->writer->getSheetByIndex(0);
                
                $this->HojaSolicitudViatico($sheet1);
                

                // $this->calledByEvent = true; // set the flag
                $event->writer->getSheetByIndex(0)->export($event->getConcernable()); // call the export on the first sheet

                return $event->getWriter()->getSheetByIndex(0);
            },
        ];
    }

    

    private function HojaSolicitudViatico($sheet)
    {
        $header = RequestFile::where('id', $this->request_id)->with(["person","person.office"])->first();
        $details = RequestFileClassifier::where('request_id', $this->request_id)->get();
        $management = Office::with('person','person.office')->where('id','1')->first();

        
            //CABECERA
            $fecha = Carbon::parse($header->request_date);
            $mfecha = $fecha->month;
            $dfecha = $fecha->day;
            $afecha = $fecha->year;
            $fechaActual = Carbon::now();
            $anioActual = $fechaActual->year;

            $correlative = substr(str_repeat(0, 4).$header->number_correlative, - 4);

            $sheet->setCellValue('R3', 'PV-'.$correlative.'-'.$anioActual);
            $sheet->setCellValue('R6', $dfecha);
            $sheet->setCellValue('T6', $mfecha);
            $sheet->setCellValue('V6', $afecha);
            $sheet->setCellValue('E8', $management->person[0]->name.' '.$management->person[0]->lastname);
            $sheet->setCellValue('C12', $header->person->name . ' ' . $header->person->lastname);
            $sheet->setCellValue('N12', $header->person->office[0]->name);
            $sheet->setCellValue('C15', $header->person->document_number);
            $sheet->setCellValue('H15', $header->cellphone);

            if($header->viatic_type == '1'){
                $sheet->setCellValue('M18', ' X');
            }elseif ($header->viatic_type == '2'){
                $sheet->setCellValue('M18','X');
            }

            $sheet->setCellValue('H20', $header->purpose);     
            $sheet->setCellValue('H22', $header->reference_document);
            $sheet->setCellValue('H24', $header->destination);    
            $sheet->setCellValue('T24', $header->number_days);

            if($header->means_of_transport == '1'){
                $sheet->setCellValue('K26', ' X');
            }elseif ($header->means_of_transport == '2'){
                $sheet->setCellValue('P26','X');
            }elseif ($header->means_of_transport == '3'){
                $sheet->setCellValue('W26','X');
            } 
            
            $sheet->setCellValue('H28', $header->departure_date); 
            $sheet->setCellValue('R28', $header->return_date);  
        
        
    }
}