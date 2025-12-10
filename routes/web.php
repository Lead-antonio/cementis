<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
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
        'message' => "Le processus pour l'étape {$process->name} a démarré en arrière-plan.",
        'process_name' => $process->name
    ], 200);
})->name('process.run');

Route::post('/process/{step}/restart', function ($step) {
    $currentMonth = now()->format('Y-m');

    // Vérifier si l’étape existe
    $process = Process::findOrFail($step);

    // Récupérer la progression en erreur
    $progression = Progression::where('step_id', $step)
        ->where('month', $currentMonth)
        ->where('status', 'error')
        ->first();

    if (!$progression) {
        return response()->json([
            'message' => "Aucune progression en erreur trouvée pour l'étape {$process->name}.",
            'process_name' => $process->name
        ], 404);
    }

    // ---- RESET COMPLET de la progression en erreur ----
    $progression->update([
        'status' => 'in_progress',
    ]);

    // ---- Relancer le job ----
    RunStepScoringCommandJob::dispatch($step);

    return response()->json([
        'message' => "Redémarrage complet lancé pour l'étape {$process->name}.",
        'process_name' => $process->name
    ], 200);
})->name('process.restart');




Route::post('/notifications/read-all', function (Request $request) {
    Auth::user()->unreadNotifications->markAsRead();
    return response()->json(['success' => true]);
})->name('notifications.markAllAsRead');

Route::get('/notifications/fetch', function () {
    return response()->json([
        'count' => Auth::user()->unreadNotifications->count(),
        'notifications' => Auth::user()->unreadNotifications->map(function ($notification) {
            return [
                'message' => $notification->data['message'],
                'url' => $notification->data['url'],
            ];
        }),
    ]);
})->name('notifications.fetch');


Route::post('/notifications/read', [NotificationController::class, 'markAsRead'])
    ->name('notifications.markAsRead');
