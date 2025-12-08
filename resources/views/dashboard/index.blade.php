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
        <div class="card shadow-sm rounded">
            <div class="card-body">
                <div class="row align-items-center justify-content-between">
                    <!-- Colonne gauche : Filtres et boutons -->
                    <div class="col-md-8">
                        <div class="d-flex flex-wrap align-items-center gap-3">

                            <div class="form-group">
                                <label for="planning" class="form-label">Planning</label>
                                <select class="form-control custom-select w-auto" name="planning" id="planning">
                                    <option value="">Veuillez choisir le planning</option>
                                    @foreach($import_calendar as $calendar)
                                        <option value="{{ $calendar->id }}" {{ $calendar->id == $selectedPlanning ? 'selected' : '' }}>
                                            {{ $calendar->name }}
                                        </option>    
                                    @endforeach
                                </select>
                            </div>
                            @if (Auth::user()->role_text != "transporteur")    
                                <div class="form-group">
                                    <label for="planning" class="form-label">Transporteur</label>
                                    <select class="form-control custom-select w-auto" name="transporteur" id="transporteur">
                                        <option value="" selected>Veuillez choisir un transporteur</option>
                                        @foreach($transporteurs as $transporteur)
                                            <option value="{{ $transporteur->id }}">
                                                {{ $transporteur->nom }}
                                            </option>    
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

       
        <div class="row">
            <!-- Première ligne -->
            <div class="col-md-3">
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
                    </div>
                </a>
            </div>
        
            <div class="col-md-3">
                <a id="vehicule-link" href="{{ route('vehicules.index', ['selectedTransporteur' => $selectedTransporteur]) }}" class="text-decoration-none">
                    <div class="card card-custom vehicule">
                        <div class="card-body card-body-vehicules">
                            <div>
                                <h4 class="card-title-custom">Véhicules</h4>
                                <h3 id="total_vehicule">{{ $totalVehicules }}</h3>
                            </div>
                            <div class="icon-container">
                                <i class="nav-icon fas fa-truck"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        
            <div class="col-md-3">
                <a id="driver-link" href="{{ route('chauffeurs.index', ['selectedTransporteur' => $selectedTransporteur]) }}" class="text-decoration-none">
                    <div class="card card-custom chauffeur">
                        <div class="card-body card-body-chauffeurs">
                            <div>
                                <h4 class="card-title-custom">Chauffeurs</h4>
                                <h3 id="total_chauffeur">{{ $totalChauffeurs }}</h3>
                            </div>
                            <div class="icon-container">
                                <i class="nav-icon fas fa-user"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a id="truck-calendar-link" href="{{ route('detail.truck-calendar') }}" class="text-decoration-none">
                    <div class="card card-custom chauffeur">
                        <div class="card-body card-body-chauffeurs">
                            <div>
                                <h4 class="card-title-custom">Véhicules dans le calendrier</h4>
                                <h3 id="truck_in_calendar">{{ $truck_in_calendar }}</h3>
                            </div>
                            <div class="icon-container">
                                <i class="nav-icon fas fa-user"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        
        </div>
        
        <div class="row">
            <div class="col-md-3">
                <a id="driver-has-scoring-link" href="{{ route('driver.score') }}" class="text-decoration-none">
                    <div class="card card-custom scoring">
                        <div class="card-body card-body-custom">
                            <div>
                                <h4 class="card-title-custom">Nombre de chauffeurs avec score</h4>
                                <h3 id="driver_has_score">{{ $driver_has_score }}</h3>
                            </div>
                            <div class="icon-container">
                                <i class="nav-icon fas fa-user"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Deuxième ligne -->
            <div class="col-md-3">
                <a id="driver-have-not-scoring-link" href="{{ route('detail.driver-have-not-scoring') }}" class="text-decoration-none">
                    <div class="card card-custom no-scoring">
                        <div class="card-body card-body-custom">
                            <div>
                                <h4 class="card-title-custom">Nombre de chauffeur sans score</h4>
                                <h3 id="driver_not_has_score">{{ $driver_not_has_score }}</h3>
                            </div>
                            <div class="icon-container">
                                <i class="nav-icon fas fa-user"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a id="badge-calendar-link" href="{{ route('detail.badge-calendar') }}" class="text-decoration-none">
                    <div class="card card-custom no-scoring">
                        <div class="card-body card-body-custom">
                            <div>
                                <h4 class="card-title-custom">Nombre de badge dans le calendrier</h4>
                                <h3 id="badge_numbers_in_calendars">
                                    {{ $drivers_badge_in_calendars }}
                                </h3>
                            </div>
                            <div class="icon-container">
                                <i class="nav-icon fas fas fa-id-badge"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a id="driver-match-rfid-link" href="{{ route('detail.driver-match-rfid') }}" class="text-decoration-none">
                    <div class="card card-custom no-scoring">
                        <div class="card-body card-body-custom">
                            <div>
                                <h4 class="card-title-custom">Taux d'utilisation RFID</h4>
                                <h3 id="driver_match_rfid">
                                    {{ $match_rfid->match_percentage !== null ? $match_rfid->match_percentage . ' %' : 0 }}
                                </h3>
                            </div>
                            <div class="icon-container">
                                <i class="nav-icon fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a id="score-zero-link" href="{{ route('driver.detail.score.zero') }}" class="text-decoration-none">
                    <div class="card card-custom no-scoring">
                        <div class="card-body card-body-custom">
                            <div>
                                <h4 class="card-title-custom">Nombre de cas avec score 0</h4>
                                <h3 id="score_zero">
                                    {{ $score_zero }}
                                </h3>
                            </div>
                            <div class="icon-container">
                                <i class="nav-icon fas fa-circle"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a id="score-zero-more-than-3-planning-link" href="{{ route('driver.detail.score.zero.more.than.3.plannings') }}" class="text-decoration-none">
                    <div class="card card-custom no-scoring">
                        <div class="card-body card-body-custom">
                            <div>
                                <h4 class="card-title-custom">Nombre score 0 plus de 3 trajets</h4>
                                <h3 id="score_zero_more_than_3_planning">
                                    {{ $score_zero_more_than_3_planning }}
                                </h3>
                            </div>
                            <div class="icon-container">
                                <i class="nav-icon fas fa-circle"></i>
                            </div>
                        </div>
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
                            {{-- <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                <canvas id="driver_not_fix"></canvas>
                            </div> --}}
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
        function updateDashboard(selectedPlanning, selectedTransporteur) {
            $('#overlay').show();
            $('#loader').show();
            $.ajax({
                url: "{{ route('dashboard') }}",
                type: "GET",
                data: { 
                    selectedPlanning: selectedPlanning,
                    selectedTransporteur: selectedTransporteur
                },
                success: function (response) {
                    console.log(response);
                    $('#driver_has_score').text(response.driver_has_score);
                    $('#driver_not_has_score').text(response.driver_not_has_score);
                    $('#truck_in_calendar').text(response.truck_in_calendar);
                    $('#total_chauffeur').text(response.total_chauffeur);
                    $('#total_vehicule').text(response.total_vehicule);
                    $('#badge_numbers_in_calendars').text(response.drivers_badge_in_calendars);
                    $('#driver_in_calendar').text(response.driver_in_calendar);
                    $('#best_scoring_container').html(response.best_scoring);
                    $('#bad_scoring_container').html(response.bad_scoring);
                    $('#score_zero').html(response.score_zero);
                    $('#score_zero_more_than_3_planning').html(response.score_zero_more_than_3_planning);

                    let percentage = response.match_rfid.match_percentage;
                    let displayValue = (percentage !== null && percentage !== undefined) ? percentage + ' %' : '0';
                    $('#driver_match_rfid').html(displayValue);

                    $('#overlay').hide();
                    $('#loader').hide();
                }
            });
        }

        // Filtre Planning
        $('#planning').change(function () {
            let selectedPlanning = $(this).val();
            let selectedTransporteur = $('#transporteur').val();
            updateDashboard(selectedPlanning, selectedTransporteur);
        });

        // Filtre Transporteur
        $('#transporteur').change(function () {
            let selectedPlanning = $('#planning').val();
            let selectedTransporteur = $(this).val();
            updateDashboard(selectedPlanning, selectedTransporteur);
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        const planningSelect = document.getElementById("planning");
        const transporteurSelect = document.getElementById("transporteur");
        
        // Récupérer tous les liens à mettre à jour
        const linksToUpdate = [
            document.getElementById("driver-match-rfid-link"),
            document.getElementById("score-zero-link"),
            document.getElementById("score-zero-more-than-3-planning-link"),
            document.getElementById("badge-calendar-link"),
            document.getElementById("driver-has-scoring-link"),
            document.getElementById("driver-have-not-scoring-link"),
            document.getElementById("truck-calendar-link"),
            document.getElementById("driver-link"),
            document.getElementById("vehicule-link"),
        ];

        function updateLinks() {
            const planning = planningSelect.value;
            const transporteur = transporteurSelect.value;

            const params = [];
            if (planning) params.push(`id_planning=${planning}`);
            if (transporteur) params.push(`id_transporteur=${transporteur}`);

            // Parcourir tous les liens à mettre à jour
            linksToUpdate.forEach(link => {
                if (link) {
                    // Récupérer l'URL existante du lien
                    const currentUrl = new URL(link.href);  // Crée un objet URL à partir du href actuel
                    
                    // Ajouter ou mettre à jour les paramètres existants
                    params.forEach(param => {
                        // Extraire le nom du paramètre
                        const paramName = param.split('=')[0];
                        const paramValue = param.split('=')[1];

                        // Vérifier si le paramètre existe déjà dans l'URL
                        if (currentUrl.searchParams.has(paramName)) {
                            // Si le paramètre existe déjà, le mettre à jour
                            currentUrl.searchParams.set(paramName, paramValue);
                        } else {
                            // Sinon, ajouter le paramètre
                            currentUrl.searchParams.append(paramName, paramValue);
                        }
                    });

                    // Mettre à jour le href du lien avec les nouveaux paramètres
                    link.href = currentUrl.toString();

                    console.log("Lien mis à jour : ", link.href);  // Pour vérifier
                }
            });
        }

        // Mise à jour au chargement
        updateLinks();

        // Mise à jour à chaque changement
        planningSelect.addEventListener("change", updateLinks);
        transporteurSelect.addEventListener("change", updateLinks);
    });




    // document.addEventListener("DOMContentLoaded", function() {
    //     const planningSelect = document.getElementById("planning");
    //     const transporteurSelect = document.getElementById("transporteur");
    //     const rfidLink = document.getElementById("driver-match-rfid-link");
    //     console.log(rfidLink);
    //     function updateRfidLink() {
    //         const planning = planningSelect.value;
    //         const transporteur = transporteurSelect.value;

    //         const params = [];
    //         if (planning) params.push(`id_planning=${planning}`);
    //         if (transporteur) params.push(`id_transporteur=${transporteur}`);

    //         rfidLink.href = params.length 
    //             ? `{{ route('detail.driver-match-rfid') }}?${params.join("&")}` 
    //             : `{{ route('detail.driver-match-rfid') }}`;

    //         console.log("Lien RFID mis à jour :", rfidLink.href); // pour vérifier
    //     }

    //     // Mise à jour au chargement
    //     updateRfidLink();

    //     // Mise à jour à chaque changement
    //     planningSelect.addEventListener("change", updateRfidLink);
    //     transporteurSelect.addEventListener("change", updateRfidLink);
    // });

    // document.addEventListener("DOMContentLoaded", function() {
    //     let select = document.getElementById("planning");
    //     let links = {
    //         "driver-not-having-scoring": "{{ route('detail.driver-have-not-scoring') }}",
    //         "driver-having-scoring": "{{ route('detail.driver-has-scoring') }}",
    //         "truck-in-calendar": "{{ route('detail.truck-calendar') }}",
    //         "badge-in-calendar": "{{ route('detail.badge-calendar') }}",
    //     };

    //     function updateLinks() {
    //         let selectedValue = select.value;
    //         for (let id in links) {
    //             let linkElement = document.getElementById(id);
    //             if (linkElement) {
    //                 linkElement.href = selectedValue ? `${links[id]}?id_planning=${selectedValue}` : links[id];
    //             }
    //         }
    //     }

    //     // Mettre à jour les liens au chargement de la page
    //     updateLinks();

    //     // Mettre à jour les liens lorsqu'on change la sélection
    //     select.addEventListener("change", updateLinks);
    // });

    
    Chart.register(ChartDataLabels);
