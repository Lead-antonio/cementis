{!! Form::open(['route' => ['chauffeurs.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    @can('chauffeurs.show')    
        <a href="{{ route('chauffeurs.show', $id) }}" class='btn btn-primary btn-xs'>
            <i class="fa fa-eye"></i>
        </a>
    @endcan
    @can('chauffeurs.edit')    
        <a href="{{ route('chauffeurs.edit', $id) }}" class='btn btn-success btn-xs'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    @can('chauffeurs.edit_story')    
        <a href="{{ route('chauffeurs.edit_story', $id) }}" class='btn btn-info btn-xs'>
            <i class="fa fa-comment"></i>
        </a>
    @endcan

    {{-- <button type="button" class='btn btn-info btn-xs' data-toggle="modal" data-target="#commentModal-{{ $id }}">
        <i class="fa fa-comment"></i>
    </button> --}}

    @can('chauffeurs.destroy')    
        @if(Auth::user()->hasRole("supper-admin") )
            {!! Form::button('<i class="fa fa-trash"></i>', [
                'type' => 'submit',
                'class' => 'btn btn-danger btn-xs',
                'onclick' => 'return confirm("'.__('crud.are_you_sure').'")'
            ]) !!}
        @endif
    @endcan
</div>
{!! Form::close() !!}

