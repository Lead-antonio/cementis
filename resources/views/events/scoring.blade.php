@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                   <h1>Rapport du score card</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Infractions</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-responsive" width="100%">
                    <thead>
                        <tr>
                            <th style="text-align: center;">Chauffeur</th>
                            <th style="text-align: center;">Transporteur</th>
                            <th style="text-align: center;">Distance parcourue (Km)</th>
                            <th style="text-align: center;">Accélération brusque (durée)</th>
                            <th style="text-align: center;">Points</th>
                            <th style="text-align: center;">Freinage brusque (durée)</th>
                            <th style="text-align: center;">Points</th>
                            <th style="text-align: center;">Excès de vitesse hors agglomération (durée)</th>
                            <th style="text-align: center;">Points</th>
                            <th style="text-align: center;">Excès de vitesse en agglomération (durée)</th>
                            <th style="text-align: center;">Points</th>
                            <th style="text-align: center;">Survitesse excessive (durée)</th>
                            <th style="text-align: center;">Points</th>
                            <th style="text-align: center;">Temps de conduite maximum dans une journée de travail (durée)</th>
                            <th style="text-align: center;">Points</th>
                            <th style="text-align: center;">Temps de repos hebdomadaire (durée)</th>
                            <th style="text-align: center;">Points</th>
                            <th style="text-align: center;">Temps de repos minimum après une journée de travail (durée)</th>
                            <th style="text-align: center;">Points</th>
                            <th style="text-align: center;">Points totaux</th>
                            <th style="text-align: center;">Scoring</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $driver => $events)
                            <tr>
                                <td style="text-align: center;"><a href="{{ route('events.table.scoring', ['chauffeur' => $driver]) }}">{{ $driver }}</a></td>
                                <td style="text-align: center;">{{ $events['transporteur'] }}</td>
                                <td style="text-align: center;">{{ getDistanceTotalDriverInCalendar($driver) }}</td>
                                <td style="text-align: center;">{{ $events['Accélération brusque']['duree']. " s" }}</td>
                                <td style="text-align: center;">{{ $events['Accélération brusque']['point'] }}</td>
                                <td style="text-align: center;">{{ $events['Freinage brusque']['duree'] . " s"}}</td>
                                <td style="text-align: center;">{{ $events['Freinage brusque']['point'] }}</td>
                                <td style="text-align: center;">{{ $events['Excès de vitesse hors agglomération']['duree'] . " s"}}</td>
                                <td style="text-align: center;">{{ $events['Excès de vitesse hors agglomération']['point'] }}</td>
                                <td style="text-align: center;">{{ $events['Excès de vitesse en agglomération']['duree'] . " s"}}</td>
                                <td style="text-align: center;">{{ $events['Excès de vitesse en agglomération']['point'] }}</td>
                                <td style="text-align: center;">{{ $events['Survitesse excessive']['duree'] . " s"}}</td>
                                <td style="text-align: center;">{{ $events['Survitesse excessive']['point'] }}</td>
                                <td style="text-align: center;">{{ $events['Temps de conduite maximum dans une journée de travail']['duree'] . " s"}}</td>
                                <td style="text-align: center;">{{ $events['Temps de conduite maximum dans une journée de travail']['point'] }}</td>
                                <td style="text-align: center;">{{ convertMinuteHeure($events['Temps de repos hebdomadaire']['duree']) }}</td>
                                <td style="text-align: center;">{{ $events['Temps de repos hebdomadaire']['point'] }}</td>
                                <td style="text-align: center;">{{ convertMinuteHeure($events['Temps de repos minimum après une journée de travail']['duree']) }}</td>
                                <td style="text-align: center;">{{ $events['Temps de repos minimum après une journée de travail']['point'] }}</td>
                                <td style="text-align: center;">{{ $events['total_point'] }}</td>
                                <td style="text-align: center;" class="
                                @php
                                    $totalDistance = getDistanceTotalDriverInCalendar($driver);
                                    $score = ($totalDistance != 0) ? ($events['total_point'] / $totalDistance) * 100 : 0;
                                    if ($score >= 0 && $score <= 2) {
                                        echo 'scoring-green';
                                    } elseif ($score > 2 && $score <= 5) {
                                        echo 'scoring-yellow';
                                    } elseif ($score > 5 && $score <= 10) {
                                        echo 'scoring-orange';
                                    } else {
                                        echo 'scoring-red';
                                    }
                                @endphp
                            ">
                                  {{ number_format($score, 2) }}
                              </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
    </style>
@endsection
