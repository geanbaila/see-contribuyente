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

Route::get('/venta', 'SaleController@list');
Route::get('/venta/nuevo', 'SaleController@show');
Route::get('/venta/editar/{encargo_id}', 'SaleController@edit');
Route::post('/venta/registrar', 'SaleController@register');

Route::get('/manifiesto', 'ManifestController@list');

Route::get('/despacho', 'DispatchController@list');

Route::get('/configuracion', 'ConfigurationController@list');
Route::post('/configuracion/salida', 'ConfigurationController@update');

Route::post('/api/v1/serie/{agencia_origen_id}/{agencia_destino_id}/{documento_id}', 'ApiController@getSerie');
Route::post('/api/v1/agencia/{sedeId}', 'ApiController@getAgencia');
Route::post('/api/v1/encargo', 'ApiController@getEncargo');
Route::post('/api/v1/sunat/{ruc}', 'ApiController@getSunat');
Route::post('/api/v1/reniec/{dni}', 'ApiController@getReniec');

Route::get('/api/v1/download/pdf/{encargo_id}', 'ApiController@downloadPdf');
Route::get('/api/v1/download/xml/{encargo_id}', 'ApiController@downloadXml');
Route::get('/api/v1/download/cdr/{encargo_id}', 'ApiController@downloadCdr');

Route::post('/api/v1/despacho/{encargo_id}', 'ApiController@despacho');
