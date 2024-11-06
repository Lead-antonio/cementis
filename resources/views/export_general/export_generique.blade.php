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
                <div class="card-title">Export de donnée</div>
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

                    <div class="form-group col-sm-2">
                        <label for="startDate">Date de début :</label>
                        <input class="form-control" type="date" id="startDate" name="startDate" onchange="verifierDates()">
                    </div> 

                    <div class="form-group col-sm-2">
                        <label for="endDate">Date de fin :</label>
                        <input class="form-control" type="date" id="endDate" name="endDate" onchange="verifierDates()">
                    </div>

                    <form action="{{ route('exportation.getexport') }}" method="POST">
                        @csrf
                        <input type="hidden" id="selectedTable" name="table">
                        <input type="hidden" id="startDateInput" name="startDate">
                        <input type="hidden" id="endDateInput" name="endDate">
                        <div style="padding-top:31px">
                            <button class="btn btn-success" type="submit" id="exportButton" style="display:none;">Exporter en Excel</button>
                        </div>
                    </form>

                </div>

                <!-- Affichage des colonnes de la table -->
                <div class="form-group col-sm-3" id="columnsContainer"  style="display:none;">
                    <h4>Colonnes de la table :</h4>
                    <ul id="columnsList"  class="list-group"></ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#modelSelect').on('change', function() {
                let modelName = $(this).val();
                if (modelName) {
                    // Récupérer les colonnes de la table sélectionnée
                    $.ajax({
                        url: "{{ route('exportation.getcolumns') }}",
                        method: 'POST',
                        data: { model: modelName },
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function(columns) {
                            $('#columnsList').empty();
                            columns.forEach(column => {
                                $('#columnsList').append(`<li class="list-group-item">${column}</li>`);
                            });
                            $('#columnsContainer').show();
                            $('#exportButton').show();
                            $('#selectedTable').val(modelName);
                        },
                        error: function() {
                            alert("Impossible de récupérer les colonnes.");
                            $('#columnsContainer').hide();
                            $('#exportButton').hide();
                        }
                    });
                } else {
                    $('#columnsContainer').hide();
                    $('#exportButton').hide();
                }
            });

            // Transférer les dates sélectionnées au formulaire
            $('#exportButton').on('click', function() {
                $('#startDateInput').val($('#startDate').val());
                $('#endDateInput').val($('#endDate').val());
            });
        });

        function verifierDates() {
            var datedebut = new Date(document.getElementById('startDate').value);
            var datefin = new Date(document.getElementById('endDate').value);

            if (datefin < datedebut) {
                // alert("La date d'échéance ne peut pas être antérieure à la date de réception !");
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur!',
                    text: 'La date début ne peut pas être antérieure à la date de fin!',
                });
                document.getElementById('endDate').value = "";
            }
        }
        
    </script>

@endsection
