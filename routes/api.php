<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FileUploadController;
use App\Http\Controllers\Api\DocumentTypeController;
use App\Http\Controllers\Api\DocumentSiafController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PersonController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ParameterController;
use App\Http\Controllers\Api\RequestFileController;
use App\Http\Controllers\Api\FinancialClassifierController;
use App\Http\Controllers\Api\CashRegisterController;
use App\Http\Controllers\Api\CashRegisterDetailController;
use App\Http\Controllers\Api\OrderRegisterController;
use App\Http\Controllers\Api\OrderRegisterDetailController;
use App\Http\Controllers\Api\ViaticRegisterController;
use App\Http\Controllers\Api\ViaticRegisterDetailController;
use App\Http\Controllers\Api\SettlementController;
use App\Http\Controllers\Api\OfficeController;
use App\Http\Controllers\Api\TypeAssetController;
use App\Http\Controllers\Api\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('DocumentSiaf/SearchSupplierByRuc', [DocumentSiafController::class, 'SearchSupplierByRuc']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::resources(['documentTypes' => DocumentTypeController::class]);
    Route::post('fileUpload/uploadExcelSiaf', [FileUploadController::class, 'uploadExcelSiaf']);
    Route::get('DocumentSiaf/SearchDocumentBySiaf', [DocumentSiafController::class, 'SearchDocumentBySiaf']);
    Route::get('DocumentSiaf/exportExcel', [DocumentSiafController::class, 'exportExcel']);
    Route::get('DocumentSiaf/exportExcelPending', [DocumentSiafController::class, 'exportExcelPending']);
    Route::get('DocumentSiaf/exportTxtPle', [DocumentSiafController::class, 'exportTxtPle']);
    Route::get('DocumentSiaf/exportTxtPlameDetail', [DocumentSiafController::class, 'exportTxtPlameDetail']);
    Route::get('DocumentSiaf/exportTxtPlameProvidersName', [DocumentSiafController::class, 'exportTxtPlameProvidersName']);
    Route::put('DocumentSiaf/closeSiafsByMonth', [DocumentSiafController::class, 'closeSiafsByMonth']);
    Route::get('parameters/GetParameterByClass', [ParameterController::class, 'GetParameterByClass']);
    Route::get('requestFile/searchRequestById', [RequestFileController::class, 'searchRequestById']);
    Route::put('requestFile/updateApproval', [RequestFileController::class, 'updateApproval']);
    Route::get('requestFile/searchRequestByCorrelativeAndRequestTypeAndYear', [RequestFileController::class, 'searchRequestByCorrelativeAndRequestTypeAndYear']);
    Route::get('settlement/getSettlementById', [SettlementController::class, 'getSettlementById']);
    Route::get('settlement/getSettlementByCorrelativeAndRequestTypeAndYear', [SettlementController::class, 'getSettlementByCorrelativeAndRequestTypeAndYear']);
    Route::put('settlement/updateApproval', [SettlementController::class, 'updateApproval']);
    Route::get('financialClassifier/search', [FinancialClassifierController::class, 'search']);
    Route::put('cashRegister/close', [CashRegisterController::class, 'close']);
    Route::get('cashRegisterDetail/listByCashRegisterId', [CashRegisterDetailController::class, 'listByCashRegisterId']);
    Route::put('orderRegister/close', [OrderRegisterController::class, 'close']);
    Route::get('orderRegisterDetail/listByOrderRegisterId', [OrderRegisterDetailController::class, 'listByOrderRegisterId']);
    Route::get('viaticRegisterDetail/listByViaticRegisterId', [ViaticRegisterDetailController::class, 'listByViaticRegisterId']);
    Route::put('viaticRegister/affidavit', [ViaticRegisterController::class, 'affidavit']);
    Route::put('viaticRegister/registerServiceComissionReport', [ViaticRegisterController::class, 'registerServiceComissionReport']);
    Route::put('viaticRegister/close', [ViaticRegisterController::class, 'close']);
    Route::get('person/searchByDni', [PersonController::class, 'searchByDni']);
    Route::get('reports/cashRegisterDetails', [ReportController::class, 'cashRegisterDetails']);
    Route::get('reports/requestFileDetails', [ReportController::class, 'requestFileDetails']);
    Route::get('reports/settlementFileDetails', [ReportController::class, 'settlementFileDetails']);
    Route::get('reports/viaticRegisterDetails', [ReportController::class, 'viaticRegisterDetails']);
    Route::get('reports/viaticRegisterReport', [ReportController::class, 'viaticRegisterReport']);
    Route::get('reports/orderRegisterDetails', [ReportController::class, 'orderRegisterDetails']);
    

    Route::resources(['DocumentSiaf' => DocumentSiafController::class]);
    Route::resources(['roles' => RoleController::class]);
    Route::resources(['personsa' => PersonController::class]);
    Route::resources(['users' => UserController::class]);
    Route::resources(['requestFile' => RequestFileController::class]);
    Route::resources(['settlement' => SettlementController::class]);
    Route::resources(['viaticRegister' => ViaticRegisterController::class]);
    Route::resources(['viaticRegisterDetail' => ViaticRegisterDetailController::class]);
    Route::resources(['orderRegister' => OrderRegisterController::class]);
    Route::resources(['orderRegisterDetail' => OrderRegisterDetailController::class]);
    Route::resources(['cashRegister' => CashRegisterController::class]);
    Route::resources(['cashRegisterDetail' => CashRegisterDetailController::class]);
    Route::resources(['offices' => OfficeController::class]);
    Route::resources(['typeAsset' => TypeAssetController::class]);
    Route::resources(['financialClassifier' => FinancialClassifierController::class]);
});
