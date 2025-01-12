<?php

use App\Modules\Bad\Http\Controllers\BadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group([
    'prefix' => 'api/bads',
    // 'middleware' => ['cors'],
], function ($router) {
    Route::get('/', [BadController::class,'index']);
    Route::get('/setCheckOutContainers', [BadController::class,'setCheckOutContainers']);
    Route::post('/insertOrReplaceBad', [BadController::class,'insertOrReplaceBad']);
    Route::post('/add_or_replace_cpus_to_bad', [BadController::class,'add_or_replace_cpus_to_bad']);
});
