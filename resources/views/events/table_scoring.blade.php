@extends('layouts.app')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA1f_TK4EnA9ZIQIv6_o5piA48iW8tuHoQ"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@section('content')
    <section class="content-header">
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
                {{-- <h3 class="card-title mb-0">Scoring Information</h3> --}}
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    
                    <button onclick="exportToPDF()" class="btn btn-outline-secondary">Exporter en PDF</button>

                    <a class="btn btn-success" href="{{ route('event.exportscoring', ['chauffeur' => $chauffeur, 'id_planning' => $id_planning]) }}"> Exporter en Excel</a>

                </div>
            </div>
            <div class="card-body p-0">
                <table id="tableau-score" class="table table-bordered table-responsive" width="100%">
                    <thead>
                        <tr>    
                            <th style="text-align: center;">Chauffeur</th>
                            <th style="text-align: center;">Transporteur</th>
                            <th style="text-align: center;">Infraction</th>
                            <th style="text-align: center;">Date de l'infraction</th>
                            <th style="text-align: center;">Coordonnées gps</th>
                            <th style="text-align: center; word-wrap: break-word; white-space: normal; width: 100px;">Durée infraction / durée effectuée</th>
                            <th style="text-align: center; word-wrap: break-word; white-space: normal; width: 80px;">Insuffisance/Excès</th>
                            <th style="text-align: center;">Distance parcourue pendant l'infraction</th>
                            <th style="text-align: center;">Distance totale dans le calendrier</th>
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
                                    <td style="text-align: center">{{ $result->driver }}</td>
                                    <td style="text-align: center">{{$result->transporteur_nom}}</td>
                                    <td style="text-align: center">{{ trim($result->infraction) }}</td>
                                    <td style="text-align: center">{{ \Carbon\Carbon::parse($result->date_fin.' '.$result->heure_fin)->format('d-m-Y H:i:s') }}</td>
                                    <td style="text-align: center">
                                        <a href="#" onclick="showMapModal('{{ $result->gps_debut }}', '{{ $result->infraction }}')">
                                            {{ $result->gps_debut }}
                                        </a>
                                    </td>
                                    <td style="text-align: center">{{ convertMinuteHeure($result->duree_infraction) }}</td>
                                    <td style="text-align: center">{{ $result->insuffisance ? convertMinuteHeure($result->insuffisance) : "" }}</td>
                                    <td style="text-align: center">{{ $result->distance }} Km</td>
                                    <td style="text-align: center"></td>
                                    <td style="text-align: center">{{ $result->point }}</td>
                                </tr>
                                @php
                                    $total_point += $result->point;
                                    $driver = $result->driver;
                                @endphp
                            @endforeach
                            @php
                                // $scoring_card = number_format(($total_point / getDistanceTotalDriverInCalendar($driver, $id_planning)) * 100, 2);
                                $distanceTotal = getDistanceTotalDriverInCalendar($driver, $id_planning);

                                if ($distanceTotal > 0) {
                                    $scoring_card = number_format(($total_point / $distanceTotal) * 100, 2);
                                } else {
                                    $scoring_card = 0; // Ou toute autre valeur par défaut
                                }
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
                                <td colspan="8" style="text-align: center;"><strong>Total :</strong></td>
                                <td class="distance-row" style="text-align: center;">{{  getDistanceTotalDriverInCalendar($driver, $id_planning). " Km"  }}</td>
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

        $("#tableau-score").rowspanizer({columns: [0, 1, 2], vertical_align:'middle'});
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
    </style>
@endsection
