@extends('layouts.app')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA1f_TK4EnA9ZIQIv6_o5piA48iW8tuHoQ"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                   <h1>Rapport du score card</h1>
                </div>
                <div class="col-sm-6">
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
                <h3 class="card-title mb-0">Scoring Information</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button onclick="exportToPDF()" class="btn btn-outline-secondary">Exporter en PDF</button>
                </div>
            </div>
            <div class="card-body p-0">
                <table id="tableau-score" class="table table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th>Chauffeur</th>
                            <th>Transporteur</th>
                            <th>Événements</th>
                            <th>Date de l'évènement</th>
                            <th>Coordonnées gps</th>
                            <th>Durée</th>
                            <th>Point de pénalité</th>
                            <th>Distance parcourue</th>
                            <th>Scoring Card</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $currentDriver = null;
                            $totalPenalty = 0;
                            $totalDistance = 0;
                            $uniqueDistances = [];
                            $duree = 0;
                        @endphp
                        @foreach ($scoring as $result)
                            @if ($currentDriver !== $result->driver)
                                @if ($currentDriver !== null)
                                    <tr class="total-row">
                                        <td colspan="6" style="text-align: center;"><strong>Total :</strong></td>
                                        <td class="point-row">{{ $totalPenalty }}</td>
                                        <td class="distance-row">{{ $totalDistance. " Km" }}</td>
                                        <td class="scoring-row">{{ number_format(($totalPenalty / $totalDistance) * 100, 2) }}</td>
                                    </tr>
                                @endif
                                @php
                                    $currentDriver = $result->driver;
                                    $duree = $result->duree;
                                    $previousDistance = null; 
                                    $totalPenalty = 0;
                                    $totalDistance = 0;
                                    $uniqueDistances = [];
                                @endphp
                            @endif
                            <tr>
                                <td>{{ $result->driver }}</td>
                                <td>{{$result->transporteur_nom}}</td>
                                <td>{{ trim($result->event) }}</td>
                                <td>{{ \Carbon\Carbon::parse($result->date_event)->format('d-m-Y H:i:s') }}</td>
                                <td>
                                    <a href="#" onclick="showMapModal('{{ $result->latitude }}', '{{ $result->longitude }}', '{{ $result->event }}')">
                                        {{ $result->latitude }}, {{ $result->longitude }}
                                    </a>
                                </td>
                                <td>{{ $result->duree }} s</td>
                                <td>{{ $result->penalty_point }}</td>
                                <td>{{ $result->distance }} Km</td>
                            </tr>
                            @php
                                $totalPenalty += $result->penalty_point;
                                if (!in_array($result->distance, $uniqueDistances)){
                                    $uniqueDistances[] = $result->distance; 
                                    $totalDistance += $result->distance;
                                }
                                
                            @endphp
                        @endforeach
                        @if ($currentDriver !== null)
                            <tr class="total-row">
                                <td colspan="6" style="text-align: center;"><strong>Total :</strong></td>
                                <td class="point-row">{{ $totalPenalty }}</td>
                                <td class="distance-row">{{ $totalDistance. " Km" }}</td>
                                <td class="scoring-row">{{ number_format(($totalPenalty / $totalDistance) * 100, 2)}}</td>
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

    


    <style>
        .scoring-row {
            background-color: #2b9ed3; /* Couleur de fond différente */
            color: #000000; /* Couleur de texte */
        }
        .point-row {
            background-color: #b4d32b; /* Couleur de fond différente */
            color: #000000; /* Couleur de texte */
        }
        .distance-row {
            background-color: #d38a2b; /* Couleur de fond différente */
            color: #000000; /* Couleur de texte */
        }
    </style>
    <script>

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


        function showMapModal(latitude, longitude, type) {
            initMap(latitude, longitude, type);
            $('#mapModal').modal('show');
        }
    </script>
    <script >
        function exportToPDF() {
            const element = document.getElementById('tableau-score');
            html2pdf().from(element).save('tableau.pdf');
        }

        $("#tableau-score").rowspanizer({columns: [0, 1, 2, 5, 7], vertical_align:'middle'});
    </script>
@endsection
