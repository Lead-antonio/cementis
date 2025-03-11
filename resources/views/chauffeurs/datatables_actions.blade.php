{!! Form::open(['route' => ['chauffeurs.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('chauffeurs.show', $id) }}" class='btn btn-primary btn-xs'>
        <i class="fa fa-eye"></i>
    </a>
    <a href="{{ route('chauffeurs.edit', $id) }}" class='btn btn-success btn-xs'>
        <i class="fa fa-edit"></i>
    </a>
    <a href="{{ route('chauffeurs.edit_story', $id) }}" class='btn btn-info btn-xs'>
        <i class="fa fa-comment"></i>
    </a>

    {{-- <button type="button" class='btn btn-info btn-xs' data-toggle="modal" data-target="#commentModal-{{ $id }}">
        <i class="fa fa-comment"></i>
    </button> --}}

    @if(Auth::user()->hasRole("supper-admin") )
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'class' => 'btn btn-danger btn-xs',
            'onclick' => 'return confirm("'.__('crud.are_you_sure').'")'
        ]) !!}
    @endif
</div>
{!! Form::close() !!}

