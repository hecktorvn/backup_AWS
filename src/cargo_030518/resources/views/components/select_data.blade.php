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
        <div class="dropdown-menu scrollable-menu" style="z-index: 1000;"></div>
    </div>
</div>
