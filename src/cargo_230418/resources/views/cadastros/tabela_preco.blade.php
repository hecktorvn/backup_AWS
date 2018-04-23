@php
    $titulo_pagina = "Tabela de Preços";
    $data = &$tabela;
@endphp

@extends('layouts.principal')
@section('content')

<div class="main-content container-fluid pt-0">
    <div class="row">
        <div style="width: 85%;" id="cadastro_tabela_preco">
            <div class="card" contentLoading>

                @component('components.modal', ['id'=>'modal_tab_preco'])
                @endcomponent
                <div class="card-body card-header-divider m-0 p-0 pb-5 mb-5">
                    <form id="dados">
                        <div class="row border-bottom bg-cinza ml-0 mb-4 pt-4 pb-4 mw-100">
                            <div class="col-6">
                                <div class="card-header card-header-divider m-0 mb-4 pt-0">
                                    Origem
                                    <span class="card-subtitle">Estado e cidade de origem</span>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <label for="static_estado_orig" class="mb-0">Estado</label>
                                        <input type="text" required noFlush id="static_estado_orig" notblock name="ESTADOORIG" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-6">
                                        <label for="static_cidade_orig" class="mb-0">Cidade</label>
                                        <input type="text" required noFlush id="static_cidade_orig" notblock name="ORIGEM" class="form-control form-control-sm">
                                    </div>
                                    <!-- FIM DO ROW !-->
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="card-header card-header-divider m-0 mb-4 pt-0">
                                    Destino
                                    <span class="card-subtitle">Estado e cidade de destino</span>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <label for="static_estado_dest" class="mb-0">Estado</label>
                                        <input type="text" required noFlush id="static_estado_dest" notblock name="ESTADODEST" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-6">
                                        <label for="static_cidade_dest" class="mb-0">Cidade</label>
                                        <input type="text" required noFlush id="static_cidade_dest" notblock name="DESTINO" class="form-control form-control-sm">
                                    </div>
                                    <!-- FIM DO ROW !-->
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 ml-0" id="dados">
                            <div class="col-3">
                                <label for="static_ind_cubagem" class="mb-0">Índice Cubagem</label>
                                <input type="text" id="static_ind_cubagem" data-type="numeric" name="IND_CUB" class="form-control form-control-sm">
                            </div>

                            <div class="col-2">
                                <label for="static_icms" class="mb-0">Alíquota ICMS</label>
                                <input type="text" id="static_icms" data-type="numeric" name="ICMS" class="form-control form-control-sm">
                            </div>

                            <div class="col-2">
                                <label for="static_prazo_dias" class="mb-0">Prazo Entrega</label>
                                <input type="text" id="static_prazo_dias" data-type="integer" name="PRAZO_ENTREGA" class="form-control form-control-sm">
                            </div>

                            <div class="col-3 pt-3">
                                <label class="be-checkbox custom-control custom-checkbox m-0" style="margin-top: 8px !important;">
                                    <input type="checkbox" name="ICMS_INCLUSO" value="1" class="custom-control-input">
                                    <span class="custom-control-label">ICMS Incluso no Frete</span>
                                </label>
                            </div>
                        </div>
                    </form>

                    <div class="tab-container">
                        <ul role="tablist" class="nav nav-tabs nav-tabs-warning">
                            <li class="nav-item"><a id="tonelada_a" href="#tonelada" data-toggle="tab" role="tab" class="nav-link active show" aria-selected="true">Tonelada</a></li>
                            <li class="nav-item"><a id="pacotinho_a" href="#pacotinho" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">Fracionada/Pacotinho</a></li>
                            <li class="nav-item"><a id="percentual_produto_a" href="#produtos" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">Percentual dos Produtos</a></li>
                        </ul>

                        <div class="tab-content p-0 mb-0 pt-2">
                            <div id="pacotinho" role="tabpanel" class="tab-pane">
                                @include('cadastros.tabela_preco.pacotinho')
                            </div>

                            <div id="tonelada" role="tabpanel" class="tab-pane active show">
                                @include('cadastros.tabela_preco.tonelada')
                            </div>

                            <div id="produtos" role="tabpanel" class="tab-pane">
                                @include('cadastros.tabela_preco.produtos')
                            </div>
                        </div>
                    </div>
                    <!-- FIM DO CARD-BODY !-->
                </div>

                <div class="row pl-6 pb-4">
                    <button type="button" onclick="window.location = '{{ url()->previous() }}';" class="btn btn-space btn-default"><i class="icon icon-left mdi mdi-undo mr-2"></i>Voltar</button>
                    <button type="button" cancelar class="btn btn-space btn-danger"><i class="icon icon-left mdi mdi-delete mr-2"></i>Cancelar</button>
                    <button type="button" gravar class="btn btn-space btn-success"><i class="icon icon-left mdi mdi-check mr-2"></i>Salvar</button>
                </div>

            <!-- FIM CARD !-->
        </div>
        <!-- FIM COL-LG-7 !-->
    </div>
    <!-- FIM ROW !-->
</div>

<script src="{{ asset('js/pages/cadastros/tabela_preco.js') }}"></script>
@endsection
