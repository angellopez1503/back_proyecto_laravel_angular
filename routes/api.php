<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;






Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1/auth'],function(){
    Route::post("login",[AuthController::class,"ingresar"]);
    Route::post("registro",[AuthController::class,"registrar"]);

    Route::group(['middleware' => 'auth:sanctum'],function(){
        Route::get("perfil",[AuthController::class,"perfil"]);
        Route::post("logout",[AuthController::class,"salir"]);
    });
});





// Route::group(['prefix' => "v1/auth"], function(){

//     Route::post("login", [AuthController::class, "ingresar"]);
//     Route::post("registro", [AuthController::class, "registro"]);

//     Route::group(['middleware' => 'auth:sanctum'], function(){

//         Route::get("perfil", [AuthController::class, "perfil"]);
//         Route::post("logout", [AuthController::class, "salir"]);
//     });
// });

