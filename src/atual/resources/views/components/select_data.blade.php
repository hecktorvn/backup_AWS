@php
    if(!isset($ini)) $ini = date('d/m/Y');
    if(!isset($fim)) $fim = date('d/m/Y');
@endphp

<div class="input-group mb-3 col-{{ $size or '2_5' }}" select_data>
    <label for="static_{{ $ipt_ini or 'dt_ini' }}" class="mb-0 col-12 pl-0">{{ $title or 'Período' }}</label>
    <input type="text" id="static_{{ $ipt_ini or 'dt_ini' }}" value="{{ $ini }}" data-type="date" name="{{ $ipt_ini or 'DT_INI' }}" class="m-0 form-control form-control-sm border-right-0">
    <input type="text" id="static_{{ $ipt_fim or 'dt_fim' }}" value="{{ $fim }}" data-type="date" name="{{ $ipt_fim or 'DT_FIM' }}" class="m-0 form-control form-control-sm">

    <div class="input-group-append be-addon form-control-sm p-0">
        <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"></button>
        <div class="dropdown-menu scrollable-menu" style="z-index: 1000;">
             <div data-val="0" class="dropdown-item">Limpar</div>
             <div data-val="1" class="dropdown-item active">Hoje</div>
             <div data-val="2" class="dropdown-item">Ontem</div>
             <div data-val="3" class="dropdown-item">Essa Semana</div>
             <div data-val="4" class="dropdown-item">Semana Passada</div>
             <div data-val="5" class="dropdown-item">Essa Quinzena</div>
             <div data-val="6" class="dropdown-item">Quinzena Passada</div>
             <div data-val="7" class="dropdown-item">Esse Mes</div>
             <div data-val="8" class="dropdown-item">Mes Passado</div>
             <div data-val="9" class="dropdown-item">Dois Meses Atraz</div>
             <div data-val="10" class="dropdown-item">Últimos 7 Dias</div>
             <div data-val="11" class="dropdown-item">Últimos 15 Dias</div>
             <div data-val="12" class="dropdown-item">Últimos 30 Dias</div>
             <div data-val="13" class="dropdown-item">Últimos 60 Dias</div>
             <div data-val="14" class="dropdown-item">Últimos 90 Dias</div>
             <div data-val="15" class="dropdown-item">Últimos 120 Dias</div>
             <div data-val="16" class="dropdown-item">Últimos 150 Dias</div>
             <div data-val="17" class="dropdown-item">Últimos 180 Dias</div>
             <div data-val="18" class="dropdown-item">Esse Ano</div>
             <div data-val="19" class="dropdown-item">Há um Ano</div>
             <div data-val="20" class="dropdown-item">Ano Passado</div>
             <div data-val="21" class="dropdown-item">Calendário</div>
        </div>
    </div>
</div>
