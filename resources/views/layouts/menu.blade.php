@php
$urlAdmin=config('fast.admin_prefix');
@endphp

@can('dashboard')
@php
$isDashboardActive = Request::is($urlAdmin);
@endphp
<li class="nav-item">
    <a href="{{ route('dashboard') }}" class="nav-link {{ $isDashboardActive ? 'active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>@lang('menu.dashboard')</p>
    </a>
</li>
@endcan

{{-- @can('generator_builder.index') 
 @php --}}
{{-- @can('generator_builder.index')

// @php
// $isUserActive = Request::is($urlAdmin.'*generator_builder*');
// @endphp
// <li class="nav-item">
//     <a href="{{ route('generator_builder.index') }}" class="nav-link {{ $isUserActive ? 'active' : '' }}">
//         <i class="nav-icon fas fa-coins"></i>
//         <p>@lang('menu.generator_builder.title')</p>
//     </a>
// </li>
// @endcan  


{{--@can('attendances.index')
@php
$isUserActive = Request::is($urlAdmin.'*attendances*');
@endphp

<li class="nav-item">
    <a href="{{ route('attendances.index') }}" class="nav-link {{ $isUserActive ? 'active' : '' }}">
        <i class="nav-icon fas fa-calendar-alt"></i>

        <p>@lang('menu.attendances.title')</p>
    </a>
</li>
@endcan

@canany(['users.index','roles.index','permissions.index'])
@php
$isUserActive = Request::is($urlAdmin.'*users*');
$isRoleActive = Request::is($urlAdmin.'*roles*');
$isPermissionActive = Request::is($urlAdmin.'*permissions*');
@endphp
<li class="nav-item {{($isUserActive||$isRoleActive||$isPermissionActive)?'menu-open':''}} ">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-shield-virus"></i>
        <p>
            @lang('menu.user.title')
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('users.index')
        <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link {{ $isUserActive ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>
                    @lang('menu.user.users')
                </p>
            </a>
        </li>
        @endcan
        @can('roles.index')
        <li class="nav-item">
            <a href="{{ route('roles.index') }}" class="nav-link {{ $isRoleActive ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-shield"></i>
                <p>
                    @lang('menu.user.roles')
                </p>
            </a>
        </li>
        @endcan
        @can('permissions.index')
        <li class="nav-item ">
            <a href="{{ route('permissions.index') }}" class="nav-link {{ $isPermissionActive ? 'active' : '' }}">
                <i class="nav-icon fas fa-shield-alt"></i>
                <p>
                    @lang('menu.user.permissions')
                </p>
            </a>
        </li>
        @endcan
    </ul>
</li>
@endcan
@can('fileUploads.index')
<li class="nav-item">
    <a href="{{ route('fileUploads.index') }}" class="nav-link {{ Request::is('fileUploads*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-file-alt"></i>
        <p>@lang('models/fileUploads.plural')</p>
    </a>
</li>
@endcan


<li class="nav-item">
    <a href="{{ route('messages.index') }}"
       class="nav-link {{ Request::is('messages*') ? 'active' : '' }}">
       <i class="nav-icon fas fa-comment"></i>
        <p>@lang('models/messages.plural')</p>
    </a>
</li> --}}

<li class="nav-item">
    <a href="{{ route('events.table.scoring') }}"
       class="nav-link {{ Request::is('rotations*') ? 'active' : '' }}">
       <i class="nav-icon fas fa-bullseye"></i>
        <p>@lang('models/events.fields.tab_scoring')</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('chauffeurs.index') }}"
       class="nav-link {{ Request::is('chauffeurs*') ? 'active' : '' }}">
       <i class="nav-icon fas fa-user-circle"></i> 
        <p>@lang('models/chauffeurs.plural')</p>
    </a>
</li>

<li class="nav-item has-treeview">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-calendar"></i> 
        <p>
            @lang('models/importExcels.fields.import_calendar')
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>

    <ul class="nav nav-treeview" style="padding-left:8px">
        <li class="nav-item">
            <a href="{{ route('importcalendars.index') }}" class="nav-link {{ Request::is('penalites*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-list"></i>
                <p>@lang('models/importExcels.fields.import_list')</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('importExcels.index') }}" class="nav-link {{ Request::is('penalites*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-file"></i>
                <p>@lang('models/importExcels.fields.import_detail')</p>
            </a>
        </li>
        <!-- Autres éléments de sous-menu peuvent être ajoutés ici -->
    </ul>
</li>

<li class="nav-item">
    <a href="{{ route('events.index') }}"
       class="nav-link {{ Request::is('events*') ? 'active' : '' }}">
       <i class="nav-icon fas fa-calendar"></i>
        <p>@lang('models/events.plural')</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('penaliteChauffeurs.index') }}"
       class="nav-link {{ Request::is('penaliteChauffeurs*') ? 'active' : '' }}">
       <i class="nav-icon fas fa-car-crash"></i> 
        <p>@lang('models/penaliteChauffeurs.plural')</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('events.scoring') }}"
       class="nav-link {{ Request::is('rotations*') ? 'active' : '' }}">
       <i class="nav-icon fas fa-bullseye"></i>
        <p>@lang('models/events.fields.detail_penalite')</p>
    </a>
