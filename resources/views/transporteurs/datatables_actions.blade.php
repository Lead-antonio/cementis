{!! Form::open(['route' => ['transporteurs.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    @can('transporteurs.show')    
        <a href="{{ route('transporteurs.show', $id) }}" class='btn btn-success btn-xs'>
            <i class="fa fa-eye"></i>
        </a>
    @endcan
    @can('transporteurs.edit')    
        <a href="{{ route('transporteurs.edit', $id) }}" class='btn btn-primary btn-xs'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    @can('transporteurs.destroy')
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
