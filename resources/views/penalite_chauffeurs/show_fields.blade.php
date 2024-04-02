<div class="row col-sm-12">
    <div class="col-sm-6">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"><i class="fas fa-wheel"></i> {!! Form::label('nom_chauffeur', __('models/penaliteChauffeurs.fields.chauffeur').':') !!}</h4>
          <p class="card-text">{{ $penaliteChauffeur->related_driver->nom }}</p>
  
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"><i class="fas fa-truck"></i> {!! Form::label('matricule', __('models/penaliteChauffeurs.fields.matricule').':') !!}</h4>
          <p class="card-text">{{ $penaliteChauffeur->related_calendar->camion }}</p>
        </div>
      </div>
    </div>
</div>

<div class="row col-sm-12">
    <div class="col-sm-6">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"><i class="fas fa-compass"></i> {!! Form::label('id_calendar', __('models/penaliteChauffeurs.fields.id_calendar').':') !!}</h4>
          <p class="card-text">{{ $penaliteChauffeur->related_calendar->adresse_livraison }}</p>
  
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"><i class="fas fa-calendar"></i> {!! Form::label('event', __('models/penaliteChauffeurs.fields.event').':') !!}</h4>
          <p class="card-text">{{ $penaliteChauffeur->related_event->type }}</p>
        </div>
      </div>
    </div>
</div>

<div class="row col-sm-12">
    <div class="col-sm-6">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"><svg xmlns="http://www.w3.org/2000/svg" style="width: 4%;" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-384c13.3 0 24 10.7 24 24V264c0 13.3-10.7 24-24 24s-24-10.7-24-24V152c0-13.3 10.7-24 24-24zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/></svg> {!! Form::label('point_penalite', __('models/penaliteChauffeurs.fields.point_penalite').':') !!}</h4>
          <p class="card-text">{{ $penaliteChauffeur->related_penalite->point_penalite }}</p>
  
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"><i class="fas fa-calendar"></i> {!! Form::label('date', __('models/penaliteChauffeurs.fields.date').':') !!}</h4>
          <p class="card-text">{{ $penaliteChauffeur->date }}</p>
        </div>
      </div>
    </div>
</div>

