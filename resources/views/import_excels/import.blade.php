@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                     <h1>@lang('models/importExcels.singular')</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        @if(session('alert'))
            <div class="alert alert-danger">
                {{ session('alert') }}
            </div>
        @endif
        
        <div class="card">

            {{-- {!! Form::open(['route' => 'import.excel']) !!} --}}
            {!! Form::open(['route' => 'import.excel', 'method' => 'post', 'enctype' => 'multipart/form-data', 'onsubmit' => 'return submitForm();']) !!}
            @csrf
            
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <div class="form-group ">
                            {!! Form::label('file_upload', __('models/fileUploads.fields.file_upload').':') !!}
                            <div class="input-group">
                                <div class="custom-file">
                                    {!! Form::file('excel_file', ['class' => 'custom-file-input','id'=>'excel_file']) !!}
                                    {!! Form::label('excel_file', 'Veuillez importer le fichier excel', ['class' => 'custom-file-label']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit(__('crud.save'), ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('importExcels.index') }}" class="btn btn-default">
                 @lang('crud.cancel')
                </a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection
