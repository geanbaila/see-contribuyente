<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/venta', 'SaleController@show');
Route::post('/venta/registrar', 'SaleController@register');


Route::get('/manifiesto', function () {
    return view('manifest.list');

});

Route::post('/api/v1/serie/{agenciaId}/{documentoId}', function ($agenciaId, $documentoId) {
    $serie = \App\Business\Serie::where('agencia_id', new MongoDB\BSON\ObjectId("$agenciaId"))
    ->where('documento_id', new MongoDB\BSON\ObjectId("$documentoId"))
    ->get();
    return response()->json($serie);
});

Route::post('/api/v1/agencia/{sedeId}', function ($sedeId) {
    $agencia = \App\Business\Agencia::where('sede_id', new MongoDB\BSON\ObjectId("$sedeId"))->get();
    return response()->json($agencia);
});
