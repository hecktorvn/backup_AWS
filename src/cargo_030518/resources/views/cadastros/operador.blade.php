@php
    $titulo_pagina = "Cadastro de Operadores";
    $data = &$operador;
    $msg = [
        'updateMensage' => 'Operador alterado com sucesso.',
        'sucessMensage' => 'Operador cadastrado com sucesso.',
        'msgModal' => 'Operador não cadastrado.'
    ];

    $table = [
        'name' => 'OPERADOR',
        'primary_key' => 'CODIGO',
        'form' => 'cadastro_operador'
    ];
@endphp

@section('content_card')
    <form id="cadastro_operador" action="{{ empty($operador) ? '/defreq/OPERADOR/ins/CODIGO' : '/defreq/OPERADOR/upd/CODIGO' }}" method="post">
        {{ csrf_field() }}
        <div class="row mb-5 border-bottom pb-5">
            <div class="col-6">
                <div class="card-header card-header-divider m-0 mb-4 pt-0">
                    Dados pessoais
                    <span class="card-subtitle">Dados pessoais do operador</span>
                </div>

                <div class="card-body p-0">
                    <div class="row mb-3">
                        <div class="col-3">
                            <label for="static-codigo" class="mb-0">Código</label>
                            <input type="text" data-type="auto_increment" value="{{@$operador->CODIGO}}" readonly class="form-control form-control-sm" name="CODIGO" id="static-codigo">
                        </div>

                        <div class="col-9">
                            <label for="static-nome" class="mb-0">Nome</label>
                            <input type="text" required value="{{@$operador->NOME}}" class="form-control form-control-sm" name="NOME" id="static-nome">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <label for="static-funcao" class="mb-0">Função</label>
                            <input type="text" required value="{{@$operador->FUNCAO}}" class="form-control form-control-sm" name="FUNCAO" id="static-funcao">
                        </div>

                        <div class="col-4">
                            <label for="static-comissao" class="mb-0">Comissão %</label>
                            <input type="text" required data-type="numeric" value="{{ Format::pct(@$operador->COMISSAO) }}" data-mask="percent" class="form-control form-control-sm" name="COMISSAO" id="static-comissao">
                        </div>

                        <div class="col-12 mt-3">
                            <label for="static_filial" class="mb-0">Filial</label>
                            <select name="FILIAL" size id="static_filial" class="form-control form-control-sm">
                                @component('components.list', ['table'=>'FILIAIS', 'key'=>'CODIGO', 'checked'=>['value'=>@$operador->FILIAL]])
                                    <option value="{$list->CODIGO}" {$list->checked}>{$list->SOCIAL}</option>
                                @endcomponent
                            </select>
                        </div>

                        <div class="col-12 mt-3">
                            <label for="static_setor" class="mb-0">Setor</label>
                            <select name="SETOR" value="{{ @$operador->SETOR }}" size id="static_setor" class="form-control form-control-sm">
                                @component('components.list', ['table'=>'SETOR', 'key'=>'CODIGO', 'checked'=>['value'=>@$operador->SETOR]])
                                    <option value="{$list->CODIGO}" {$list->checked}>{$list->DESCR}</option>
                                @endcomponent
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card-header card-header-divider m-0 mb-4 pt-0">
                    Senha
                    <span class="card-subtitle">Senha do operador</span>
                </div>

                <div class="card-body p-0">
                    <div class="row mb-0">
                    <div class="col-12">
                        <h5 class="mt-0">Tipo de Entrada</h5>
                        <label class="custom-control custom-checkbox custom-control-inline">
                            <input type='hidden' value='0' name='ALTERAR_SENHA'>
                            <input type="checkbox"  {{@$operador->ALTERAR_SENHA == 1 ? 'checked' : ''}} name="ALTERAR_SENHA" class="custom-control-input" value="1">
                            <span class="custom-control-label custom-control-color">Alterar senha no próximo logon</span>
                        </label>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="static_senha" class="mb-0">Senha</label>
                        <input data-type="cryptografa" type="password" required value="{{@$operador->SENHA}}" name="SENHA" id="static_senha" class="form-control form-control-sm">
                    </div>

                    <div class="col-4">
                        <label for="static_datacad" class="mb-0">Data Cadastro</label>
                        <input type="text" data-mask="date" data-type="date" readonly value="{{ Format::date(@$operador->DATACAD) }}" name="DATACAD" id="static_datacad" class="form-control form-control-sm">
                    </div>

                    <div class="col-4">
                        <label for="static_ultima_alt" class="mb-0">Última Alteração</label>
                        <input type="text" data-mask="date" data-type="timestamp" readonly value="{{ Format::date(@$operador->ALTERACAO) }}" name="ALTERACAO" id="static_ultima_alt" class="form-control form-control-sm">
                    </div>
                </div>
                </div>
            </div>
            <!-- FIM COL-6 !-->
        </div>
        <!-- FIM ROW !-->
        @component('components.defaultButtons', ['variable'=>&$operador])
        @endcomponent
    </form>
@if(@$not_extend == true)
    @show
@else
    @endsection
@endif

@includeWhen(!@$not_extend, 'layouts.cadastro')
