@php
    $titulo_pagina = "Cadastro de Clientes";
    $data = &$cliente;
    $msg = [
        'updateMensage' => 'Cliente cadastrado com sucesso.',
        'sucessMensage' => 'Cliente alterado com sucesso.',
        'msgModal' => 'Cliente não cadastrado.'
    ];

    $table = [
        'name' => 'CLIENTE',
        'primary_key' => 'CNPJ_CPF',
        'form' => 'cadastro_cliente'
    ];
@endphp

@section('content_card')
    <form id="cadastro_cliente" action="{{ empty($cliente) ? '/defreq/CLIENTE/ins/CNPJ_CPF' : '/defreq/CLIENTE/upd/CNPJ_CPF' }}">
        <div class="card-header card-header-divider m-0 mb-4 pt-0">
            @if(@$not_extend != true)
                Documentos
                <span class="card-subtitle">Documentos pessoais do cliente</span>
            @endif
        </div>

        <input type="hidden" name="CODIGO" value="{{@$cliente->CODIGO}}" data-type="auto_increment">

        <div class="card-body p-0">
            <div class="row">
                <div class="col">
                    <label for="static-cnpj_cpf" class="mb-0">CPF/CNPJ</label>
                    <input type="text" data-type="number" name="CNPJ_CPF" {{ !empty($cliente) ? 'readonly' : '' }} data-mask="cnpj_cpf" value="{{@$cliente->CNPJ_CPF}}" required class="form-control form-control-sm" id="static-cnpj_cpf">
                </div>

                <div class="col">
                    <label for="static-rg_insc" class="mb-0">RG/Insc. Estadual</label>
                    <input type="text" name="RG_INSC" value="{{@$cliente->RG_INSC}}" required class="form-control form-control-sm" id="static-rg_insc">
                </div>

                <div class="col-2">
                    <label for="static-insc_municipal" class="mb-0">Insc. Municipal</label>
                    <input type="text" name="INSC_MUNIC" value="{{@$cliente->INSC_MUNIC}}" required class="form-control form-control-sm" id="static-insc_municipal">
                </div>

                <div class="col-2">
                    <label for="static-cnae" class="mb-0">CNAE</label>
                    <input type="text" name="CNAE" value="{{@$cliente->CNAE}}" required class="form-control form-control-sm" id="static-cnae">
                </div>

                <div class="col">
                    <label for="static-condicao_contribuinte" class="mb-0">Condição Contribuinte</label>
                    <select size name="CONDICAO_TRIBUTARIA" value="{{@$cliente->CONDICAO_TRIBUTARIA}}" id="static-condicao_contribuinte" class="form-control form-control-sm">
                        <option value=""></option>
                        <option value="1">Contribuinte</option>
                        <option value="2">Isento</option>
                        <option value="9">Não Contribuinte</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card-header card-header-divider m-0 mb-4">
            Dados Cadastrais
            <span class="card-subtitle">Dados pessoais do cliente para o cadastro</span>
        </div>

        <div class="car-body p-0">
            <div class="row mb-3">
                <div class="col-10">
                    <label for="static-razao_social" class="mb-0">Razão Social</label>
                    <input type="text" name="SOCIAL" value="{{@$cliente->SOCIAL}}" required class="form-control form-control-sm" id="static-razao_social">
                </div>

                <div class="col-2">
                    <label for="static-datacad" class="mb-0">Abertura</label>
                    <input type="text" data-type="date" name="DATACAD" value="{{Format::date(@$cliente->DATACAD)}}" required class="form-control form-control-sm" id="static-datacad">
                </div>
            </div>

            <div class="row">
                <div class="col-10">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-9">
                                <label for="fantasia" class="mb-0">Fantasia</label>
                                <input type="text" value="{{@$cliente->FANTASIA}}" class="form-control form-control-sm" name="FANTASIA" id="fantasia">
                            </div>

                            <div class="col">
                                <label for="telefone" class="mb-0">Telefone</label>
                                <input type="text" data-mask="phone" value="{{@$cliente->TELEFONES}}" class="form-control form-control-sm" name="TELEFONES" id="telefone">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-9">
                                <label for="static_filial" class="mb-0">Filial cadastro</label>
                                <input type="text" required readonly data-default="{{ @$cliente->FILIAL == '' ? Auth::user()->FILIAL : $cliente->FILIAL }}" data-type="numeric" class="form-control form-control-sm" name="FILIAL" id="static_filial">
                            </div>

                            <div class="col">
                                <label for="fax" class="mb-0">Fax</label>
                                <input type="text" value="{{@$cliente->FAX}}" data-mask="phone" class="form-control form-control-sm" name="FAX" id="fax">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-2">
                    <div class="border p-2 pl-3 pb-0">
                        <h5>Situação</h5>
                        <label class="mb-1 custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="situacao_ativo" {{@$cliente->SITUACAO == 0 ? 'checked' : ''}} name="SITUACAO" value="0">
                            <span class="custom-control-label">Ativo</span>
                        </label>

                        <label class="mb-0 custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="situacao_inativo" {{@$cliente->SITUACAO == 1 ? 'checked' : ''}} name="SITUACAO" value="1">
                            <span class="custom-control-label">Inativo</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="segmento" class="mb-0">Segmento</label>
                    <select size name="SEGMENTO" id="segmento" class="form-control form-control-sm">
                        @component('components.list', ['table'=>'PARAMETROS', 'key'=>'COD1', 'where'=>['CODIGO'=>'SEGMENTO'], 'checked'=>['value'=>@$cliente->SEGMENTO]])
                            <option value="{$list->COD1}">{$list->DESCR}</option>
                        @endcomponent
                    </select>
                </div>

                <div class="col">
                    <label for="vendedor" class="mb-0">Vendedor</label>
                    <select size name="VENDEDOR" id="vendedor" class="form-control form-control-sm">
                        @component('components.list', ['table'=>'OPERADOR', 'key'=>'CODIGO', 'checked'=>['value'=>@$cliente->VENDEDOR]])
                            <option value="{$list->CODIGO}">{$list->NOME}</option>
                        @endcomponent
                    </select>
                </div>
            </div>

            <div class="row mb-5 border-bottom pb-5">
                <div class="col">
                    <label for="email" class="mb-0">E-mail</label>
                    <div class="input-group input-group-sm">
                        <input type="email" name="EMAIL" required value="{{@$cliente->EMAIL}}" id="email" class="form-control" aria-describedby="email">
                    </div>
                </div>

                <div class="col">
                    <label for="email2" class="mb-0">E-mail 2</label>
                    <div class="input-group input-group-sm">
                        <input type="email" name="EMAIL2" value="{{@$cliente->EMAIL2}}" id="email2" class="form-control form-control-sm">
                    </div>
                </div>
            </div>

            @component('components.defaultButtons', ['variable'=>&$cliente])
            @endcomponent
        </div>
    </form>

@if(@$not_extend == true)
    @show
@else
    @endsection
@endif

@includeWhen(!@$not_extend, 'layouts.cadastro')

@if(@$not_script != true)
<script src="{{ asset('js/pages/cadastros/cliente.js') }}"></script>
@endif
