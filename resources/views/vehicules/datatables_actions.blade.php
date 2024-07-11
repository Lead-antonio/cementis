{!! Form::open(['route' => ['vehicules.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('vehicules.show', $id) }}" class='btn btn-primary btn-xs'>
        <i class="fa fa-eye"></i>
    </a>
    <a href="{{ route('vehicules.edit', $id) }}" class='btn btn-success btn-xs'>
        <i class="fa fa-edit"></i>
    </a>
    @if(Auth::user()->hasRole("supper-admin") )
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'class' => 'btn btn-danger btn-xs',
            'onclick' => 'return confirm("'.__('crud.are_you_sure').'")'
        ]) !!}
    @endif
    
</div>
{!! Form::close() !!}
