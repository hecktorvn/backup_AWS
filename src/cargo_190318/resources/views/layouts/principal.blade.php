<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="{{ asset('/') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title or 'OrionCargo'}}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/lib/material-design-icons/css/material-design-iconic-font.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/lib/perfect-scrollbar/css/perfect-scrollbar.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/lib/jquery.gritter/css/jquery.gritter.css') }}"/>

    <!--[if lt IE 9]>
     <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
     <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="{{ asset('assets/lib/jquery/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/lib/jquery.maskedinput/jquery.maskedinput.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/typeahead.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/hogan-3.0.1.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/AutoComplete.js') }}" type="text/javascript"></script>
</head>
<body>
    <div class="be-wrapper">
        @include('layouts.topo')
        @include('layouts.sidebar')

        <div class="be-content">

            @if(isset($titulo_pagina))
            <div class="page-head">
                <h2 class="page-head-title">{{ $titulo_pagina }}</h2>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb page-head-nav">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Pages</a></li>
                        <li class="breadcrumb-item active">{{ $titulo_pagina }}</li>
                    </ol>
                </nav>
            </div>
            @endif

            @yield('content')
        </div>
        @include('layouts.right_sidebar')
    </div>
</body>
</html>

<script src="{{ asset('assets/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/lib/bootstrap/dist/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/lib/jquery.gritter/js/jquery.gritter.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/js/app.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/app-form-masks.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/app-ui-notifications.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/app-loaders.js') }}" type="text/javascript"></script>

<script src="{{ asset('js/script.js') }}" type="text/javascript"></script>
