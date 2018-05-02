@php
    $titulo_pagina = "Extrato de Cargas";
@endphp

@extends('layouts.principal')
@section('content')
<script src="{{ asset('js/pages/extrato_carga.js') }}"></script>
<div class="row" id="extrato_carga">
    <div class="main-content container-fluid pt-0">
        <div contentLoading style="width: 85%;" class="card">
            <div class="main-content container-fluid be-loading p-0 pt-2">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="static_filial" class="mb-0">Filial</label>
                            <input type="text" data-default="{{Auth::user()->minhaFilial()}}" id="static_filial" name="FILIAL" class="form-control form-control-sm">
                        </div>

                        <div class="col-2">
                            <label for="static_posicao" class="mb-0">Posição do Cliente</label>
                            <select id="static_posicao" size name="POSICAO" class="form-control form-control-sm">
                                <option value="0">Remetente</option>
                                <option value="1">Destinatário</option>
                                <option value="2">Consignatário</option>
                                <option value="3" selected>Todos</option>
                            </select>
                        </div>

                        <div class="col-6">
                            <label for="static_cliente" class="mb-0">Cliente <small>(Nome ou CNPJ/CPF)</small></label>
                            <input type="text" id="static_cliente" name="CLIENTE" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-2">
                            <label for="static_filtro" class="mb-0">Filtro</label>
                            <select id="static_filtro" size name="FILTRO" class="form-control form-control-sm">
                                <option value="0">Emissão</option>
                                <option value="1">Nota Fiscal</option>
                                <option value="2">Chegada</option>
                                <option value="3">Envio</option>
                            </select>
                        </div>

                        @component('components.select_data', ['size'=>3])
                        @endcomponent

                        <div class="col-1">
                            <label for="static_uf" class="mb-0">UF</label>
                            <input type="text" id="static_uf" name="UF" class="form-control form-control-sm">
                        </div>

                        <div class="col-4">
                            <label for="static_entrega" class="mb-0">Cidade Entrega</label>
                            <input type="text" id="static_entrega" desblock="none" name="ENTREGA" class="form-control form-control-sm">
                        </div>

                        <div class="col-2">
                            <label for="static_situacao" class="mb-0">Situação</label>
                            <select id="static_situacao" size name="SITUACAO" class="form-control form-control-sm">
                                <option value="0">Sem Manifesto</option>
                                <option value="1">Em Manifesto</option>
                                <option value="2">Sem Romaneio</option>
                                <option value="3">Em Romaneio</option>
                                <option value="4" selected>Todos</option>
                                <option value="5">Cancelados</option>
                                <option value="6">Autorizados</option>
                                <option value="7">Transp. Origem</option>
                                <option value="8">Transp. Destino</option>
                                <option value="9">Entregues</option>
                                <option value="10">Á Entregar</option>
                                <option value="11">Denegados</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-4">
                            <label for="static_vendedor" class="mb-0">Vendedor</label>
                            <input type="text" id="static_vendedor" name="VENDEDOR" class="form-control form-control-sm">
                        </div>

                        <div class="col-4">
                            <label for="static_motorista" class="mb-0">Motorista</label>
                            <input type="text" id="static_motorista" name="MOTORISTA" class="form-control form-control-sm">
                        </div>

                        <div class="col-2">
                            <label for="static_formapagto" class="mb-0">Forma de Pagamento</label>
                            <input type="text" id="static_formapagto" name="FORMAPAGTO" class="form-control form-control-sm">
                        </div>

                        <div class="col-2">
                            <label for="static_faturado" class="mb-0">Faturados</label>
                            <select id="static_faturado" size name="FATURADO" class="form-control form-control-sm">
                                <option value="0">Todos</option>
                                <option value="1">Faturados</option>
                                <option value="2">Não Faturados</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12" id="botoes">
                            <button id="pesquisar" class="btn btn-space btn-success">Pesquisar</button>

                            <div class="btn-group btn-space">
                                <button id="imprimir" type="button" data-toggle="dropdown" class="btn btn-secondary dropdown-toggle">Imprimir <span class="icon-dropdown mdi mdi-chevron-down"></span></button>
                                <div role="menu" id="imprimir_menu" class="dropdown-menu">
                                    <div class="dropdown-item">
                                        <label class="be-checkbox custom-control custom-checkbox m-0">
                                            <input type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label">Quebra Emissão</span>
                                        </label>
                                    </div>

                                    <div class="dropdown-item">
                                        <label class="be-checkbox custom-control custom-checkbox m-0">
                                            <input type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label">Quebra Remetente</span>
                                        </label>
                                    </div>

                                    <div class="dropdown-item">
                                        <label class="be-checkbox custom-control custom-checkbox m-0">
                                            <input type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label">Quebra Destinatário</span>
                                        </label>
                                    </div>

                                    <div class="dropdown-item">
                                        <label class="be-checkbox custom-control custom-checkbox m-0">
                                            <input type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label">Resumo Em Páginas Separada</span>
                                        </label>
                                    </div>

                                    <div class="dropdown-item">
                                        <label class="be-checkbox custom-control custom-checkbox m-0">
                                            <input type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label">Resumo na Útima Página</span>
                                        </label>
                                    </div>

                                    <div class="dropdown-item">
                                        <label class="be-checkbox custom-control custom-checkbox m-0">
                                            <input type="checkbox" class="custom-control-input">
                                            <span class="custom-control-label">Subdetalhe</span>
                                        </label>
                                    </div>

                                    <div class="dropdown-divider"></div>
                                    <div class="dropdown-item view blue">Visualizar</div>
                                </div>
                            </div>

                            <button id="gerar" class="btn btn-space btn-primary">Gerar</button>
                        </div>
                    </div>

                    <div class="row border-top mt-3" style="margin-left: -20px; margin-right: -20px;">
                        <table class="table table-striped table-hover table-condensed" scrolly="200" scrollx="8000" id="itens">
                            <thead>
                                <tr>
                                    <th>Doc</th>
                                    <th>Código</th>
                                    <th>Emissão</th>
                                    <th width="300px">Consignatário</th>
                                    <th width="300px">Remetente</th>
                                    <th width="300px">Destinatário</th>
                                    <th width="100px">Forma de Pagto</th>
                                    <th>Coleta</th>
                                    <th>Entrega</th>
                                    <th width="70px">Tipo Frete</th>
                                    <th>Previsão</th>
                                    <th>Chegada</th>
                                    <th>Peso</th>
                                    <th>Cubagem</th>
                                    <th>Volumes</th>
                                    <th>Mercadoria</th>
                                    <th width="70px">Valor Frete</th>
                                    <th>Serviço</th>
                                    <th>Manifesto</th>
                                    <th>Romaneio</th>
                                    <th>Fatura</th>
                                    <th width="70px">Tipo Doc</th>
                                    <th width="200px">Notas Fiscais/Outros Doc</th>
                                    <th>Situação</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div class="col-12 mt-3 p-0">
                        <div class="border" id="totais">
                            <div class="card-header m-0 p-2 border-bottom">Totais</div>

                            <div class="custom-control-inline w-100">
                                <div class="col-2 text-right p-2 border-bottom border-right">
                                    <label class="text-primary mb-0 w-100">Conhecimentos</label>
                                    <strong CONHECIMENTO>0</strong>
                                </div>

                                <div class="col-2 text-right p-2 border-bottom border-right">
                                    <label class="text-primary mb-0 w-100">Volumes</label>
                                    <strong PESO>0,00</strong>
                                </div>

                                <div class="col-2 text-right p-2 border-bottom border-right">
                                    <label class="text-primary mb-0 w-100">Peso</label>
                                    <strong TOTAL_MERC>0,00</strong>
                                </div>

                                <div class="col-2 text-right p-2 border-bottom border-right">
                                    <label class="text-primary mb-0 w-100">Cubagem</label>
                                    <strong FRETE>0,00</strong>
                                </div>

                                <div class="col-2 text-right p-2 border-bottom border-right">
                                    <label class="text-primary mb-0 w-100">Mercadoria</label>
                                    <strong FRETE>0,00</strong>
                                </div>

                                <div class="col-2 text-right p-2 border-bottom">
                                    <label class="text-primary mb-0 w-100">Frete</label>
                                    <strong FRETE>0,00</strong>
                                </div>
                                <!-- FIM DO ROW !-->
                            </div>
                        </div>
                    </div>

                    <!-- FIM CARD BODY !-->
                </div>
                <!--FIM MAIN-CONTENT !-->
            </div>
        </div>
    </div>
</div>
@endsection
