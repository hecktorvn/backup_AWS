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
        <div style="width: 85%;">
            <div class="card" contentLoading>

                <div class="row p-4">
                    <div class="col-2">
                        <label for="static_status" class="mb-0">Status</label>
                        <input type="text" readonly id="static_status" name="status" class="form-control form-control-sm">
                    </div>

                    <div class="col">
                        <label for="static_chave" class="mb-0">Chave de acesso</label>
                        <input type="text" readonly id="static_chave" name="chave" class="form-control form-control-sm">
                    </div>

                    <div class="col-2">
                        <label for="static_numero" class="mb-0">Número</label>
                        <input type="text" readonly id="static_numero" name="numero" class="form-control form-control-sm">
                    </div>

                    <div class="col-2">
                        <label for="static_versao" class="mb-0">Versão XML</label>
                        <input type="text" readonly id="static_versao" name="versao" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="tab-container">
                    <ul role="tablist" class="nav nav-tabs nav-tabs-warning">
                        <li class="nav-item"><a href="#dados" data-toggle="tab" role="tab" class="nav-link active show" aria-selected="true">Dados</a></li>
                        <li class="nav-item"><a href="#remetente" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">Remetente</a></li>
                        <li class="nav-item"><a href="#destinatario" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">Destinatario</a></li>
                        <li class="nav-item"><a href="#tomador" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">Tomador</a></li>
                        <li class="nav-item"><a href="#documentos" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">Documentos</a></li>
                    </ul>

                    <div class="tab-content p-0">
                        <div id="dados" role="tabpanel" class="tab-pane active show p-4">
                            @include('cte.dados')
                        </div>

                        <div id="remetente" role="tabpanel" class="tab-pane p-4">
                            @include('cte.remetente')
                        </div>

                        <div id="destinatario" role="tabpanel" class="tab-pane p-4">
                            @include('cte.destinatario')
                        </div>

                        <div id="tomador" role="tabpanel" class="tab-pane p-4">
                            @include('cte.tomador')
                        </div>

                        <div id="documentos" role="tabpanel" class="tab-pane">
                            @include('cte.documentos')
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
