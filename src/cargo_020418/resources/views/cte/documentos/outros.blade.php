<form id="outros_documentos">
    <div class="row mb-3">
        <div class="col-2">
            <label for="static_tipo" class="mb-0">Tipo Doc</label>
            <select name="TIPO" required id="static_tipo" size class="form-control form-control-sm">
                <option value="0">Declaração</option>
                <option value="1">Outros</option>
            </select>
        </div>

        <div class="col-2">
            <label for="static_numero" class="mb-0">Número</label>
            <input type="text" required data-type="integer" id="static_numero" name="NUMERO" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_emissao" class="mb-0">Emissão</label>
            <input type="text" required data-type="date" id="static_emissao" name="EMISSAO" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_valor" class="mb-0">Valor</label>
            <input type="text" data-type="numeric" required id="static_valor" name="VALOR" class="form-control form-control-sm">
        </div>

        <div class="col-4">
            <label for="static_descricao" class="mb-0">Descrição</label>
            <input type="text" required id="static_descricao" name="DESCRICAO" class="form-control form-control-sm">
        </div>
    </div>

    <div class="row pb-4">
        <div class="col">
            <button incluir class="btn btn-space btn-success align-middle m-0 mr-4" style="margin-top:2px !important;">
                <i class="icon icon-left mdi mdi-plus-circle-o"></i> Incluir
            </button>

            <button excluir class="btn btn-space btn-danger align-middle m-0" style="margin-top:2px !important;">
                <i class="icon icon-left mdi mdi-minus-circle-outline"></i> Excluir
            </button>
        </div>
    </div>

    <div class="row border-top" style="margin-left: -20px; margin-right: -20px; margin-bottom: -20px;">
        <table id="itens_doc" click scrolly="150" class="table table-striped table-hover table-condensed">
            <thead>
                <tr>
                    <th style="width:60px;">Tipo Doc</th>
                    <th style="width:30px;">Número</th>
                    <th style="width:90px;">Emissão</th>
                    <th style="width:50px;">Valor</th>
                    <th>Descrição</th>
                    <th style="width:10px;"></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</form>
<script src="{{ asset('js/pages/cte/documentos/outros.js') }}"></script>
