<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RotationController;
use App\Http\Controllers\WebhookController;
use App\Jobs\RunStepScoringCommandJob;
use App\Models\Progression;
use App\Models\Process;

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


Route::post('/webhook-endpoint', [WebhookController::class, 'handle']);

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);
Auth::routes();

Route::get('/', function () {
    return redirect(route('dashboard'));
})->name('home');

Route::get('/checkOnline', function (App\Repositories\AttendanceRepository $attendanceRepo) {
    if (Auth::check()) { }
    return $attendanceRepo->CountUserOnline();
})->name('checkOnline');


Route::get('/get/data/api', 'App\Http\Controllers\RotationController@getDataFromApi')->name('get.data.api');

Route::get('/get-rotations/{vehicle}', 'App\Http\Controllers\RotationController@getRotationDurations')->name('rotations.by.vehicle');

Route::get('/show/map/{latitude}/{longitude}', 'App\Http\Controllers\EventController@showMap')->name('show.map');

Route::post('/process/{step}/run', function ($step) {
    $currentMonth = now()->format('Y-m');

    // Vérifier si l'étape existe
    $process = Process::findOrFail($step);

    // Vérifier ou initialiser la progression
    $progression = Progression::firstOrCreate(
        [
            'step_id' => $step,
            'month' => $currentMonth,
        ],
        [
            'status' => "in_progress",
        ]
    );

    
    // Lancer le job en arrière-plan
    RunStepScoringCommandJob::dispatch($step);

    // Retourner une réponse immédiate
    return response()->json([
        'message' => "Le processus pour l'étape {$step} a démarré en arrière-plan."
    ], 200);
})->name('process.run');
