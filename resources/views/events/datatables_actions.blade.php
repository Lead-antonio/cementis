{!! Form::open(['route' => ['events.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    @can('events.show')
        <a href="{{ route('events.show', $id) }}" class='btn btn-primary btn-xs'>
            <i class="fa fa-eye"></i>
        </a>
    @endcan
    @can('events.edit')    
        <a href="{{ route('events.edit', $id) }}" class='btn btn-success btn-xs'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    @can('events.destroy')    
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'class' => 'btn btn-danger btn-xs',
            'onclick' => 'return confirm("'.__('crud.are_you_sure').'")'
        ]) !!}
    @endcan
</div>
{!! Form::close() !!}
