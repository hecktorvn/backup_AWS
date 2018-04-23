@php
    $titulo_pagina = "Manifesto";
@endphp

@extends('layouts.principal')
@section('content')
<script src="{{ asset('js/pages/manifesto/manifesto.js') }}"></script>
<div class="row" id="manifesto">
    <div class="main-content container-fluid pt-0">
        <div contentLoading style="width: 85%;" class="card">
            <div class="main-content container-fluid be-loading p-0">

                <div class="tab-container border-bottom">
                    <ul role="tablist" class="nav nav-tabs nav-tabs-warning">
                        <li class="nav-item"><a id="emissao_a" href="#emissao" data-toggle="tab" role="tab" class="nav-link active show" aria-selected="true">Emissão</a></li>
                        <li class="nav-item"><a id="dados_a" href="#dados" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">Dados</a></li>
                        <li class="nav-item"><a id="transporte_a" href="#transporte" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">Transporte</a></li>
                        <li class="nav-item"><a id="seguro_a" href="#seguro" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">Seguro</a></li>
                    </ul>

                    <div class="tab-content p-0 m-0">
                        <div id="emissao" role="tabpanel" class="tab-pane active show">
                            @include('manifesto.emissao')
                        </div>

                        <div id="dados" role="tabpanel" class="tab-pane">
                            @include('manifesto.dados')
                        </div>

                        <div id="transporte" role="tabpanel" class="tab-pane">
                            @include('manifesto.transporte')
                        </div>

                        <div id="seguro" role="tabpanel" class="tab-pane">
                            @include('manifesto.seguro')
                        </div>
                    </div>
                </div>

                <!-- FIM CARD-BODY !-->
            </div>
            <!-- FIM CONTENTLOAD !-->
        </div>
        <!-- FIM ROW !-->
    </div>
</div>

@if(!empty($mdfe))
    <script>
        $(function(){
            $.manifesto.data = {!! json_encode($mdfe) !!};
            $.manifesto.seguro = {!! json_encode($seguro) !!};
            $.manifesto.itens = {!! json_encode($cte) !!};
            $.manifesto.drawData();
        });
    </script>
@endif

@endsection
