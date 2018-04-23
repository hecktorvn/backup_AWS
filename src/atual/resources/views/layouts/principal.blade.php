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
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/lib/datatables/datatables.net-bs4/css/dataTables.bootstrap4.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/lib/material-design-icons/css/material-design-iconic-font.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/lib/perfect-scrollbar/css/perfect-scrollbar.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/lib/jquery.gritter/css/jquery.gritter.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/lib/datetimepicker/css/bootstrap-datetimepicker.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/lib/select2/css/select2.min.css') }}"/>

    <!--[if lt IE 9]>
     <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
     <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="{{ asset('assets/lib/jquery/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/lib/jquery.maskmoney/jquery.maskmoney.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/lib/jquery.mask/jquery.maskedinput.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/typeahead.bundle.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/hogan-3.0.1.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/AutoComplete.js') }}" type="text/javascript"></script>
</head>
<body class="be-loading-active be-loading">
    <div class="be-spinner" id="loadingBody">
    	<svg width="40px" height="40px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
    	        <circle fill="none" stroke-width="4" stroke-linecap="round" cx="33" cy="33" r="30" class="circle"></circle>
    	</svg>
    </div>

    @component('components.modal', ['id'=>'modal_default'])
    @endcomponent
    <div class="be-wrapper">
        @include('layouts.topo')
        @include('layouts.sidebar')

        <div class="be-content">

            @if(isset($titulo_pagina))
            <div class="page-head">
                <h2 class="page-head-title">{{ $titulo_pagina }}</h2>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb page-head-nav">
                        @php
                            $menu = HomeController::getMenu(url('/'));
                            $menuAtual = @$menu['LIST'][Request::url()];

                            if(!isset($menuAtual['CAMINHO']) && isset($linkPage)){
                                $caminho = $linkPage;
                            } else {
                                $caminho = $menuAtual['CAMINHO'];
                            }

                            $listItem = explode('/', $caminho);
                            $newUrl = '';
                        @endphp

                        @foreach($listItem as $item)
                            @php
                                $newUrl = strtolower($item) . '/';
                                if($menuAtual['NOME'] == $item){
                                    $url = Request::url() . '#';
                                } else {
                                    $url = $newUrl;
                                }
                            @endphp
                            <li class="breadcrumb-item {{ $menuAtual['NOME'] == $item ? 'active' : '' }}"><a href="{{ $url }}">{{ $item }}</a></li>
                        @endforeach
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
<script src="{{ asset('assets/lib/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/lib/select2/js/select2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/lib/select2/js/select2.full.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/js/app.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/app-form-masks.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/app-ui-notifications.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/app-loaders.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/app-form-elements.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/lib/datatables/datatables.net/js/jquery.dataTables.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/lib/datatables/datatables.net-bs4/js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>

@yield('endPage')
<script src="{{ asset('js/visibilityChanged.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/script.js') }}" type="text/javascript"></script>
