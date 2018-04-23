<form id="transporte">
    <div class="card-body pt-4">
        <div class="row mb-3">
            <div class="col">
                <label for="static_manifesto" class="mb-0">Manifesto</label>
                <input type="text" id="static_manifesto" readonly name="CODIGO" class="form-control form-control-sm">
            </div>

            <div class="col-2">
                <label for="static_chegada" class="mb-0">Chegada</label>
                <input type="text" id="static_chegada" data-type="date" readonly name="CHEGADA" class="form-control form-control-sm">
            </div>

            <div class="col">
                <label for="static_uf_origem" class="mb-0">Estado Origem</label>
                <input type="text" id="static_uf_origem" data-default="{{Auth::user()->getFilial()->UF}}" required name="UF_ORIGEM" class="form-control form-control-sm">
            </div>

            <div class="col">
                <label for="static_cidade_origem" class="mb-0">Cidade Origem</label>
                <input type="text" id="static_cidade_origem" data-default="{{Auth::user()->getFilial()->CIDADE}}" required name="CIDADE_ORIGEM" class="form-control form-control-sm">
            </div>

            <div class="col">
                <label for="static_uf" class="mb-0">Estado Destino</label>
                <input type="text" id="static_uf" required name="UF" class="form-control form-control-sm">
            </div>

            <div class="col">
                <label for="static_cidade" class="mb-0">Cidade Destino</label>
                <input type="text" id="static_cidade" required name="CIDADE" class="form-control form-control-sm">
            </div>
            <!--FIM DO ROW !-->
        </div>

        <div class="row mb-3">
            <div class="col-2">
                <label for="static_rota" class="mb-0">Rota</label>
                <input type="text" required id="static_rota" name="ROTA" class="form-control form-control-sm">
            </div>

            <div class="col-2">
                <label for="static_veiculo" class="mb-0">Veículo</label>
                <input type="text" required id="static_veiculo" name="VEICULO" class="form-control form-control-sm">
            </div>

            <div class="col-2">
                <label for="static_carreta" class="mb-0">Carreta</label>
                <input type="text" id="static_carreta" name="CARRETA" class="form-control form-control-sm">
            </div>

            <div class="col-3">
                <label for="static_motorista" class="mb-0">Motorista</label>
                <input type="text" required id="static_motorista" name="MOTORISTA" class="form-control form-control-sm">
            </div>

            <div class="col-3">
                <label for="static_motorista_2" class="mb-0">Motorista 2</label>
                <input type="text" id="static_motorista_2" name="MOTORISTA_2" class="form-control form-control-sm">
            </div>
            <!--FIM DO ROW !-->
        </div>

        <div class="row mb-4">
            <div class="input-group col-8">
                <div class="col-6 pl-0 mb-3">
                    <label for="static_lacres" class="mb-0">Lacres</label>
                    <input type="text" id="static_lacres" name="LACRE" class="form-control form-control-sm">
                </div>

                <div class="col-6 pr-0 mb-3">
                    <label for="static_tele_risco" class="mb-0">Tele-Risco</label>
                    <input type="text" id="static_tele_risco" name="TELE_RISCO" class="form-control form-control-sm">
                </div>

                <div class="col-6 pl-0">
                    <label for="static_ciot" class="mb-0">CIOT (Conta Frete)</label>
                    <input type="text" id="static_ciot" name="CIOT" class="form-control form-control-sm">
                </div>

                <div class="col-2">
                    <label for="static_dtsaida" class="mb-0">Saída</label>
                    <input type="text" id="static_dtsaida" required data-format_picker="dd/mm/yy" data-type="date" name="DTSAIDA" class="form-control form-control-sm">
                </div>

                <div class="col-1 p-0">
                    <label for="static_hora_saida" class="mb-0">Hora</label>
                    <input type="text" id="static_hora_saida" required data-type="time" name="HORA_SAIDA" class="form-control form-control-sm">
                </div>

                <div class="col-2">
                    <label for="static_previsao" class="mb-0">Previsão</label>
                    <input type="text" id="static_previsao" required data-format_picker="dd/mm/yy" data-type="date" name="PREVISAO" class="form-control form-control-sm">
                </div>

                <div class="col-1 p-0">
                    <label for="static_hora_previsao" class="mb-0">Hora</label>
                    <input type="text" id="static_hora_previsao" data-type="time" name="HORA_PREVISAO" class="form-control form-control-sm">
                </div>
            </div>

            <div class="col-4">
                <label for="static_observacao" class="col-12 mb-0 pl-0">Observação</label>
                <textarea id="static_observacao" name="OBSERVACAO" style="height:94px; resize:none;" class="form-control form-control-sm"></textarea>
            </div>
            <!--FIM DO ROW !-->
        </div>

        <div class="row pl-3">
            <button id="gravarMF" class="btn btn-space btn-success mb-0">Gravar MF</button>
        </div>
    </div>
</form>

<script src="{{ asset('js/pages/manifesto/transporte.js') }}"></script>
