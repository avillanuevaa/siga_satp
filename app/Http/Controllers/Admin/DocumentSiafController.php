<?php

namespace App\Http\Controllers\Admin;

use File;
use Carbon\Carbon;
use App\Models\DocumentSiaf;
use App\Models\FileUpload;
use App\Models\Institution;
use App\Exports\DocumentsSiafExport;
use App\Imports\DocumentsSiafImport;
use Illuminate\Http\Request;
use App\Http\Requests\DocumentSiafRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;


class DocumentSiafController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        Log::info('DT Request Payload:', $request->all());
        if ($request->ajax()) {
            $query = DocumentSiaf::where('active', 1)
                ->select([
                    'id',
                    'siaf',
                    'date',
                    'type_new',
                    'serie',
                    'number',
                    'ruc',
                    'amount',
                    'total_honorary',
                    'payment_date',
                    'source',
                    'status',
                ])->orderBy('date', 'desc');

            session(['previous_url_document_siaf' => url()->current().'?'.$request->getQueryString()]);

            return DataTables::of($query)
                ->addColumn('siaf', fn($item) => $item->siaf)
                ->addColumn('fecha_emision', fn($item) => Carbon::parse($item->date)->format('d/m/Y'))
                ->addColumn('tipo_doc', fn($item) => $item->type_new)
                ->addColumn('serie_doc', fn($item) => $item->serie)
                ->addColumn('num_doc', fn($item) => $item->number)
                ->addColumn('ruc', fn($item) => $item->ruc)
                ->addColumn('total', fn($item) =>
                    ($item->type_new !== 'R' && $item->type_new !== 'N')
                        ? number_format($item->amount, 2)
                        : number_format($item->total_honorary, 2)
                    )
                ->addColumn('fecha_pago', fn($item) =>
                    $item->payment_date
                        ? Carbon::parse($item->payment_date)->format('d/m/Y')
                        : ''
                    )
                ->addColumn('origen', fn($item) => $item->source === 1 ? 'Importado' : ($item->source === 2 ? 'Manual': 'None'))
                ->addColumn('estado', function($item) {
                    return match($item->status) {
                        1 => '<span class="badge bg-danger">Pendiente</span>',
                        2 => '<span class="badge bg-success">Registrado</span>',
                        3 => '<span class="badge bg-info">Cerrado</span>',
                        default => '<span class="badge bg-secondary">?</span>',
                    };
                })
                ->addColumn('action', function($item) {
                    $editUrl = route('documentSiafs.edit', $item->id);
                    $edit    = "<a href='$editUrl' class='btn btn-sm btn-info me-1'><i class='fas fa-edit'></i></a>";
                    $del     = "<button class='btn btn-sm btn-danger btn-delete' data-id='{$item->id}'><i class='fas fa-trash-alt'></i></button>";
                    return "<div class='btn-group'>$edit$del</div>";
                })
                ->filterColumn('siaf', function($query, $keyword) {
                    $query->where('siaf', $keyword);
                })
                ->filterColumn('tipo_doc', function($query, $keyword) {
                    $query->where('type_new', $keyword);
                })
                ->filterColumn('number', function($query, $keyword) {
                    $query->where('number', 'like', "%{$keyword}%");
                })
                ->filterColumn('ruc', function($query, $keyword) {
                    $query->where('ruc', 'like', "%{$keyword}%");
                })
                ->filterColumn('fecha_emision', function($query, $keyword) {
                    if (!empty($keyword)) {
                        $dates = explode('|', $keyword);

                        if (count($dates) == 2) {
                            $fromDate = $dates[0];
                            $toDate = $dates[1];

                            if (!empty($fromDate) && !empty($toDate)) {
                                $fromDate = Carbon::createFromFormat('d/m/Y', $fromDate)->startOfDay();
                                $toDate = Carbon::createFromFormat('d/m/Y', $toDate)->endOfDay();
                                $query->whereBetween('date', [$fromDate, $toDate]);
                            } elseif (!empty($fromDate)) {
                                $fromDate = Carbon::createFromFormat('d/m/Y', $fromDate)->startOfDay();
                                $query->where('date', '>=', $fromDate);
                            } elseif (!empty($toDate)) {
                                $toDate = Carbon::createFromFormat('d/m/Y', $toDate)->endOfDay();
                                $query->where('date', '<=', $toDate);
                            }
                        } else {
                            try {
                                $date = Carbon::createFromFormat('d/m/Y', $keyword);
                                $query->whereDate('date', $date->format('Y-m-d'));
                            } catch (\Exception $e) {
                                Log::info($e);
                            }
                        }
                    }
                })
                ->filterColumn('origen', function($query, $keyword) {
                    if ($keyword !== '') {
                        $query->where('source', $keyword);
                    }
                })
                ->filterColumn('estado', function($query, $keyword) {
                    if ($keyword !== '') {
                        $query->where('status', $keyword);
                    }
                })
                ->rawColumns(['estado', 'action'])
                ->toJson();
        }

        return view('admin.document_siaf.index');
    }


