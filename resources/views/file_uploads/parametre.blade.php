@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        @if (session('error_message'))
            <div class="alert alert-danger">
                {{ session('error_message') }}
            </div>
        @endif

        {!! Form::open(['route' => 'fileUploads.store', 'id' => 'fileUploadForm']) !!}

        <!-- Name Field (hidden) -->
        {!! Form::hidden('name', isset($fileName) ? $fileName : null) !!}

        <!-- File Upload Field (hidden) -->
        {!! Form::hidden('excel_file', isset($filePath) ? $filePath : null) !!}

        <div class="card">
            <div class="card-body">
                <div class="column container">
                    {{-- Sélection de la table --}}
                    <div class="d-flex gap-3">
                        <!-- Table Selection -->
                        {!! Form::select(
                            'option',
                            [
                                '' => 'Choisissez une table',
                                'Infraction' => 'infraction',
                            ],
                            null,
                            [
                                'class' => 'form-control',
                                'id' => 'options',
                            ],
                        ) !!}

                        <!-- Model Selection -->
                        {!! Form::select(
                            'choixModel',
                            [
                                '' => 'Choisissez un modèle',
                            ],
                            null,
                            [
                                'class' => 'form-control ml-5',
                                'id' => 'choixModels',
                            ],
                        ) !!}
                    </div>

                    {{-- Liste colonnes --}}
                    {{-- Champs de la table --}}
                    <div class="column my-5 border p-3">
                        <div class="mb-3">
                            <div class="d-flex">
                                <div class="w-50 pr-2">
                                    <h3 class="text-center">Colonnes De La Table</h3>
                                </div>
                                <div class="w-50">
                                    <h3 class="text-center">Colonnes Excel</h3>
                                </div>
                            </div>
                        </div>

                        <div id="fillableFields">
                            <!-- Les champs fillable seront affichés ici -->
                        </div>
                    </div>

                    <div class="form-check">
                        {!! Form::checkbox('choixSauver', '1', false, ['class' => 'form-check-input', 'id' => 'choixSauver']) !!}
                        {!! Form::label('choixSauver', 'Sauvegarder le modèle d\'appariement', ['class' => 'form-check-label']) !!}
                    </div>
                </div>
            </div>

            <div class="card-footer">
                {!! Form::button('Save', ['class' => 'btn btn-primary', 'id' => 'saveButton']) !!}
                <a href="{{ route('fileUploads.index') }}" class="btn btn-default">
                    @lang('crud.cancel')
                </a>
            </div>
        </div>

        {!! Form::close() !!}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let selects;

            document.getElementById('options').addEventListener('change', function() {
                const table = this.value;
                const modelSelect = document.getElementById('choixModels');
                const fillableFieldsDiv = document.getElementById('fillableFields');

                if (table) {
                    // Fetch fillable fields for the table
                    fetch(`get-fillable-fields/${table}`)
                        .then(response => response.json())
                        .then(data => {
                            let html = '';

                            data.forEach(field => {
                                html += `
                                    <div class="mb-3">
                                        <div class="d-flex">
                                            <div class="w-50 pr-2">
                                                <label for="label_${field}" class="col-form-label">${field}</label>
                                            </div>
                                            <div class="w-50">
                                                <select name="index_map[${field}]" class="column-select form-control">
                                                    <option value="">Choisir une colonne</option>
                                                    @foreach ($data[0][0] as $column)
                                                        <option value="{{ $loop->index }}">{{ $column }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });

                            fillableFieldsDiv.innerHTML = html;
                            selects = document.querySelectorAll('.column-select');
                            updateOptions();
                        })
                        .catch(error => console.error('Error:', error));

                    // Fetch model options for the table
                    fetch(`get-models/${table}`)
                        .then(response => response.json())
                        .then(models => {
                            let optionsHtml = '<option value="">Choisissez un modèle</option>';

                            Object.keys(models).forEach(key => {
                                optionsHtml += `<option value="${key}">${models[key]}</option>`;
                            });

                            modelSelect.innerHTML = optionsHtml;
                        })
                        .catch(error => console.error('Error:', error));
                } else {
                    fillableFieldsDiv.innerHTML = '';
                    modelSelect.innerHTML = '<option value="">Choisissez un modèle</option>';
                }
            });

            document.getElementById('choixModels').addEventListener('change', function() {
                const modelId = this.value;
                const fillableFieldsDiv = document.getElementById('fillableFields');

                if (modelId) {
                    // Fetch associations for the model
                    fetch(`get-associations/${modelId}`)
                        .then(response => response.json())
                        .then(associations => {
                            const selects = document.querySelectorAll('.column-select');

                            selects.forEach(select => {
                                const field = select.getAttribute('name').match(/\[(.*?)\]/)[
                                    1]; // Extract field name
                                const selectedIndex = associations[field];

                                if (selectedIndex !== undefined) {
                                    select.value = selectedIndex;
                                }
                            });

                            updateOptions();
                        })
                        .catch(error => console.error('Error:', error));
                }
            });

            function updateOptions() {
                const selectedValues = Array.from(selects).map(select => select.value);

                selects.forEach(select => {
                    const options = Array.from(select.querySelectorAll('option'));

                    options.forEach(option => {
                        if (option.value === '') {
                            option.style.display = 'block';
                        } else if (selectedValues.includes(option.value) && option.value !== select
                            .value) {
                            option.style.display = 'none';
                        } else {
                            option.style.display = 'block';
                        }
                    });
                });
            }

            document.addEventListener('change', function(event) {
                if (event.target.classList.contains('column-select')) {
                    updateOptions();
                }
            });

            // SweetAlert integration before form submission
            document.getElementById('saveButton').addEventListener('click', function() {
                const form = document.getElementById('fileUploadForm');
                const checkbox = document.getElementById('choixSauver');
                const tableSelection = document.getElementById('options').value;
                const defaultName = `Importation Model ${@json($newId)}`;

                let modelName = defaultName;
                if (tableSelection) {
                    modelName = `${tableSelection} ${defaultName}`;
                }

                if (checkbox.checked) {
                    // Open SweetAlert popup with a default value
                    Swal.fire({
                        title: 'Sauvegarder le modèle',
                        input: 'text',
                        inputLabel: 'Nom du modèle',
                        inputPlaceholder: 'Entrez un nom pour le modèle',
                        inputValue: modelName, // Set default value
                        showCancelButton: true,
                        confirmButtonText: 'Sauvegarder',
                        cancelButtonText: 'Annuler',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Vous devez entrer un nom pour le modèle!';
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Add a hidden field for the model name
                            const modelNameInput = document.createElement('input');
                            modelNameInput.type = 'hidden';
                            modelNameInput.name = 'nom';
                            modelNameInput.value = result.value;

                            form.appendChild(modelNameInput); // Append to the form

                            // Submit the form after the popup
                            form.submit();
                        }
                    });
                } else {
                    form.submit(); // Submit the form without SweetAlert if checkbox is not checked
                }
            });
        });
    </script>
@endsection
