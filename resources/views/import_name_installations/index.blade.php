@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                   {{-- @lang('models/importNameInstallations.plural') --}}
                   <h1> Importation de l'installation </h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href="{{ route('import.installation.affichage') }}">
                       Importer
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        {{-- @include('flash::message') --}}

        @include('sweetalert::alert')


        <div class="clearfix"></div>

        <div class="card">
            <div class="card-body p-0">
                @include('import_name_installations.table')

                <div class="card-footer clearfix float-right">
                    <div class="float-right">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


