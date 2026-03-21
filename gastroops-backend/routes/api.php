<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\OperationalUnitController;
use App\Http\Controllers\Api\V1\FixedCostController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\AssetController;
use App\Http\Controllers\Api\V1\CashFlowEntryController;
use App\Http\Controllers\Api\V1\SalesChannelController;
use App\Http\Controllers\Api\V1\UtilityBillController;
use App\Http\Controllers\Api\V1\LicenseController;
use App\Http\Controllers\Api\V1\CampaignController;

Route::prefix('v1')->group(function () {
    Route::get('/operational-units', [OperationalUnitController::class, 'index']);
    Route::post('/operational-units', [OperationalUnitController::class, 'store']);
    Route::put('/operational-units/{operationalUnit}', [OperationalUnitController::class, 'update']);
    Route::delete('/operational-units/{operationalUnit}', [OperationalUnitController::class, 'destroy']);

    Route::get('/fixed-costs/summary', [FixedCostController::class, 'summary']);
    Route::get('/fixed-costs', [FixedCostController::class, 'index']);
    Route::post('/fixed-costs', [FixedCostController::class, 'store']);
    Route::put('/fixed-costs/{fixedCost}', [FixedCostController::class, 'update']);
    Route::delete('/fixed-costs/{fixedCost}', [FixedCostController::class, 'destroy']);

    Route::get('/employees/summary', [EmployeeController::class, 'summary']);
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::put('/employees/{employee}', [EmployeeController::class, 'update']);
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy']);

    Route::get('/assets/summary', [AssetController::class, 'summary']);
    Route::get('/assets', [AssetController::class, 'index']);
    Route::post('/assets', [AssetController::class, 'store']);
    Route::put('/assets/{asset}', [AssetController::class, 'update']);
    Route::delete('/assets/{asset}', [AssetController::class, 'destroy']);

    Route::get('/cash-flow/summary', [CashFlowEntryController::class, 'summary']);
    Route::get('/cash-flow', [CashFlowEntryController::class, 'index']);
    Route::post('/cash-flow', [CashFlowEntryController::class, 'store']);
    Route::put('/cash-flow/{cashFlowEntry}', [CashFlowEntryController::class, 'update']);
    Route::delete('/cash-flow/{cashFlowEntry}', [CashFlowEntryController::class, 'destroy']);

    Route::get('/channels/summary', [SalesChannelController::class, 'summary']);
    Route::get('/channels', [SalesChannelController::class, 'index']);
    Route::post('/channels', [SalesChannelController::class, 'store']);
    Route::put('/channels/{salesChannel}', [SalesChannelController::class, 'update']);
    Route::delete('/channels/{salesChannel}', [SalesChannelController::class, 'destroy']);

    Route::get('/utility-bills/summary', [UtilityBillController::class, 'summary']);
    Route::get('/utility-bills', [UtilityBillController::class, 'index']);
    Route::post('/utility-bills', [UtilityBillController::class, 'store']);
    Route::put('/utility-bills/{utilityBill}', [UtilityBillController::class, 'update']);
    Route::delete('/utility-bills/{utilityBill}', [UtilityBillController::class, 'destroy']);

    Route::get('/licenses/summary', [LicenseController::class, 'summary']);
    Route::get('/licenses', [LicenseController::class, 'index']);
    Route::post('/licenses', [LicenseController::class, 'store']);
    Route::put('/licenses/{license}', [LicenseController::class, 'update']);
    Route::delete('/licenses/{license}', [LicenseController::class, 'destroy']);

    Route::get('/campaigns/summary', [CampaignController::class, 'summary']);
    Route::get('/campaigns', [CampaignController::class, 'index']);
    Route::post('/campaigns', [CampaignController::class, 'store']);
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update']);
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy']);
});