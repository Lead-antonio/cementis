<?php

namespace App\Repositories;

use App\Models\Attendance;
use App\Models\Transporteur;
use Carbon\Carbon;

/**
 * Class DashboardRepository
 * @package App\Repositories
 * @version July 26, 2021, 12:17 pm UTC
 */

class DashboardRepository
{
    /** @var  UserRepository */
    private $userRepository;
    /** @var  RoleRepository */
    private $roleRepository;
    /** @var  PermissionRepository */
    private $permissionRepository;
    /** @var  AttendanceRepository */
    private $attendanceRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RoleRepository $roleRepo, UserRepository $userRepo, PermissionRepository $permissionRepo, AttendanceRepository $attendanceRepo)
    {
        $this->permissionRepository = $permissionRepo;
        $this->userRepository = $userRepo;
        $this->roleRepository = $roleRepo;
        $this->attendanceRepository = $attendanceRepo;
    }

    private function getDashboardInfo()
    {
        $dashboardInfo = [];
        $dashboardInfo['user_count'] =  $this->userRepository->count();
        $dashboardInfo['role_count'] =  $this->roleRepository->count();
        $dashboardInfo['permission_count'] =  $this->permissionRepository->count();
        $dashboardInfo['user_online'] =  $this->attendanceRepository->CountUserOnline();
        $dashboardInfo['topDriver'] = topDriver();
        $dashboardInfo['driverTop'] = driverTop();
        $dashboardInfo['driverWorst'] = driverWorst();
        $dashboardInfo['scoring'] = scoringCard();
        //$dashboardInfo['count_vehicule_transporteur'] = Transporteur::withCount('vehicule')->get();
        //$dashboardInfo['count_driver_transporteur'] = Transporteur::withCount('chauffeurs')->get();
        $transporteurs = Transporteur::withCount(['vehicule', 'chauffeurs'])
                                        ->orderByDesc('vehicule_count')
                                        ->orderByDesc('chauffeurs_count')
                                        ->get();
        $dashboardInfo['transporteurs'] = $transporteurs;
        $totalChauffeurs = $transporteurs->sum('chauffeurs_count');
        $totalVehicules = $transporteurs->sum('vehicule_count');
        //$totalVehicules = $dashboardInfo['count_vehicule_transporteur']->sum('vehicule_count');
        //$totalChauffeurs = $dashboardInfo['count_driver_transporteur']->sum('chauffeurs_count');
        // dd($dashboardInfo['count_driver_transporteur']);
        // dd($dashboardInfo['count_driver_transporteur']);

        return $dashboardInfo;
    }


    private function getChartUserCheckinInfo()
    {
        $labels = [];
        $dataset1 = [];
        $dataset1['label'] = 'My Daily';
        $dataset1['data'] = [];
        $dataset1['borderColor'] = 'rgb(75, 192, 192)';

        $data = $this->attendanceRepository->TotalCheckInByDay(auth()->user()->id);
        foreach ($data as $key => $value) {
            $dataset1['data'][$key] = $value;
            $labels[$key] = $key;
        }

        $dataset2 = [];
        $dataset2['label'] = 'User Daily';
        $dataset2['data'] = [];
        $dataset2['borderColor'] = 'rgb(20, 150, 192)';

        $data = $this->attendanceRepository->TotalCheckInByDay();
        foreach ($data as $key => $value) {
            $dataset2['data'][$key ] = $value;
            $labels[$key] = $key;
        }

        $datasets = [];
        $datasets[] = $dataset1;
        $datasets[] = $dataset2;

        $data = [];
        $data['labels'] = array_values($labels);
        $data['datasets'] = $datasets;

        $chart = [];
        $chart['type'] = 'line';
        $chart['data'] = $data;
        return $chart;
    }
    public function GetData(){
        $dashboard = [];
        $dashboard['dashboardInfo'] = $this->getDashboardInfo();
        $dashboard['chartUserCheckin'] = $this->getChartUserCheckinInfo();
        $dashboard['chartDriver'] =  driverChart();
        return $dashboard;
    }
}
