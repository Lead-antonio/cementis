<div class="card">
    <div id="dataTable" class="card-body p-0" >
        <form id="commentForm" method="POST" action="{{ route('save.comments') }}">
            @csrf
            <table id="scoringTable" class="table table-bordered" width="100%">
                <thead>
                    <tr>
                        <th style="text-align: center;background-color: darkgray;">Chauffeur dans le calendrier</th>
                        <th style="text-align: center;background-color: darkgray;">N° badge dans le calendrier</th>
                        <th style="text-align: center;background-color: darkgray;">Chauffeur dans l'infraction</th>
                        <th style="text-align: center;background-color: darkgray;">N° badge sur RFID</th>
                        <th style="text-align: center;background-color: darkgray;">Transporteur</th>
                        <th style="text-align: center;background-color: darkgray;width: 10%;">Camion</th>
                        <th style="text-align: center;width: 10%;background-color: #101010;color: white" id="maiHeader">Scoring <span id="maiSortIcon" class="mai-sort-icon fas fa-sort-amount-down" style="margin-left: 5px; cursor: pointer"></span></th>
                        <th style="text-align: center;width: 20%;background-color: #101010;color: white">Infraction les plus fréquentes</th>
                        <th style="text-align: center;width: 20%;background-color: darkgray">Commentaire</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $countCheckedTrucks = 0;
                        $chauffeurBadge = 0;
                    @endphp
                    @foreach ($scoring as $item)
                        <tr>
                            <td style="text-align: center;">
                                @if(!empty($item->driver))
                                    @php
                                        $chauffeur = $item->driver->latest_update ?? $item->driver;
                                    @endphp
                                    <a href="{{ route('driver.detail.scoring', ['chauffeur' => $item->driver->nom, 'id_planning'  => $selectedPlanning]) }}">
                                        {{ $chauffeur->nom }}
                                    </a>
                                @else
                                    <span>Chauffeur inexistant pour le numéro de badge : {{$item->badge_calendar}}</span>
                                @endif
                            </td>
                            <td style="text-align: center">{{ $item->badge_calendar }}</td>
                            <td style="text-align: center">{{ getDriverByRFID(false, $item->rfid_chauffeur)}}</td>
                            <td style="text-align: center">{{ $item->badge_rfid }}</td>
                            {{-- <td style="text-align: center">{{ getBadgeCalendarByTruck($selectedPlanning,$item->camion) }}</td> --}}
                            <td style="text-align: center;">
                                @if (!empty($item->transporteur))
                                   {{ $item->transporteur->nom }}
                                @else
                                    
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('truck.detail.scoring', ['vehicule' => $item->camion, 'id_planning'  => $selectedPlanning]) }}">
                                    {{  $item->camion  }}
                                </a>
                            </td>
                            {{-- <td style="text-align: center;">{{  getTruckByImei($item->camion)  }}</td> --}}
                            <td style="text-align: center;" class="
                                @php
                                    $score = round($item->point, 2);
                                    // $chauffeur = $item->driver->latest_update ?? $item->driver;
                                    // $isTruckinCalendarChecked = checkBadgeinCalendar($selectedPlanning, $chauffeur->numero_badge);
                                    // if ($isTruckinCalendarChecked) {
                                    //     $countCheckedTrucks++;
                                    // }
                                    // if($score == 0 && $isTruckinCalendarChecked){
                                    if($score == 0){
                                        echo 'scoring-green';
                                    } elseif ($score > 0 && $score <= 2) {
                                        echo 'scoring-green';
                                    } elseif ($score > 2 && $score <= 5) {
                                        echo 'scoring-yellow';
                                    } elseif ($score > 5 && $score <= 10) {
                                        echo 'scoring-orange';
                                    } elseif ($score > 10) {
                                        echo 'scoring-red';
                                    }
                                @endphp
                            ">{{ round($item->point, 2) }}</td>
                            <td>
                                @if (!empty($item->driver))
                                     {{ getInfractionWithmaximumPoint($item->driver->id, $selectedPlanning )}}
                                @endif
                            </td>
                            <td style="text-align: center;"><textarea class="form-control" name="commentaire[{{ $item->id }}]" id="" cols="30" rows="2 ">{{ $item->comment }}</textarea></td>
                        </tr>
                    @endforeach
                </tbody>
                {{-- <p>Nombre de chauffeur avec badge : {{ $chauffeurBadge }}</p> --}}
            </table>
            {{-- <div class="d-flex justify-content-end" style="margin: 0% 2% 1% 0%;">
                <button type="submit" class="btn btn-primary" onclick="submitForm()">Enregistrer les commentaires</button>
            </div> --}}
        </form>
        <div id="noResultsMessage" style="display: none;text-align: center">Aucun résultat trouvé.</div>
    </div>        
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            function submit() {
                submitForm();
                document.getElementById('commentForm').submit();
            }

            // Function to attach event listeners for comment blur events
            function attachCommentListeners() {
                const commentInputs = document.querySelectorAll('textarea[name^="commentaire"]');
                commentInputs.forEach(input => {
                    input.addEventListener('blur', function() {
                        submit();
                    });
                });
            }

            // Function to attach event listeners for sorting
            function attachSortListeners() {
                let ascending = false;
                const maiSortIcon = document.getElementById('maiSortIcon');

                // Function to sort the table by the "Mai" column
                function sortTableByMai() {
                    const table = document.getElementById('scoringTable');
                    const tbody = table.querySelector('tbody');
                    const rows = Array.from(tbody.querySelectorAll('tr'));

                    // Sort the rows by the values in the "Mai" column
                    rows.sort((a, b) => {
                        const aMai = parseFloat(a.cells[4].textContent);
                        const bMai = parseFloat(b.cells[4].textContent);
                        return ascending ? aMai - bMai : bMai - aMai;
                    });

                    // Replace the table rows with the sorted rows
                    rows.forEach(row => tbody.appendChild(row));
                }

                // Add a click event listener to the "Mai" column header
                const maiHeader = document.getElementById('maiHeader');
                maiHeader.addEventListener('click', function() {
                    ascending = !ascending;
                    maiSortIcon.className = ascending ? 'mai-sort-icon fas fa-sort-amount-up' : 'mai-sort-icon fas fa-sort-amount-down';
                    sortTableByMai();
                });

                // Initial sort by "Mai" column
                sortTableByMai();
            }

            // Attach initial event listeners
            attachCommentListeners();
            attachSortListeners();


            $('#planning').change(function() {
                $('#overlay').show();
                $('#loader').show();
                var selectedValue = $(this).val();
                console.log(selectedValue);
                
                // Mettre à jour le lien d'exportation avec l'ID du planning sélectionné
                var exportLink = $('#export-link');
                var baseUrl = "{{ route('export.excel.scoring') }}";
                exportLink.attr('href', baseUrl + '/' + selectedValue);

                var selectedAlphaciment = $('#alphaciment_driver').val(); // Récupérer la valeur de alphaciment_driver
                
                // Mettre à jour le lien d'exportation avec les deux paramètres
                var exportLink = $('#export-link');
                var baseUrl = "{{ route('export.excel.scoring') }}";
                exportLink.attr('href', baseUrl + '?planning=' + selectedValue + '&alphaciment_driver=' + selectedAlphaciment);
                
                
                $.ajax({
                    url: "{{ route('ajax.scoring') }}", // Route à laquelle la requête Ajax sera envoyée
                    type: 'GET',
                    data: { planning: selectedValue },
                    success: function(response) {
                        $('#dataTable').html('')
                        $('#dataTable').html(response);
                        $('#overlay').hide();
                        $('#loader').hide();

                        attachCommentListeners();
                        attachSortListeners();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });


            $('#alphaciment_driver').change(function() {
                $('#overlay').show();
                $('#loader').show();
                
                var selectedPlanning = $('#planning').val(); // Récupère le planning sélectionné
                var selectedAlphaciment = $(this).val(); // Récupère la valeur de alphaciment_driver

                console.log("Planning:", selectedPlanning);
                console.log("Alphaciment Driver:", selectedAlphaciment);

                // Mettre à jour l'URL du bouton d'exportation
                var exportLink = $('#export-link');
                var baseUrl = "{{ route('export.excel.scoring') }}";
                exportLink.attr('href', baseUrl + '?planning=' + selectedPlanning + '&alphaciment_driver=' + selectedAlphaciment);


                $.ajax({
                    url: "{{ route('ajax.scoringdriver') }}", // Route à laquelle la requête Ajax sera envoyée
                    type: 'GET',
                    data: { planning: selectedPlanning ,alphaciment_driver : selectedAlphaciment   },
                    success: function(response) {
                        $('#dataTable').html('')
                        $('#dataTable').html(response);
                        $('#overlay').hide();
                        $('#loader').hide();

                        attachCommentListeners();
                        attachSortListeners();
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

        function submit(){
            submitForm();
            document.getElementById('commentForm').submit();
        }

        // Ajoutez des écouteurs d'événements aux champs de saisie de commentaire pour déclencher l'enregistrement automatique
        document.addEventListener('DOMContentLoaded', function() {
            const commentInputs = document.querySelectorAll('textarea[name^="commentaire"]');
            commentInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    submit();
                });
            });
        });

        // Ajoutez des écouteurs d'événements aux trie de la colonne scoring
        document.addEventListener('DOMContentLoaded', function() {
            let ascending = false;
            const maiSortIcon = document.getElementById('maiSortIcon');
            // Fonction pour trier les lignes du tableau en fonction des valeurs de la colonne "Mai"
            function sortTableByMai() {
                const table = document.getElementById('scoringTable');
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr'));

                // Trie les lignes du tableau en fonction des valeurs de la colonne "Mai"
                rows.sort((a, b) => {
                    const aMai = parseFloat(a.cells[4].textContent);
                    const bMai = parseFloat(b.cells[4].textContent);
                    return ascending ? aMai - bMai : bMai - aMai;
                });

                // Remplace les lignes du tableau avec les lignes triées
                rows.forEach(row => tbody.appendChild(row));
            }

            // Ajoute un gestionnaire d'événements pour le clic sur l'en-tête de la colonne "Mai"
            const maiHeader = document.getElementById('maiHeader');
            maiHeader.addEventListener('click', function() {
                // console.log("Sorted scoring");
                ascending = !ascending;
                maiSortIcon.className = ascending ? 'mai-sort-icon fas fa-sort-amount-up' : 'mai-sort-icon fas fa-sort-amount-down';
                sortTableByMai();
            });

            sortTableByMai();
        });


    </script>