<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#voirButton').click(function() {
            var chauffeur = $('#chauffeurSelect').val();
            // var mois = $('#moisSelect').val();

            console.log(chauffeur);
            
            $.ajax({
                url: "{{ route('scoring.monthly', ['chauffeur' => ':chauffeur']) }}".replace(':chauffeur', chauffeur),
                method: 'GET',
                data: { chauffeur: chauffeur },
                success: function(response) {
                    $('#resultats').html(response);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
