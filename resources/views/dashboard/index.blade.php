@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">@lang('models/dashboards.header.index')</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="">@lang('models/dashboards.header.home')</a></li>
                    <li class="breadcrumb-item active">@lang('models/dashboards.header.index')</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <div class="col-md-3 col-sm-6 mb-2">
            <select class="form-control" name="planning" id="planning">
                <option value="">Veuillez choisir le planning</option>
                @foreach($import_calendar as $calendar)
                    <option value="{{$calendar->id}}" {{ $calendar->id == $selectedPlanning ? 'selected' : '' }}>
                        {{$calendar->name}}
                    </option>    
                @endforeach
            </select>
        </div>

        <div class="row">
            <!-- Transporteurs -->
            <div class="col-md-2">
                <a href="{{ route('transporteurs.index') }}" class="text-decoration-none">
                    <div class="card card-custom transporteur">
                        <div class="card-body card-body-transporteurs">
                            <div>
                                <h4 class="card-title-custom">Transporteurs</h4>
                                <h3>{{$totalTransporteurs}}</h3>
                            </div>
                            <div class="icon-container">
                                <i class="nav-icon fas fa-city"></i>
                            </div>
                        </div>
                        {{-- <div class="card-footer card-footer-custom">
                            <small>+15% depuis hier</small>
                        </div> --}}
                    </div>
                </a>
            </div>
        
            <!-- Véhicules -->
            <div class="col-md-2">
                <a href="{{ route('vehicules.index') }}" class="text-decoration-none">
                    <div class="card card-custom vehicule">
                        <div class="card-body card-body-vehicules">
                            <div>
                                <h4 class="card-title-custom">Véhicules</h4>
                                <h3>{{ $totalVehicules }}</h3>
                            </div>
                            <div class="icon-container">
                                <i class="nav-icon fas fa-truck"></i>
                            </div>
                        </div>
                        {{-- <div class="card-footer card-footer-custom">
                            <small>+15% depuis hier</small>
                        </div> --}}
                    </div>
                </a>
            </div>
        
            <!-- Chauffeurs -->
            <div class="col-md-2">
                <a href="{{ route('chauffeurs.index') }}" class="text-decoration-none">
                    <div class="card card-custom chauffeur">
                        <div class="card-body card-body-chauffeurs">
                            <div>
                                <h4 class="card-title-custom">Chauffeurs</h4>
                                <h3>{{ $totalChauffeurs }}</h3>
                            </div>
                            <div class="icon-container">
                                <i class="nav-icon fas fa-user"></i>
                            </div>
                        </div>
                        {{-- <div class="card-footer card-footer-custom">
                            <small>+15% depuis hier</small>
                        </div> --}}
                    </div>
                </a>
            </div>

            <div class="col-md-2">
                <a href="{{ route('detail.driver-has-scoring') }}" class="text-decoration-none" id="truck-having-scoring">
                    <div class="card card-custom scoring">
                        <div class="card-body card-body-custom">
                            <div>
                                <h4 class="card-title-custom">Nombre de chauffeur avec score</h4>
                                <h3 id="driver_has_score">{{ $driver_has_score }}</h3>
                            </div>
                            <div class="icon-container">
                                <i class="nav-icon fas fa-user"></i>
                            </div>
                        </div>
                        {{-- <div class="card-footer card-footer-custom">
                            <small>+15% depuis hier</small>
                        </div> --}}
                    </div>
                </a>
            </div>

            <div class="col-md-2">
                <a href="{{ route('detail.truck-have-not-scoring') }}" class="text-decoration-none" id="truck-not-having-scoring">
                    <div class="card card-custom no-scoring">
                        <div class="card-body card-body-custom">
                            <div>
                                <h4 class="card-title-custom">Nombre de véhicules dans calendrier sans score</h4>
                                <h3 id="driver_not_has_score">{{ $driver_not_has_score }}</h3>
                            </div>
                            <div class="icon-container">
                                <i class="nav-icon fas fa-user"></i>
                            </div>
                        </div>
                        {{-- <div class="card-footer card-footer-custom">
                            <small>+15% depuis hier</small>
                        </div> --}}
                    </div>
                </a>
            </div>

            <div class="col-md-2">
                <a href="{{ route('detail.truck-calendar') }}" class="text-decoration-none" id="truck-in-calendar">
                    <div class="card card-custom calendar">
                        <div class="card-body card-body-custom">
                            <div>
                                <h4 class="card-title-custom">Nombre de véhicules dans le calendrier</h4>
                                <h3 id="driver_in_calendar">{{ $driver_in_calendar }}</h3>
                            </div>
                            <div class="icon-container">
                                <i class="nav-icon fas fa-truck"></i>
                            </div>
                        </div>
                        {{-- <div class="card-footer card-footer-custom">
                            <small>+15% depuis hier</small>
                        </div> --}}
                    </div>
                </a>
            </div>
        </div>
        
        <!-- /.row -->
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12">
                <div class="card">
                    <!-- Header de la carte avec les tabs -->
                    <div class="card-header d-flex  align-items-center">
                        <!-- Navigation des Tabs dans le header -->
                        
                        <ul class="nav nav-tabs card-header-tabs flex-grow-1" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                                    <strong>Classement des scores</strong> 
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="vehicule-tab" data-bs-toggle="tab" data-bs-target="#vehicule" type="button" role="tab" aria-controls="vehicule" aria-selected="false">
                                    <strong>Répartition des véhicules par transporteurs</strong> 
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="chauffeur-tab" data-bs-toggle="tab" data-bs-target="#chauffeur" type="button" role="tab" aria-controls="chauffeur" aria-selected="false">
                                    <strong>Répartition des chauffeurs par transporteurs</strong> 
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">
                                    <strong>Répartition des chauffeurs non fixes</strong> 
                                </button>
                            </li>
                        </ul>
        
                        <!-- Boutons de gestion de la carte -->
                        <div class="card-tools d-flex justify-content-end">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
        
                    <div class="card-body">
                        <!-- Contenu des Tabs -->
                        <div class="tab-content mt-3" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h1 class="card-title" style="padding-left: 31px;"><i class="fas fa-medal" style="color: #eded3c;"></i> Meilleur Scoring </h1>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                        
                                            <div class="card-body">
                                                <div class="card-body" id="best_scoring_container">
                                                    @include('dashboard.best_scoring', ['best_scoring' => $best_scoring, 'selectedPlanning' => $selectedPlanning])
                                                </div>
                                            </div>
                                            <!-- /.card-header -->
                                        </div>
                                        <!-- /.card -->
                                    </div>
                        
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h1 class="card-title" style="padding-left: 31px;"><i class="fas fa-exclamation-triangle" style="color: red;"></i> Moins Bon Scoring </h1>
                        
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                        
                                            <div class="card-body">
                                                <div class="card-body" id="bad_scoring_container">
                                                    @include('dashboard.bad_scoring', ['best_scoring' => $bad_scoring, 'selectedPlanning' => $selectedPlanning])
                                                </div>
                                            </div>
                                            <!-- /.card-header -->
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                </div> 
                            </div>
                            <div class="tab-pane fade" id="vehicule" role="tabpanel" aria-labelledby="vehicule-tab">
                                <canvas id="vehiculeChart" ></canvas>
                            </div>
                            <div class="tab-pane fade" id="chauffeur" role="tabpanel" aria-labelledby="chauffeur-tab">
                                <canvas id="chauffeurChart" ></canvas>
                            </div>
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                <canvas id="driver_not_fix"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>

        </div>

        <!-- /.row -->
    </div>
    <!--/. container-fluid -->
