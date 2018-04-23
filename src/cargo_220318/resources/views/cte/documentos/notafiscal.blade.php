<form id="documentos_notafiscal">
    <div class="row mb-3">
        <div class="col-5">
            <label for="static_chave" class="mb-0">
                Chave NFe <small class="text-danger">(Obrigatório para <strong>Nota Fiscal Eletrônica</strong>)</small>
            </label>
            <input type="text" data-mask="notafiscal" id="static_chave" name="CHAVE" class="form-control form-control-sm">
        </div>
        <!-- FIM DO ROW !-->
    </div>

    <div class="row mb-3">
        <div class="col-1">
            <label for="static_numero" class="mb-0">Número</label>
            <input type="text" id="static_numero" name="NUMERO" class="form-control form-control-sm">
        </div>

        <div class="col-1">
            <label for="static_modelo" class="mb-0">Modelo</label>
            <input type="text" id="static_modelo" name="MODELO" class="form-control form-control-sm">
        </div>

        <div class="col-1">
            <label for="static_serie" class="mb-0">Série</label>
            <input type="text" id="static_serie" name="SERIE" class="form-control form-control-sm">
        </div>

        <div class="col-1">
            <label for="static_cfop" class="mb-0">CFOP</label>
            <input type="text" id="static_cfop" name="CFOP" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_emissao" class="mb-0">Emissão</label>
            <input type="text" id="static_emissao" name="EMISAO" data-type="date" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_total" class="mb-0">Total da Nota</label>
            <input type="text" name="TOTAL" id="static_total" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_base_icms" class="mb-0">Base ICMS</label>
            <input type="text" id="static_base_icms" name="BASE_ICMS" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_valor_icms" class="mb-0">Valor ICMS</label>
            <input type="text" id="static_valor_icms" name="VALOR_ICMS" class="form-control form-control-sm">
        </div>
        <!-- FIM DO ROW !-->
    </div>

    <div class="row mb-3">

        <div class="col-2">
            <label for="static_icms_st" class="mb-0">ICMS ST</label>
            <input type="text" id="static_icms_st" name="ICMS_ST" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_peso" class="mb-0">Peso</label>
            <input type="text" id="static_peso" name="PESO" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_volumes" class="mb-0">Volumes</label>
            <input type="text" id="static_volumes" name="VOLUMES" class="form-control form-control-sm">
        </div>

        <div class="col-3 pt-3">
            <button class="btn btn-space btn-success align-middle m-0 mr-4" style="margin-top:2px !important;">
                <i class="icon icon-left mdi mdi-check"></i> Incluir
            </button>

            <button class="btn btn-space btn-danger align-middle m-0" style="margin-top:2px !important;">
                <i class="icon icon-left mdi mdi-alert-circle"></i> Excluir
            </button>
        </div>
        <!-- FIM DO ROW !-->
    </div>

    <div class="row border-top" style="margin-left: -20px; margin-right: -20px;">
        <table id="notas_fiscais" class="table table-striped table-hover table-condensed" style="width: 200%;">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Modelo</th>
                    <th>Série</th>
                    <th>CFOP</th>
                    <th>Emissão</th>
                    <th>Total</th>
                    <th>Base ICMS</th>
                    <th>Valor ICMS</th>
                    <th>ICMS Substituto</th>
                    <th>Peso</th>
                    <th>Volumes</th>
                    <th width="80px">Chave NFe</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>0000</th>
                    <td>57</th>
                    <td>003</th>
                    <td>5909</th>
                    <td>22/03/2018 14:33</th>
                    <td>2.568,52</th>
                    <td>2.568,52</th>
                    <td>68,52</th>
                    <td>265</th>
                    <td>3</th>
                    <td>1</th>
                    <td>1234-1234-4567-7896-4567-9875-6459</th>
                    <td></th>
                </tr>
                <tr>
                    <td>0000</th>
                    <td>57</th>
                    <td>003</th>
                    <td>5909</th>
                    <td>22/03/2018 14:33</th>
                    <td>2.568,52</th>
                    <td>2.568,52</th>
                    <td>68,52</th>
                    <td>265</th>
                    <td>3</th>
                    <td>1</th>
                    <td>1234-1234-4567-7896-4567-9875-6459</th>
                    <td></th>
                </tr>
            </tbody>
        </table>
    </div>
</form>
