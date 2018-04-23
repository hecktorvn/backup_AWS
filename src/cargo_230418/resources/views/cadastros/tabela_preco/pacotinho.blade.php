<form id="pacotinho_form">
    <div class="row border-bottom pb-2 m-0">
    <div class="col-2">
        <div class="row pt-2 pb-2">
            <div class="col">
                <label for="static_peso" class="mb-0">Peso <strong><small>(Kg)</small></strong></label>
                <input required type="text" id="static_peso" data-type="numeric" name="PESO_FRETE" class="form-control form-control-sm">
            </div>

            <div class="col-7">
                <label for="static_frete" class="mb-0">Frete</label>
                <input required type="text" id="static_frete" data-type="numeric" name="VALOR2_SEC_CAT" class="form-control form-control-sm">
            </div>
        </div>
    </div>

    <div class="col-2_5">
        <div class="row pt-2 pb-2 bg-cinza">
            <div class="col">
                <label for="static_pct_ad" class="mb-0 p-0">Ad-Valorem <small style="margin-top: 2px;">(%)</small></label>
                <input required type="text" id="static_pct_ad" data-type="numeric" name="VALOR1_PERCNOTA" class="form-control form-control-sm">
            </div>

            <div class="col">
                <label for="static_minimo_ad" class="mb-0">Mínimo</label>
                <input required type="text" id="static_minimo_ad" data-type="numeric" name="VALOR10_ADVALOREM_MINIMO" class="form-control form-control-sm">
            </div>
        </div>
    </div>

    <div class="col col-1 pt-2 pb-2">
        <label for="static_pedagio" class="mb-0">Pedágio</label>
        <input required type="text" id="static_pedagio" data-type="numeric" name="VALOR4_PEDAGIO" class="form-control form-control-sm">
    </div>

    <div class="col-2_5">
        <div class="row pt-2 pb-2 bg-cinza">
            <div class="col">
                <label for="static_pct_gris" class="mb-0">GRIS <small style="margin-top: 2px;">(%)</small></label>
                <input required type="text" id="static_pct_gris" data-type="numeric" name="VALOR8_GRIS" class="form-control form-control-sm">
            </div>

            <div class="col">
                <label for="static_minimo_gris" class="mb-0 p-0">Mínimo</label>
                <input required type="text" id="static_minimo_gris" data-type="numeric" name="VALOR9_GRIS_MINIMO" class="form-control form-control-sm">
            </div>
        </div>
    </div>

    <div class="col-1 pt-2 pb-2">
        <label for="static_vigencia" class="mb-0">Vigência</label>
        <input required type="text" id="static_vigencia" data-type="date" name="DATA" class="form-control form-control-sm">
    </div>

    <div class="col-2 p-0 pt-5 pb-2" style="margin-top:3px;">
        <button type="button" incluir class="btn btn-space btn-success m-0">
            <i class="icon icon-left mdi mdi-account-add mr-2"></i>
            Incluir
        </button>

        <button type="button" excluir class="btn btn-space btn-danger m-0">
            <i class="icon icon-left mdi mdi-delete mr-2"></i>
            Excluir
        </button>
    </div>
    <!-- FIM DO ROW !-->
</div>

    <div class="row p-0 m-0">
        <table click scrolly="150">
            <thead>
                <tr>
                    <th>Até(kg)</th>
                    <th>Frete Peso</th>
                    <th>Ad-Valorem</th>
                    <th>Ad-Valorem Mínimo</th>
                    <th>GRIS</th>
                    <th>GRIS Mínimo</th>
                    <th>Pedágio</th>
                    <th>Vigência</th>
                    <th></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</form>

<script src="{{ asset('js/pages/cadastros/tabela_preco/pacotinho.js') }}"></script>
