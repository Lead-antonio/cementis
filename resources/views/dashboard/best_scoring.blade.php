{{-- @php
    $totalItems = count($best_scoring);
@endphp
@foreach ($best_scoring as $key => $item)
@php
    $chauffeur_calendar = getDriverByNumberBadge($item->badge_calendar, $selectedPlanning);
@endphp
    <div class="card rounded-card">
        @if (!empty($item->imei) && !empty($item->badge_calendar))
            <a class="text-decoration-none text-dark" href="{{ route('driver.detail.scoring', ['imei' => $item->imei, 'badge' => $item->badge_calendar , 'id_planning'  => $selectedPlanning]) }}">
                <div class="card-body card-list">
                    <div class="number-circle">{{ $key + 1 }}</div>
                    <strong> {{ $chauffeur_calendar }} : </strong>  
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
        @else
            <div class="card-body card-list">
                    <div class="number-circle">{{ $key + 1 }}</div>
                    <strong> {{ $chauffeur_calendar }} : </strong>  
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
        @endif
    </div>
@endforeach --}}
@foreach ($best_scoring as $key => $item)
    <div class="card rounded-card">
        {{-- <a class="text-decoration-none text-dark" href="{{ route('driver.detail.scoring', ['imei' => $item->imei, 'badge' => $item->badge_calendar , 'id_planning'  => $selectedPlanning]) }}"> --}}
        <div class="card-body card-list">
            <div class="number-circle">{{ $key + 1 }}</div>
            <strong> {{ $item?->driver?->nom }} : </strong>  
            <span class="badge rounded-pill 
                {{ 
                    (round($item->score) == 0) ? 'bg-success' : 
                    (round($item->score) > 2 && round($item->score) <= 5 ? 'bg-warning' : 
                    (round($item->score) > 5 && round($item->score) <= 10 ? 'bg-orange' : 
                    (round($item->score) > 10 ? 'bg-danger' : ''))) 
                }}"
            >
                {{ $item->score }}
            </span>
        </div>
        {{-- </a> --}}
    </div>
@endforeach