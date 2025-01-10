<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;

Route::get('/', [
    App\Http\Controllers\DashboardController::class, 'index'
])->name('dashboard');

/* Routes ajoutés */
/* Route Parametre importation */
Route::post('fileUploads/file-read', [App\Http\Controllers\FileUploadController::class, 'read'])->name('fileUploads.read');
Route::get('fileUploads/parametre', [App\Http\Controllers\FileUploadController::class, 'parametre'])->name('fileUploads.parametre');
Route::get('fileUploads/get-fillable-fields/{model}', [App\Http\Controllers\FileUploadController::class, 'getFillableFields'])->name('fileUploads.getFillableFields');
Route::get('fileUploads/get-models/{model}', [App\Http\Controllers\FileUploadController::class, 'getModels'])->name('fileUploads.getModels');
Route::get('fileUploads/get-associations/{modelId}', [App\Http\Controllers\FileUploadController::class, 'getAssociations'])->name('fileUploads.getAssociations');
/* ----------------------------------------------------------------------------------------------------------------------------- */

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



Route::resource('penalites', App\Http\Controllers\PenaliteController::class);

Route::resource('importExcels', App\Http\Controllers\ImportExcelController::class);

Route::get('/import-affichage', 'App\Http\Controllers\ImportExcelController@affichage_import')->name('import.affichage');

Route::post('/import-excel', 'App\Http\Controllers\ImportExcelController@import_excel')->name('import.excel');

Route::post('import/driver/excel', 'App\Http\Controllers\ChauffeurController@import_driver_excel')->name('import.driver.excel');

Route::get('/import-liste', 'App\Http\Controllers\ImportExcelController@liste_importation')->name('import.liste');

Route::get('import-excels/detail/{id}', 'App\Http\Controllers\ImportExcelController@detail_liste_importation')->name('import_excels.detail_liste_importation');

Route::resource('importcalendars', App\Http\Controllers\ImportcalendarController::class);

Route::get('/import-installation-affichage', 'App\Http\Controllers\ImportInstallationController@affichageImportation')->name('import.installation.affichage');

Route::post('/import-installation', 'App\Http\Controllers\ImportInstallationController@import_data_installation')->name('import.installation.store');

Route::get('import-excels/installation/{id}', 'App\Http\Controllers\ImportInstallationController@liste_importation')->name('import_excels.installation');

Route::get('/exportation-generale', 'App\Http\Controllers\ExportController@exportation_excel')->name('exportation.view');
Route::post('/getcolumns', 'App\Http\Controllers\ExportController@getTableColumns')->name('exportation.getcolumns');
Route::post('/exportation-table', 'App\Http\Controllers\ExportController@exportTable')->name('exportation.getexport');


Route::resource('chauffeurs', App\Http\Controllers\ChauffeurController::class);

Route::post('chauffeur/updatetransporteur', 'App\Http\Controllers\ChauffeurController@update_tranporteur_id')->name('chauffeur.updatetransporteur');

Route::post('chauffeur/filtre', 'App\Http\Controllers\TransporteurController@filterChauffeurs')->name('chauffeur.filtre');


Route::resource('penaliteChauffeurs', App\Http\Controllers\PenaliteChauffeurController::class);

Route::get('/scoring/{chauffeur}', 'App\Http\Controllers\ImportExcelController@associateEventWithCalendar')->name('scoring.monthly');

Route::get('events/scoring', 'App\Http\Controllers\EventController@viewScoring')->name('events.scoring');

Route::get('/event/routes', 'App\Http\Controllers\EventController@getRoutes')->name('event.routes');

Route::get('/event/exportscoring/{chauffeur}/{id_planning}', 'App\Http\Controllers\EventController@exportScoring')->name('event.exportscoring');

Route::get('/event/exportscoringcard/{planning?}', 'App\Http\Controllers\EventController@exportscoringcard')->name('event.exportscoringcard');

Route::post('/save-comments', [App\Http\Controllers\EventController::class, 'saveComments'])->name('save.comments');

Route::get('/new/scoring', 'App\Http\Controllers\EventController@newscoring')->name('new.scoring');

Route::get('/ajax/scoring', 'App\Http\Controllers\EventController@ajaxHandle')->name('ajax.scoring');


Route::get('/events/table/scoring/{chauffeur}/{id_planning}', 'App\Http\Controllers\EventController@TableauScoring')->name('events.table.scoring');

Route::get('/scoring/pdf', 'App\Http\Controllers\EventController@TableauScoringPdf')->name('scoring.pdf');

Route::resource('events', App\Http\Controllers\EventController::class);


Route::resource('transporteurs', App\Http\Controllers\TransporteurController::class);


Route::resource('groupeEvents', App\Http\Controllers\GroupeEventController::class);

Route::resource('vehicules', App\Http\Controllers\VehiculeController::class);

Route::resource('infractions', App\Http\Controllers\InfractionController::class);


Route::resource('scorings', App\Http\Controllers\ScoringController::class);


Route::resource('installateurs', App\Http\Controllers\InstallateurController::class);


Route::resource('installations', App\Http\Controllers\InstallationController::class);


Route::resource('importInstallations', App\Http\Controllers\ImportInstallationController::class);


Route::resource('importNameInstallations', App\Http\Controllers\ImportNameInstallationController::class);


Route::resource('importInstallationErrors', App\Http\Controllers\ImportInstallationErrorController::class);


Route::resource('movements', App\Http\Controllers\MovementController::class);

Route::resource('importModels', App\Http\Controllers\ImportModelController::class);
