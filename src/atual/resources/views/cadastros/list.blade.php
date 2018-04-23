@extends('layouts.principal')
@php
    $namePage = ucfirst( strtolower($dataList['name']) );
    if(!isset($dataList['options']['title'])) $titulo_pagina = "Cadastro de {$namePage}s";
    else $titulo_pagina = $dataList['options']['title'];

    $dataTable = [
        'name' => $dataList['name'],
        'url' => "lista/{$dataList['name']}",
        'campos' => array_keys($dataList['header']),
        'options' => $dataList['options'],
        'pk' => @$dataList['pk']
    ];
@endphp

@section('content')
@parent
<div class="main-content container-fluid pt-0">
    <div class="row">
        <div style="min-width:955px;">
            <div class="card card-border-color card-border-color-primary">
                <div class="main-content container-fluid p-0">
                    <table id="table1" datatablesList style="width:100%;" class="table table-striped table-hover table-fw-widget">
                        <thead>
                            <tr>
                                @foreach($dataList['header'] as $campo=>$name)
                                    <th {{ @$dataList['ordem'] == $campo ? 'order' : '' }}>{{ $name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@section('endPage')
    <script src="{{ asset('assets/lib/datatables/datatables.net-buttons/js/dataTables.buttons.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/lib/datatables/datatables.net-buttons/js/buttons.html5.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/lib/datatables/datatables.net-buttons/js/buttons.flash.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/lib/datatables/datatables.net-buttons/js/buttons.print.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/lib/datatables/datatables.net-buttons/js/buttons.colVis.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/lib/datatables/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/app-tables-datatables.js') }}" type="text/javascript"></script>

    <script>
        $(function(){
            window.__table =  {!!json_encode($dataTable)!!};
            App.dataTables();
        });
    </script>
@endsection
@endsection
