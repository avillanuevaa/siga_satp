<?php

namespace App\Http\Controllers\Admin;

use App\Models\RequestFile;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\CashRegistersExport;
use App\Exports\OrderRegistersExport;
use App\Exports\ViaticRegistersExport;
use App\Exports\RequestOrderExport;
use App\Exports\RequestViaticExport;
use App\Exports\SettlementOrderExport;
use App\Exports\SettlementViaticExport;

use Maatwebsite\Excel\Facades\Excel;


class ReportController extends AdminController
{
    //

    public function cashRegisterDetails(Request $request)
    {
      return Excel::download((new CashRegistersExport($request->cash_register_id)), 'excelCajaChica.xlsx', null, [\Maatwebsite\Excel\Excel::XLSX]);
    }
  
    public function orderRegisterDetails(Request $request)
    {
      return Excel::download((new OrderRegistersExport($request->order_register_id)), 'excelEncargo.xlsx', null, [\Maatwebsite\Excel\Excel::XLSX]);
    }
  
    public function viaticRegisterDetails(Request $request)
    {
      return Excel::download((new ViaticRegistersExport($request->viatic_register_id, 1)), 'excelViatico.xlsx', null, [\Maatwebsite\Excel\Excel::XLSX]);
    }
  
    public function viaticRegisterReport(Request $request)
    {
      return Excel::download((new ViaticRegistersExport($request->viatic_register_id, 2)), 'excelViatico.xlsx', null, [\Maatwebsite\Excel\Excel::XLSX]);
    }
  
    public function requestFileDetails(Request $request)
    {
  
      $type = RequestFile::select('request_type')->where('id', '=', $request->request_id)->first();
  
      if ($type->request_type == 1) {
        return Excel::download((new RequestOrderExport($request->request_id)), 'excelSolicitudEncargo.xlsx', null, [\Maatwebsite\Excel\Excel::XLSX]);
      } elseif ($type->request_type == 2) {
        return Excel::download((new RequestViaticExport($request->request_id)), 'excelSolicitudViatico.xlsx', null, [\Maatwebsite\Excel\Excel::XLSX]);
      }
    }
    
    public function settlementFileDetails(Request $request)
    {
      $type = Settlement::select('request_type')->where('id',$request->settlement_id)->first();
  
      if ($type->request_type == 1) {
        return Excel::download((new SettlementOrderExport($request->settlement_id)), 'excelLiquidacionEncargo.xlsx', null, [\Maatwebsite\Excel\Excel::XLSX]);
  
      } elseif ($type->request_type == 2) {
        return Excel::download((new SettlementViaticExport($request->settlement_id)), 'excelLiquidacionViaticosYPasajes.xlsx', null, [\Maatwebsite\Excel\Excel::XLSX]);
      }
    }
}
