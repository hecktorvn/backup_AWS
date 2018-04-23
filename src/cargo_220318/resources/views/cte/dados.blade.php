<form id="dados_cte">
    <div class="row mb-3">
        <div class="col-1">
            <label for="static_modelo" class="mb-0">Modelo</label>
            <input type="text" readonly value="57" id="static_modelo" name="MODELO" class="form-control form-control-sm">
        </div>

        <div class="col-1">
            <label for="static_serie" class="mb-0">Série</label>
            <input type="text" required id="static_serie" name="SERIE" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_data_emissao" class="mb-0">Data da Emissão</label>
            <input type="text" data-type="timestamp" required id="static_data_emissao" name="DATA_EMISSAO" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_previsao" class="mb-0">Data da entrega</label>
            <input type="text" data-type="date" id="static_previsao" name="PREVISAO" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_previsao" class="mb-0">Hora da entrega</label>
            <input type="text" data-type="time" id="static_previsao" name="PREVISAO" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label class="mb-0" for="static_tipo_servico">Tipo de Serviço</label>
            <select name="TIPO_SERVICO" id="static_tipo_servico" required class="form-control form-control-sm" size>
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
            <input type="text" required id="static_cfop" name="CFOP" class="form-control form-control-sm">
        </div>

        <div class="col-7">
            <label class="mb-0" for="static_natureza">Natureza da Operação</label>
            <input type="text" id="static_natureza" name="NATUREZA" class="form-control form-control-sm" required>
        </div>

        <div class="col-2">
            <label for="static_forma" class="mb-0">Forma de Emissão</label>
            <select name="FORMA" id="static_forma" size required class="form-control form-control-sm">
                <option value="0">Normal</option>
            </select>
        </div>

        <div class="col-2">
            <label for="static_formato_dact" class="mb-0">Formato Impressão</label>
            <select name="FORMATO_DACT" id="static_formato_dact" size required class="form-control form-control-sm">
                <option value="0">Retrato</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <div class="card-header card-header-divider m-0 mb-2 pb-1">
                Origem
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="static_cid_coleta" class="mb-0">UF</label>
                    <input type="text" maxlength="2" readonly id="static_cid_coleta" name="CID_COLETA" required class="form-control form-control-sm">
                </div>

                <div class="col">
                    <label for="static_coleta" class="mb-0">Município</label>
                    <input type="text" id="static_coleta" name="COLETA" required class="form-control form-control-sm">
                </div>
            </div>
            <!-- FIM DO COL-6 !-->
        </div>

        <div class="col">
            <div class="card-header card-header-divider m-0 mb-2 pb-1">
                Destino
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="static_cid_entrega" class="mb-0">UF</label>
                    <input type="text" maxlength="2" readonly id="static_cid_entrega" name="CID_ENTREGA" required class="form-control form-control-sm">
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
            <textarea name="observacao" id="observacao" cols="30" rows="10" class="form-control form-control-sm"></textarea>
        </div>
    </div>
</form>

<script type='text/javascript' src="{{ asset('js/pages/cte/dados.js') }}"></script>
