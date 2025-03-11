@foreach ($best_scoring as $key => $item)
    <div class="card rounded-card">
        <a class="text-decoration-none text-dark" href="{{ route('driver.detail.scoring', ['chauffeur' => $item->driver_link, 'id_planning'  => $selectedPlanning]) }}">
            <div class="card-body card-list">
                <div class="number-circle">{{ $key + 1 }}</div>
                <strong> {{ $item->transporteur }}</strong> - {{ $item->driver }} : 
                <span class="badge rounded-pill 
                    {{ 
                        (round($item->point) == 0) ? 'bg-success' : 
                        (round($item->point) > 2 && round($item->point) <= 5 ? 'bg-warning' : 
                        (round($item->point) > 5 && round($item->point) <= 10 ? 'bg-orange' : 
                        (round($item->point) > 10 ? 'bg-danger' : ''))) 
                    }}"
                >
                    {{ $item->point }}
                </span>
            </div>
        </a>
    </div>
@endforeach