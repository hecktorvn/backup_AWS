<form id="cubagem_valores">
    <div class="row mb-4">
        <div class="col-2">
            <label for="static_qtd" class="mb-0">Quantidade</label>
            <input type="text" id="static_qtd" name="QTD" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_altura" class="mb-0">Altura <small>(m)</small></label>
            <input type="text" id="static_altura" name="ALTURA" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_largura" class="mb-0">Largura <small>(m)</small></label>
            <input type="text" id="static_largura" name="LARGURA" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_comprimento" class="mb-0">Comprimento <small>(m)</small></label>
            <input type="text" id="static_comprimento" name="COMPRIMENTO" class="form-control form-control-sm">
        </div>

        <div class="col-3 pt-3">
            <button class="btn btn-space btn-success align-middle m-0 mr-4" style="margin-top:2px !important;">
                <i class="icon icon-left mdi mdi-plus-circle-o"></i> Incluir
            </button>

            <button class="btn btn-space btn-danger align-middle m-0" style="margin-top:2px !important;">
                <i class="icon icon-left mdi mdi-minus-circle-outline"></i> Excluir
            </button>
        </div>
        <!-- FIM DO ROW !-->
    </div>

    <div class="row border-top" style="margin-left: -20px; margin-right: -20px; margin-bottom: -20px;">
        <table id="table_valores_cubagem" scrollx="1500" scrolly="150" class="table table-striped table-hover table-condensed">
            <thead>
                <tr>
                    <th>Sequência</th>
                    <th>Quantidade</th>
                    <th>Altura (m)</th>
                    <th>Largura (m)</th>
                    <th>Comprimento (m)</th>
                    <th>Total (m3)</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</form>
