@php
    $titulo_pagina = "Cadastro de Cidades";
    $data = &$cidade;
    $msg = [
        'updateMensage' => 'Cidade alterada com sucesso.',
        'sucessMensage' => 'Cidade cadastrada com sucesso.',
        'msgModal' => 'Cidade não cadastrada.'
    ];

    $table = [
        'name' => 'CIDADES',
        'primary_key' => 'CODIGO',
        'form' => 'cadastro_cidade'
    ];
@endphp

@section('content_card')
    <form id="cadastro_cidade" action="{{ empty($cidade) ? '/defreq/CIDADES/ins/CODIGO' : '/defreq/CIDADES/upd/CODIGO' }}" method="post">
        {{ csrf_field() }}
        <div class="card-body card-header-divider m-0 mb-5">
            <div class="row mb-3">
                <div class="col-3">
                    <label for="static_codigo" class="mb-0">Código</label>
                    <input type="text" value="{{ @$cidade->CODIGO }}" data-type="integer" id="static_codigo" required name="CODIGO" class="form-control form-control-sm">
                </div>

                <div class="col-9">
                    <label for="static_descricao" class="mb-0">Descrição</label>
                    <input type="text" value="{{ @$cidade->DESCRICAO }}" id="static_descricao" required name="DESCRICAO" class="form-control form-control-sm">
                </div>
                <!-- FIM DO ROW !-->
            </div>

            <div class="row mb-3">
                <div class="col-3">
                    <label for="static_abreviatura" class="mb-0">Abreviatura</label>
                    <input type="text" value="{{ @$cidade->ABREVIATURA }}" id="static_abreviatura" required name="ABREVIATURA" class="form-control form-control-sm">
                </div>

                <div class="col-5">
                    <label for="static_estado" class="mb-0">Estado</label>
                    <input type="text" value="{{ @$cidade->ESTADO }}" id="static_estado" name="ESTADO" class="form-control form-control-sm">
                </div>

                <div class="col-3 col-sm-8 col-lg-3 mt-4">
                    <label class="custom-control custom-checkbox">
                        <input type="hidden" name="CAPITAL" value="0">
                        <input type="checkbox"  {{ @$cidade->CAPIRAL == '1' ? 'checked' : '' }} name="CAPITAL" class="custom-control-input" value="1">
                        <span class="custom-control-label">Capital</span>
                    </label>
                </div>
                <!-- FIM DO ROW !-->
            </div>

            <div class="row mb-3">
                <div class="col-3">
                    <label for="static_latitude" class="mb-0">Latitude</label>
                    <input type="text" value="{{ Format::float(@$cidade->LATITUDE) }}" data-type="numeric" data-mask="integer" id="static_latitude" name="LATITUDE" class="form-control form-control-sm">
                </div>

                <div class="col-3">
                    <label for="static_longitude" class="mb-0">Longitude</label>
                    <input type="text" value="{{ Format::float(@$cidade->LONGITUDE) }}" data-type="numeric" data-mask="integer" id="static_longitude" name="LONGITUDE" class="form-control form-control-sm">
                </div>

                <div class="col-3">
                    <label for="static_altitude" class="mb-0">Altitude</label>
                    <input type="text" value="{{ Format::float(@$cidade->ALTITUDE) }}" data-type="numeric" data-mask="integer" id="static_altitude" name="ALTITUDE" class="form-control form-control-sm">
                </div>

                <div class="col-3">
                    <label for="static_area" class="mb-0">Área</label>
                    <input type="text" value="{{ Format::float(@$cidade->AREA) }}" data-type="numeric" data-mask="integer" id="static_area" name="AREA" class="form-control form-control-sm">
                </div>
                <!-- FIM DO ROW !-->
            </div>
        </div>

        @component('components.defaultButtons', ['variable'=>&$cidade])
        @endcomponent
    </form>
@if(@$not_extend == true)
    @show
@else
    @endsection
@endif

@includeWhen(!@$not_extend, 'layouts.cadastro')
