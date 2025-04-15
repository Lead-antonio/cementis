@php
    $totalItems = count($bad_scoring);
@endphp
@foreach ($bad_scoring as $key => $item)
@php
    $chauffeur_calendar = getDriverByNumberBadge($item->badge_calendar);
@endphp
    <div class="card rounded-card">
        <a class="text-decoration-none text-dark" href="{{ route('driver.detail.scoring', ['chauffeur' => $chauffeur_calendar, 'id_planning'  => $selectedPlanning]) }}">
            <div class="card-body card-list">
                <div class="number-circle-worst">{{ $totalItems - $key }}</div>
                <strong class="text-dark"> {{ $chauffeur_calendar }} : </strong>  
                <span class="badge rounded-pill
                    {{ 
                        (round($item->total_point) == 0) ? 'bg-success' : 
                        (round($item->total_point) > 2 && round($item->total_point) <= 5 ? 'bg-warning' : 
                        (round($item->total_point) > 5 && round($item->total_point) <= 10 ? 'bg-orange' : 
                        (round($item->total_point) > 10 ? 'bg-danger' : ''))) 
                    }}" 
                >
                    {{ $item->total_point }}
                </span>
            </div>
        </a>
    </div>
@endforeach