<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//llamamos a nuestro controlador (lo ultimo es el nombre del controlador)
use App\Http\Controllers\authController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Password; 

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//vamos a generar la ruta

Route::controller(authController::Class)->group(function(){
// momentos: get,post,put,delete
//nombre de la ruta ('/register')--nombre de la funcion('register')
    Route::post('/register','register');
    Route::post('/login', 'login');

});

Route::middleware('auth:sanctum')->delete('/logout', [AuthController::class, 'logout']);
Route::get('/users/show/{id}', [UserController::class,'showById']);



// aqui es donde vamos a hacer la ruta para consumir el servicio

//auth:sanctum  indica si estamos auntenticados 