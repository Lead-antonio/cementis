@extends('layouts.app')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA1f_TK4EnA9ZIQIv6_o5piA48iW8tuHoQ"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@section('content')
    {{-- <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                   <h1>Détail du score Card</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('new.scoring') }}" class="btn btn-primary float-right">
                        Retour
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        <div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="mapModalLabel">Carte</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <div id="map" style="height: 400px;"></div>
                </div>
            </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-header">
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    
                    <button onclick="exportToPDF()" class="btn btn-outline-secondary">Exporter en PDF</button>
                </div>
            </div>
            <div class="card-body p-0">
                <table id="tableau-score" class="table table-bordered table-responsive" width="100%">
                    <thead>
                        <tr>    
                            <th style="text-align: center;">Camion</th>
                            <th style="text-align: center;">RFID</th>
                            <th style="text-align: center;">Transporteur</th>
                            <th style="text-align: center;">Infraction</th>
                            <th style="text-align: center;">Date début</th>
                            <th style="text-align: center;">Date fin</th>
                            <th style="text-align: center;">Coordonnées gps</th>
                            <th style="text-align: center; word-wrap: break-word; white-space: normal; width: 100px;">Durée infraction / durée effectuée</th>
                            <th style="text-align: center; word-wrap: break-word; white-space: normal; width: 80px;">Insuffisance/Excès</th>
                            <th style="text-align: center;">Distance totale calendrier</th>
                            <th style="text-align: center;">Point de pénalité</th>
                            <th style="text-align: center;">Scoring Card</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_point = 0;
                            $driver = "";
                            $scoringClass = "";
                            $scoring_card = 0;
                        @endphp
                        
                        @if (!$scoring->isEmpty())
                            @foreach ($scoring as $result)
                                <tr class="driver-row">
                                    <td style="text-align: center">{{ $result->camion }}</td>
                                    <td style="text-align: center">{{ $result->rfid_conducteur ?? $result->rfid_infraction }}</td>
                                    <td style="text-align: center">{{ get_transporteur($result->imei, $result->camion) }}</td>
                                    <td style="text-align: center">{{ trim($result->infraction) }}</td>
                                    <td style="text-align: center">{{ \Carbon\Carbon::parse($result->date_debut.' '.$result->heure_debut)->format('d-m-Y H:i:s') }}</td>
                                    <td style="text-align: center">{{ \Carbon\Carbon::parse($result->date_fin.' '.$result->heure_fin)->format('d-m-Y H:i:s') }}</td>
                                    <td style="text-align: center">
                                        <a href="#" onclick="showMapModal('{{ $result->gps_debut }}', '{{ $result->infraction }}')">
                                            {{ $result->gps_debut }}
                                        </a>
                                    </td>
                                    <td style="text-align: center">{{ convertMinuteHeure($result->duree_infraction) }}</td>
                                    <td style="text-align: center">{{ $result->insuffisance ? convertMinuteHeure($result->insuffisance) : "" }}</td>
                                    <td style="text-align: center"></td>
                                    <td style="text-align: center">{{ $result->point }}</td>
                                </tr>
                                @php
                                    $total_point += $result->point;

                                @endphp
                            @endforeach
                            @php
                                $scoring_card = $total_point; // Ou toute autre valeur par défaut
                                
                                if ($scoring_card >= 0 && $scoring_card <= 2) {
                                    $scoringClass = 'scoring-green';
                                } elseif ($scoring_card > 2 && $scoring_card <= 5) {
                                    $scoringClass = 'scoring-yellow';
                                } elseif ($scoring_card > 5 && $scoring_card <= 10) {
                                    $scoringClass = 'scoring-orange';
                                } else {
                                    $scoringClass = 'scoring-red';
                                }
                            @endphp
                            <tr class="total-row">
                                <td colspan="9" style="text-align: center;"><strong>Total :</strong></td>
                                <td class="distance-row" style="text-align: center;">{{   " 0Km"  }}</td>
                                <td class="point-row" style="text-align: center;">{{ $total_point }}</td>
                                <td class="{{ $scoringClass }}" style="text-align: center;">
                                    {{ $scoring_card }}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="11" style="text-align: center;">Aucun élément</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                
            </div>
        </div>

        
    </div> --}}

    <section class="content-header py-4">
        <div class="container-fluid">
            <div class="card shadow-sm rounded">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
    
                        <!-- Colonne gauche : Titre et bouton retour -->
                        <div class="col-md-8 d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3">
                            <h1 class="mb-2 mb-md-0">Détail du score card</h1>
                        </div>
    
                        <!-- Colonne droite : Boutons PDF et Excel -->
                        <div class="col-md-4 text-md-right text-center mt-3 mt-md-0">
                            <div class="d-flex justify-content-md-end justify-content-center gap-2 flex-wrap">
                                <a href="{{ route('new.scoring') }}" class="btn btn-primary">
                                    Retour
                                </a>
                                <button type="button" class="btn btn-outline-secondary" onclick="exportToPDF()">
                                    <i class="fas fa-file-pdf mr-1"></i> PDF
                                </button>
                                {{-- <a class="btn btn-success" href="{{ route('export.excel.detail.scoring', ['imei' => $imei, 'badge' => $badge, 'id_planning' => $id_planning]) }}">
                                    <i class="fas fa-file-excel mr-1"></i> Excel
                                </a> --}}
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <div class="content px-3">
        <!-- Modal pour la carte -->
        <div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Carte</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="map" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Tableau du scoring -->
        <div class="card shadow-sm rounded">
    
            <div class="card-body p-0 table-responsive">
                <div class="table-responsive">
                    <table id="tableau-score" class="table table-bordered text-center align-middle mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>Camion</th>
                                <th>RFID</th>
                                <th>Transporteur</th>
                                <th>Infraction</th>
                                <th>Date début</th>
                                <th>Date fin</th>
                                <th>Coordonnées GPS</th>
                                <th style="width: 120px;">Durée infraction / effectuée</th>
                                <th style="width: 90px;">Insuff. / Excès</th>
                                <th>Distance totale</th>
                                <th>Points</th>
                                <th>Scoring Card</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_point = 0;
                                $scoringClass = '';
                                $scoring_card = 0;
                            @endphp
    
                            @if (!$scoring->isEmpty())
                                @foreach ($scoring as $result)
                                    <tr>
                                        <td>{{ $result->camion }}</td>
                                        <td>{{ $result->rfid_conducteur ?? $result->rfid_infraction }}</td>
                                        <td>{{ get_transporteur($result->imei, $result->camion) }}</td>
                                        <td>{{ trim($result->infraction) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($result->date_debut.' '.$result->heure_debut)->format('d-m-Y H:i:s') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($result->date_fin.' '.$result->heure_fin)->format('d-m-Y H:i:s') }}</td>
                                        <td>
                                            <a href="#" onclick="showMapModal('{{ $result->gps_debut }}', '{{ $result->infraction }}')">
                                                {{ $result->gps_debut }}
                                            </a>
                                        </td>
                                        <td>{{ convertMinuteHeure($result->duree_infraction) }}</td>
                                        <td>{{ $result->insuffisance ? convertMinuteHeure($result->insuffisance) : '' }}</td>
                                        <td></td>
                                        <td>{{ $result->point }}</td>
                                        <td></td>
                                    </tr>
                                    @php
                                        $total_point += $result->point;
                                    @endphp
                                @endforeach
    
                                @php
                                    $scoring_card = $total_point;
                                    $scoringClass = match(true) {
                                        $scoring_card <= 2 => 'badge badge-success',
                                        $scoring_card <= 5 => 'badge badge-warning',
                                        $scoring_card <= 10 => 'badge badge-danger',
                                        default => 'badge badge-dark'
                                    };
                                @endphp
    
                                <tr class="font-weight-bold bg-light">
                                    <td colspan="9">Total :</td>
                                    <td>0 Km</td>
                                    <td>{{ $total_point }}</td>
                                    <td><span class="{{ $scoringClass }}">{{ $scoring_card }}</span></td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="12" class="text-center">Aucun élément</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/plugins/jquery.rowspanizer.min.js') }}"></script>


    <script>

        $(document).ready(function() {
            $(".driver-row").each(function() {
                $(this).click(function() {
                    // Basculer l'affichage des détails du chauffeur
                    $(this).next(".driver-details").slideToggle();
                    
                    // Basculer l'icône entre "+" et "-"
                    var icon = $(this).find(".expand-icon");
                    if (icon.hasClass("fa-plus-circle")) {
                        icon.removeClass("fa-plus-circle").addClass("fa-minus-circle");
                    } else {
                        icon.removeClass("fa-minus-circle").addClass("fa-plus-circle");
                    }
                });
            });
        });

        let map;

        async function initMap(latitude, longitude, type) {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: parseFloat(latitude), lng: parseFloat(longitude)},
                zoom: 15
            });

            var marker = new google.maps.Marker({
                position: {lat: parseFloat(latitude), lng: parseFloat(longitude)},
                map: map,
                title: type
            });
        }


        function showMapModal(gps, type) {
            var tab = gps.split(',');
            initMap(tab[0], tab[1], type);
            $('#mapModal').modal('show');
        }
    </script>
    <script >
        function exportToPDF() {
            const element = document.getElementById('tableau-score');
            const options = {
                margin: 0.5, // Marge autour du contenu
                filename: 'tableau.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 4 }, // Augmente la résolution
                jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' } // Paysage pour un tableau large
            };

            html2pdf().set(options).from(element).save();
        }

        $("#tableau-score").rowspanizer({columns: [0, 1, 2, 3], vertical_align:'middle'});
    </script>
    <style>
        .scoring-green {
            background-color: #6dac10; /* Vert */
            color: #000000; /* Couleur de texte */
        }
        .scoring-yellow {
            background-color: #f7d117; /* Jaune */
            color: #000000; /* Couleur de texte */
        }
        .scoring-orange {
            background-color: #f58720; /* Orange */
            color: #000000; /* Couleur de texte */
        }
        .scoring-red {
            background-color: #f44336; /* Rouge */
            color: #ffffff; /* Couleur de texte */
        }
        .point-row {
            background-color: #808080; /* Couleur de fond différente */
            color: #000000; /* Couleur de texte */
        }
        .distance-row {
            background-color: #808080;  /* Couleur de fond différente */
            color: #000000; /* Couleur de texte */
        }
        #tableau-score {
            /* font-size: 10px; */
            width: 100%; /* Assurez-vous que le tableau utilise toute la largeur */
        }

        #tableau-score th, #tableau-score td {
            padding: 4px; /* Réduire l'espacement */
        }


        .table th, .table td {
            vertical-align: middle !important;
            font-size: 0.95rem;
            padding: 0.6rem;
        }

        .badge {
            font-size: 0.85rem;
            padding: 0.5em 0.75em;
            border-radius: 1rem;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-danger {
            background-color: #fd7e14;
        }

        .badge-dark {
            background-color: #dc3545;
        }
    </style>
@endsection
