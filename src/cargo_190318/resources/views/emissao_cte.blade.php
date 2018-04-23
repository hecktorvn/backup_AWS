@php
    $titulo_pagina = "Emissão de CT-e";
@endphp

@extends('layouts.principal')
@section('content')
<script type='text/javascript' src="{{ asset('js/pages/cte/extend_cliente.js') }}"></script>

@section('buttons')
    <button type="button" data-dismiss="modal" data-button="ok" class="btn btn-space btn-secondary">Ok</button>
    <button type="button" data-dismiss="modal" data-button="cadastrar" class="btn btn-space btn-success">Cadastrar</button>
@endsection

@section('id', 'modal_danger_remetente')
@include('layouts.modal')

<div class="main-content container-fluid pt-0">
    <div class="row">
        <div class="col-lg-7">
            <div class="card" contentLoading>
                <div class="tab-container">
                    <ul role="tablist" class="nav nav-tabs nav-tabs-warning">
                        <li class="nav-item"><a href="#remetente" data-toggle="tab" role="tab" class="nav-link active show" aria-selected="false">Remetente</a></li>
                        <li class="nav-item"><a href="#destinatario" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">Destinatario</a></li>
                        <li class="nav-item"><a href="#tomador" data-toggle="tab" role="tab" class="nav-link" aria-selected="true">Tomador</a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="remetente" role="tabpanel" class="tab-pane active show">
                            @include('cte.remetente')
                        </div>

                        <div id="destinatario" role="tabpanel" class="tab-pane">
                            @include('cte.destinatario')
                        </div>

                        <div id="tomador" role="tabpanel" class="tab-pane">
                            @include('cte.tomador')
                        </div>
                        <!-- FIM TAB-CONTENT !-->
                    </div>
                    <!-- FIM TAB-CONTAINER !-->
                </div>

            </div>
            <!-- FIM CARD !-->
        </div>
        <!-- FIM COL-LG-7 !-->
    </div>
    <!-- FIM ROW !-->
</div>
@endsection
