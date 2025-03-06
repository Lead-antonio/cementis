{!! Form::open(['route' => ['chauffeurUpdateStories.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>

    @if ($validation == false)
        <button type="button" class='btn btn-success btn-xs' data-toggle="modal" data-target="#commentModal-{{ $id }}">
            <i class="fa fa-check"> Validé</i>
        </button> 
    @else
        Validé
    @endif
    {{-- <a href="{{ route('chauffeurUpdateStories.show', $id) }}" class='btn btn-default btn-xs'>
        <i class="fa fa-eye"></i>
    </a>
    <a href="{{ route('chauffeurUpdateStories.edit', $id) }}" class='btn btn-default btn-xs'>
        <i class="fa fa-edit"></i>
    </a>
    {!! Form::button('<i class="fa fa-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-xs',
        'onclick' => 'return confirm("'.__('crud.are_you_sure').'")'
    ]) !!} --}}
</div>
{!! Form::close() !!}


<div class="modal fade" id="commentModal-{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel-{{ $id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentModalLabel-{{ $id }}">Voulez-vous valider cet mise à jour?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                {!! Form::submit('Enregistrer', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