</section>

<!-- /.content -->
@endsection

@push('third_party_scripts')
<!-- ChartJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.0/chart.min.js" integrity="sha512-asxKqQghC1oBShyhiBwA+YgotaSYKxGP1rcSYTDrB0U6DxwlJjU59B67U8+5/++uFjcuVM8Hh5cokLjZlhm3Vg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
@endpush
@push('page_scripts')

<script>

    $(document).ready(function () {
        $('#planning').change(function () {
            $('#overlay').show();
            $('#loader').show();
            var selectedPlanning = $(this).val();
            
            if (selectedPlanning) {
                $.ajax({
                    url: "{{ route('dashboard') }}",
                    type: "GET",
                    data: { selectedPlanning: selectedPlanning },
                    success: function (response) {
                        console.log("RESPONSE :", response);
                        // Mettre à jour les données dynamiquement (exemple)
                        $('#driver_has_score').text(response.driver_has_score);
                        $('#driver_not_has_score').text(response.driver_not_has_score);
                        $('#driver_in_calendar').text(response.driver_in_calendar);
                        $('#best_scoring_container').html(response.best_scoring);
                        $('#bad_scoring_container').html(response.bad_scoring);
                        // Autres mises à jour selon tes données
                        $('#overlay').hide();
                        $('#loader').hide();
                    }
                });
            }
        });
    });

    // document.addEventListener("DOMContentLoaded", function() {
    //     let select = document.getElementById("planning");
    //     let link = document.getElementById("truck-not-having-scoring");

    //     function updateLink() {
    //         let selectedValue = select.value;
    //         let baseUrl = "{{ route('detail.truck-have-not-scoring') }}";
    //         link.href = selectedValue ? `${baseUrl}?id_planning=${selectedValue}` : baseUrl;
    //     }

    //     // Mettre à jour le lien au chargement de la page
    //     updateLink();

    //     // Mettre à jour le lien lorsqu'on change la sélection
    //     select.addEventListener("change", updateLink);
    // });

    // document.addEventListener("DOMContentLoaded", function() {
    //     let select = document.getElementById("planning");
    //     let link = document.getElementById("truck-having-scoring");

    //     function updateLink() {
    //         let selectedValue = select.value;
    //         let baseUrl = "{{ route('detail.driver-has-scoring') }}";
    //         link.href = selectedValue ? `${baseUrl}?id_planning=${selectedValue}` : baseUrl;
    //     }

    //     // Mettre à jour le lien au chargement de la page
    //     updateLink();

    //     // Mettre à jour le lien lorsqu'on change la sélection
    //     select.addEventListener("change", updateLink);
    // });

    document.addEventListener("DOMContentLoaded", function() {
        let select = document.getElementById("planning");
        let links = {
            "truck-not-having-scoring": "{{ route('detail.truck-have-not-scoring') }}",
            "truck-having-scoring": "{{ route('detail.driver-has-scoring') }}",
            "truck-in-calendar": "{{ route('detail.truck-calendar') }}",
        };

        function updateLinks() {
            let selectedValue = select.value;
            for (let id in links) {
                let linkElement = document.getElementById(id);
                if (linkElement) {
                    linkElement.href = selectedValue ? `${links[id]}?id_planning=${selectedValue}` : links[id];
                }
            }
        }

        // Mettre à jour les liens au chargement de la page
        updateLinks();

        // Mettre à jour les liens lorsqu'on change la sélection
        select.addEventListener("change", updateLinks);
    });