/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return response()->view('admin.document_siaf.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentSiafRequest $request)
    {
        //
        try{

            $data = $request->all();

            $year = date("Y", strtotime($request->date));

            $register = DocumentSiaf::where('type_new', $request->type_new)
                        ->where('serie', $request->serie)
                        ->where('number', $request->number)
                        ->whereYear('date', '=', $year)
                        ->where('ruc', $request->ruc )
                        ->where('active', 1)
                        ->first();
            if ($register) {
                return redirect()->back()->withErrors(['siaf' => 'Documento ya se encuentra registrado'])->withInput();
            }

            $data['month'] = date("m", strtotime($request->payment_date));
            $data['type'] = $request->type_new;
            $data['status'] = 2;
            $data['source'] = 2;

            DocumentSiaf::create($data);

            return redirect()->to(session('previous_url_document_siaf'))
                            ->with([
                                'notif' => [
                                    'message' => 'Siaf creado satisfactoriamente',
                                    'icon' => 'success'
                                ]
                            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()])->withInput();
        }

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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DocumentSiaf  $documentSiaf
     * @return \Illuminate\Http\Response
     */
    public function edit(DocumentSiaf $documentSiaf)
    {
        //
        return response()->view('admin.document_siaf.edit', [
            'documentSiaf' => $documentSiaf
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DocumentSiaf  $documentSiaf
     * @return \Illuminate\Http\Response
     */
    public function update(DocumentSiafRequest $request, DocumentSiaf $documentSiaf)
    {
        //
        $data = $request->all();

        $year = date("Y", strtotime($request->date));

        $register = DocumentSiaf::whereYear('date', '=', $year)
                                ->where('type_new', $request->type_new)
                                ->where('serie', $request->serie)
                                ->where('number', $request->number)
                                ->where('id', '!=' , $documentSiaf->id)
                                ->where('ruc', $request->ruc )
                                ->where('active', 1)
                                ->first();

        if ($register) {
            return redirect()->back()->withErrors(['Documento ya se encuentra registrado'])->withInput();
        }

        $documentSiaf->status = 2;
        $documentSiaf->update($data);

        return redirect()->to(session('previous_url_document_siaf'))
                         ->with([
                            'notif' => [
                                'message' => 'Siaf actualizado satisfactoriamente',
                                'icon' => 'success'
                            ]
                        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DocumentSiaf  $documentSiaf
     * @return \Illuminate\Http\Response
     */
    public function destroy(DocumentSiaf $documentSiaf)
    {
        //
        $documentSiaf->update(array('active' => 0));

        return response()->json([
            "message" => "Documento eliminado correctamente."
        ]);
    }

    public function importExcel()
    {
        return response()->view('admin.document_siaf.import_excel');
    }

    public function uploadExcel(Request $request)
    {


        try {
            $data = $request->all();

            $validator = Validator::make($data, [
                'file' => 'required|file|mimes:xls,xlsx'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error.message', 'error|Hubo un error al guardar los cambios.');
            }

            DB::beginTransaction();

            $file = $request->file('file');

            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $newfilename = date('dmYHis') . "." . $extension;
            $fileSize = $file->getSize();
            $today = date('dmY');
            $location = "uploads/excel-siaf/" . $today;

            $fileUpload = FileUpload::create([
                'original_name' => $filename,
                'name' => $newfilename,
                'extension' => $extension,
                'size' => $fileSize,
                'path' => $location,
                'type' => 1
            ]);

            Excel::import($newsDocumentsSiaf = new DocumentsSiafImport($fileUpload->id), $file);

            $file->move($location, $newfilename);

            DB::commit();

            return redirect()->route('documentSiafs.index')
                        ->with([
                            'notif' => [
                                'message' => 'Importación realizada satisfactoriamente.',
                                'icon' => 'success'
                            ],
                            'totalUploadedFiles' => 'success|'.$newsDocumentsSiaf->getRowCount() . ' filas se importaron correctamente.',
                        ]);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error.message', 'error|Hubo un error al guardar los cambios.');
        }


    }

    public function exportClose(){

        $years = config('constants.years');
        $months = config('constants.months');
        $typeReports = config('constants.report_type_siaf');

        foreach ($months as $month) {
            $convertedMonths[] = (object) $month;
        }

        foreach ($years as $year) {
            $convertedYears[] = (object) $year;
        }

        foreach ($typeReports as $typeReport) {
            $convertedTypeReports[] = (object) $typeReport;
        }

        return response()->view('admin.document_siaf.export_close', [
            'years' => $convertedYears,
            'months' => $convertedMonths,
            'type_reports' => $convertedTypeReports
        ]);
    }

    public function exportExcel(Request $request)
    {
        return Excel::download((new DocumentsSiafExport($request->month, $request->year, $request->type_report)), 'excelSiaf.xlsx', null, [\Maatwebsite\Excel\Excel::XLSX]);
    }

    public function exportExcelPending(Request $request)
    {
        return Excel::download((new DocumentsSiafExport(0, $request->year, 3)), 'excelSiafPendientes.xlsx', null, [\Maatwebsite\Excel\Excel::XLSX]); // 3 es el tipo que exporte pendientes
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

        $documentSiaf = DocumentSiaf::where(array('status' => 2, 'active' => 1))
                        ->whereMonth('payment_date', $month)
                        ->whereYear('payment_date', '=', $year)
                        ->update(array('status' => 3));

        return response()->json([
            "message" => "Se actualizaron " . $documentSiaf . " registros."
        ]);
    }

    public function exportTxtPle(Request $request)
    {
        ob_start();
        if ($request->type_report == 1) {

            $data = DocumentSiaf::select('id', 'date', 'type_new', 'serie', 'number', 'ruc', 'business_name', 'taxable_basis', 'untaxed_basis', 'impbp', 'igv', 'other_concepts', 'amount', 'doc_code', 'num_doc', 'doc_modify_date_of_issue', 'doc_modify_type', 'doc_modify_serie', 'doc_modify_number', 'payment_date', 'nParCodigo as idtipocomprobante', 'detraction_date', 'num_operation')
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
                $anio_emision_aduanera = "";
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
                $Base_Imponible_Adq_Gravadas_No_BV = ($value->type_new == '03' || $value->igv == "0.00" ) ? $value->amount : $value->untaxed_basis;
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

                $new_template = filter_var($request->new_template, FILTER_VALIDATE_BOOLEAN);

                if ( $new_template ){
                    $satpRUC = "20441554436";
                    $satpRazonSocial = "SERVICIO DE ADMINIS. TRIBUTARIA DE PIURA";
                    $periodo = $value->idtipocomprobante == 10 ? Carbon::createFromFormat('Y-m-d', $value->payment_date)->format('Ym') : $year . $mm;
                    $carSUNAT = "";
                    $nroFinalRango = "";
                    $Codigo_Dependencia_Aduanera = "";
                    $Error_vacio = "";
                    $Fecha_Detraccion = !is_null($value->detraction_date) ? Carbon::createFromFormat('Y-m-d', $value->detraction_date)->format('d/m/Y') : '';
                    $numero_operacion = $value->num_operation;
                    $Tipo_Cambio = "";
                    $fec_Emi_Comp_Pago_Modifica = ($fec_Emi_Comp_Pago_Modifica == "01/01/1900") ? '' : $fec_Emi_Comp_Pago_Modifica;

                    $content_txt .= $satpRUC . "|" . $satpRazonSocial . "|" . $periodo . "|" . $carSUNAT . "|" . $fecha_comprobante . "|" . $fecha_vencimiento . "|" . $tipo_comprobante . "|" . $serie_comprobante . "|" . $anio_emision_aduanera . "|" . $numero_comp_Pago . "|". $nroFinalRango  . "|" . $Tipo_Doc_Ident_Proveedor     . "|" . $RUC_Proveedor . "|" . $Razonsocial_Proveedor . "|" . $Base_Imponible_Adq_Gravadas_1 . "|" . $IGV_IPM_1 . "|" . $Base_Imponible_Adq_Gravadas_2 . "|" . $IGV_IPM_2 . "|" . $Base_Imponible_Adq_Gravadas_3 . "|" . $IGV_IPM_3 . "|" . $Base_Imponible_Adq_Gravadas_No_BV . "|" . $ISC . "|" . $Bolsas_plastico . "|" . $Otros_Tributos . "|" . $Importe_Total_Adq . "|" . $Codigo_Moneda . "|" . $Tipo_Cambio . "|" . $fec_Emi_Comp_Pago_Modifica . "|" . $Tipo_Comp_Pago_Modificado . "|" . $Serie_Comp_Modificado  . "|" . $Codigo_Dependencia_Aduanera . "|" . $Numero_Comp_Modificado . "|" . $Marca_Comp . "|" . $Clasificacion_Bienes_Servicios_Adquiridos . "|" . $Identificacion_Contrato_Proyecto_Soc_Irregulares . "|" . $Error_1 . "|" . $Error_2 . "|" . $Error_3 . "|" . $Error_4 . "|" . $Indicador_Comprobantes_pago_ . "|" . $Error_vacio . "|" . $Fecha_Detraccion . "|" . $numero_operacion . "\r\n";

                }else{
                    $content_txt .= $periodo . "|" . $cuo . "|" . $correlativo . "|" . $fecha_comprobante . "|" . $fecha_vencimiento . "|" . $tipo_comprobante . "|" . $serie_comprobante . "|" . $anio_emision_aduanera . "|" . $numero_comp_Pago     . "|" . $Importe_No_Credito_Fiscal . "|" . $Tipo_Doc_Ident_Proveedor     . "|" . $RUC_Proveedor . "|" . $Razonsocial_Proveedor . "|" . $Base_Imponible_Adq_Gravadas_1 . "|" . $IGV_IPM_1 . "|" . $Base_Imponible_Adq_Gravadas_2 . "|" . $IGV_IPM_2 . "|" . $Base_Imponible_Adq_Gravadas_3 . "|" . $IGV_IPM_3 . "|" . $Base_Imponible_Adq_Gravadas_No_BV . "|" . $ISC . "|" . $Bolsas_plastico . "|" . $Otros_Tributos . "|" . $Importe_Total_Adq . "|" . $Codigo_Moneda . "|" . $Tipo_Cambio . "|" . $fec_Emi_Comp_Pago_Modifica . "|" . $Tipo_Comp_Pago_Modificado . "|" . $Serie_Comp_Modificado . "|" . $Codigo_Dependencia_Aduanera . "|" . $Numero_Comp_Modificado . "|" . $Fecha_Detraccion . "|" . $Numero_Const_Detrac . "|" . $Marca_Comp . "|" . $Clasificacion_Bienes_Servicios_Adquiridos . "|" . $Identificacion_Contrato_Proyecto_Soc_Irregulares . "|" . $Error_1 . "|" . $Error_2 . "|" . $Error_3 . "|" . $Error_4 . "|" . $Indicador_Comprobantes_pago_ . "|" . $Estado . "|" . "\r\n";
                }

            }
            if (ob_get_level() > 0) {
              ob_end_clean();
            }
            Storage::disk('local')->put($full_path, $content_txt);
            $path = storage_path() . '/' . 'app' . '/' . $full_path;
            return response()->download($path)->deleteFileAfterSend(true);
        }
    }

    public function exportTxtPlameDetail(Request $request)
    {

        ob_start();
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

            if (ob_get_level() > 0) {
              ob_end_clean();
            }

            Storage::disk('local')->put($full_path, $content_txt);
            $path = storage_path() . '/' . 'app' . '/' . $full_path;
            return response()->download($path)->deleteFileAfterSend(true);
        }
    }

    public function exportTxtPlameProvidersName(Request $request)
    {
        ob_start();
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

            if (ob_get_level() > 0) {
              ob_end_clean();
            }

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

            // Determine if the request was successful based on response data
            $responseData = json_decode($response, true);
            $responseData['success'] = isset($responseData['razonSocial']); // Customize this condition based on your response structure
            return  response()->json($responseData);
        }
    }




}
