@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('models/transporteurs.singular') : {{ $transporteur->nom }}</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right"
                       href="{{ route('transporteurs.index') }}">
                         @lang('crud.back')
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('transporteurs.show_fields')

                    <p><h4>Liste chauffeurs</h4></p>

                    <table class="table table-striped table-bordered dataTable no-footer">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <input  type="checkbox" id="select-all">
                                </th>
                                <th scope="col">Rfid</th>
                                <th scope="col">Nom</th>
                                <th scope="col">Transporteur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($chauffeur as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="select-checkbox" name="selected_chauffeurs[]" value="{{ $item->id }}">
                                    </td>
                                    <td>{{ $item->rfid }}</td>
                                    <td>{{ $item->nom }}</td>
                                    <td>
                                        @if ($item->transporteur)
                                            {{ $item->transporteur->nom }}
                                        @else
                                            
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    
                    <button type="submit" class="btn btn-primary" id="get-selected" onclick="update_transporteurid({{ $transporteur->id }})">Valider</button>


                </div>
            </div>
        </div>
    </div>

@endsection

{{-- @section('scripts')
    
@endsection --}}
