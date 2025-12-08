<div class="card shadow-sm rounded overflow-auto">
    <div id="dataTable" class="card-body table-responsive p-0">
        <form id="commentForm" method="POST" action="{{ route('save.comments') }}">
            @csrf
            <table id="scoringTable" class="table table-hover table-bordered align-middle text-center mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>Nom du chauffeur</th>
                        <th>N° badge</th>
                        <th>Transporteur</th>
                        <th id="maiHeader" class="bg-dark text-white">
                            Scoring
                            <span id="maiSortIcon" class="mai-sort-icon fas fa-sort-amount-down ml-1" style="cursor: pointer"></span>
                        </th>
                        <th>
                            Infraction le plus fréquent
                        </th>
                    </tr>
                </thead>
                <tbody id="scoringBody">
                    @include('score_drivers.rows', ['scoring' => $scoring])
                </tbody>
            </table>
        </form>
        <div id="scoringMeta" data-last-page="{{ $scoring->lastPage() }}" data-current-page="1" style="display:none;"></div>
        <div id="loading" class="text-center my-3" style="display:none;">
            <span class="spinner-border"></span> Chargement...
        </div>
        <div id="noResultsMessage" class="py-3 text-center text-muted" style="display: none;">Aucun résultat trouvé.</div>
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
                var baseUrl = "{{ route('driver.score.excel') }}";
                exportLink.attr('href', baseUrl + '/' + selectedValue);
                
                
                $.ajax({
                    url: "{{ route('driver.score.filter.planning') }}", // Route à laquelle la requête Ajax sera envoyée
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

        $(document).ready(function() {
            let isLoading = false; // Pour éviter les appels AJAX multiples

            $(window).on('scroll', function() {
                var scrollBottom = $(window).scrollTop() + $(window).height();
                var docHeight = $(document).height();

                if(scrollBottom >= docHeight - 200){
                    let meta = $('#scoringMeta');
                    let currentPage = parseInt(meta.data('current-page'));
                    let lastPage = parseInt(meta.data('last-page'));

                    if(currentPage >= lastPage || isLoading) return;

                    isLoading = true;
                    $('#loading').show();

                    let planning = $('#planning').val();
                    let alphaciment = $('#alphaciment_driver').val();

                    $.ajax({
                        url: "{{ route('driver.score') }}",
                        type: 'GET',
                        data: {
                            page: currentPage + 1,
                            planning: planning,
                            alphaciment_driver: alphaciment
                        },
                        success: function(response){
                            if(response.trim().length){
                                $('#scoringTable tbody').append(response);
                                meta.data('current-page', currentPage + 1); // Mise à jour de la page actuelle
                            }
                            $('#loading').hide();
                            isLoading = false;

                            // Réattacher les listeners sur les nouveaux champs de commentaire et tri
                            attachCommentListeners();
                            attachSortListeners();
                        },
                        error: function(err){
                            console.error(err);
                            $('#loading').hide();
                            isLoading = false;
                        }
                    });
                }
            });
        });


    </script>
    <style>
        #scoringTable th {
            vertical-align: middle;
        }

        #scoringTable td, #scoringTable th {
            padding: 0.75rem;
            font-size: 0.95rem;
        }

        .badge {
            font-size: 0.85rem;
            padding: 0.5em 0.75em;
            border-radius: 1rem;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-danger {
            background-color: #fd7e14;
            color: white;
        }

        .badge-dark {
            background-color: #dc3545;
            color: white;
        }

    </style>