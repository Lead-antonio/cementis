{!! Form::open(['route' => ['chauffeurUpdateStories.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>

    @if ($validation == false)
        <button type="button" class='btn btn-success saveButton' data-id="{{ $id }}">
           Validé
        </button> 
    @else
        Validé
    @endif
</div>
{!! Form::close() !!}

<script>
    // Sélectionner tous les boutons avec la classe saveButton
    document.querySelectorAll('.saveButton').forEach(button => {
        button.addEventListener('click', function() {
            const chauffeurId = this.getAttribute('data-id'); // Récupérer l'ID du chauffeur
            Swal.fire({
                title: 'Confirmer la validation',
                text: 'Voulez-vous vraiment valider ce chauffeur ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui, valider',
                cancelButtonText: 'Annuler',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Soumettre le formulaire correspondant à l'ID du chauffeur
                    document.getElementById('form-delete-' + chauffeurId).submit();
                }
            });
        });
    });
</script>