<form id="dados_cte">
    <div class="row mb-3">
        <div class="col-1">
            <label for="static_modelo" class="mb-0">Modelo</label>
            <input type="text" readonly maxlength="2" value="57" id="static_modelo" name="MODELO" class="form-control form-control-sm">
        </div>

        <div class="col-1">
            <label for="static_serie" class="mb-0">Série</label>
            <input type="text" required data-mask="integer" maxlength="3" id="static_serie" name="SERIE" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_emissao" class="mb-0">Data da Emissão</label>
            <input type="text" data-type="date" required id="static_emissao" name="EMISSAO" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <input name="DT_ENTREGA" type="hidden" data-type="timestamp">
            <label for="static_previsao" class="mb-0">Previsão da entrega</label>
            <input type="text" data-type="date" id="static_previsao" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_previsao_hora" class="mb-0">Hora da entrega</label>
            <input type="text" data-type="time" id="static_previsao_hora" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label class="mb-0" for="static_tiposervico">Tipo de Serviço</label>
            <select name="TIPOSERVICO" id="static_tiposervico" required class="form-control form-control-sm" size>
                <option value="0">Normal</option>
            </select>
        </div>

        <div class="col-2">
            <label class="mb-0" for="static_finalidade">Finalidade</label>
            <select name="FINALIDADE" id="static_finalidade" required class="form-control form-control-sm" size>
                <option value="0">CT-e Normal</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-1">
            <label for="static_cfop" class="mb-0">CFOP</label>
            <input type="text" maxlength="4" required id="static_cfop" name="CFOP" class="form-control form-control-sm">
        </div>

        <div class="col-5">
            <label class="mb-0" for="static_natureza">Natureza da Operação</label>
            <input type="text" maxlength="30" id="static_natureza" name="NATUREZA" class="form-control form-control-sm" required>
        </div>

        <div class="col-2">
            <label for="static_especie" class="mb-0">Especie</label>
            <input type="text" id="static_especie" name="ESPECIE" value="VOLUMES" class="form-control form-control-sm" required>
        </div>

        <div class="col-2">
            <label for="static_forma" class="mb-0">Forma de Emissão</label>
            <select name="TIPO_DOC" id="static_forma" size required class="form-control form-control-sm">
                <option value="0">Normal</option>
            </select>
        </div>

        <div class="col-2">
            <label for="static_formato_dact" class="mb-0">Formato Impressão</label>
            <select id="static_formato_dact" size required class="form-control form-control-sm">
                <option value="0">Retrato</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <div class="card-header card-header-divider p-0 m-0 mb-3 pb-1">
                Origem
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="static_cid_coleta" class="mb-0">UF</label>
                    <input type="hidden" name="CID_COLETA">
                    <input type="text" maxlength="2" readonly id="static_cid_coleta" required class="form-control form-control-sm">
                </div>

                <div class="col">
                    <label for="static_coleta" class="mb-0">Município</label>
                    <input type="text" id="static_coleta" name="COLETA" required class="form-control form-control-sm">
                </div>
            </div>
            <!-- FIM DO COL-6 !-->
        </div>

        <div class="col">
            <div class="card-header card-header-divider p-0 m-0 mb-3 pb-1">
                Destino
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="static_cid_entrega" class="mb-0">UF</label>
                    <input type="hidden" name="CID_ENTREGA">
                    <input type="text" maxlength="2" readonly id="static_cid_entrega" required class="form-control form-control-sm">
                </div>

                <div class="col">
                    <label for="static_entrega" class="mb-0">Município</label>
                    <input type="text" id="static_entrega" name="ENTREGA" required class="form-control form-control-sm">
                </div>
            </div>
            <!-- FIM DO COL-6 !-->
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <label for="static_observacao" class="mb-0">Observação</label>
            <textarea name="OBSERVACAO" id="static_observacao" cols="30" rows="10" class="form-control form-control-sm"></textarea>
        </div>
    </div>
</form>

<script type='text/javascript' src="{{ asset('js/pages/cte/dados.js') }}"></script>
