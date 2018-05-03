<form id="cce_form">
    <div class="row p-4" style="padding-bottom:0 !important;">
        <div class="col-2">
            <label for="static_coleta" class="mb-0">Coleta</label>
            <input type="text" notblock id="static_coleta" data-default="{{$cte['COLETA']}}" name="COLETA" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_entrega" class="mb-0">Entrega</label>
            <input type="text" notblock id="static_entrega" data-default="{{$cte['ENTREGA']}}" name="ENTREGA" class="form-control form-control-sm">
        </div>

        <div class="col-1_5">
            <label for="static_previsao_cce" class="mb-0">Previsão</label>
            <input type="text" notblock data-type="date" value="{{Format::HTML($cte['DT_ENTREGA'], 'timestamp')}}" id="static_previsao_cce" name="PREVISAO" class="form-control form-control-sm">
        </div>

        <div class="col-3">
            <label for="static_motorista_cce" class="mb-0">Motorista</label>
            <input type="text" notblock id="static_motorista" data-default="{{$cte['MOTORISTA']}}" name="MOTORISTA" class="form-control form-control-sm">
        </div>

        <div class="col-1_5">
            <label for="static_veiculo_cce" class="mb-0">Veículo</label>
            <input type="text" notblock id="static_veiculo_cce" data-default="{{$cte['VEICULO']}}" name="VEICULO" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_especie_cce" class="mb-0">Espécie</label>
            <input type="text" notblock id="static_especie" value="{{$cte['ESPECIE']}}" name="ESPECIE" class="form-control form-control-sm">
        </div>
        <!-- FIM ROW !-->
    </div>

    <div class="row p-4">
        <div class="col-12">
            <label for="static_observacao" class="mb-0">Observação</label>
            <textarea name="OBSERVACAO" notblock id="static_observacao_cce" class="form-control form-control-sm" style="height:100px; resize:none;">{{$cte['OBSERVACAO']}}</textarea>
        </div>
    </div>
</form>

<div class="card-body border-top pt-3 pb-2" id="buttonsCCe">
    <button id="enviar" class="btn btn-space btn-success">Enviar</button>
</div>

<script src="{{ asset('js/pages/cte/carta_correcao.js') }}"></script>
