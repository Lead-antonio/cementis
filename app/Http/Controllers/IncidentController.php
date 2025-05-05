<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Flash;
use Response;

class IncidentController extends AppBaseController
{
    /**
     * Display a listing of the User.
     *
     * @param UserDataTable $userDataTable
     * @return Response
     */
    public function index()
    {
        $positions = [
            ['heure' => '15:00:24', 'lat' => -19.640238, 'long' => 47.314418, 'vitesse' => 0],
            ['heure' => '15:01:04', 'lat' => -19.640238, 'long' => 47.314418, 'vitesse' => 0],
            ['heure' => '15:01:44', 'lat' => -19.640191, 'long' => 47.314467, 'vitesse' => 7],
            ['heure' => '15:01:54', 'lat' => -19.639984, 'long' => 47.314711, 'vitesse' => 15],
            ['heure' => '15:02:04', 'lat' => -19.639762, 'long' => 47.314951, 'vitesse' => 15],
            ['heure' => '15:02:14', 'lat' => -19.63932,  'long' => 47.315284, 'vitesse' => 25],
            ['heure' => '15:02:24', 'lat' => -19.638784, 'long' => 47.315818, 'vitesse' => 31],
            ['heure' => '15:02:34', 'lat' => -19.63822,  'long' => 47.316462, 'vitesse' => 33],
            ['heure' => '15:02:44', 'lat' => -19.637673, 'long' => 47.317098, 'vitesse' => 33],
            ['heure' => '15:02:54', 'lat' => -19.637164, 'long' => 47.317698, 'vitesse' => 27],
            ['heure' => '15:03:04', 'lat' => -19.636869, 'long' => 47.318036, 'vitesse' => 9],
            ['heure' => '15:03:14', 'lat' => -19.636618, 'long' => 47.318253, 'vitesse' => 16],
            ['heure' => '15:03:24', 'lat' => -19.636198, 'long' => 47.318467, 'vitesse' => 22],
            ['heure' => '15:03:34', 'lat' => -19.635616, 'long' => 47.318742, 'vitesse' => 24],
            ['heure' => '15:03:44', 'lat' => -19.635164, 'long' => 47.318956, 'vitesse' => 14],
            ['heure' => '15:03:54', 'lat' => -19.634816, 'long' => 47.319124, 'vitesse' => 17],
            ['heure' => '15:04:04', 'lat' => -19.634398, 'long' => 47.319418, 'vitesse' => 22],
            ['heure' => '15:04:14', 'lat' => -19.633858, 'long' => 47.31984,  'vitesse' => 29],
            ['heure' => '15:04:29', 'lat' => -19.63352,  'long' => 47.320982, 'vitesse' => 31],
            ['heure' => '15:04:46', 'lat' => -19.63436,  'long' => 47.321893, 'vitesse' => 25],
            ['heure' => '15:05:20', 'lat' => -19.634351, 'long' => 47.322698, 'vitesse' => 15],
            ['heure' => '15:05:30', 'lat' => -19.6343,   'long' => 47.323191, 'vitesse' => 19],
            ['heure' => '15:05:40', 'lat' => -19.634287, 'long' => 47.323747, 'vitesse' => 23],
            ['heure' => '15:05:50', 'lat' => -19.634282, 'long' => 47.324498, 'vitesse' => 32],
            ['heure' => '15:06:08', 'lat' => -19.634836, 'long' => 47.326013, 'vitesse' => 34],
            ['heure' => '15:06:18', 'lat' => -19.634944, 'long' => 47.32692,  'vitesse' => 34],
            ['heure' => '15:06:28', 'lat' => -19.635191, 'long' => 47.32768,  'vitesse' => 31],
            ['heure' => '15:06:38', 'lat' => -19.6349,   'long' => 47.328449, 'vitesse' => 32],
            ['heure' => '15:06:48', 'lat' => -19.634604, 'long' => 47.329231, 'vitesse' => 32],
            ['heure' => '15:06:58', 'lat' => -19.634282, 'long' => 47.330062, 'vitesse' => 34],
            ['heure' => '15:07:08', 'lat' => -19.633782, 'long' => 47.330751, 'vitesse' => 26],
            ['heure' => '15:07:18', 'lat' => -19.633351, 'long' => 47.331053, 'vitesse' => 22],
            ['heure' => '15:07:28', 'lat' => -19.632776, 'long' => 47.331258, 'vitesse' => 15],
            ['heure' => '15:07:38', 'lat' => -19.632456, 'long' => 47.33132,  'vitesse' => 16],
            ['heure' => '15:07:48', 'lat' => -19.631969, 'long' => 47.331529, 'vitesse' => 27],
            ['heure' => '15:07:58', 'lat' => -19.631524, 'long' => 47.332044, 'vitesse' => 22],
            ['heure' => '15:08:08', 'lat' => -19.631191, 'long' => 47.332511, 'vitesse' => 25],
            ['heure' => '15:08:18', 'lat' => -19.630764, 'long' => 47.333129, 'vitesse' => 31],
            ['heure' => '15:08:28', 'lat' => -19.630247, 'long' => 47.333853, 'vitesse' => 34],
            ['heure' => '15:08:38', 'lat' => -19.629733, 'long' => 47.334596, 'vitesse' => 33],
            ['heure' => '15:08:48', 'lat' => -19.629273, 'long' => 47.335276, 'vitesse' => 17],
            ['heure' => '15:09:38', 'lat' => -19.629209, 'long' => 47.33536,  'vitesse' => 9],
            ['heure' => '15:09:48', 'lat' => -19.629004, 'long' => 47.335653, 'vitesse' => 16],
            ['heure' => '15:09:58', 'lat' => -19.628722, 'long' => 47.336111, 'vitesse' => 25],
            ['heure' => '15:10:08', 'lat' => -19.628533, 'long' => 47.336813, 'vitesse' => 29],
            ['heure' => '15:10:18', 'lat' => -19.628516, 'long' => 47.337613, 'vitesse' => 28],
            ['heure' => '15:10:28', 'lat' => -19.62844,  'long' => 47.338356, 'vitesse' => 26],
            ['heure' => '15:10:38', 'lat' => -19.628182, 'long' => 47.338991, 'vitesse' => 26],
            ['heure' => '15:10:48', 'lat' => -19.627749, 'long' => 47.33952,  'vitesse' => 25],
            ['heure' => '15:10:58', 'lat' => -19.627369, 'long' => 47.339951, 'vitesse' => 15],
            ['heure' => '15:11:08', 'lat' => -19.627073, 'long' => 47.340276, 'vitesse' => 20],
            ['heure' => '15:11:18', 'lat' => -19.626644, 'long' => 47.340733, 'vitesse' => 28],
            ['heure' => '15:11:28', 'lat' => -19.626107, 'long' => 47.341324, 'vitesse' => 33],
            ['heure' => '15:11:38', 'lat' => -19.625509, 'long' => 47.341942, 'vitesse' => 30],
            ['heure' => '15:11:48', 'lat' => -19.625047, 'long' => 47.342458, 'vitesse' => 20],
            ['heure' => '15:11:50', 'lat' => -19.62502,  'long' => 47.342489, 'vitesse' => 6],
            ['heure' => '15:15:08', 'lat' => -19.62502,  'long' => 47.342489, 'vitesse' => 0],
            ['heure' => '15:18:55', 'lat' => -19.62502,  'long' => 47.342489, 'vitesse' => 0],
        ];
        return view('incidents.index', ['positions' => $positions]);
    }

}
