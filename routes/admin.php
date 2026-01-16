<?php

Route::get('login', 'Admin\LoginController@index')->name('login');
Route::post('CheckLogin', 'Admin\LoginController@CheckLogin');

Route::middleware('authAdmin:admin')->group(function () {
    $langs = ['', 'th', 'en', 'ch'];
    foreach ($langs as $lang) {
        Route::prefix($lang)->group(function () {
                Route::resource('/', 'Admin\HomeController');
                Route::resource('Dashboard', 'Admin\HomeController');
                Route::post('DumpSQL/CheckPassword', 'Admin\DumpSQLController@CheckPassword');
                Route::resource('DumpSQL', 'Admin\DumpSQLController');
                Route::get('Menu/Lists', 'Admin\MenuController@lists');
                Route::resource('Menu', 'Admin\MenuController');
                Route::resource('Profile', 'Admin\ProfileController');
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

                Route::post('/ProductCategory/Lists', 'Admin\ProductCategoryController@lists');
                Route::get('/ProductCategory/ExportPDF', 'Admin\ProductCategoryController@export_pdf');
                Route::get('/ProductCategory/ExportExcel', 'Admin\ProductCategoryController@export_excel');
                Route::get('/ProductCategory/ExportPrint', 'Admin\ProductCategoryController@export_print');
                Route::resource('/ProductCategory', Admin\ProductCategoryController::class);

                Route::post('/BrandProduct/Lists', 'Admin\BrandProductController@lists');
                Route::get('/BrandProduct/ExportPDF', 'Admin\BrandProductController@export_pdf');
                Route::get('/BrandProduct/ExportExcel', 'Admin\BrandProductController@export_excel');
                Route::get('/BrandProduct/ExportPrint', 'Admin\BrandProductController@export_print');
                Route::resource('/BrandProduct', Admin\BrandProductController::class);

                Route::post('/DesignProduct/Lists', 'Admin\DesignProductController@lists');
                Route::get('/DesignProduct/ExportPDF', 'Admin\DesignProductController@export_pdf');
                Route::get('/DesignProduct/ExportExcel', 'Admin\DesignProductController@export_excel');
                Route::get('/DesignProduct/ExportPrint', 'Admin\DesignProductController@export_print');
                Route::resource('/DesignProduct', Admin\DesignProductController::class);

                Route::post('/UnitProduct/Lists', 'Admin\UnitProductController@lists');
                Route::get('/UnitProduct/ExportPDF', 'Admin\UnitProductController@export_pdf');
                Route::get('/UnitProduct/ExportExcel', 'Admin\UnitProductController@export_excel');
                Route::get('/UnitProduct/ExportPrint', 'Admin\UnitProductController@export_print');
                Route::resource('/UnitProduct', Admin\UnitProductController::class);

                Route::post('/Product/Lists', 'Admin\ProductController@lists');
                Route::get('/Product/Search', 'Admin\ProductController@Search');
                Route::get('/Product/ExportPDF', 'Admin\ProductController@export_pdf');
                Route::get('/Product/ExportExcel', 'Admin\ProductController@export_excel');
                Route::get('/Product/ExportPrint', 'Admin\ProductController@export_print');
                Route::resource('/Product', Admin\ProductController::class);

                Route::post('/Currency/Lists', 'Admin\CurrencyController@lists');
                Route::get('/Currency/ExportPDF', 'Admin\CurrencyController@export_pdf');
                Route::get('/Currency/ExportExcel', 'Admin\CurrencyController@export_excel');
                Route::get('/Currency/ExportPrint', 'Admin\CurrencyController@export_print');
                Route::resource('/Currency', Admin\CurrencyController::class);

                Route::post('/Customer/Lists', 'Admin\CustomerController@lists');
                Route::get('/Customer/ExportPDF', 'Admin\CustomerController@export_pdf');
                Route::get('/Customer/ExportExcel', 'Admin\CustomerController@export_excel');
                Route::get('/Customer/ExportPrint', 'Admin\CustomerController@export_print');
                Route::resource('/Customer', Admin\CustomerController::class);

                Route::post('/QuotationStatus/Lists', 'Admin\QuotationStatusController@lists');
                Route::get('/QuotationStatus/ExportPDF', 'Admin\QuotationStatusController@export_pdf');
                Route::get('/QuotationStatus/ExportExcel', 'Admin\QuotationStatusController@export_excel');
                Route::get('/QuotationStatus/ExportPrint', 'Admin\QuotationStatusController@export_print');
                Route::resource('/QuotationStatus', Admin\QuotationStatusController::class);

                Route::post('/Incoterm/Lists', 'Admin\IncotermController@lists');
                Route::get('/Incoterm/ExportPDF', 'Admin\IncotermController@export_pdf');
                Route::get('/Incoterm/ExportExcel', 'Admin\IncotermController@export_excel');
                Route::get('/Incoterm/ExportPrint', 'Admin\IncotermController@export_print');
                Route::resource('/Incoterm', Admin\IncotermController::class);

                Route::post('/CreditPayment/Lists', 'Admin\CreditPaymentController@lists');
                Route::get('/CreditPayment/ExportPDF', 'Admin\CreditPaymentController@export_pdf');
                Route::get('/CreditPayment/ExportExcel', 'Admin\CreditPaymentController@export_excel');
                Route::get('/CreditPayment/ExportPrint', 'Admin\CreditPaymentController@export_print');
                Route::resource('/CreditPayment', Admin\CreditPaymentController::class);

                Route::post('/Quotation/Lists', 'Admin\QuotationController@lists');
                Route::get('/Quotation/ExportPDF', 'Admin\QuotationController@export_pdf');
                Route::get('/Quotation/{id}/pdf', 'Admin\QuotationController@view_pdf');
                Route::get('/Quotation/ExportExcel', 'Admin\QuotationController@export_excel');
                Route::get('/Quotation/ExportPrint', 'Admin\QuotationController@export_print');
                Route::resource('/Quotation', Admin\QuotationController::class);

                Route::post('/CustomerLevel/Lists', 'Admin\CustomerLevelController@lists');
                Route::get('/CustomerLevel/Product', 'Admin\CustomerLevelController@product');
                Route::get('/CustomerLevel/ExportPDF', 'Admin\CustomerLevelController@export_pdf');
                Route::get('/CustomerLevel/ExportExcel', 'Admin\CustomerLevelController@export_excel');
                Route::get('/CustomerLevel/ExportPrint', 'Admin\CustomerLevelController@export_print');
                Route::resource('/CustomerLevel', Admin\CustomerLevelController::class);

                Route::post('/AdminDepartment/Lists', 'Admin\AdminDepartmentController@lists');
    Route::get('/AdminDepartment/ExportPDF', 'Admin\AdminDepartmentController@export_pdf');
    Route::get('/AdminDepartment/ExportExcel', 'Admin\AdminDepartmentController@export_excel');
    Route::get('/AdminDepartment/ExportPrint', 'Admin\AdminDepartmentController@export_print');
    Route::resource('/AdminDepartment', Admin\AdminDepartmentController::class);

    ##FOR##REPLACE##INSTALL##
        });
    }



});
?>
