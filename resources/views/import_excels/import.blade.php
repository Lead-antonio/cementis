@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                     @lang('models/fichierExcels.singular')
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {{-- {!! Form::open(['route' => 'import.excel']) !!} --}}
            {!! Form::open(['route' => 'import.excel', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
            @csrf
            
            <div class="card-body">
                <div class="row">
                    
                    <div class="form-group col-sm-6">
                        {!! Form::label('fichier', __('Veuillez importer le fichier excel').':') !!}
                        {!! Form::file('excel_file', ['class' => 'form-control']) !!}
                    </div>

                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('fichierExcels.index') }}" class="btn btn-default">
                 @lang('crud.cancel')
                </a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
