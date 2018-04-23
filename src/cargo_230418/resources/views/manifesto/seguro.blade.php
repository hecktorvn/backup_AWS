<div class="card-body pt-3">
    <form id="seguro">
        <div class="row mb-3">
            <div class="col-2">
                <label for="static_tipo" class="mb-0">Tipo do Responsável</label>
                <select size id="static_tipo" name="TIPO" class="form-control form-control-sm" data-default="0">
                    <option value="0">EMITENTE</option>
                    <option value="1">CONTRATANTE</option>
                </select>
            </div>

            <div class="col-5">
                <label for="static_responsavel" class="mb-0">Responsável pelo Seguro</label>
                <input type="text" id="static_responsavel" name="RESPONSAVEL" class="form-control form-control-sm">
            </div>

            <div class="col-5">
                <label for="static_seguradora" class="mb-0">Seguradora</label>
                <input type="text" id="static_seguradora" required name="SEGURADORA" class="form-control form-control-sm">
            </div>
            <!-- FIM ROW !-->
        </div>

        <div class="row">
            <div class="col-3">
                <label for="static_apolice" class="mb-0">Seguro (apólice)</label>
                <input required type="text" id="static_apolice" name="APOLICE" class="form-control form-control-sm">
            </div>

            <div class="col-4">
                <label for="static_averbacao" class="mb-0">Averbação (apólice)</label>
                <input required type="text" id="static_averbacao" name="AVERBACAO" class="form-control form-control-sm">
            </div>

            <div class="col-4 pt-3" style="margin-top:3px;">
                <button type="button" id="incluir_seguro" class="btn btn-space btn-success">
                    <i class="icon icon-left mdi mdi-plus-circle-o mr-2"></i>Incluir
                </button>

                <button type="button" id="excluir_seguro" class="btn btn-space btn-danger">
                    <i class="icon icon-left mdi mdi-minus-circle-outline mr-2"></i>Excluir
                </button>
            </div>
            <!-- FIM ROW !-->
        </div>

        <div class="row border-top mt-3" style="margin-left:-20px; margin-right:-20px;">
            <table click id="seguro_table" scrolly="150">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>CNPJ/CPF</th>
                        <th>Responsável</th>
                        <th>Seguradora</th>
                        <th>Apólice</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </form>
</div>

<script src="{{ asset('js/pages/manifesto/seguro.js') }}"></script>
