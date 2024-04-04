{!! Form::open(['route' => ['importcalendars.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('import_excels.detail_liste_importation', $id) }}" class='btn btn-success btn-xs'>
        <i class="fa fa-eye"></i>
    </a>
    
    {{-- <a href="{{ route('importcalendars.edit', $id) }}" class='btn btn-default btn-xs'>
        <i class="fa fa-edit"></i>
    </a> --}}
    {!! Form::button('<i class="fa fa-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-xs',
        'onclick' => 'return confirm("'.__('crud.are_you_sure').'")'
    ]) !!}
</div>
{!! Form::close() !!}
