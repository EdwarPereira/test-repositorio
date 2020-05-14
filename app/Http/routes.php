<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/


Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/','IndexController@index');

    Route::get('/home', 'HomeController@index');
    Route::get('/home/es', 'HomeController@es');
    Route::get('/home/pt_BR', 'HomeController@pt_BR');

    Route::resource('/setor', 'DepartmentController');

    Route::resource('/protocolo', 'TicketController');

    Route::resource('protocolo.concluir', 'CloseTicketController');
    Route::resource('protocolo.timeline', 'TicketTimelineController');

    Route::resource('protocolo.setor', 'TicketDepartmentController');

    Route::resource('contato', 'ContactController');

    Route::resource('/cliente', 'CustomerController');
    Route::resource('/placa', 'VehicleController');

    Route::resource('/relatorio', 'ReportController');
    Route::resource('/setorusuario', 'UserDepartmentController');
});
