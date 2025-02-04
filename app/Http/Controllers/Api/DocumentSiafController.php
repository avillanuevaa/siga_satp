<?php

namespace App\Http\Controllers\Api;

use File;
use Carbon\Carbon;
use App\Models\Institution;
use App\Models\DocumentSiaf;
use Illuminate\Http\Request;
use App\Exports\DocumentsSiafExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;



class DocumentSiafController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DocumentSiaf  $documentSiaf
     * @return \Illuminate\Http\Response
     */
    public function show(DocumentSiaf $documentSiaf)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DocumentSiaf  $documentSiaf
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $year = date("Y", strtotime($request->date));

        $document = DocumentSiaf::find($id);
        if (isset($document)) {
            $register = DocumentSiaf::whereYear('date', '=', $year)
                        ->where('type_new', $request->type_new)
                        ->where('serie', $request->serie)
                        ->where('number', $request->number)
                        ->where('id', '!=' , $id)
                        ->where('ruc', $request->ruc )
                        ->where('active', 1)
                        ->first();
            if ($register) throw new Exception('Documento ya se encuentra registrado');

            $document->status = 2;
            $document->update($request->all());
        } else {
            $register = DocumentSiaf::where('type_new', $request->type_new)
                        ->where('serie', $request->serie)
                        ->where('number', $request->number)
                        ->whereYear('date', '=', $year)
                        ->where('ruc', $request->ruc )
                        ->where('active', 1)
                        ->first();
            if ($register) throw new Exception('Documento ya se encuentra registrado');

            $request->merge(['month' => date("m", strtotime($request->payment_date))]);
            $request->merge(['type' => $request->type_new]);
            $request->merge(['status' => 2]);
            DocumentSiaf::create($request->all());
        }
        
        return $document;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DocumentSiaf  $documentSiaf
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DocumentSiaf::where(array('id' => $id))->update(array('active' => 0));
        return response()->json([
            "message" => "Documento eliminado correctamente."
        ]);
    }

    public function SearchDocumentBySiaf(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'siaf' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $siaf = $request->siaf;
        $current_year = filter_var($request->current_year, FILTER_VALIDATE_BOOLEAN);
        if ($current_year){
            $year = date("Y");
            $data = DocumentSiaf::where('siaf', $siaf)->where('active', 1)->whereYear('date', $year)->get();
        }else{
            $data = DocumentSiaf::where('siaf', $siaf)->where('active', 1)->get();
        }
        return response($data);
    }

    public function exportExcel(Request $request)
    {
        return Excel::download((new DocumentsSiafExport($request->month, $request->year, $request->type_report)), 'excelSiaf.xlsx', null, [\Maatwebsite\Excel\Excel::XLSX]);
    }

    public function exportExcelPending(Request $request)
    {
        return Excel::download((new DocumentsSiafExport(0, $request->year, 3)), 'excelSiafPendientes.xlsx', null, [\Maatwebsite\Excel\Excel::XLSX]); // 3 es el tipo que exporte pendientes
    }

    public function exportTxtPle(Request $request)
    {
        if ($request->type_report == 1) {

            $data = DocumentSiaf::select('id', 'date', 'type_new', 'serie', 'number', 'ruc', 'business_name', 'taxable_basis', 'untaxed_basis', 'impbp', 'igv', 'other_concepts', 'amount', 'doc_code', 'num_doc', 'doc_modify_date_of_issue', 'doc_modify_type', 'doc_modify_serie', 'doc_modify_number', 'payment_date', 'nParCodigo as idtipocomprobante')
                ->join('parameters as tipocomprobante',  function ($join) {
                    $join->on('documents_siaf.type_new', '=', 'tipocomprobante.cParValor');
                    $join->on('tipocomprobante.nParClase', '=', DB::raw('1006'));
                    $join->on('tipocomprobante.nParTipo', '=', DB::raw('1'));
                })
                ->whereMonth('payment_date', $request->month)
                ->whereYear('payment_date', '=', $request->year)
                ->where('documents_siaf.active', 1)
                ->whereNot('type_new', 'R')
                ->whereNot('type_new', 'N')
                ->orderBy('date')->get();
            $institution = Institution::first();
            $year =$request->year;
            $mm = str_pad($request->month, 2, "0", STR_PAD_LEFT);
            $dd = "00";
            $llll = "080100";
            $cc = "00";
            $o = "1";
            $l = "1";
            $m = "1";
            $g = "1";
            $name_file = "LE" . $institution->ruc . $year . $mm . $dd . $llll . $cc . $o . $l . $m . $g . ".txt";
            $full_path = 'public/' . $name_file;

            $content_txt = "";
            foreach ($data as $key => $value) {
                $periodo = $year . $mm . $dd;
                $cuo = $value->doc_code . str_pad($value->num_doc, 6, "0", STR_PAD_LEFT) . str_pad($request->month, 4, "0", STR_PAD_LEFT); #"140011760006";
                $correlativo = "M" . str_pad($key + 1, 9, "0", STR_PAD_LEFT); #"M000000001";
                $fecha_comprobante = Carbon::createFromFormat('Y-m-d', $value->date)->format('d/m/Y');
                $fecha_vencimiento = $value->idtipocomprobante == 10 ? Carbon::createFromFormat('Y-m-d', $value->payment_date)->format('d/m/Y') : "";
                // $fecha_vencimiento = "";
                $tipo_comprobante = $value->type_new;
                $serie_comprobante = $value->serie;
                $anio_emision_aduanera = "0";
                $numero_comp_Pago = $value->number;
                $Importe_No_Credito_Fiscal = "";
                $Tipo_Doc_Ident_Proveedor = "6";
                $RUC_Proveedor = $value->ruc;
                $Razonsocial_Proveedor = $value->business_name;
                $Base_Imponible_Adq_Gravadas_1 = "0";
                $IGV_IPM_1 = "0";
                $Base_Imponible_Adq_Gravadas_2 = "0";
                $IGV_IPM_2 = "0";
                $Base_Imponible_Adq_Gravadas_3 = $value->taxable_basis;
                $IGV_IPM_3 = $value->igv;
                $Base_Imponible_Adq_Gravadas_No_BV = ($value->type_new != '03' ? '0' : $value->untaxed_basis);
                $ISC = "0";
                $Bolsas_plastico = $value->impbp;
                $Otros_Tributos = $value->other_concepts;
                $Importe_Total_Adq = $value->amount;
                $Codigo_Moneda = "PEN";
                $Tipo_Cambio = "0.000";
                $fec_Emi_Comp_Pago_Modifica = is_null($value->doc_modify_date_of_issue) ? "01/01/1900" : Carbon::createFromFormat('Y-m-d', $value->doc_modify_date_of_issue)->format('d/m/Y'); //Aqui Carbon::createFromFormat('Y-m-d', $value->date)->format('d/m/Y');
                $Tipo_Comp_Pago_Modificado = $value->doc_modify_type ?? "";
                $Serie_Comp_Modificado = $value->doc_modify_serie ?? "";
                $Codigo_Dependencia_Aduanera = "";
                $Numero_Comp_Modificado = $value->doc_modify_number ?? "";
                $Fecha_Detraccion = "01/01/0001";
                $Numero_Const_Detrac = "0";
                $Marca_Comp = "";
                $Clasificacion_Bienes_Servicios_Adquiridos = "";
                $Identificacion_Contrato_Proyecto_Soc_Irregulares = "";
                $Error_1 = "";
                $Error_2 = "";
                $Error_3 = "";
                $Error_4 = "";
                $Indicador_Comprobantes_pago_ = "";
                $Estado = "";
                if ($value->type_new == "03" || $value->igv == "0.00") {
                    $Estado = "0";
                } else if (Carbon::createFromFormat('Y-m-d', $value->date)->format('n') == $request->month) {
                    $Estado = "1";
                } else {
                    $ts1 = strtotime($value->date);
                    $ts2 = strtotime($value->payment_date);
                    if ($value->date < $value->payment_date) {
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

                $content_txt .= $periodo . "|" . $cuo . "|" . $correlativo . "|" . $fecha_comprobante . "|" . $fecha_vencimiento . "|" . $tipo_comprobante . "|" . $serie_comprobante . "|" . $anio_emision_aduanera . "|" . $numero_comp_Pago     . "|" . $Importe_No_Credito_Fiscal . "|" . $Tipo_Doc_Ident_Proveedor     . "|" . $RUC_Proveedor . "|" . $Razonsocial_Proveedor . "|" . $Base_Imponible_Adq_Gravadas_1 . "|" . $IGV_IPM_1 . "|" . $Base_Imponible_Adq_Gravadas_2 . "|" . $IGV_IPM_2 . "|" . $Base_Imponible_Adq_Gravadas_3 . "|" . $IGV_IPM_3 . "|" . $Base_Imponible_Adq_Gravadas_No_BV . "|" . $ISC . "|" . $Bolsas_plastico . "|" . $Otros_Tributos . "|" . $Importe_Total_Adq . "|" . $Codigo_Moneda . "|" . $Tipo_Cambio . "|" . $fec_Emi_Comp_Pago_Modifica . "|" . $Tipo_Comp_Pago_Modificado . "|" . $Serie_Comp_Modificado . "|" . $Codigo_Dependencia_Aduanera . "|" . $Numero_Comp_Modificado . "|" . $Fecha_Detraccion . "|" . $Numero_Const_Detrac . "|" . $Marca_Comp . "|" . $Clasificacion_Bienes_Servicios_Adquiridos . "|" . $Identificacion_Contrato_Proyecto_Soc_Irregulares . "|" . $Error_1 . "|" . $Error_2 . "|" . $Error_3 . "|" . $Error_4 . "|" . $Indicador_Comprobantes_pago_ . "|" . $Estado . "|" . "\r\n";
            }
            ob_end_clean();
            Storage::disk('local')->put($full_path, $content_txt);
            $path = storage_path() . '/' . 'app' . '/' . $full_path;
            return response()->download($path)->deleteFileAfterSend(true);
        }
    }

    public function exportTxtPlameDetail(Request $request)
    {

        if ($request->type_report == 2) {

            $data = DocumentSiaf::select('id', 'date', 'type_new', 'serie', 'number', 'ruc', 'total_honorary', 'payment_date', 'have_retention')->whereMonth('payment_date', $request->month)
            ->whereYear('payment_date', '=', $request->year)->where('active', 1)->where(function ($query) {
                $query->where('type_new', 'R')->orWhere('type_new', 'N');
            })->get();
            $institution = Institution::first();
            $ffff = "0601"; //codigo formulario
            $year = date("Y");
            $mm = str_pad($request->month, 2, "0", STR_PAD_LEFT);
            $name_file = $ffff . $year . $mm . $institution->ruc . ".4ta";
            $full_path = 'public/' . $name_file;
            $content_txt = "";
            foreach ($data as $value) {
                $Type_Doc_Ident_Proveedor = "06";
                $RUC_Proveedor = $value->ruc;
                $type = $value->type_new;
                $serie = $value->serie;
                $number =  str_pad($value->number, 4, "0", STR_PAD_LEFT);
                $total_honorary = $value->total_honorary;
                $date_emision = Carbon::createFromFormat('Y-m-d', $value->date)->format('d/m/Y');
                $date_payment = Carbon::createFromFormat('Y-m-d', $value->payment_date)->format('d/m/Y');
                $have_retention = $value->have_retention ? 1 : 0;
                $content_txt .= $Type_Doc_Ident_Proveedor . "|" . $RUC_Proveedor . "|" . $type . "|" . $serie . "|" . $number . "|" . $total_honorary . "|" . $date_emision . "|" . $date_payment . "|" . $have_retention . "|" . "|" . "|" . "\r\n";
            }
            ob_end_clean();
            Storage::disk('local')->put($full_path, $content_txt);
            $path = storage_path() . '/' . 'app' . '/' . $full_path;
            return response()->download($path)->deleteFileAfterSend(true);
        }
    }

    public function exportTxtPlameProvidersName(Request $request)
    {

        if ($request->type_report == 2) {

            $data = DocumentSiaf::select('ruc', 'last_name', 'mother_last_name', 'name')->whereMonth('payment_date', $request->month)->whereYear('payment_date', '=', $request->year)->where('active', 1)->where(function ($query) {
                $query->where('type_new', 'R')->orWhere('type_new', 'N');
            })->distinct()->orderBy(DB::raw('CONCAT(last_name, mother_last_name, name)'))->get();
            $institution = Institution::first();
            $ffff = "0601"; //codigo formulario
            $year = date("Y");
            $mm = str_pad($request->month, 2, "0", STR_PAD_LEFT);
            $name_file = $ffff . $year . $mm . $institution->ruc . ".ps4";
            $full_path = 'public/' . $name_file;
            $content_txt = "";
            foreach ($data as $value) {
                $Type_Doc_Ident_Proveedor = "06";
                $RUC_Proveedor = $value->ruc;
                $domiciled = "1";
                $double_axation_agreement = "0";
                $content_txt .= $Type_Doc_Ident_Proveedor . "|" . $RUC_Proveedor . "|" . $value->last_name . "|" . $value->mother_last_name . "|" . $value->name . "|" . $domiciled . "|" . $double_axation_agreement . "|" . "\r\n";
            }
            ob_end_clean();
            Storage::disk('local')->put($full_path, $content_txt);
            $path = storage_path() . '/' . 'app' . '/' . $full_path;
            return response()->download($path)->deleteFileAfterSend(true);
        }
    }

    public function SearchSupplierByRuc(Request $request)
    {
        $ruc = $request->ruc;
        if (strlen($ruc) == 11) {
            $url = "https://dniruc.apisperu.com/api/v1/ruc/";
            $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImJtaWd1ZWxiYzE2QGdtYWlsLmNvbSJ9.XZpuAXOqgQr_RovqfSXTtLA2D7X6gKK8A9IsE7HdUYw";
            $url .= $ruc . '?token=' . $token;
            $response = Http::get($url);
            return json_decode($response);
        }
    }

    public function closeSiafsByMonth(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'month' => 'required',
            'year' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $month = $request->month;
        $year = $request->year;

        $document_siaf = DocumentSiaf::where(array('status' => 2, 'active' => 1))
                        ->whereMonth('payment_date', $month)
                        ->whereYear('payment_date', '=', $year)
                        ->update(array('status' => 3));

        return response()->json([
            "message" => "Se actualizaron " . $document_siaf . " registros."
        ]);
    }
}
