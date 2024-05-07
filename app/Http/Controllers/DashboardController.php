<?php

namespace App\Http\Controllers;

use App\Models\Transporteur;
use Illuminate\Http\Request;

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
        $data = $this->dashboardRepository->GetData();

       $topworst = topAndWorstChauffeur();

        // Structurer les données pour inclure les clés "text" et "children"
        $structuredData = [];
        foreach ($topworst as $item) {
            $transporteurData = [
                'text' => $item['transporteur'],
                'children' => [
                    [
                        'text' => 'Top',
                        'type' => 'top', // Définissez le type comme "top"
                        'children' => [],
                    ],
                    [
                        'text' => 'Pire',
                        'type' => 'worst', // Définissez le type comme "worst"
                        'children' => [],
                    ],
                ],
            ];
    
            // Ajouter les meilleurs chauffeurs
            $topChauffeurs = $item['top_chauffeurs']->take(3); // Prendre les top 3
            foreach ($topChauffeurs as $topChauffeur) {
                $transporteurData['children'][0]['children'][] = [
                    'text' => $topChauffeur->driver . ' (Score: ' . $topChauffeur->score_card . ')',
                    'icon' => 'fa fa-user'
                ];
            }
    
            // Ajouter les pires chauffeurs
            $worstChauffeurs = $item['worst_chauffeurs']->take(3); // Prendre les pires 3
            foreach ($worstChauffeurs as $worstChauffeur) {
                $transporteurData['children'][1]['children'][] = [
                    'text' => $worstChauffeur->driver . ' (Score: ' . $worstChauffeur->score_card . ')',
                    'icon' => 'fa fa-user'
                ];
            }
    
            $structuredData[] = $transporteurData;
        }
    
        $data['transporteurData'] = json_encode($structuredData);

        return view('dashboard.index', $data);
    }
}
