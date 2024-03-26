<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;

Route::get('/', [
    App\Http\Controllers\DashboardController::class, 'index'
])->name('dashboard');

Route::resource('permissions', App\Http\Controllers\PermissionController::class);
Route::post('permissions/loadFromRouter', [App\Http\Controllers\PermissionController::class, 'LoadPermission'])->name('permissions.load-router');

Route::resource('roles', App\Http\Controllers\RoleController::class);

Route::get('profile', [App\Http\Controllers\UserController::class, 'showProfile'])->name('users.profile');
Route::patch('profile', [App\Http\Controllers\UserController::class, 'updateProfile'])->name('users.updateProfile');

Route::resource('users', App\Http\Controllers\UserController::class);


Route::resource('attendances', App\Http\Controllers\AttendanceController::class);

Route::get('generator_builder', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@builder')->name('generator_builder.index');

Route::get('field_template', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@fieldTemplate')->name('generator_builder.field_template');

Route::get('relation_field_template', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@relationFieldTemplate')->name('generator_builder.relation_field_template');

Route::post('generator_builder/generate', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@generate')->name('generator_builder.generate');

Route::post('generator_builder/rollback', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@rollback')->name('generator_builder.rollback');

Route::post(
    'generator_builder/generate-from-file',
    '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@generateFromFile'
)->name('generator_builder.from_file');


Route::resource('fileUploads', App\Http\Controllers\FileUploadController::class);



Route::resource('messages', App\Http\Controllers\MessageController::class);


Route::resource('rotations', App\Http\Controllers\RotationController::class);



Route::resource('parametres', App\Http\Controllers\ParametreController::class);


Route::resource('fichierExcels', App\Http\Controllers\FichierExcelController::class);

Route::post('/import-excel', 'App\Http\Controllers\FichierExcelController@import_excel')->name('import.excel');

Route::get('/import-affichage', 'App\Http\Controllers\FichierExcelController@affichage_import')->name('import.affichage');


Route::resource('fichierExcels', App\Http\Controllers\FichierExcelController::class);


Route::resource('fichierExcels', App\Http\Controllers\FichierExcelController::class);


Route::resource('fichierExcels', App\Http\Controllers\FichierExcelController::class);


Route::resource('fichierExcels', App\Http\Controllers\FichierExcelController::class);
