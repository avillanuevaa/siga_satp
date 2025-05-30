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
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->secure(route('dashboard.index'));
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

/*
|--------------------------------------------------------------------------
| Configuración
|--------------------------------------------------------------------------
*/
Route::prefix('configuracion')->group(function () {

    Route::get('clasificadores/search', [FinancialClassifierController::class, 'search'])->name('financialClassifiers.search');

    Route::resource('clasificadores', FinancialClassifierController::class)
        ->except(['show'])
        ->parameters(['clasificadores' => 'financialClassifier'])
        ->names('financialClassifiers');

    Route::resource('trabajadores', PersonController::class)
        ->except(['show'])
        ->parameters(['trabajadores' => 'person'])
        ->names('persons');

    Route::get('trabajadores/searchByDni', [PersonController::class, 'searchByDni'])->name('persons.searchByDni');

    Route::get('trabajadores/searchById', [PersonController::class, 'searchById'])->name('persons.searchById');

    Route::get('trabajadores/search', [PersonController::class, 'search'])->name('persons.search');

    Route::get('oficinas/getOfficeAndParent', [OfficeController::class, 'getOfficeAndParent'])->name('offices.getOfficeAndParent');

    Route::get('activos/getListByClassifierCode', [TypeAssetController::class, 'getListByClassifierCode'])->name('assets.getListByClassifierCode');
});

/*
|--------------------------------------------------------------------------
| Contabilidad
|--------------------------------------------------------------------------
*/
Route::prefix('contabilidad')->group(function () {

    Route::resource('registro-siaf', DocumentSiafController::class)
        ->except(['show'])
        ->parameters(['registro-siaf' => 'documentSiaf'])
        ->names('documentSiafs');

    Route::prefix('registro-siaf')->group(function () {
        Route::get('SearchSupplierByRuc', [DocumentSiafController::class, 'SearchSupplierByRuc'])->name('documentSiafs.SearchSupplierByRuc');
        Route::get('import-excel', [DocumentSiafController::class, 'importExcel'])->name('documentSiafs.importExcel');
        Route::post('upload-excel', [DocumentSiafController::class, 'uploadExcel'])->name('documentSiafs.uploadExcel');
        Route::get('exportar-cierre', [DocumentSiafController::class, 'exportClose'])->name('documentSiafs.exportClose');
        Route::get('exportar-excel', [DocumentSiafController::class, 'exportExcel'])->name('documentSiafs.exportExcel');
        Route::get('exportar-excel-pendientes', [DocumentSiafController::class, 'exportExcelPending'])->name('documentSiafs.exportExcelPending');
        Route::get('exportar-txt-ple', [DocumentSiafController::class, 'exportTxtPle'])->name('documentSiafs.exportTxtPle');
        Route::get('exportar-txt-plame-detalle', [DocumentSiafController::class, 'exportTxtPlameDetail'])->name('documentSiafs.exportTxtPlameDetail');
        Route::get('exportar-txt-plame-proveedores', [DocumentSiafController::class, 'exportTxtPlameProvidersName'])->name('documentSiafs.exportTxtPlameProvidersName');
        Route::post('cerrar-siaf-por-mes', [DocumentSiafController::class, 'closeSiafsByMonth'])->name('documentSiafs.closeSiafsByMonth');
    });
});

