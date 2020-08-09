<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'HomeController@getIndex')->name('index');
Route::get('/home', 'HomeController@getIndex')->name('home');
Route::get('/configuracion', 'HomeController@getConfiguracion')->name('configuracion');
Route::get('/consulta', 'HomeController@getConsulta')->name('consulta');
Route::get('/exportar', 'HomeController@getExportar')->name('exportar');

Route::post('/grabar-configuracion', 'HomeController@postGrabarConfiguracion')->name('configuracion.grabar');