</li>

{{-- <li class="nav-item">
    <a href="{{ route('event.routes') }}"
       class="nav-link {{ Request::is('rotations*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-thermometer"></i>
        <p>@lang('models/events.fields.route')</p>
    </a>
</li> --}}

{{-- <li class="nav-item">
    <a href="{{ route('rotations.index') }}"
       class="nav-link {{ Request::is('rotations*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-location-arrow"></i>
        <p>@lang('models/rotations.plural')</p>
    </a>
</li> --}}


{{-- <li class="nav-item">
    <a href="{{ route('get.data.api') }}"
       class="nav-link {{ Request::is('rotations*') ? 'active' : '' }}">
       <i class="nav-icon fas fa-database"></i>
        <p>@lang('models/rotations.fields.event')</p>
    </a>
</li> --}}



{{-- <li class="nav-item">
    <a href="{{ route('parametres.index') }}"
       class="nav-link {{ Request::is('parametres*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-wrench"></i>
        <p>@lang('models/parametres.plural')</p>
    </a>
</li> --}}

{{-- <li class="nav-item">
    <a href="{{ route('dataExcels.index') }}" class="nav-link {{ Request::is('dataExcels*') ? 'active' : '' }}">
        <i class="fas fa-file-excel"></i> <!-- Icône Excel -->
        <p>@lang('models/dataExcels.plural')</p>
    </a>
</li>

 
    <a href="{{ route('fichierExcels.index') }}"
       class="nav-link {{ Request::is('fichierExcels*') ? 'active' : '' }}">
       <i class="nav-icon fas fa-file"></i> 
       <p>@lang('models/fichierExcels.plural')</p>
    </a>
</li> --}}

{{-- <li class="nav-item">
    <a href="{{ route('importExcels.index') }}"
       class="nav-link {{ Request::is('importExcels*') ? 'active' : '' }}">
       <i class="nav-icon fas fa-file"></i> 
        <p>@lang('models/importExcels.plural')</p>
    </a>
</li> --}}



<li class="nav-item has-treeview" >
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-database"></i>
        <p>
            @lang('menu.database.title')
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview" style="padding-left:8px">
        <li class="nav-item">
            <a href="{{ route('penalites.index') }}" class="nav-link {{ Request::is('penalites*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-exclamation-triangle"></i>
                <p>@lang('models/penalites.plural')</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('transporteurs.index') }}"
               class="nav-link {{ Request::is('transporteurs*') ? 'active' : '' }}">
               <i class="nav-icon fas fa-truck"></i>
                <p>@lang('models/transporteurs.plural')</p>
            </a>
        </li>
        
    </ul>
</li>

{{-- <li class="nav-item">
    <a href="{{ route('penalites.index') }}"
       class="nav-link {{ Request::is('penalites*') ? 'active' : '' }}">
       <i class="nav-icon fas fa-exclamation-triangle"></i> 
        <p>@lang('models/penalites.plural')</p>
    </a>
</li> --}}


{{-- <li class="nav-item">
    <a href="{{ route('fichierExcels.index') }}"
       class="nav-link {{ Request::is('fichierExcels*') ? 'active' : '' }}">
        <p>@lang('models/fichierExcels.plural')</p>
    </a>
</li> --}}

{{-- <li class="nav-item">
    <a href="{{ route('importcalendars.index') }}"
       class="nav-link {{ Request::is('importcalendars*') ? 'active' : '' }}">
        <p>@lang('models/importcalendars.plural')</p>
    </a>
</li> --}}


