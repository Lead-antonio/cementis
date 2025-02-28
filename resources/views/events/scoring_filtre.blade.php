<div class="card">
    {{-- <div  class="card-body p-0" style="display: none">
        <table class="table table-bordered table-responsive" width="100%">
            <thead>
                <tr>
                    <th style="text-align: center;">Chauffeur</th>
                    <th style="text-align: center;">Transporteur</th>
                    <th style="text-align: center;">Distance parcourue (Km)</th>
                    <th style="text-align: center;">Accélération brusque (durée)</th>
                    <th style="text-align: center;">Points</th>
                    <th style="text-align: center;">Freinage brusque (durée)</th>
                    <th style="text-align: center;">Points</th>
                    <th style="text-align: center;">Excès de vitesse hors agglomération (durée)</th>
                    <th style="text-align: center;">Points</th>
                    <th style="text-align: center;">Excès de vitesse en agglomération (durée)</th>
                    <th style="text-align: center;">Points</th>
                    <th style="text-align: center;">Survitesse excessive (durée)</th>
                    <th style="text-align: center;">Points</th>
                    <th style="text-align: center;">Temps de conduite maximum dans une journée de travail (durée)</th>
                    <th style="text-align: center;">Points</th>
                    <th style="text-align: center;">Temps de repos hebdomadaire (durée)</th>
                    <th style="text-align: center;">Points</th>
                    <th style="text-align: center;">Temps de repos minimum après une journée de travail (durée)</th>
                    <th style="text-align: center;">Points</th>
                    <th style="text-align: center;">Points totaux</th>
                    <th style="text-align: center;">Scoring</th>
                </tr>
            </thead>
            <tbody>
                @if (count($data) > 0)
                        @foreach ($data as $driver => $events)
                        <tr>
                            <td style="text-align: center;"><a href="{{ route('events.table.scoring', ['chauffeur' => $driver, 'id_planning'  => $selectedPlanning]) }}">{{ $driver }}</a></td>
                            <td style="text-align: center;">{{ $events['transporteur'] }}</td>
                            <td style="text-align: center;">{{ getDistanceTotalDriverInCalendar($driver, $selectedPlanning) }}</td>
                            <td style="text-align: center;">{{ $events['Accélération brusque']['valeur']. " s" }}</td>
                            <td style="text-align: center;">{{ $events['Accélération brusque']['point'] }}</td>
                            <td style="text-align: center;">{{ $events['Freinage brusque']['valeur'] . " s"}}</td>
                            <td style="text-align: center;">{{ $events['Freinage brusque']['point'] }}</td>
                            <td style="text-align: center;">{{ $events['Excès de vitesse hors agglomération']['duree'] . " s"}}</td>
                            <td style="text-align: center;">{{ $events['Excès de vitesse hors agglomération']['point'] }}</td>
                            <td style="text-align: center;">{{ $events['Excès de vitesse en agglomération']['duree'] . " s"}}</td>
                            <td style="text-align: center;">{{ $events['Excès de vitesse en agglomération']['point'] }}</td>
                            <td style="text-align: center;">{{ $events['Survitesse excessive']['duree'] . " s"}}</td>
                            <td style="text-align: center;">{{ $events['Survitesse excessive']['point'] }}</td>
                            <td style="text-align: center;">{{ convertMinuteHeure($events['Temps de conduite maximum dans une journée de travail']['duree'])}}</td>
                            <td style="text-align: center;">{{ $events['Temps de conduite maximum dans une journée de travail']['point'] }}</td>
                            <td style="text-align: center;">{{ convertMinuteHeure($events['Temps de repos hebdomadaire']['duree']) }}</td>
                            <td style="text-align: center;">{{ $events['Temps de repos hebdomadaire']['point'] }}</td>
                            <td style="text-align: center;">{{ convertMinuteHeure($events['Temps de repos minimum après une journée de travail']['duree']) }}</td>
                            <td style="text-align: center;">{{ $events['Temps de repos minimum après une journée de travail']['point'] }}</td>
                            <td style="text-align: center;">{{ $events['total_point'] }}</td>
                            <td style="text-align: center;" class="
                            @php
                                $totalDistance = getDistanceTotalDriverInCalendar($driver, $selectedPlanning);
                                $score = ($totalDistance != 0) ? ($events['total_point'] / $totalDistance) * 100 : 0;
                                if ($score >= 0 && $score <= 2) {
                                    echo 'scoring-green';
                                } elseif ($score > 2 && $score <= 5) {
                                    echo 'scoring-yellow';
                                } elseif ($score > 5 && $score <= 10) {
                                    echo 'scoring-orange';
                                } else {
                                    echo 'scoring-red';
                                }
                            @endphp
                        ">
                            {{ number_format($score, 2) }}
                        </td>

                        </tr>
                    @endforeach
                @else
                    <td colspan="21" style="text-align: center;">Aucun élément</td>
                @endif
            </tbody>
        </table>
    </div> --}}



    <div id="dataTable" class="card-body p-0" >
        <form id="commentForm" method="POST" action="{{ route('save.comments') }}">
            @csrf
            <table id="scoringTable" class="table table-bordered" width="100%">
                <thead>
                    <tr>
                        <th style="text-align: center;background-color: darkgray;">Chauffeur</th>
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
                    @endphp
                    @foreach ($scoring as $item)
                        <tr>
                            <td style="text-align: center;"><a href="{{ route('events.table.scoring', ['chauffeur' => $item->driver->nom, 'id_planning'  => $selectedPlanning]) }}">{{$item->driver->nom}}</a></td>
                            <td style="text-align: center;">{{ $item->transporteur->nom }}</td>
                            <td style="text-align: center;">{{  $item->camion  }}</td>
                            <td style="text-align: center;" class="
                                @php
                                    $score = round($item->point, 2);
                                    $isTruckinCalendarChecked = checkTruckinCalendar($selectedPlanning, $item->camion);
                                    if ($isTruckinCalendarChecked) {
                                        $countCheckedTrucks++; // Incrémentation si le camion est présent dans le calendrier
                                    }
                                    if($score == 0 && $isTruckinCalendarChecked){
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
                            <td>{{ getInfractionWithmaximumPoint($item->driver->id, $selectedPlanning )}}</td>
                            <td style="text-align: center;"><textarea class="form-control" name="commentaire[{{ $item->id }}]" id="" cols="30" rows="2 ">{{ $item->comment }}</textarea></td>
                        </tr>
                    @endforeach
                </tbody>
                {{-- <p>Nombre de camions dans le calendrier : {{ $countCheckedTrucks }}</p> --}}
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
                        const aMai = parseFloat(a.cells[3].textContent);
                        const bMai = parseFloat(b.cells[3].textContent);
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
                var baseUrl = "{{ route('event.exportscoringcard') }}";
                exportLink.attr('href', baseUrl + '/' + selectedValue);
                
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
                    const aMai = parseFloat(a.cells[3].textContent);
                    const bMai = parseFloat(b.cells[3].textContent);
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