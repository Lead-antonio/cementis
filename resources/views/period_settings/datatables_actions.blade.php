{!! Form::open(['route' => ['periodSettings.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    @can('periodSettings.show')    
        <a href="{{ route('periodSettings.show', $id) }}" class='btn btn-primary btn-xs'>
            <i class="fa fa-eye"></i>
        </a>
    @endcan
    @can('periodSettings.edit')    
        <a href="{{ route('periodSettings.edit', $id) }}" class='btn btn-success btn-xs'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    @can('periodSettings.destroy')    
        {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'class' => 'btn btn-danger btn-xs',
            'onclick' => 'return confirm("'.__('crud.are_you_sure').'")'
        ]) !!}
    @endcan
</div>
{!! Form::close() !!}