// ---------------------------------- CHART TRANSPORTEUR VEHICULE-------------------------------------------
    var ctx = document.getElementById('vehiculeChart').getContext('2d');
    var vehicules = @json($vehicule_transporteur); // On récupère les données

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
    var chauffeurs = @json($driver_transporteur); // On récupère les données

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
    
</script>

<style>
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
        /* min-height: 94%; */
    }

    .card-custom.vehicule {
        background: linear-gradient(145deg, #5e5e65, #ee3e35);
        /* min-height: 94%; */
    }

    .card-custom.transporteur {
        background: linear-gradient(145deg, #5e5e65, #ee3e35);
        /* min-height: 94%; */
    }

    .card-custom.scoring {
        background: linear-gradient(145deg, #000000, #ffffff);
        /* min-height: 94%; */
    }

    .card-custom.badge {
        background: linear-gradient(145deg, #000000, #ffffff);
        /* min-height: 94%; */
    }

    .card-custom.calendar {
        background: linear-gradient(145deg, #000000, #ffffff);
        /* min-height: 94%; */
    }

    .card-custom.no-scoring {
        background: linear-gradient(145deg, #000000, #ffffff);
        /* max-height: 86%; */
        /* max-height: 94%; */
    }

    .card-custom:hover {
        transform: scale(1.05);
        box-shadow: 2px 6px 20px rgba(0, 0, 0, 0.2);
    }

    .card-body-custom {
        display: flex;
        justify-content: space-between;
        /* flex-direction: column; */
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

    .scoring-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b; /* gris foncé élégant */
            text-transform: uppercase;
            margin-bottom: 0;
        }

        .search-group input {
            min-width: 250px;
        }

        .gap-3 > * {
            margin-right: 1rem;
            margin-bottom: 0.5rem;
        }

        @media (max-width: 768px) {
            .gap-3 > * {
                width: 100% !important;
                margin-right: 0;
            }

            .scoring-title {
                width: 100%;
                text-align: center;
                margin-bottom: 1rem;
            }
        }

</style>

@endpush
