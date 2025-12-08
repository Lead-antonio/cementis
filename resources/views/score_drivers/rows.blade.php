@foreach ($scoring as $item)
<tr>
    <td>
        <a href="{{ route('driver.score.detail', ['badge' => $item?->badge, 'id_planning'  => $item?->id_planning]) }}">
            {{ $item->driver?->nom }}
        </a>
    </td>
    <td>
        {{$item->badge}}
    </td>
    <td>{{ $item->company?->nom }}</td>
    <td>
        @php
            $score = round($item->score, 2);
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
        {{
            $item->most_infraction
        }}
    </td>
</tr>
@endforeach