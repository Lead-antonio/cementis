{!! Form::open(['route' => ['importExcels.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    @can('importExcels.show')    
        <a href="{{ route('importExcels.show', $id) }}" class='btn btn-primary btn-xs'>
            <i class="fa fa-eye"></i>
        </a>
    @endcan
    @can('importExcels.edit')    
        <a href="{{ route('importExcels.edit', $id) }}" class='btn btn-success btn-xs'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    @can('importExcels.destroy')    
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'class' => 'btn btn-danger btn-xs',
            'onclick' => 'return confirm("'.__('crud.are_you_sure').'")'
        ]) !!}
    @endcan
</div>
{!! Form::close() !!}
