@php
    $titulo_pagina = "Emissão de CT-e";
@endphp

@extends('layouts.principal')
@section('content')
<script type='text/javascript' src="{{ asset('js/pages/cte/extend_cliente.js') }}"></script>

@component('components.modal')
    @slot('buttons')
        <button type="button" data-dismiss="modal" data-button="ok" class="btn btn-space btn-secondary">Ok</button>
        <button type="button" data-dismiss="modal" data-button="cadastrar" class="btn btn-space btn-success">Cadastrar</button>
    @endslot

    @slot('id', 'modal_danger_remetente')
@endcomponent

<div class="main-content container-fluid pt-0">
    <div class="row" id="emissao_cte">
        <div style="width: 85%;">
            <div class="card" contentLoading>

                <div class="row p-4" id="dadosCTe">
                    <div class="col-2">
                        <label for="static_status" class="mb-0">Status</label>
                        <input type="text" readonly id="static_status" name="STATUS" class="form-control form-control-sm">
                    </div>

                    <div class="col">
                        <label for="static_chave" class="mb-0">Chave de acesso</label>
                        <input type="text" readonly id="static_chave" data-mask="notafiscal" name="CHAVE" class="form-control form-control-sm">
                    </div>

                    <div class="col-2">
                        <label for="static_numero" class="mb-0">Número</label>
                        <input type="text" readonly id="static_numero" name="CODIGO" class="form-control form-control-sm">
                    </div>

                    <div class="col-2">
                        <label for="static_versao" class="mb-0">Versão XML</label>
                        <input type="text" value="3.00" readonly id="static_versao" name="VERSAO" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="tab-container border-bottom">
                    <ul role="tablist" class="nav nav-tabs nav-tabs-warning">
                        <li class="nav-item"><a id="dados_a" href="#dados" data-toggle="tab" role="tab" class="nav-link active show" aria-selected="true">Dados</a></li>
                        <li class="nav-item"><a id="remetente_a" href="#remetente" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">Remetente</a></li>
                        <li class="nav-item"><a id="destinatario_a" href="#destinatario" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">Destinatário</a></li>
                        <li class="nav-item"><a id="tomador_a" href="#tomador" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">Tomador</a></li>
                        <li class="nav-item"><a id="documentos_a" href="#documentos" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">Documentos</a></li>
                        <li class="nav-item"><a id="seguro_a" href="#seguro" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">Seguro</a></li>
                        <li class="nav-item"><a id="valores_a" href="#valores" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">Valores</a></li>
                        <li class="nav-item"><a id="resumo_a" href="#resumo" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">Resumo</a></li>
                    </ul>

                    <div class="tab-content p-0 m-0">
                        <div id="dados" role="tabpanel" class="tab-pane active show p-4">
                            @include('cte.dados')
                        </div>

                        <div id="remetente" role="tabpanel" class="tab-pane">
                            @include('cte.remetente')
                        </div>

                        <div id="destinatario" role="tabpanel" class="tab-pane">
                            @include('cte.destinatario')
                        </div>

                        <div id="tomador" role="tabpanel" class="tab-pane">
                            @include('cte.tomador')
                        </div>

                        <div id="documentos" role="tabpanel" class="tab-pane">
                            @include('cte.documentos')
                        </div>

                        <div id="seguro" role="tabpanel" class="tab-pane p-4">
                            @include('cte.seguro')
                        </div>

                        <div id="valores" role="tabpanel" class="tab-pane">
                            @include('cte.valores')
                        </div>

                        <div id="resumo" role="tabpanel" class="tab-pane">
                            @include('cte.resumo')
                        </div>
                        <!-- FIM TAB-CONTENT !-->
                    </div>
                    <!-- FIM TAB-CONTAINER !-->
                </div>

                <div class="row p-4">
                    <div class="col-12">
                        <button id="gravarCTe" class="btn btn-space btn-success">Salvar</button>
                        <button id="enviarCTe" class="btn hidden btn-space btn-secondary">Enviar</button>
                        <button id="recalcularCTe" class="btn hidden btn-space btn-primary">Recalcular</button>
                    </div>
                </div>
            </div>
            <!-- FIM CARD !-->
        </div>
        <!-- FIM COL-LG-7 !-->
    </div>
    <!-- FIM ROW !-->
</div>

<script src="{{ asset('js/pages/cte/emissao.js') }}"></script>
@endsection
