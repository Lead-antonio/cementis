<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicule;
use App\Models\Transporteur;
use App\Models\Chauffeur;
use App\Repositories\DashboardRepository;

class DashboardController extends Controller
{
    /** @var  DashboardRepository */
    private $dashboardRepository;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(DashboardRepository $dashboardRepo)
    {
        $this->dashboardRepository = $dashboardRepo;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transporteurs = Transporteur::withCount('vehicule')->get();
        $totalVehicules = Vehicule::count();
        $totalTransporteurs = Transporteur::count();
        $totalChauffeurs = Chauffeur::count();
        $data = $this->dashboardRepository->GetData();
        $data['totalVehicules'] = $totalVehicules;
        $data['totalTransporteurs'] = $totalTransporteurs;
        $data['totalChauffeurs'] = $totalChauffeurs;
        $data['transporteurs '] = $transporteurs ;
        
        return view('dashboard.index', $data);
    }
}
