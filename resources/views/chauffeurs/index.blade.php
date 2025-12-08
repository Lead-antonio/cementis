@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-3">
                <div class="col-md-6">
                    <h1>@lang('models/chauffeurs.plural')</h1>
                </div>

                <div class="col-md-6 d-flex justify-content-end gap-2">
                    <div class="mr-2">
                        <select id="filter-planning" class="form-control">
                            <option value="">Filtrer par planning</option>
                            @foreach($plannings as $planning)
                                <option value="{{ $planning->id }}" {{ $selected_planning == $planning->id ? 'selected' : '' }}>{{ $planning->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @can('chauffeurs.create')
                        <a class="btn btn-primary" href="{{ route('chauffeurs.create') }}">
                            @lang('crud.add_new')
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </section>


    <div class="content px-3">

        @include('sweetalert::alert')

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body p-0">
                @include('chauffeurs.table')

                <div class="card-footer clearfix float-right">
                    <div class="float-right">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('filter-planning').addEventListener('change', function() {
            let planningId = this.value;
            let url = "{{ route('chauffeurs.index') }}"; // Remplace par la route de ta page
            if (planningId) {
                window.location.href = url + '?id_planning=' + planningId;
            } else {
                window.location.href = url; // si "aucun filtre"
            }
        });
    </script>

@endsection