// var userCheckinChart = new Chart(document.getElementById('userCheckinChart').getContext('2d'), @json($chartUserCheckin));

// var driverStat = new Chart(document.getElementById('driverStat').getContext('2d'), @json($chartDriver));

    // $('#jstree').jstree({
    //     'core': {
    //         'data': {!! $transporteurData !!},
    //     },
    //     'types': {
    //         'default': {
    //             'icon': 'fa fa-truck transporteur-icon'
    //         },
    //         'transporteur': {
    //             'icon': 'fa fa-truck transporteur-icon' // Icône pour les transporteurs
    //         },
    //         'top': {
    //             'icon': 'fa fa-trophy top-icon' // Icône pour les éléments "Top"
    //         },
    //         'worst': {
    //             'icon': 'fa fa-arrow-circle-down worst-icon' // Icône pour les éléments "Worst"
    //         },
    //         'chauffeur': {
    //             'icon': 'fa fa-user' // Icône pour les chauffeurs
    //         }
    //     },
    //     'plugins': ['types']
    // });
    
    Chart.register(ChartDataLabels);
// ---------------------------------- CHART TRANSPORTEUR VEHICULE-------------------------------------------
    var ctx = document.getElementById('vehiculeChart').getContext('2d');
    var vehicules = @json($dashboardInfo['vehicule_transporteur']); // On récupère les données

    // Extraire les noms, le nombre de véhicules et de chauffeurs
    var labels = vehicules.map(t => t.nom);
    var vehiculesData = vehicules.map(t => t.vehicule_count);


    var vehiculeChart  = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                    {
                        label: 'Nombre de véhicules',
                        data: vehiculesData, // Véhicules par transporteur
                        backgroundColor: 'rgba(255, 99, 132, 0.6)', // Rouge
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
        },
        options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: true,
                animation: {
                    duration: 0 // Désactivation des animations pour améliorer les performances
                },
                scales: {
                    x: {
                        beginAtZero: true,
                    }
                },
                plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 16, // Taille de la police pour la légende
                            family: 'Arial', // Police de caractères
                            weight: 'bold', // Poids de la police (ex. 'normal', 'bold')
                            lineHeight: 1.2 // Hauteur de ligne
                        },
                        color: '#333' // Couleur de la légende
                    }
                },
                datalabels: {
                    anchor: 'center', // Positionnement du texte
                    align: 'center', // Alignement du texte
                    color: '#000', // Couleur du texte
                    font: {
                        size: 15, // Taille de la police pour la légende
                        family: 'Arial', // Police de caractères
                        weight: 'bold', // Poids de la police (ex. 'normal', 'bold')
                        lineHeight: 1.2 // Hauteur de ligne
                    },
                }
            }
        }
    });
