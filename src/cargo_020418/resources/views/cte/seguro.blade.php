<form id="dados_seguradora">
    <div class="row mb-3">
        <div class="col-2">
            <label for="static_responsavel" class="mb-0">Responsavel</label>
            <select name="RESP_SEGURO" id="static_responsavel" required size class="form-control form-control-sm">
                <option value="0">Remetente</option>
                <option value="1">Expedidor</option>
                <option value="2">Recebedor</option>
                <option value="3">Destinatário</option>
                <option value="4">Emitente CT-e</option>
                <option value="5">Consignatário</option>
            </select>
        </div>

        <div class="col-5">
            <label for="static_seguradora" class="mb-0">Seguradora</label>
            <input type="text" id="static_seguradora" required name="SEGURADORA" class="form-control form-control-sm">
        </div>

        <div class="col-5">
            <label for="static_apolice" class="mb-0">Apólice <strong><small>(para mais de uma, separar por vírgula)</small></strong></label>
            <input type="text" id="static_apolice" name="APOLICE" class="form-control form-control-sm">
        </div>
        <!-- FIM DO ROW !-->
    </div>

    <div class="row">
        <div class="col-2">
            <label for="static_averbacao" class="mb-0">Averbação</label>
            <input type="text" id="static_averbacao" name="AVERBACAO" class="form-control form-control-sm">
        </div>

        <div class="col-2">
            <label for="static_valor" class="mb-0">Valor</label>
            <input type="text" id="static_valor" data-type="numeric" name="AVERBADO" class="form-control form-control-sm">
        </div>
        <!-- FIM DO ROW !-->
    </div>
</form>

<script src="{{ asset('js/pages/cte/seguro.js') }}"></script>
