<div class="card">
    <div id="tableau_score" class="card-body p-0">
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
                @if (count($data) > 0)
                        @foreach ($data as $driver => $events)
                        <tr>
                            <td style="text-align: center;"><a href="{{ route('events.table.scoring', ['chauffeur' => $driver, 'id_planning'  => $selectedPlanning]) }}">{{ $driver }}</a></td>
                            <td style="text-align: center;">{{ $events['transporteur'] }}</td>
                            <td style="text-align: center;">{{ getDistanceTotalDriverInCalendar($driver, $selectedPlanning) }}</td>
                            <td style="text-align: center;">{{ $events['Accélération brusque']['valeur']. " s" }}</td>
                            <td style="text-align: center;">{{ $events['Accélération brusque']['point'] }}</td>
                            <td style="text-align: center;">{{ $events['Freinage brusque']['valeur'] . " s"}}</td>
                            <td style="text-align: center;">{{ $events['Freinage brusque']['point'] }}</td>
                            <td style="text-align: center;">{{ $events['Excès de vitesse hors agglomération']['duree'] . " s"}}</td>
                            <td style="text-align: center;">{{ $events['Excès de vitesse hors agglomération']['point'] }}</td>
                            <td style="text-align: center;">{{ $events['Excès de vitesse en agglomération']['duree'] . " s"}}</td>
                            <td style="text-align: center;">{{ $events['Excès de vitesse en agglomération']['point'] }}</td>
                            <td style="text-align: center;">{{ $events['Survitesse excessive']['duree'] . " s"}}</td>
                            <td style="text-align: center;">{{ $events['Survitesse excessive']['point'] }}</td>
                            <td style="text-align: center;">{{ convertMinuteHeure($events['Temps de conduite maximum dans une journée de travail']['duree'])}}</td>
                            <td style="text-align: center;">{{ $events['Temps de conduite maximum dans une journée de travail']['point'] }}</td>
                            <td style="text-align: center;">{{ convertMinuteHeure($events['Temps de repos hebdomadaire']['duree']) }}</td>
                            <td style="text-align: center;">{{ $events['Temps de repos hebdomadaire']['point'] }}</td>
                            <td style="text-align: center;">{{ convertMinuteHeure($events['Temps de repos minimum après une journée de travail']['duree']) }}</td>
                            <td style="text-align: center;">{{ $events['Temps de repos minimum après une journée de travail']['point'] }}</td>
                            <td style="text-align: center;">{{ $events['total_point'] }}</td>
                            <td style="text-align: center;" class="
                            @php
                                $totalDistance = getDistanceTotalDriverInCalendar($driver, $selectedPlanning);
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
                @else
                    <td colspan="21" style="text-align: center;">Aucun élément</td>
                @endif
            </tbody>
        </table>
    </div>
</div>