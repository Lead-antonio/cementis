<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: linear-gradient(145deg, #0d7a8a, #024d56);">
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{url('images/alpha_ciment.jpg')}}" style="border-radius: 16% !important"
             alt="{{ config('app.name') }} Logo"
             class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light" style="font-weight: bold !important;">{{ config('app.name') }}</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @include('layouts.menu')
            </ul>
        </nav>
    </div>


</aside>
{{-- background: linear-gradient(145deg, #0d7a8a, #024d56); // Bleu-vert premium
background: linear-gradient(145deg, #000000, #a24742); --}}
{{-- background: linear-gradient(145deg, #1d4ed8, #1e1b4b); // Bleu profond --}}
