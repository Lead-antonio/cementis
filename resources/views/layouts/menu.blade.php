@php
    $urlAdmin = config('fast.admin_prefix');
@endphp

@can('dashboard')
    @php
        $isDashboardActive = Request::is($urlAdmin);
    @endphp
    <li class="nav-item">
        <a href="{{ route('dashboard') }}" class="nav-link {{ $isDashboardActive ? 'active' : '' }}" onclick="submitForm()">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>@lang('menu.dashboard')</p>
        </a>
    </li>
@endcan

{{-- @can('generator_builder.index')
    @php
        $isUserActive = Request::is($urlAdmin . '*generator_builder*');
    @endphp
    <li class="nav-item">
        <a href="{{ route('generator_builder.index') }}" class="nav-link {{ $isUserActive ? 'active' : '' }}">
            <i class="nav-icon fas fa-coins"></i>
            <p>@lang('menu.generator_builder.title')</p>
        </a>
    </li>
@endcan --}}


@can('attendances.index')
    @php
        $isUserActive = Request::is($urlAdmin . '*attendances*');
    @endphp

    {{-- <li class="nav-item">
    <a href="{{ route('attendances.index') }}" class="nav-link {{ $isUserActive ? 'active' : '' }}">
        <i class="nav-icon fas fa-calendar-alt"></i>

        <p>@lang('menu.attendances.title')</p>
    </a>
</li> --}}
@endcan

@canany(['users.index', 'roles.index', 'permissions.index'])
    @php
        $isUserActive = Request::is($urlAdmin . '*users*');
        $isRoleActive = Request::is($urlAdmin . '*roles*');
        $isPermissionActive = Request::is($urlAdmin . '*permissions*');
    @endphp

    @if (Auth::user()->hasRole('supper-admin'))
        <li class="nav-item {{ $isUserActive || $isRoleActive || $isPermissionActive ? 'menu-open' : '' }} ">
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
    @endif
@endcan

<li class="nav-item">
    <a href="{{ route('new.scoring') }}" class="nav-link {{ Request::is('rotations*') ? 'active' : '' }}"
        onclick="submitForm()">
        <i class="nav-icon fas fa-bullseye"></i>
        <p>@lang('models/events.fields.scoring')</p>
    </a>
</li>


{{-- <li class="nav-item">
    <a href="{{ route('events.table.scoring') }}"
       class="nav-link {{ Request::is('rotations*') ? 'active' : '' }}" onclick="submitForm()">
       <i class="nav-icon fas fa-bullseye"></i>
        <p>@lang('models/events.fields.tab_scoring')</p>
    </a>
</li> --}}


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
            <a href="{{ route('importcalendars.index') }}"
                class="nav-link {{ Request::is('penalites*') ? 'active' : '' }}" onclick="submitForm()">
                <i class="nav-icon fas fa-list"></i>
                <p>@lang('models/importExcels.fields.import_list')</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('importExcels.index') }}"
                class="nav-link {{ Request::is('penalites*') ? 'active' : '' }}" onclick="submitForm()">
                <i class="nav-icon fas fa-file"></i>
                <p>@lang('models/importExcels.fields.import_detail')</p>
            </a>
        </li>
        <!-- Autres éléments de sous-menu peuvent être ajoutés ici -->
    </ul>
</li>

<li class="nav-item">
    <a href="{{ route('infractions.index') }}" class="nav-link {{ Request::is('infractions*') ? 'active' : '' }}"
        onclick="submitForm()">
        <i class="nav-icon fas fa-virus"></i>
        <p>@lang('models/infractions.plural')</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('events.index') }}" class="nav-link {{ Request::is('events*') ? 'active' : '' }}"
        onclick="submitForm()">
        <i class="nav-icon fas fa-calendar"></i>
        <p>@lang('models/events.plural')</p>
    </a>
</li>



<li class="nav-item has-treeview">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-database"></i>
        <p>
            @lang('menu.database.title')
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview" style="padding-left:8px">
        <li class="nav-item">
            <a href="{{ route('chauffeurs.index') }}"
                class="nav-link {{ Request::is('chauffeurs*') ? 'active' : '' }}" onclick="submitForm()">
                <i class="nav-icon fas fa-user-circle"></i>
                <p>@lang('models/chauffeurs.plural')</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('penalites.index') }}" class="nav-link {{ Request::is('penalites*') ? 'active' : '' }}"
                onclick="submitForm()">
                <i class="nav-icon fas fa-exclamation-triangle"></i>
                <p>@lang('models/penalites.plural')</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('transporteurs.index') }}"
                class="nav-link {{ Request::is('transporteurs*') ? 'active' : '' }}" onclick="submitForm()">
                <i class="nav-icon fas fa-truck"></i>
                <p>@lang('models/transporteurs.plural')</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('vehicules.index') }}" class="nav-link {{ Request::is('vehicules*') ? 'active' : '' }}"
                onclick="submitForm()">
                <i class="nav-icon fas fa-car"></i>
                <p>@lang('models/vehicules.plural')</p>
            </a>
        </li>

    </ul>
</li>


<li class="nav-item">
    <a href="{{ route('process.index') }}" class="nav-link {{ Request::is('process*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-microchip"></i>
        <p>@lang('models/process.plural')</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('installateurs.index') }}" class="nav-link {{ Request::is('installateurs*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user"></i>
        <p>@lang('models/installateurs.plural')</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('installations.index') }}" class="nav-link {{ Request::is('installations*') ? 'active' : '' }}">
        <i class="nav-icon fa-brands fa-instalod"></i>
        <p>@lang('models/installations.plural')</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('importNameInstallations.index') }}"
        class="nav-link {{ Request::is('importNameInstallations*') ? 'active' : '' }}">
        <i class="nav-icon fa fa-upload"></i>
        <p>Import installation</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('movements.index') }}" class="nav-link {{ Request::is('movements*') ? 'active' : '' }}">
        <i class="nav-icon fa fa-route"></i>
        <p>@lang('models/movements.plural')</p>
    </a>
</li>



{{-- Importattion générique --}}
@php
    $isImportModelActive = Request::is($urlAdmin . '*importModels*');
    $isFileUploadActive = Request::is($urlAdmin . '*fileUploads*');
@endphp
<li class="nav-item {{ $isImportModelActive || $isFileUploadActive ? 'menu-open' : '' }} ">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-upload"></i>
        <p>
            @lang('menu.import')
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">

        <li class="nav-item">
            <a href="{{ route('fileUploads.index') }}" class="nav-link {{ $isFileUploadActive ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-alt ml-4"></i>
                <p>@lang('models/fileUploads.singular')</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('importModels.index') }}" class="nav-link {{ $isImportModelActive ? 'active' : '' }}">
                <i class="nav-icon fas fa-arrows-alt-h ml-4"></i>
                <p>@lang('models/importModels.plural')</p>
            </a>
        </li>


    </ul>
</li>


<li class="nav-item">
    <a href="{{ route('exportation.view') }}" class="nav-link {{ Request::is('movements*') ? 'active' : '' }}">
        <i class="nav-icon fa fa-file-export"></i>
        <p>Exportation generale</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('periodSettings.index') }}"
       class="nav-link {{ Request::is('periodSettings*') ? 'active' : '' }}">
       <i class="nav-icon fa fa-business-time"></i>
        <p>@lang('models/periodSettings.plural')</p>
    </a>
</li>

