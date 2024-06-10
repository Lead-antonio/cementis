@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                   <h1>Rapport du score card</h1>
                </div>
                <div class="col-sm-6">
                    <div class="form-row">
                        <select class="form form-control" name="planning" id="planning" style="width: auto;">
                            <option value="">Veuillez-choisir le planning</option>
                            @foreach($import_calendar as $calendar)
                                <option value="{{$calendar->id}}" {{ $calendar->id == $selectedPlanning ? 'selected' : '' }}>{{$calendar->name}}</option>    
                            @endforeach
                        </select>

                        <div class="form-row" style="margin: 0% 0% 0% 4%;width: 62%;">
                            <div class="input-group">
                                <input class="form-control" type="text" id="searchInput"  placeholder="Chauffeur, transporteur"  style="margin: 0% 1% 0% 0%">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div  class="content px-3">
        @include('events.scoring_filtre')
    </div>
    <style>
      .scoring-green {
          background-color: #6dac10; /* Vert */
          color: #000000; /* Couleur de texte */
      }
      .scoring-yellow {
          background-color: #f7d117; /* Jaune */
          color: #000000; /* Couleur de texte */
      }
      .scoring-orange {
          background-color: #f58720; /* Orange */
          color: #000000; /* Couleur de texte */
      }
      .scoring-red {
          background-color: #f44336; /* Rouge */
          color: #ffffff; /* Couleur de texte */
      }

    </style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#planning').change(function() {
                $('#overlay').show();
                $('#loader').show();
                var selectedValue = $(this).val();
                $.ajax({
                    url: "{{ route('ajax.scoring') }}", // Route à laquelle la requête Ajax sera envoyée
                    type: 'GET',
                    data: { planning: selectedValue },
                    success: function(response) {
                        $('#dataTable').html('')
                        $('#dataTable').html(response);
                        $('#overlay').hide();
                        $('#loader').hide();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });


        document.addEventListener("DOMContentLoaded", function() {
            var searchInput = document.getElementById('searchInput');
            var dataTable = document.getElementById('dataTable');
            var noResultsMessage = document.getElementById('noResultsMessage');

            if (dataTable) {
                var rows = dataTable.getElementsByTagName('tr');
                var headerRow = dataTable.querySelector('thead tr');
                var headerCells = headerRow.getElementsByTagName('th');
                var originalWidths = [];

                for (var i = 0; i < headerCells.length; i++) {
                    originalWidths.push(headerCells[i].offsetWidth);
                }

                searchInput.addEventListener('input', function() {
                    var searchText = searchInput.value.toLowerCase();
                    $('#overlay').show();
                    $('#loader').show();

                    setTimeout(function() {
                        var hasResults = false;

                        for (var i = 1; i < rows.length; i++) { // Commencer à l'indice 1 pour exclure les lignes d'en-tête
                            var row = rows[i];
                            var cells = row.getElementsByTagName('td');
                            var found = false;

                            for (var j = 0; j < cells.length; j++) {
                                var cell = cells[j];
                                var cellText = cell.textContent.toLowerCase();

                                if (cellText.indexOf(searchText) > -1) {
                                    found = true;
                                    hasResults = true;
                                    break;
                                }
                            }

                            if (found) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                            if (!hasResults) {
                                noResultsMessage.style.display = 'block';
                            } else {
                                noResultsMessage.style.display = 'none';
                            }
                        }
                        for (var i = 0; i < headerCells.length; i++) {
                            headerCells[i].style.width = originalWidths[i] + 'px';
                        }
                        $('#overlay').hide();
                        $('#loader').hide();
                    }, 500)
                    
                });
            }
        });


    </script>
@endsection
