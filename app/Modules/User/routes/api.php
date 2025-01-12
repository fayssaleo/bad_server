<?php

use App\Modules\User\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group([
    'prefix' => 'api/users',
    // 'middleware' => ['cors'],
], function ($router) {
    Route::post('/register', [UserController::class,'register']);
});
