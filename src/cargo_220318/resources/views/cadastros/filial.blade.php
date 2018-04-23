@php
    $titulo_pagina = "Cadastro de Filiais";
    $data = &$filial;
    $msg = [
        'updateMensage' => 'Filial alterada com sucesso.',
        'sucessMensage' => 'Filial cadastrada com sucesso.',
        'msgModal' => 'Filial não cadastrada.'
    ];

    $table = [
        'name' => 'FILIAIS',
        'primary_key' => 'CODIGO',
        'form' => 'cadastro_filial'
    ];

    $size = 7;
@endphp

@section('content_card')
    <form id="cadastro_filial" action="{{ empty($filial) ? '/defreq/FILIAIS/ins/CODIGO' : '/defreq/FILIAIS/upd/CODIGO' }}" method="post">
        {{ csrf_field() }}

        <div class="row m-0">
            <div class="col-12">
                <div class="card-header card-header-divider m-0 mb-4 pt-0">
                    Dados Cadastrais
                    <span class="card-subtitle">Dados da filial para o cadastro</span>
                </div>

                <div class="card-body p-0">
                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="static_codigo" class="mb-0">Código</label>
                            <input type="text" value="{{ @$filial->CODIGO }}" data-type="auto_increment" readonly id="static_codigo" name="CODIGO" class="form-control form-control-sm">
                        </div>

                        <div class="col-7">
                            <label for="static_social" class="mb-0">Razão Sócial</label>
                            <input type="text" maxlength="50" value="{{ @$filial->SOCIAL }}" required id="static_social" name="SOCIAL" class="form-control form-control-sm">
                        </div>

                        <div class="col-3">
                            <label for="static_telefones" class="mb-0">Telefone</label>
                            <input type="text" data-mask="phone" value="{{ @$filial->TELEFONES }}" required id="static_telefone" name="TELEFONES" class="form-control form-control-sm">
                        </div>
                        <!--FIM ROW !-->
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="static_fantasia" class="mb-0">Fantasia</label>
                            <input type="text" required maxlength="50" value="{{ @$filial->FANTASIA }}" id="static_fantasia" name="FANTASIA" class="form-control form-control-sm">
                        </div>
                        <!--FIM ROW !-->
                    </div>

                    <div class="row mb-3">
                        <div class="col-2">
                            <label for="static_cep" class="mb-0">CEP</label>
                            <div class="input-group">
                                <input type="text" data-mask="cep" CEP data-type="number" value="{{ @$filial->CEP }}" id="static_cep" name="CEP" class="form-control form-control-sm">
                                <div class="input-group-append">
                                    <button type="button" getcep class="btn btn-sm btn-dark"><span class="mdi mt-1 mdi-search"></span></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-2">
                            <label for="static_lograd" class="mb-0">Logradouro</label>
                            <select name="LOGRAD" LOGRAD id="static_lograd" size class="form-control form-control-sm">
                                @component('components.list', ['table'=>'PARAMETROS', 'key'=>'DESCR', 'checked'=>['value'=>@$filial->LOGRAD], 'where'=>['CODIGO'=>'LOGRADOURO']])
                                    <option value="{$list->DESCR}" {$list->checked}>{$list->DESCR}</option>
                                @endcomponent
                            </select>
                        </div>

                        <div class="col-6">
                            <label for="static_endereco" class="mb-0">Endereço</label>
                            <input type="text" required ENDERECO value="{{ @$filial->ENDERECO }}" id="static_endereco" name="ENDERECO" class="form-control form-control-sm">
                        </div>

                        <div class="col-2">
                            <label for="static_numero" class="mb-0">Número</label>
                            <input type="text" required value="{{ @$filial->NUMERO }}" id="static_numero" name="NUMERO" class="form-control form-control-sm">
                        </div>

                        <!-- FIM ROW !-->
                    </div>

                    <div class="row mb-3">
                        <div class="col-3">
                            <label for="static_bairro" class="mb-0">Bairro</label>
                            <input type="text" BAIRRO value="{{ @$filial->BAIRRO }}" id="static_bairro" name="BAIRRO" class="form-control form-control-sm">
                        </div>

                        <div class="col-4">
                            <label for="static_uf" class="mb-0">Estado</label>
                            <input type="text" UF value="{{ @$filial->UF }}" required id="static_uf" name="UF" class="form-control form-control-sm">
                        </div>

                        <div class="col-5">
                            <label for="static_cidade" class="mb-0">Cidade</label>
                            <input type="text" CIDADE value="{{ @$filial->CIDADE }}" required id="static_cidade" name="CIDADE" class="form-control form-control-sm">
                        </div>
                        <!-- FIM DO ROW !-->
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="static_complemento" class="mb-0">Complemento</label>
                            <input type="text" value="{{ @$filial->COMPLEMENTO }}" id="static_complemento" name="COMPLEMENTO" class="form-control form-control-sm">
                        </div>

                        <div class="col-6">
                            <label for="static_email" class="mb-0">E-mail</label>
                            <input type="text" value="{{ @$filial->EMAIL }}" id="static_email" name="EMAIL" class="form-control form-control-sm">
                        </div>
                        <!-- FIM DO ROW !-->
                    </div>

                    <div class="row mb-3">
                        <div class="col-3">
                            <label for="static_cnpj" class="mb-0">CNPJ</label>
                            <input type="text" value="{{ @$filial->CNPJ }}" required data-mask="cnpj" data-type="number" id="static_cnpj" name="CNPJ" class="form-control form-control-sm">
                        </div>

                        <div class="col-3">
                            <label for="static_insc_estadual" class="mb-0">Inscrição</label>
                            <input type="text" value="{{ @$filial->INSC_ESTADUAL }}" id="static_insc_estadual" name="INSC_ESTADUAL" class="form-control form-control-sm">
                        </div>

                        <div class="col-3">
                            <label for="static_insc_munic" class="mb-0">Inscrição Municipal</label>
                            <input type="text" value="{{ @$filial->INSC_MUNIC }}" id="static_insc_munic" name="INSC_MUNIC" class="form-control form-control-sm">
                        </div>

                        <div class="col-3">
                            <label for="static_cnae" class="mb-0">C.N.A.E</label>
                            <input type="text" value="{{ @$filial->CNAE }}" id="static_cnae" name="CNAE" class="form-control form-control-sm">
                        </div>
                        <!-- FIM DO ROW !-->
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label for="static_rntrc" class="mb-0">RNTRC</label>
                            <input type="text" value="{{ @$filial->RNTRC }}" id="static_rntrc" name="RNTRC" class="form-control form-control-sm">
                        </div>

                        <div class="col">
                            <label for="static_banco_dados" class="mb-0">Caminho FTP/Banco de Dados</label>
                            <input type="text" value="{{ @$filial->BANCO_DADOS }}" id="static_banco_dados" name="BANCO_DADOS" class="form-control form-control-sm">
                        </div>

                        <div class="col">
                            <label for="static_ip" class="mb-0">Endereço IP</label>
                            <input type="text" value="{{ @$filial->IP }}" id="static_ip" name="IP" class="form-control form-control-sm">
                        </div>
                        <!-- FIM DO ROW !-->
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="static_certificado" class="mb-0">Certificado Digital</label>
                            <input type="text" value="{{ @$filial->CERTIFICADO }}" id="static_certificado" name="CERTIFICADO" class="form-control form-control-sm">
                        </div>

                        <div class="col-6 col-sm-8 col-lg-6 form-check mt-2">
                            <label class="custom-control custom-checkbox custom-control-inline mb-0 mt-2 ml-2">
                                <input type="hidden" name="SITUACAO" class="custom-control-input" value="0">
                                <input type="checkbox" {{ @$filial->SITUACAO == '1' ? 'checked' : '' }} name="SITUACAO" class="custom-control-input" value="1">
                                <span class="custom-control-label">Filial em Atividade</span>
                            </label>

                            <label class="custom-control custom-checkbox custom-control-inline mb-0 mt-2 ml-2">
                                <input type="hidden" name="OPTANTE_SIMPLES" class="custom-control-input" value="0">
                                <input type="checkbox" {{ @$filial->OPTANTE_SIMPLES == '1' ? 'checked' : '' }} name="OPTANTE_SIMPLES" class="custom-control-input" value="1">
                                <span class="custom-control-label">Optante do Simples</span>
                            </label>
                        </div>
                        <!-- FIM DO ROW !-->
                    </div>

                </div>
                <!--FIM CARD-BODY !-->

                <div class="card-header card-header-divider m-0 mb-4 pt-0">
                    Lei da Transparência
                    <span class="card-subtitle">Texto e percentagem</span>
                </div>

                <div class="card-body card-header-divider p-0 m-0 mb-5 pb-5">
                    <div class="row">
                        <div class="col-9">
                            <label for="static_texto_transp" class="mb-0">Texto que será impresso no CTe</label>
                            <input type="text" value="{{ @$filial->TEXTO_TRANSP }}" id="static_texto_transp" name="TEXTO_TRANSP" class="form-control form-control-sm">
                        </div>
                        <div class="col-3">
                            <label for="static_imposto" class="mb-0">%Imposto</label>
                            <input type="text" value="{{ Format::pct(@$filial->IMPOSTO) }}" data-mask="integer" data-type="numeric" id="static_imposto" name="IMPOSTO" class="form-control form-control-sm">
                        </div>
                        <!--FIM DO ROW !-->
                    </div>
                    <!--FIM CARD-BODY !-->
                </div>

                @component('components.defaultButtons', ['variable'=>&$filial])
                @endcomponent
            </div>
        </div>
    </form>
@if(@$not_extend == true)
    @show
@else
    @endsection
@endif

@includeWhen(!@$not_extend, 'layouts.cadastro')
