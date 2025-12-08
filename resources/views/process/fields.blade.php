<div class="row">
    <!-- LIGNE 1 : Ordre + Nom -->
    <div class="col-md-3">
        {!! Form::label('order', __('models/process.fields.order').':', ['class' => 'fw-bold']) !!}
        {!! Form::number('order', null, [
            'class' => 'form-control',
            'min' => '0',
            'placeholder' => 'Ordre'
        ]) !!}
    </div>

    <div class="col-md-9">
        {!! Form::label('name', __('models/process.fields.name').':', ['class' => 'fw-bold']) !!}
        {!! Form::text('name', null, [
            'class' => 'form-control',
            'placeholder' => 'Nom du process'
        ]) !!}
    </div>

    <!-- LIGNE 2 : Description -->
    <div class="col-md-12 mt-3">
        {!! Form::label('description', __('models/process.fields.description').':', ['class' => 'fw-bold']) !!}
        {!! Form::textarea('description', null, [
            'class' => 'form-control',
            'rows' => 7,
            'placeholder' => 'Description...'
        ]) !!}
    </div>

</div>

