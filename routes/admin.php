<?php

Route::get('login', 'Admin\LoginController@index')->name('login');
Route::post('CheckLogin', 'Admin\LoginController@CheckLogin');

Route::middleware('authAdmin:admin')->group(function () {
    Route::resource('/', 'Admin\HomeController');

    Route::resource('Dashboard', 'Admin\HomeController');

    Route::post('DumpSQL/CheckPassword', 'Admin\DumpSQLController@CheckPassword');
    Route::resource('DumpSQL', 'Admin\DumpSQLController');

    Route::get('Menu/Lists', 'Admin\MenuController@lists');
    Route::resource('Menu', 'Admin\MenuController');

    Route::resource('Profile', 'Admin\ProfileController');

    // Route::get('AdminUser/Lists', 'Admin\AdminUserController@lists');
    // Route::patch('AdminUser/{id}/password', 'Admin\AdminUserController@password');
    // Route::resource('AdminUser', 'Admin\AdminUserController');

    Route::resource('Setting', 'Admin\SettingController');

    Route::get('AdminUser/Lists', 'Admin\AdminUserController@lists');
    Route::get('AdminUser/ExportPDF', 'Admin\AdminUserController@exportPDF');
    Route::get('AdminUser/ExportExcel', 'Admin\AdminUserController@exportExcel');
    Route::get('AdminUser/ExportPrint', 'Admin\AdminUserController@exportPrint');
    Route::get('AdminUser/{id}/permission', 'Admin\AdminUserController@showPermission');
    Route::put('AdminUser/{id}/permission', 'Admin\AdminUserController@updatePermission');
    Route::patch('AdminUser/{id}/password', 'Admin\AdminUserController@password');
    Route::resource('AdminUser', 'Admin\AdminUserController');

    Route::get('Permission/Lists', 'Admin\PermissionController@lists');
    Route::resource('Permission', 'Admin\PermissionController');

    Route::resource('SettingSystem', 'Admin\SettingSystemController');

    Route::get('Logout', 'Admin\LoginController@logout');

    Route::get('PermissionDenined', 'Admin\HomeController@permissionDenined');

    // file
    Route::post('/upload_file', 'Admin\UploadFileController@index');
    Route::post('/checkUploadFile', 'Admin\UploadFileController@store');

    ##FOR##REPLACE##INSTALL##
});
?>
