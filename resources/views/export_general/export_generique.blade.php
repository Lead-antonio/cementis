@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="clearfix"></div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Export de données</div>
            </div>
            <div class="card-body" style="padding: 25px">
                <!-- Sélection du modèle -->
                <div class="row">
                    <div class="form-group col-sm-3">
                        <label for="modelSelect">Choisissez un modèle :</label>
                        <select id="modelSelect" class="form-control" required>
                            <option value="">Sélectionner un modèle</option>
                            @foreach($modelNames as $modelName)
                                <option value="{{ $modelName }}">{{ $modelName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <form action="{{ route('exportation.getexport') }}" method="POST">
                        @csrf
                        <input type="hidden" id="selectedTable" name="table">
                        <input type="hidden" id="filterData" name="filters">
                        <div style="padding-top:33px">
                            <button class="btn btn-success" type="submit" id="exportButton" style="display:none;">Exporter en Excel</button>
                        </div>
                    </form>
                </div>
    
                <!-- Affichage des colonnes de la table -->
                <div class="row">
                    <div class="col-sm-3" id="columnsContainer" style="display:none;">
                        <h4>Colonnes de la table :</h4>
                        <ul id="columnsList" class="list-group"></ul>
                    </div>
    
                    <!-- Filtres dynamiques -->
                    <div class="col-sm-9" id="filterContainer" style="display:none;">
                        <h4>Filtres :</h4>
                        <div id="filters">
                            <!-- Les filtres dynamiques apparaîtront ici -->
                        </div>
                        {{-- <button class="btn btn-primary" onclick="ajouterFiltre()">Ajouter une condition</button> --}}
                        <button id="addConditionButton" class="btn btn-primary" onclick="ajouterFiltre()">Ajouter une condition</button>

                    </div>
                </div>
    
                <!-- Bouton pour l'export -->
                
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#modelSelect').on('change', function() {
                let modelName = $(this).val();
                if (modelName) {
                    $.ajax({
                        url: "{{ route('exportation.getcolumns') }}",
                        method: 'POST',
                        data: { model: modelName },
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function(columns) {
                            $('#columnsList').empty();
                            $('#filters').empty(); // Vider les filtres pour chaque nouveau modèle
                            $('#filterContainer').show();
                            $('#columnsContainer').show();
                            $('#exportButton').show();
                            $('#selectedTable').val(modelName);
                            window.columns = columns;

                            // Afficher toutes les colonnes dans la liste des colonnes disponibles
                            columns.forEach(column => {
                                $('#columnsList').append(`<li class="list-group-item">${column.name} (${column.type})</li>`);
                            });

                            // Restreindre les colonnes filtrables pour le modèle 'Infraction'
                            let filterableColumns = columns;
                            if (modelName === 'Infraction') {
                                filterableColumns = columns.filter(column => ['date_debut', 'date_fin'].includes(column.name));
                            }

                            // Stocker les colonnes filtrables pour utilisation lors de l'ajout d'un filtre
                            window.filterableColumns = filterableColumns;
                        },
                        error: function() {
                            alert("Impossible de récupérer les colonnes.");
                            $('#columnsContainer').hide();
                            $('#filterContainer').hide();
                            $('#exportButton').hide();
                        }
                    });
                } else {
                    $('#columnsContainer').hide();
                    $('#filterContainer').hide();
                    $('#exportButton').hide();
                }
            });
        });
    
        function ajouterFiltre() {
            const index = $('#filters .filter-group').length;
            let filterHtml = `<div class="filter-group" style="margin-bottom: 10px;">
                <div class="row">
                    <!-- Sélection de la colonne (required) -->
                    <div class="form-group col-sm-3">
                        <select class="form-control filter-column" name="filters[${index}][column]" required onchange="changerTypeValeur(this, ${index}); updateFilterableColumns();">
                            <option value="">Sélectionner une colonne</option>`;
            
            window.filterableColumns.forEach(column => {
                filterHtml += `<option value="${column.name}" data-type="${column.type}">${column.name}</option>`;
            });

            filterHtml += `</select>
                    </div>
                    
                    <!-- Sélection de l'opérateur (required) -->
                    <div class="form-group col-sm-2">
                        <select class="form-control filter-operator" name="filters[${index}][operator]" required>
                            <option value="=">=</option>
                            <option value=">=">>=</option>
                            <option value="<="><=</option>
                            <option value="<"><</option>
                            <option value=">">></option>
                        </select>
                    </div>

                    <!-- Valeur de la condition (required) -->
                    <div class="form-group col-sm-3">
                        <input type="text" class="form-control filter-value" name="filters[${index}][value]" placeholder="Valeur" id="filterValue${index}" required>
                    </div>

                    <!-- Condition de liaison -->
                    <div class="form-group col-sm-2">
                        <select class="form-control filter-connector" name="filters[${index}][connector]" onchange="ajouterConditionSupp(${index})">
                            <option value="">Aucun</option>
                            <option value="AND">AND</option>
                            <option value="OR">OR</option>
                        </select>
                    </div>
                    
                    <div class="form-group col-sm-2">
                        <button class="btn btn-danger" onclick="$(this).closest('.filter-group').remove(); updateFilterableColumns(); toggleAddConditionButton(); checkFormCompletion();">Supprimer</button>
                    </div>
                </div>
            </div>`;

            $('#filters').append(filterHtml);
            updateFilterableColumns();
            toggleAddConditionButton();
            checkFormCompletion(); // Vérifie si le formulaire est complet
        }




    
        function changerTypeValeur(selectElement, index) {
            const selectedOption = $(selectElement).find('option:selected');
            const columnType = selectedOption.data('type');
            console.log('selectElement',selectElement);
            console.log('columnType',columnType);
            const valueInput = $(`#filterValue${index}`);
            
            if (columnType === 'datetime') {
                valueInput.attr('type', 'date');
            } else if (columnType === 'integer') {
                valueInput.attr('type', 'number');
            } else {
                valueInput.attr('type', 'text');
            }
        }
    
        function ajouterConditionSupp(index) {
            const connector = $(`select[name="filters[${index}][connector]"]`).val();
            if (connector) {
                ajouterFiltre();
            }
        }
    
        $('#exportButton').on('click', function() {
            const filters = [];
            $('#filters .filter-group').each(function() {
                const column = $(this).find('.filter-column').val();
                const operator = $(this).find('.filter-operator').val();
                const value = $(this).find('.filter-value').val();
                const connector = $(this).find('.filter-connector').val();
                if (column && operator && value) {
                    filters.push({ column, operator, value, connector });
                }
            });
            $('#filterData').val(JSON.stringify(filters));
        });

        function updateFilterableColumns() {
            // Obtenez toutes les colonnes déjà sélectionnées
            const selectedColumns = [];
            $('.filter-column').each(function() {
                const value = $(this).val();ajouterFiltre
                if (value) {
                    selectedColumns.push(value);
                }
            });

            // Mettre à jour les options de chaque sélecteur pour désactiver les colonnes déjà sélectionnées
            $('.filter-column').each(function() {
                const currentValue = $(this).val();
                $(this).find('option').each(function() {
                    if (selectedColumns.includes($(this).val()) && $(this).val() !== currentValue) {
                        $(this).attr('disabled', true);
                    } else {
                        $(this).attr('disabled', false);
                    }
                });
            });
        }

        // Fonction pour afficher ou masquer le bouton "Ajouter une condition"
        function toggleAddConditionButton() {
            const hasFilters = $('#filters .filter-group').length > 0;
            if (hasFilters) {
                $('#addConditionButton').hide();
            } else {
                $('#addConditionButton').show();
            }
        }

        // Fonction pour vérifier que tous les champs requis sont remplis
        function checkFormCompletion() {
            let isComplete = true;
            
            // Vérifie chaque filtre pour s'assurer que tous les champs requis sont remplis
            $('#filters .filter-group').each(function() {
                $(this).find('select[required], input[required]').each(function() {
                    if ($(this).val() === '') {
                        isComplete = false;
                    }
                });
            });
            
            // Active ou désactive le bouton Exporter selon l'état
            $('#exportButton').prop('disabled', !isComplete);
        }

        // Vérifie le formulaire à chaque changement dans les filtres
        $(document).on('change', '#filters .filter-group select, #filters .filter-group input', checkFormCompletion);

        // Appel initial pour masquer ou afficher le bouton "Ajouter une condition" et vérifier l'état du formulaire
        $(document).ready(function() {
            toggleAddConditionButton();
            checkFormCompletion(); // Vérification initiale de l'état du formulaire
        });

    </script>
    
    

@endsection
