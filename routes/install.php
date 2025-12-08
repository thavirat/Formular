<?php

Route::middleware('authAdmin:admin')->group(function () {

Route::resource('/', Install\InstallController::class);
Route::get('/getField', 'Install\InstallController@getField');
Route::get('/getModel', 'Install\InstallController@getModel');
Route::get('/GetFieldFromModel', 'Install\InstallController@getFieldFromModel');
Route::get('/foo', 'Install\InstallController@foo');

});


?>