// ---------------------------------------------------------------------------------------------------

// ---------------------------------- CHART TRANSPORTEUR CHAUFFEUR-------------------------------------------
var ctx = document.getElementById('chauffeurChart').getContext('2d');
    var chauffeurs = @json($dashboardInfo['driver_transporteur']); // On récupère les données

    // Extraire les noms, le nombre de véhicules et de chauffeurs
    var labels = chauffeurs.map(t => t.nom);
    var chauffeurData = chauffeurs.map(t => t.chauffeurs_count);

    
    var chauffeurChart  = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                    {
                        label: 'Nombre de chauffeurs',
                        data: chauffeurData, 
                        backgroundColor: 'rgba(75, 192, 75, 0.2)', 
                        borderColor: 'rgba(75, 192, 75, 1)', 
                        borderWidth: 1,
                    }
                ]
        },
        options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    x: {
                        beginAtZero: true,
                    }
                },
                plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 16, // Taille de la police pour la légende
                            family: 'Arial', // Police de caractères
                            weight: 'bold', // Poids de la police (ex. 'normal', 'bold')
                            lineHeight: 1.2 // Hauteur de ligne
                        },
                        color: '#333' // Couleur de la légende
                    }
                },

                datalabels: {
                    anchor: 'center', // Positionnement du texte
                    align: 'center', // Alignement du texte
                    color: '#000', // Couleur du texte
                    font: {
                        size: 15, // Taille de la police pour la légende
                        family: 'Arial', // Police de caractères
                        weight: 'bold', // Poids de la police (ex. 'normal', 'bold')
                        lineHeight: 1.2 // Hauteur de ligne
                    },
                }
            }
        }
    });
// ---------------------------------------------------------------------------------------------------

