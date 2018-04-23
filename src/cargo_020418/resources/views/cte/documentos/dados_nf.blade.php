<form id="{{ $form_name }}">
    <div class="row mb-4 border-bottom pb-4">
        <div class="col-6">
            <label for="static_chave" class="mb-0">
                Chave NFe <small class="text-danger">(Obrigatório para <strong>Nota Fiscal Eletrônica</strong>)</small>
            </label>

            <div class="input-group mb-3">
                <input type="text" required data-mask="notafiscal" data-type="integer" id="static_chave" name="CHAVE" class="form-control form-control-sm">
                <div class="input-group-append">
                    <button type="button" searchNF class="btn btn-space btn-primary">
                        <span class="mdi mdi-search"></span>
                    </button>
                </div>
            </div>
        </div>
        <!-- FIM DO ROW !-->
    </div>

    <div class="row mb-3">
        <div class="col-1">
            <label for="static_numero" class="mb-0">Número</label>
            <input type="text" required id="static_numero" data-type="integer" name="NUMERO" class="form-control form-control-sm">
        </div>

        <div class="col-1">
            <label for="static_modelo" class="mb-0">Modelo</label>
            <input type="text" required id="static_modelo" data-type="integer" name="MODELO" class="form-control form-control-sm">
        </div>

        <div class="col-1">
            <label for="static_serie" class="mb-0">Série</label>
            <input type="text" required id="static_serie" name="SERIE" class="form-control form-control-sm">
        </div>

        <div class="col-1">
            <label for="static_cfop" class="mb-0">CFOP</label>
            <input type="text" required maxlength="4" data-type="integer" minlength="4" id="static_cfop" name="CFOP" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_emissao" class="mb-0">Emissão</label>
            <input type="text" required id="static_emissao" name="EMISSAO" data-type="date" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_total" class="mb-0">Total da Nota</label>
            <input type="text" required data-type="numeric" name="TOTAL" id="static_total" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_base_icms" class="mb-0">Base ICMS</label>
            <input type="text" required data-type="numeric" id="static_base_icms" name="BASE_ICMS" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_valor_icms" class="mb-0">Valor ICMS</label>
            <input type="text" required data-type="numeric" id="static_valor_icms" name="VALOR_ICMS" class="form-control form-control-sm">
        </div>
        <!-- FIM DO ROW !-->
    </div>

    <div class="row mb-4">

        <div class="col-2">
            <label for="static_icms_st" class="mb-0">ICMS ST</label>
            <input type="text" required data-type="numeric" id="static_icms_st" name="ICMS_SUBSTITUTO" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_peso" class="mb-0">Peso</label>
            <input type="text" required data-type="numeric" id="static_peso" name="PESO" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_volumes" class="mb-0">Volumes</label>
            <input type="text" required data-type="numeric" id="static_volumes" name="VOLUMES" class="form-control form-control-sm">
        </div>

        <div class="col-3 pt-3">
            <button incluir class="btn btn-space btn-success align-middle m-0 mr-4" style="margin-top:2px !important;">
                <i class="icon icon-left mdi mdi-plus-circle-o"></i> Incluir
            </button>

            <button excluir class="btn btn-space btn-danger align-middle m-0" style="margin-top:2px !important;">
                <i class="icon icon-left mdi mdi-minus-circle-outline"></i> Excluir
            </button>
        </div>
        <!-- FIM DO ROW !-->
    </div>

    <div class="row border-top" style="margin-left: -20px; margin-right: -20px; margin-bottom: -20px;">
        <table id="table_{{ $form_name }}_nf" click scrollx="1500" scrolly="150" class="table table-striped table-hover table-condensed">
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
                    <th style="width: 250px;">Chave NFe</th>
                    <th></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</form>

<script src="{{ asset('js/pages/cte/documentos/nf.js') }}" charset="utf-8"></script>
