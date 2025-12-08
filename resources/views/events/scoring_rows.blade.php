@foreach ($scoring as $item)
<tr>
    <td>
        @php
            $chauffeur_calendar = getDriverByNumberBadge($item->badge_calendar, $selectedPlanning);
        @endphp
        @if (!empty($item->imei) && !empty($item->badge_calendar) && !empty($chauffeur_calendar))
            <a href="{{ route('driver.detail.scoring', ['imei' => $item->imei, 'badge' => $item->badge_calendar, 'id_planning'  => $selectedPlanning]) }}">
                {{ $chauffeur_calendar }}
            </a>
        @elseif ($chauffeur_calendar)
            <small class="text-muted"> {{ $chauffeur_calendar }}</small>
        @else
            <small class="text-muted">Chauffeur inexistant pour le badge : {{$item->badge_calendar}}</small>
        @endif
    </td>
    <td>{{ $item->badge_calendar }}</td>
    <td>
        @php
            $conducteur = getDriverByRFID(false, $item->rfid_chauffeur, $selectedPlanning);
        @endphp
        @if (!empty($item->imei) && !empty($item->badge_calendar) && !empty($conducteur))
            <a href="{{ route('driver.detail.scoring', ['imei' => $item->imei, 'badge' => $item->badge_calendar, 'id_planning'  => $selectedPlanning]) }}">
                {{ $conducteur }}
            </a>
        @elseif (empty($item->rfid_chauffeur))
            <small class="text-muted">Pas de RFID ni IMEI</small>
        @elseif (is_null($conducteur))
            <small class="text-muted">Chauffeur inexistant pour RFID : {{$item->rfid_chauffeur}}</small>
        @endif
    </td>
    <td>{{ $item->badge_rfid }}</td>
    <td>{{ $item->transporteur->nom ?? '' }}</td>
    <td>
        <a href="{{ route('truck.detail.scoring', ['vehicule' => $item->camion, 'id_planning'  => $selectedPlanning]) }}">
            {{ $item->camion }}
        </a>
    </td>
    <td>
        @php
            $score = round($item->point, 2);
            $scoreClass = match(true) {
                $score == 0, $score <= 2 => 'badge badge-success',
                $score <= 5 => 'badge badge-warning',
                $score <= 10 => 'badge badge-danger',
                default => 'badge badge-dark'
            };
        @endphp
        <span class="{{ $scoreClass }}">{{ $score }}</span>
    </td>
    <td>
        {{ $item->driver }}
        @if ($score > 0)
            @if (!empty($item->driver))
                {{ getDriverInfractionWithmaximumPoint($item->driver->id, $item->imei, $selectedPlanning) }}
            @else
                {{ getTruckInfractionWithmaximumPoint($item->imei, $selectedPlanning) }}
            @endif
        @endif
    </td>
    <td>
        <textarea class="form-control" name="commentaire[{{ $item->id }}]" rows="2">{{ $item->comment }}</textarea>
    </td>
</tr>
@endforeach