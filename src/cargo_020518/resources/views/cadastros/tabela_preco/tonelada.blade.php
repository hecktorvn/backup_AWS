<form id="tonelada">
    <div class="row m-0 mb-3">
        <div class="col col-1_5">
            <div class="row pt-2 pb-2">
                <div class="col">
                    <label for="static_frete_peso" class="mb-0">Frete Peso <small>(Kg)</small></label>
                    <input type="text" id="static_frete_peso" data-type="numeric" name="PESO_FRETE" class="form-control form-control-sm">
                </div>
            </div>
        </div>

        <div class="col col-1_5">
            <div class="row pt-2 pb-2">
                <div class="col-12">
                    <label for="static_taxa_coleta" class="mb-0">Taxa de Coleta</label>
                    <input type="text" id="static_taxa_coleta" data-type="numeric" name="VALOR6_COLETA" class="form-control form-control-sm">
                </div>
            </div>
        </div>

        <div class="col-3">
            <div class="row pt-2 pb-2 bg-cinza">
                <div class="col">
                    <label for="static_pct_ad_tonelada" class="mb-0 p-0">Ad-Valorem <small style="margin-top: 2px;">(%)</small></label>
                    <input type="text" id="static_pct_ad_tonelada" data-type="numeric" name="VALOR1_PERCNOTA" class="form-control form-control-sm">
                </div>

                <div class="col">
                    <label for="static_minimo_ad_tonelada" class="mb-0">Mínimo</label>
                    <input type="text" id="static_minimo_ad_tonelada" data-type="numeric" name="VALOR10_ADVALOREM_MINIMO" class="form-control form-control-sm">
                </div>
            </div>
        </div>

        <div class="col-1 col-1-5">
            <div class="row pt-2 pb-2">
                <div class="col-12">
                    <label for="static_sec_cat" class="mb-0">SEC/CAT</label>
                    <input type="text" id="static_sec_cat" data-type="numeric" name="VALOR2_SEC_CAT" class="form-control form-control-sm">
                </div>
            </div>
        </div>

        <div class="col-3">
            <div class="row pt-2 pb-2 bg-cinza">
                <div class="col">
                    <label for="static_pct_gris_tonelada" class="mb-0">GRIS <small style="margin-top: 2px;">(%)</small></label>
                    <input type="text" id="static_pct_gris_tonelada" data-type="numeric" name="VALOR8_GRIS" class="form-control form-control-sm">
                </div>

                <div class="col">
                    <label for="static_minimo_gris_tonelada" class="mb-0 p-0">Mínimo</label>
                    <input type="text" id="static_minimo_gris_tonelada" data-type="numeric" name="VALOR9_GRIS_MINIMO" class="form-control form-control-sm">
                </div>
            </div>
        </div>
        <!-- FIM DO ROW !-->
    </div>

    <div class="row m-0">
        <div class="col-1_5">
            <label for="static_taxa_entrega" class="mb-0">Taxa Entrega</label>
            <input type="text" id="static_taxa_entrega" data-type="numeric" name="VALOR7_ENTREGA" class="form-control form-control-sm">
        </div>

        <div class="col-1_5">
            <label for="static_despacho" class="mb-0">Despacho</label>
            <input type="text" id="static_despacho" data-type="numeric" name="VALOR3_DESPACHO" class="form-control form-control-sm">
        </div>

        <div class="col-1_5">
            <label for="static_pedagio" class="mb-0">Pedágio</label>
            <input type="text" id="static_pedagio" data-type="numeric" name="VALOR4_PEDAGIO" class="form-control form-control-sm">
        </div>

        <div class="col-1_5">
            <label for="static_val_minimo" class="mb-0">Valor Mínimo</label>
            <input type="text" id="static_val_minimo" data-type="numeric" name="VALOR5_MINIMO" class="form-control form-control-sm">
        </div>
        <!-- FIM DO ROW !-->
    </div>
</form>
