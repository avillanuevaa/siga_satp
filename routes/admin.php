<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FinancialClassifierController;
use App\Http\Controllers\Admin\PersonController;
use App\Http\Controllers\Admin\DocumentSiafController;
use App\Http\Controllers\Admin\RequestFileController;
use App\Http\Controllers\Admin\SettlementController;
use App\Http\Controllers\Admin\CashRegisterController;
use App\Http\Controllers\Admin\CashRegisterDetailController;
use App\Http\Controllers\Admin\OfficeController;
use App\Http\Controllers\Admin\TypeAssetController;
use App\Http\Controllers\Admin\OrderRegisterController;
use App\Http\Controllers\Admin\OrderRegisterDetailController;
use App\Http\Controllers\Admin\ViaticRegisterController;
use App\Http\Controllers\Admin\ViaticRegisterDetailController;
use App\Http\Controllers\Admin\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () { return redirect()->secure(route('dashboard.index')); });
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');


Route::prefix('configuracion')->group(function () {
  Route::get('clasificadores/search', [FinancialClassifierController::class, 'search'])->name('financialClassifiers.search');;
  Route::resource('clasificadores', FinancialClassifierController::class)
    ->except(['show'])
    ->parameters(['clasificadores' => 'financialClassifier'])
    ->names('financialClassifiers');

  Route::resource('trabajadores', PersonController::class)
    ->except(['show'])
    ->parameters(['trabajadores' => 'person'])
    ->names('persons');

  Route::get('trabajadores/searchByDni', [PersonController::class, 'searchByDni'])->name('persons.searchByDni');
  
  Route::get('oficinas/getOfficeAndParent', [OfficeController::class, 'getOfficeAndParent'])->name('offices.getOfficeAndParent');
  
  Route::get('activos/getListByClassifierCode', [TypeAssetController::class, 'getListByClassifierCode'])->name('assets.getListByClassifierCode');

});

Route::prefix('contabilidad')->group(function () {
  Route::resource('registro-siaf', DocumentSiafController::class)
      ->except(['show'])
      ->parameters(['registro-siaf' => 'documentSiaf'])
      ->names('documentSiafs');

  Route::prefix('registro-siaf')->group(function () {
    Route::get('SearchSupplierByRuc', [DocumentSiafController::class, 'SearchSupplierByRuc'])->name('documentSiafs.SearchSupplierByRuc');
    Route::get('import-excel', [DocumentSiafController::class, 'importExcel'])->name('documentSiafs.importExcel'); // vista importar excel
    Route::post('upload-excel', [DocumentSiafController::class, 'uploadExcel'])->name('documentSiafs.uploadExcel');
    Route::get('exportar-cierre', [DocumentSiafController::class, 'exportClose'])->name('documentSiafs.exportClose'); // vista exportar y cerrar
    Route::get('exportar-excel', [DocumentSiafController::class, 'exportExcel'])->name('documentSiafs.exportExcel');
    Route::get('exportar-excel-pendientes', [DocumentSiafController::class, 'exportExcelPending'])->name('documentSiafs.exportExcelPending');
    Route::get('exportar-txt-ple', [DocumentSiafController::class, 'exportTxtPle'])->name('documentSiafs.exportTxtPle');
    Route::get('exportar-txt-plame-detalle', [DocumentSiafController::class, 'exportTxtPlameDetail'])->name('documentSiafs.exportTxtPlameDetail');
    Route::get('exportar-txt-plame-proveedores', [DocumentSiafController::class, 'exportTxtPlameProvidersName'])->name('documentSiafs.exportTxtPlameProvidersName');
    Route::post('cerrar-siaf-por-mes', [DocumentSiafController::class, 'closeSiafsByMonth'])->name('documentSiafs.closeSiafsByMonth');

  });
});


Route::prefix('rendiciones')->group(function () {
  Route::resource('solicitud', RequestFileController::class)
      ->except(['show'])
      ->parameters(['solicitud' => 'requestFile'])
      ->names('requestFiles');

  Route::post('solicitud/{requestFile}/updateApproval', [RequestFileController::class, 'updateApproval'])->name('requestFiles.updateApproval');
  Route::get('solicitud/searchRequestByCorrelativeAndRequestTypeAndYear', [RequestFileController::class, 'searchRequestByCorrelativeAndRequestTypeAndYear'])->name('requestFiles.searchRequestByCorrelativeAndRequestTypeAndYear');

  Route::resource('liquidacion', SettlementController::class)
      ->except(['show'])
      ->parameters(['liquidacion' => 'settlement'])
      ->names('settlements');

  Route::post('liquidacion/{settlement}/updateApproval', [SettlementController::class, 'updateApproval'])->name('settlements.updateApproval');

  Route::resource('caja-chica', CashRegisterController::class)
      ->parameters(['caja-chica' => 'cashRegister'])
      ->names('cashRegisters')
      ->only(['index', 'create', 'store', 'show']);

  Route::prefix('caja-chica')->group(function () {
    Route::post('cerrar', [CashRegisterController::class, 'close'])->name('cashRegisters.close');
  });

  Route::resource('caja-chica.detalle', CashRegisterDetailController::class)
      ->parameters(['caja-chica' => 'cashRegister', 'detalle' => 'cashRegisterDetail'])
      ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
      ->names('cashRegisterDetails');

  Route::resource('encargos', OrderRegisterController::class)
      ->parameters(['encargos' => 'orderRegister'])
      ->names('orderRegisters')
      ->only(['index', 'create', 'store', 'show']);

  Route::prefix('encargos')->group(function () {
    Route::post('cerrar', [OrderRegisterController::class, 'close'])->name('orderRegisters.close');
  });

  Route::resource('encargos.detalle', OrderRegisterDetailController::class)
      ->parameters(['encargos' => 'orderRegister', 'detalle' => 'orderRegisterDetail'])
      ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
      ->names('orderRegisterDetails');

  Route::resource('viaticos', ViaticRegisterController::class)
      ->parameters(['viaticos' => 'viaticRegister'])
      ->names('viaticRegisters')
      ->only(['index', 'create', 'store', 'update', 'show']);

  Route::prefix('viaticos')->group(function () {
    Route::post('cerrar', [ViaticRegisterController::class, 'close'])->name('viaticRegisters.close');
  });
  
  Route::resource('viaticos.detalle', ViaticRegisterDetailController::class)
      ->parameters(['viaticos' => 'viaticRegister', 'detalle' => 'viaticRegisterDetail'])
      ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
      ->names('viaticRegisterDetails');

});

Route::prefix('reports')->group(function() {
  Route::name('reports.')->group(function() {
    Route::get('cashRegisterDetails', [ReportController::class, 'cashRegisterDetails'])->name('cashRegisterDetails');
    Route::get('requestFileDetails', [ReportController::class, 'requestFileDetails'])->name('requestFileDetails');
    Route::get('settlementFileDetails', [ReportController::class, 'settlementFileDetails'])->name('settlementFileDetails');
    Route::get('viaticRegisterDetails', [ReportController::class, 'viaticRegisterDetails'])->name('viaticRegisterDetails');
    Route::get('viaticRegisterReport', [ReportController::class, 'viaticRegisterReport'])->name('viaticRegisterReport');
    Route::get('orderRegisterDetails', [ReportController::class, 'orderRegisterDetails'])->name('orderRegisterDetails');
  });

});