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
    return view('index');
});

Route::get('/inicioSesion', 'inicioController@login')->name('inicioSesion');

Route::get('/changePassword/{token}', 'inicioController@changePassword')->name('changePassword');

Route::get('/updatePassword', 'inicioController@updatePassword')->name('updatePassword');

Route::get('/ReestablecerUsuario', 'inicioController@ReestablecerUsuario')->name('ReestablecerUsuario');

Route::get('/solicitud', 'SolicitudController@index')->name('solicitud');

Route::get('/consulta', 'EditPedidoController@index');

Route::get('/editPedido/{ConsecutivoPedido}', 'EditPedidoController@index');

Route::get('/sendConsulta', 'ConsultaController@consulta')->name('sendConsulta');

Route::get('/showProductHistorial', 'ConsultaController@showProductHistorial')->name('showProductHistorial');

Route::get('/filtrarTable', 'ConsultaController@FiltrarTabla')->name('filtrarTable');

Route::get('/filtrarHistorial', 'HistorialController@FiltrarHistorial')->name('filtrarHistorial');

Route::post('/GenerarCSV', 'ConsultaController@GenerarCSV')->name('GenerarCSV');

Route::post('/CSVGeneral', 'HistorialController@CSVGeneral')->name('CSVGeneral');

Route::get('/GenerarPDF/{ConsecutivoPedido}', 'ConsultaController@GenerarPDF');

Route::post('/PDFGeneral', 'HistorialController@PDFGeneral')->name('PDFGeneral');

Route::get('/ChangeInput', 'SolicitudController@ChangeInput')->name('ChangeInput');

Route::post('/updateSolicitud', 'ConsultaController@update')->name('updateSolicitud');

Route::post('/UpdatePedido', 'EditPedidoController@UpdatePedido')->name('edit.updatePedido');

Route::resource('solicitud', 'SolicitudController');

Route::resource('consulta', 'ConsultaController');

Route::resource('editPedido', 'EditPedidoController');
