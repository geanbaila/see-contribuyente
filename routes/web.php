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
Route::get('/{encargoId}', 'SaleController@writeBill');


Route::get('/venta', 'SaleController@list');
Route::get('/venta/nuevo', 'SaleController@show');
Route::get('/venta/editar/{encargoId}', 'SaleController@edit');
Route::post('/venta/registrar', 'SaleController@register');


Route::get('/manifiesto', function () {
    return view('manifest.list');

});

Route::post('/api/v1/serie/{agenciaOrigenId}/{agenciaDestinoId}/{documentoId}', 'ApiController@getSerie');
Route::post('/api/v1/agencia/{sedeId}', 'ApiController@getAgencia');
Route::post('/api/v1/encargo', 'ApiController@getEncargo');
Route::post('/api/v1/venta/comprobante/{encargoId}', 'ApiController@getComprobantePago');
