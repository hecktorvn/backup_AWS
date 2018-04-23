<div class="row mb-3">
    <div class="col-2">
        <label for="static_ind_cubagem" class="mb-0">Índice Cubagem</label>
        <input type="text" id="static_ind_cubagem" name="IND_CUBAGEM" class="form-control form-control-sm">
    </div>

    <div class="col-2">
        <label for="static_icms" class="mb-0">Alíquota ICMS</label>
        <input type="text" id="static_icms" name="ICMS" class="form-control form-control-sm">
    </div>

    <div class="col-2">
        <label for="static_prazo_dias" class="mb-0">Prazo Entrega em Dias</label>
        <input type="text" id="static_prazo_dias" name="prazo_dias" class="form-control form-control-sm">
    </div>

    <div class="col pt-3">
        <label class="be-checkbox custom-control custom-checkbox m-0" style="margin-top: 8px !important;">
            <input type="checkbox" name="ICMS_INCLUSO" class="custom-control-input">
            <span class="custom-control-label">ICMS Incluso no Frete</span>
        </label>
    </div>
</div>

<div class="card-header card-header-divider p-0 m-0 pb-1">
    Fracionada/Pacotinho
</div>

<div class="row">
    <div class="col-3">
        <div class="row pt-2 pb-2">
            <div class="col">
                <label for="static_peso" class="mb-0">Peso <strong><small>(Kg)</small></strong></label>
                <input type="text" id="static_peso" name="PESO" class="form-control form-control-sm">
            </div>

            <div class="col">
                <label for="static_frete" class="mb-0">Frete</label>
                <input type="text" id="static_frete" name="FRETE" class="form-control form-control-sm">
            </div>
        </div>
    </div>

    <div class="col-3">
        <div class="row pt-2 pb-2" style="background: rgba(0,0,0,0.1);">
            <div class="col">
                <label for="static_pct_ad" class="mb-0 p-0">Ad-Valorem <small style="margin-top: 2px;">(%)</small></label>
                <input type="text" id="static_pct_ad" name="PCT_AD" class="form-control form-control-sm">
            </div>

            <div class="col">
                <label for="static_minimo_ad" class="mb-0">Mínimo</label>
                <input type="text" id="static_minimo_ad" name="MINIMO_AD" class="form-control form-control-sm">
            </div>
        </div>
    </div>

    <div class="col-1 col-1-5">
        <div class="row pt-2 pb-2">
            <div class="col-12">
                <label for="static_pedagio" class="mb-0">Pedágio</label>
                <input type="text" id="static_pedagio" name="PEDAGIO" class="form-control form-control-sm">
            </div>
        </div>
    </div>

    <div class="col-3">
        <div class="row pt-2 pb-2" style="background: rgba(0,0,0,0.1);">
            <div class="col">
                <label for="static_pct_gris" class="mb-0">GRIS <small style="margin-top: 2px;">(%)</small></label>
                <input type="text" id="static_pct_gris" name="PCT_GRIS" class="form-control form-control-sm">
            </div>

            <div class="col">
                <label for="static_minimo_gris" class="mb-0 p-0">Mínimo</label>
                <input type="text" id="static_minimo_gris" name="MINIMO_GRIS" class="form-control form-control-sm">
            </div>
        </div>
    </div>

    <div class="col-2"></div>
    <!-- FIM DO ROW !-->
</div>

<div class="card-header card-header-divider p-0 m-0 pb-1">
    Tonelada
</div>

<div class="row mb-3">
    <div class="col-2">
        <div class="row pt-2 pb-2">
            <div class="col">
                <label for="static_frete_peso" class="mb-0">Frete Peso <small>(Kg)</small></label>
                <input type="text" id="static_frete_peso" name="FRETE_PESO" class="form-control form-control-sm">
            </div>
        </div>
    </div>

    <div class="col-3">
        <div class="row pt-2 pb-2" style="background: rgba(0,0,0,0.1);">
            <div class="col">
                <label for="static_pct_ad_tonelada" class="mb-0 p-0">Ad-Valorem <small style="margin-top: 2px;">(%)</small></label>
                <input type="text" id="static_pct_ad_tonelada" name="PCT_AD_TONELADA" class="form-control form-control-sm">
            </div>

            <div class="col">
                <label for="static_minimo_ad_tonelada" class="mb-0">Mínimo</label>
                <input type="text" id="static_minimo_ad_tonelada" name="MINIMO_AD_TONELADA" class="form-control form-control-sm">
            </div>
        </div>
    </div>

    <div class="col-1 col-1-5">
        <div class="row pt-2 pb-2">
            <div class="col-12">
                <label for="static_sec_cat" class="mb-0">SEC/CAT</label>
                <input type="text" id="static_sec_cat" name="SEC_CAT" class="form-control form-control-sm">
            </div>
        </div>
    </div>

    <div class="col-3">
        <div class="row pt-2 pb-2" style="background: rgba(0,0,0,0.1);">
            <div class="col">
                <label for="static_pct_gris_tonelada" class="mb-0">GRIS <small style="margin-top: 2px;">(%)</small></label>
                <input type="text" id="static_pct_gris_tonelada" name="PCT_GRIS_TONELADA" class="form-control form-control-sm">
            </div>

            <div class="col">
                <label for="static_minimo_gris_tonelada" class="mb-0 p-0">Mínimo</label>
                <input type="text" id="static_minimo_gris_tonelada" name="MINIMO_GRIS_TONELADA" class="form-control form-control-sm">
            </div>
        </div>
    </div>

    <div class="col-2">
        <div class="row pt-2 pb-2">
            <div class="col-12">
                <label for="static_taxa_coleta" class="mb-0">Taxa de Coleta</label>
                <input type="text" id="static_taxa_coleta" name="TAXA_COLETA" class="form-control form-control-sm">
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-2">
        <label for="static_taxa_entrega" class="mb-0">Taxa de Entrega</label>
        <input type="text" id="static_taxa_entrega" name="TAXA_ENTREGA" class="form-control form-control-sm">
    </div>

    <div class="col-2">
        <label for="static_despacho" class="mb-0">Despacho</label>
        <input type="text" id="static_despacho" name="DESPACHO" class="form-control form-control-sm">
    </div>

    <div class="col-2">
        <label for="static_pedagio" class="mb-0">Pedágio</label>
        <input type="text" id="static_pedagio" name="PEDAGIO" class="form-control form-control-sm">
    </div>

    <div class="col-2">
        <label for="static_val_minimo" class="mb-0">Valor Mínimo</label>
        <input type="text" id="static_val_minimo" name="VAL_MINIMO" class="form-control form-control-sm">
    </div>
</div>