// ---------------------------------- CHART DRIVER NON FIXE -------------------------------------------
// Créer un graphique à barres avec Chart.js
    var ctx = document.getElementById('driver_not_fix').getContext('2d');

    var driver_not_fix = @json($driver_not_fix);
    
    // Extraire les données nécessaires pour le graphique
    var labels = driver_not_fix.map(function(item) {
        return item.nom; // Nom du transporteur
    });
    
    var data = driver_not_fix.map(function(item) {
        return item.nombre_chauffeurs_non_fixes; // Nombre de chauffeurs non fixes
    });

    var totalNonFixed = data.reduce(function(total, current) {
        return total + current; // Additionner les chauffeurs non fixes
    }, 0);

    
    var myChart = new Chart(ctx, {
        type: 'bar', // Type de graphique (ici un graphique à barres)
        data: {
            labels: labels, // Étiquettes (noms des transporteurs)
            datasets: [{
                label: 'Nombre de chauffeurs non fixes',
                data: data, // Données (nombre de chauffeurs non fixes)
                backgroundColor: 'rgba(54, 162, 235, 0.2)', // Couleur de fond des barres
                borderColor: 'rgba(54, 162, 235, 1)', // Couleur des bordures des barres
                borderWidth: 1
            }, {
                // label: 'Total des Chauffeurs non fixes: ' + totalNonFixed, // Légende supplémentaire
                label: 'Total des chauffeurs non fixes : '+ totalNonFixed,
                backgroundColor: 'rgba(255, 99, 132, 0)', // Transparent
                borderColor: 'rgba(255, 99, 132, 1)', // Couleur pour le total
                borderWidth: 0,
                borderDash: [5, 5], // Style de ligne en tirets
                pointRadius: 0, // Pas de point
                fill: false // Pas de remplissage
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true // Commencer l'axe Y à zéro
                }
            },
            plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 16, // Taille de la police pour la légende
                            family: 'Arial', // Police de caractères
                            weight: 'bold', // Poids de la police (ex. 'normal', 'bold')
                            lineHeight: 1.2 // Hauteur de ligne
                        },
                        color: '#333' // Couleur de la légende
                    }
                },
                datalabels: {
                    anchor: 'center', // Positionnement du texte
                    align: 'center', // Alignement du texte
                    color: '#000', // Couleur du texte
                    font: {
                        size: 15, // Taille de la police pour la légende
                        family: 'Arial', // Police de caractères
                        weight: 'bold', // Poids de la police (ex. 'normal', 'bold')
                        lineHeight: 1.2 // Hauteur de ligne
                    },
                }
            }
        }
    });
    
</script>

<style>
    /* .badge {
    display: inline-block;
    padding: 0.25em 0.4em;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.375rem !important;
} */
    .card-custom {
        border-radius: 10px;
        background: linear-gradient(145deg, #6e7bff, #5560ea);
        color: white;
        box-shadow: 2px 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
        width: 100%; /* Assurez-vous qu'elles occupent la même largeur */
        min-height: 150px; /* Hauteur identique pour éviter les variations */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 10px; /* Espacement interne pour éviter que le texte ne touche les bords */
    }

    .card-custom.chauffeur {
        background: linear-gradient(145deg, #5e5e65, #ee3e35);
        min-height: 94%;
    }

    .card-custom.vehicule {
        background: linear-gradient(145deg, #5e5e65, #ee3e35);
        min-height: 94%;
    }

    .card-custom.transporteur {
        background: linear-gradient(145deg, #5e5e65, #ee3e35);
        min-height: 94%;
    }

    .card-custom.scoring {
        background: linear-gradient(145deg, #000000, #ffffff);
        min-height: 94%;
    }

    .card-custom.calendar {
        background: linear-gradient(145deg, #000000, #ffffff);
        min-height: 94%;
    }

    .card-custom.no-scoring {
        background: linear-gradient(145deg, #000000, #ffffff);
        /* max-height: 86%; */
        max-height: 94%;
    }

    .card-custom:hover {
        transform: scale(1.05);
        box-shadow: 2px 6px 20px rgba(0, 0, 0, 0.2);
    }

    .card-body-custom {
        display: flex;
        justify-content: space-between;
        flex-direction: column;
        align-items: center;
        flex-grow: 1; /* Permet à la carte de s'étendre pour remplir l'espace disponible */
    }

    .card-body-transporteurs {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-grow: 1; /* Permet à la carte de s'étendre pour remplir l'espace disponible */
    }

    .card-body-vehicules {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-grow: 1; /* Permet à la carte de s'étendre pour remplir l'espace disponible */
    }

    .card-body-chauffeurs {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-grow: 1; /* Permet à la carte de s'étendre pour remplir l'espace disponible */
    }

    .icon-container {
        font-size: 24px; /* Réduire la taille des icônes pour s'ajuster dans la carte */
        background-color: rgba(255, 255, 255, 0.2);
        padding: 5%;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-title-custom {
        font-size: 1rem; /* Ajuster la taille du texte */
        font-weight: 700;
    }

    .card-footer-custom {
        display: flex;
        justify-content: flex-end;
        font-size: 0.8rem; /* Ajuster la taille du texte du footer */
        opacity: 0.7;
    }

</style>

@endpush