/*
|--------------------------------------------------------------------------
| Rendiciones
|--------------------------------------------------------------------------
*/
Route::prefix('rendiciones')->group(function () {

    // Solicitudes
    Route::resource('solicitud', RequestFileController::class)
        ->except(['show'])
        ->parameters(['solicitud' => 'requestFile'])
        ->names('requestFiles');

    Route::post('solicitud/{requestFile}/updateApproval', [RequestFileController::class, 'updateApproval'])->name('requestFiles.updateApproval');
    Route::get('solicitud/searchRequestByCorrelativeAndRequestTypeAndYear', [RequestFileController::class, 'searchRequestByCorrelativeAndRequestTypeAndYear'])->name('requestFiles.searchRequestByCorrelativeAndRequestTypeAndYear');

    // Liquidaciones
    Route::resource('liquidacion', SettlementController::class)
        ->except(['show'])
        ->parameters(['liquidacion' => 'settlement'])
        ->names('settlements');

    Route::post('liquidacion/{settlement}/updateApproval', [SettlementController::class, 'updateApproval'])->name('settlements.updateApproval');

    // Caja chica
    Route::resource('caja-chica', CashRegisterController::class)
        ->only(['index', 'create', 'store', 'show'])
        ->parameters(['caja-chica' => 'cashRegister'])
        ->names('cashRegisters');

    Route::post('caja-chica/cerrar', [CashRegisterController::class, 'close'])->name('cashRegisters.close');

    Route::resource('caja-chica.detalle', CashRegisterDetailController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->parameters(['caja-chica' => 'cashRegister', 'detalle' => 'cashRegisterDetail'])
        ->names('cashRegisterDetails');

    // Encargos
    Route::resource('encargos', OrderRegisterController::class)
        ->only(['index', 'create', 'store', 'show'])
        ->parameters(['encargos' => 'orderRegister'])
        ->names('orderRegisters');

    Route::post('encargos/cerrar', [OrderRegisterController::class, 'close'])->name('orderRegisters.close');

    Route::resource('encargos.detalle', OrderRegisterDetailController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->parameters(['encargos' => 'orderRegister', 'detalle' => 'orderRegisterDetail'])
        ->names('orderRegisterDetails');

    // Viáticos
    Route::resource('viaticos', ViaticRegisterController::class)
        ->only(['index', 'create', 'store', 'update', 'show'])
        ->parameters(['viaticos' => 'viaticRegister'])
        ->names('viaticRegisters');

    Route::post('viaticos/cerrar', [ViaticRegisterController::class, 'close'])->name('viaticRegisters.close');

    Route::resource('viaticos.detalle', ViaticRegisterDetailController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->parameters(['viaticos' => 'viaticRegister', 'detalle' => 'viaticRegisterDetail'])
        ->names('viaticRegisterDetails');
});

/*
|--------------------------------------------------------------------------
| Seguridad - Usuarios
|--------------------------------------------------------------------------
*/
Route::prefix('security')->name('users.')->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('index');
    Route::post('users', [UserController::class, 'store'])->name('store');
    Route::get('users/create', [UserController::class, 'create'])->name('create');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('update');
    Route::get('users/{user}', [UserController::class, 'show'])->name('show');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('destroy');
    Route::get('users-export-print', [UserController::class, 'exportPrint'])->name('exportPrint');
    Route::get('users-copy', [UserController::class, 'exportCopy'])->name('exportCopy');
    Route::get('users-export-excel', [UserController::class, 'exportExcel'])->name('exportExcel');
    Route::get('users-export-csv', [UserController::class, 'exportCsv'])->name('exportCsv');
    Route::get('users-export-pdf', [UserController::class, 'exportPdf'])->name('exportPdf');
});

/*
|--------------------------------------------------------------------------
| Reportes
|--------------------------------------------------------------------------
*/
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('cashRegisterDetails', [ReportController::class, 'cashRegisterDetails'])->name('cashRegisterDetails');
    Route::get('requestFileDetails', [ReportController::class, 'requestFileDetails'])->name('requestFileDetails');
    Route::get('settlementFileDetails', [ReportController::class, 'settlementFileDetails'])->name('settlementFileDetails');
    Route::get('viaticRegisterDetails', [ReportController::class, 'viaticRegisterDetails'])->name('viaticRegisterDetails');
    Route::get('viaticRegisterReport', [ReportController::class, 'viaticRegisterReport'])->name('viaticRegisterReport');
    Route::get('orderRegisterDetails', [ReportController::class, 'orderRegisterDetails'])->name('orderRegisterDetails');
});
