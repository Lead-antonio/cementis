{!! Form::open(['route' => ['incidentVehicules.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('incident_vehicule.detail', $id) }}" class='btn btn-success btn-xs'>
        <i class="fa fa-eye"></i>
    </a>

    <a href="{{ route('incidentVehicules.edit', $id) }}" class='btn btn-primary btn-xs'>
        <i class="fa fa-edit"></i>
    </a>
    {!! Form::button('<i class="fa fa-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-xs',
        'onclick' => 'return confirm("'.__('crud.are_you_sure').'")'
    ]) !!}
</div>
{!! Form::close() !!}
