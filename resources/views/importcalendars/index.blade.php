@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                   <h1>Liste des importations</h1>
                </div>
                @can('import.affichage')
                    <div class="col-sm-6">
                        <a class="btn btn-primary float-right"
                        href="{{ route('import.affichage') }}">
                            Téléverser un fihier
                        </a>
                    </div>
                @endcan
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('sweetalert::alert')

        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body p-0">
                @include('importcalendars.table')
                
                <div class="card-footer clearfix float-right">
                    <div class="float-right">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


