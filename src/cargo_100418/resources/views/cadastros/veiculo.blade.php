@php
    $titulo_pagina = "Cadastro de Veículos";
    $data = &$veiculo;
    $msg = [
        'updateMensage' => 'Veículo alterado com sucesso.',
        'sucessMensage' => 'Veículo cadastrado com sucesso.',
        'msgModal' => 'Veículo não cadastrado.'
    ];

    $table = [
        'name' => 'VEICULOS',
        'primary_key' => 'CODIGO',
        'form' => 'cadastro_veiculo'
    ];

    $size = 7;
@endphp

@section('content_card')
    <form id="cadastro_veiculo" action="{{ empty($veiculo) ? '/defreq/VEICULOS/ins/CODIGO' : '/defreq/VEICULOS/upd/PLACA' }}">
        <div class="card-header card-header-divider m-0 mb-3 pt-0">
            Dados do Veículo (Cavalo)
            <span class="card-subtitle">Dados do cavalo do veículo</span>
        </div>

        <div class="card-body p-0 mb-3">
            <div class="row mb-3">
                <div class="col-2">
                    <label for="static_codigo" class="mb-0">Código</label>
                    <input type="text" data-type="auto_increment" value="{{ @$veiculo->CODIGO }}" class="form-control form-control-sm" name="CODIGO" readonly id="codigo">
                </div>

                <div class="col-7">
                    <label for="static_veiculo" class="mb-0">Veículo</label>
                    <input type="text" value="{{ @$veiculo->VEICULO }}" required id="static_veiculo" class="form-control form-control-sm" name="VEICULO">
                </div>

                <div class="col">
                    @php
                        if(!empty($veiculo)) $tipo[$veiculo->TIPO] = 'selected';
                    @endphp
                    <label for="static_tipo" class="mb-0">Tipo</label>
                    <select name="TIPO" id="static_tipo" class="form-control form-control-sm" size>
                        <option value="0" {{ @$tipo[0] }}>CAMINHÃO</option>
                        <option value="1" {{ @$tipo[1] }}>CARRETA</option>
                        <option value="2" {{ @$tipo[2] }}>CAVALO</option>
                        <option value="3" {{ @$tipo[3] }}>CAVALO TRUNCADO</option>
                    </select>
                </div>
            <!-- FIM DO ROW !-->
            </div>

            <div class="row mb-3">
                <div class="col-2">
                    <label for="static_placa" class="mb-0">Placa</label>
                    <input type="text" {{ !empty($veiculo) ? 'readonly' : '' }}  value="{{ @$veiculo->PLACA }}" class="form-control form-control-sm" id="static_placa" name="PLACA" required>
                </div>

                <div class="col-4">
                    <label for="static_chassi" class="mb-0">Chassi</label>
                    <input type="text" value="{{ @$veiculo->CHASSI }}" class="form-control form-control-sm" id="static_chassi" name="CHASSI" required>
                </div>

                <div class="col">
                    <label for="static_renavam" class="mb-0">Renavam</label>
                    <input type="text" value="{{ @$veiculo->RENAVAM }}" name="RENAVAM" id="static_renavam" class="form-control form-control-sm">
                </div>

                <div class="col-2">
                    <label for="static_capacidade" class="mb-0">Tara (Peso total)</label>
                    <input type="text" data-type="numeric" value="{{ @$veiculo->CAPACIDADE }}" id="static_capacidade" name="CAPACIDADE" class="form-control form-control-sm">
                </div>

                <div class="col-2">
                    <label for="static_cilindrada" class="mb-0">Cilindrada</label>
                    <input type="text" data-type="numeric" value="{{ @$veiculo->CILINDRADA }}" id="static_cilindrada" name="CILINDRADA" class="form-control form-control-sm">
                </div>
            <!-- FIM DO ROW !-->
            </div>

            <div class="row mb-3">
                <div class="col-2">
                    <label for="static_potencia" class="mb-0">Potencia</label>
                    <input type="text" data-type="integer" value="{{ @$veiculo->POTENCIA }}" id="static_potencia" name="POTENCIA" class="form-control form-control-sm">
                </div>

                <div class="col-2">
                    <label for="static_categoria" class="mb-0">Categoria</label>
                        <input type="text" value="{{ @$veiculo->CATEGORIA }}" id="static_categoria" name="CATEGORIA" class="form-control form-control-sm">
                </div>

                <div class="col-2">
                    @php
                        if(!empty($veiculo)) $combustivel[$veiculo->COMBUSTIVEL] = 'selected';
                    @endphp
                    <label for="static_combustivel" class="mb-0">Combustível</label>
                    <select name="COMBUSTIVEL" id="static_combustivel" class="form-control form-control-sm" size>
                        <option {{ @$combustivel['ALCOOL'] }}>ALCOOL</option>
                        <option {{ @$combustivel['DIESEL'] }}>DIESEL</option>
                        <option {{ @$combustivel['GAS NATURAL'] }}>GAS NATURAL</option>
                        <option {{ @$combustivel['GASOLINA'] }}>GASOLINA</option>
                        <option {{ @$combustivel['OUTROS'] }}>OUTROS</option>
                    </select>
                </div>

                <div class="col-2">
                    <label for="static_cor" class="mb-0">Cor</label>
                    <input type="text" value="{{ @$veiculo->COR }}" id="static_cor" name="COR" class="form-control form-control-sm">
                </div>

                <div class="col-2">
                    <label for="static_qtd_eixo" class="mb-0">Eixos</label>
                    <input type="text" data-type="numeric" value="{{ @$veiculo->QTD_EIXO }}" id="static_qtd_eixo" name="QTD_EIXO" class="form-control form-control-sm">
                </div>

                <div class="col-2">
                    <label for="static_km_atual" class="mb-0">Km Atual</label>
                    <input type="text" data-type="numeric" value="{{ @$veiculo->KM_ATUAL }}" id="static_km_atual" name="KM_ATUAL" class="form-control form-control-sm">
                </div>
            <!-- FIM DO ROW !-->
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label for="static_motorista" class="mb-0">Motorista</label>
                    <input type="text" value="{{ @$veiculo->MOTORISTA }}" id="static_motorista" name="MOTORISTA" class="form-control form-control-sm">
                </div>

                <div class="col">
                    <label for="static_ano_fab" class="mb-0">Ano Fabricação</label>
                    <select name="ANO_FAB" id="static_ano_fab" size class="form-control form-control-sm">
                        @for($dtInicio=date('Y')-38;$dtInicio<date('Y')+3;$dtInicio++)
                            <option value="{{ $dtInicio }}" {{ $dtInicio == @$veiculo->ANO_FAB ? 'selected' : '' }}>{{ $dtInicio }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col">
                    <label for="static_ano_mod" class="mb-0">Ano Modelo</label>
                    <select name="ANO_MOD" id="static_ano_mod" size class="form-control form-control-sm">
                        @for($dtInicio=date('Y')-38;$dtInicio<date('Y')+3;$dtInicio++)
                            <option value="{{ $dtInicio }}" {{ $dtInicio == @$veiculo->ANO_MOD ? 'selected' : '' }}>{{ $dtInicio }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col">
                    <label for="static_capacidade_kg" class="mb-0">Capacidade Kg</label>
                    <input type="text" data-type="numeric" value="{{ @$veiculo->CAPACIDADE_KG }}" id="static_capacidade_kg" name="CAPACIDADE_KG" class="form-control form-control-sm">
                </div>
            <!-- FIM DO ROW !-->
            </div>

            <div class="row mb-3">
                <div class="col-4">
                    <label for="static_modelo" class="mb-0">Modelo</label>
                    <input type="text" value="{{ @$veiculo->MODELO }}" id="static_modelo" name="MODELO" class="form-control form-control-sm">
                </div>

                <div class="col-2"></div>

                <div class="col-4">
                    <label for="static_marca" class="mb-0">Marca</label>
                    <input type="text" value="{{ @$veiculo->MARCA }}" id="static_marca" name="MARCA" class="form-control form-control-sm">
                </div>
            <!-- FIM DO ROW !-->
            </div>
        <!-- FIM DO CARD-BODY !-->
        </div>

        <div class="card-header card-header-divider m-0 mb-4 pt-0">
            Dados da Carreta
            <span class="card-subtitle">Dados da carreta do veículo</span>
        </div>

        <div class="card-body p-0 mb-3">
            <div class="row mb-3">
                <div class="col-3">
                    <label for="static_placa2" class="mb-0">Placa</label>
                    <input type="text" value="{{ @$veiculo->PLACA2 }}" id="static_placa2" name="PLACA2" class="form-control form-control-sm">
                </div>

                <div class="col-3">
                    <label for="static_chassi2" class="mb-0">Chassi</label>
                    <input type="text" value="{{ @$veiculo->CHASSI2 }}" id="static_chassi2" name="CHASSI2" class="form-control form-control-sm">
                </div>

                <div class="col-3">
                    <label for="static_renavam2" class="mb-0">Renavam</label>
                    <input type="text" value="{{ @$veiculo->RENAVAM2 }}" id="static_renavam2" name="RENAVAM2" class="form-control form-control-sm">
                </div>

                <div class="col-3">
                    <label for="static_modelo2" class="mb-0">Modelo</label>
                    <input type="text" value="{{ @$veiculo->MODELO2 }}" id="static_modelo2" name="MODELO2" class="form-control form-control-sm">
                </div>
            <!-- FIM DO ROW !-->
            </div>

            <div class="row mb-3">
                <div class="col-3">
                    <label for="static_marca2" class="mb-0">Marca</label>
                    <input type="text" value="{{ @$veiculo->MARCA2 }}" id="static_marca2" name="MARCA2" class="form-control form-control-sm">
                </div>

                <div class="col-2">
                    <label for="static_ano_fab2" class="mb-0">Ano</label>
                    <select name="ANO_FAB2" id="static_ano_fab2" size class="form-control form-control-sm">
                        @for($dtInicio=date('Y')-38;$dtInicio<date('Y')+3;$dtInicio++)
                            <option value="{{ $dtInicio }}" {{ $dtInicio == @$veiculo->ANO_FAB2 ? 'selected' : '' }}>{{ $dtInicio }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-2">
                    <label for="static_cor2" class="mb-0">Cor</label>
                    <input type="text" value="{{ @$veiculo->COR2 }}" id="static_cor2" name="COR2" class="form-control form-control-sm">
                </div>
            <!-- FIM DO ROW !-->
            </div>
        <!-- FIM DO CARD-BODY !-->
        </div>

        <div class="card-header card-header-divider m-0 mb-5 pt-0">
            Dados do Proprietário
            <span class="card-subtitle">Dados do proprietário do veículo</span>
        </div>

        <div class="card-body p-0 mb-5">
            <div class="row mb-3">
                <div class="col-3">
                    <label for="static_cpf_prop" class="mb-0">CNPJ/CPF</label>
                    <input type="text" data-mask="cnpj_cpf" data-type="number" value="{{ @$veiculo->CPF_PROP }}" required id="static_cpf_prop" name="CPF_PROP" class="form-control form-control-sm">
                </div>

                <div class="col-6">
                    <label for="static_nome_prop" class="mb-0">Nome</label>
                    <input type="text" value="{{ @$veiculo->NOME_PROP }}" required id="static_nome_prop" name="NOME_PROP" class="form-control form-control-sm">
                </div>

                <div class="col-3">
                    <label for="static_contato" class="mb-0">Contato</label>
                    <input type="text" value="{{ @$veiculo->CONTATO }}" id="static_contato" name="CONTATO" class="form-control form-control-sm">
                </div>
            <!-- FIM DO ROW !-->
            </div>

            <div class="row mb-3">
                <div class="col-2">
                    <label for="static_cep" class="mb-0">CEP</label>
                    <div class="input-group">
                        <input type="text" data-mask="cep" CEP data-type="number" value="{{ @$veiculo->CEP }}" id="static_cep" name="CEP" class="form-control form-control-sm">
                        <div class="input-group-append">
                            <button type="button" getcep class="btn btn-sm btn-dark"><span class="mdi mt-1 mdi-search"></span></button>
                        </div>
                    </div>
                </div>

                <div class="col-2">
                    <label for="static_lograd" class="mb-0">Logradouro</label>
                    <select name="LOGRAD" LOGRAD id="static_lograd" size class="form-control form-control-sm">
                        @component('components.list', ['table'=>'PARAMETROS', 'key'=>'DESCR', 'checked'=>['value'=>@$veiculo->LOGRAD], 'where'=>['CODIGO'=>'LOGRADOURO']])
                            <option value="{$list->DESCR}" {$list->checked}>{$list->DESCR}</option>
                        @endcomponent
                    </select>
                </div>

                <div class="col-6">
                    <label for="static_endereco" class="mb-0">Endereço</label>
                    <input type="text" ENDERECO value="{{ @$veiculo->ENDERECO }}" id="static_endereco" name="ENDERECO" class="form-control form-control-sm">
                </div>

                <div class="col-2">
                    <label for="static_bairro" class="mb-0">Bairro</label>
                    <input type="text" BAIRRO value="{{ @$veiculo->BAIRRO }}" id="static_bairro" name="BAIRRO" class="form-control form-control-sm">
                </div>
            <!-- FIM DO ROW !-->
            </div>

            <div class="row mb-3">
                <div class="col-4">
                    <label for="static_uf" class="mb-0">Estado</label>
                    <input type="text" UF value="{{ @$veiculo->UF }}" required id="static_uf" name="UF" class="form-control form-control-sm">
                </div>

                <div class="col-4">
                    <label for="static_cidade" class="mb-0">Cidade</label>
                    <input type="text" CIDADE value="{{ @$veiculo->CIDADE }}" required id="static_cidade" name="CIDADE" class="form-control form-control-sm">
                </div>

                <div class="col-4">
                    <label for="static_email" class="mb-0">E-mail</label>
                    <input type="text" value="{{ @$veiculo->EMAIL }}" id="static_email" name="EMAIL" class="form-control form-control-sm">
                </div>
            <!-- FIM DO ROW !-->
            </div>

            <div class="row mb-3">
                <div class="col-3">
                    <label for="static_telefone" class="mb-0">Telefone</label>
                    <input type="text" data-mask="phone" value="{{ @$veiculo->TELEFONE }}" required id="static_telefone" name="TELEFONE" class="form-control form-control-sm">
                </div>

                <div class="col-2">
                    <label for="static_rg" class="mb-0">I.E/RG</label>
                    <input type="text" value="{{ @$veiculo->RG }}" required id="static_rg" name="RG" class="form-control form-control-sm">
                </div>

                <div class="col-2">
                    <label for="static_rg_emissor" class="mb-0">Emissor</label>
                    <input type="text" value="{{ @$veiculo->RG_EMISSOR }}" id="static_rg_emissor" name="RG_EMISSOR" class="form-control form-control-sm">
                </div>

                <div class="col-2">
                    <label for="static_rg_emissao" class="mb-0">Emissão</label>
                    <input type="text" data-type="date" value="{{ Format::date(@$veiculo->RG_EMISSAO) }}" id="static_rg_emissao" name="RG_EMISSAO" class="form-control form-control-sm">
                </div>

                <div class="col-3">
                    <label for="static_rntrc" class="mb-0">RNTRC</label>
                    <input type="text" value="{{ @$veiculo->RNTRC }}" required id="static_rntrc" name="RNTRC" class="form-control form-control-sm">
                </div>
            <!-- FIM DO ROW !-->
            </div>

            <div class="row m-0">
                <div class="col-12 p-0">
                    <label for="static_observacao" class="mb-0">Observação</label>
                    <textarea name="OBSERVACAO" id="static_observacao" cols="30" rows="10" class="form-control" style="height:100px;">{{ @$veiculo->OBSERVACAO }}</textarea>
                </div>
            </div>
        <!-- FIM DO CARD-BODY !-->
        </div>

        @component('components.defaultButtons', ['variable'=>&$veiculo])
        @endcomponent
    </form>
@if(@$not_extend == true)
    @show
@else
    @endsection
@endif

@includeWhen(!@$not_extend, 'layouts.cadastro')
<script src="{{ asset('js/pages/cadastros/veiculo.js') }}"></script>
