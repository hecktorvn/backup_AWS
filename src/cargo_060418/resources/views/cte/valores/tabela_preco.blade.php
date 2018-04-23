<div class="row mb-3" dados>
    <div class="col-1_5">
        <label for="static_ind_cubagem" class="mb-0">Índice Cubagem</label>
        <input type="text" id="static_ind_cubagem" name="IND_CUB" data-type="numeric" class="form-control form-control-sm">
    </div>

    <div class="col-1_5">
        <label for="static_icms" class="mb-0">Alíquota ICMS</label>
        <input type="text" id="static_icms" data-type="numeric" name="ICMS" class="form-control form-control-sm">
    </div>

    <div class="col-1_5">
        <label for="static_prazo_dias" class="mb-0">Prazo Entrega</label>
        <input type="text" id="static_prazo_dias" data-type="integer" name="PRAZO_ENTREGA" class="form-control form-control-sm">
    </div>

    <div class="col-3 pt-3">
        <label class="be-checkbox custom-control custom-checkbox m-0" style="margin-top: 8px !important;">
            <input type="checkbox" name="ICMS_INCLUSO" class="custom-control-input">
            <span class="custom-control-label">ICMS Incluso no Frete</span>
        </label>
    </div>
</div>

<div class="card-header card-header-divider p-0 m-0 pb-1">
    Fracionada/Pacotinho
</div>

<div class="row" pacotinho>
    <div class="col-3">
        <div class="row pt-2 pb-2">
            <div class="col">
                <label for="static_peso" class="mb-0">Peso <strong><small>(Kg)</small></strong></label>
                <input type="text" id="static_peso" name="PESO_FRETE" data-type="numeric" class="form-control form-control-sm">
            </div>

            <div class="col">
                <label for="static_frete" class="mb-0">Frete</label>
                <input type="text" id="static_frete" name="VALOR2_SEC_CAT" data-type="numeric" class="form-control form-control-sm">
            </div>
        </div>
    </div>

    <div class="col-3">
        <div class="row pt-2 pb-2 bg-cinza">
            <div class="col">
                <label for="static_pct_ad" class="mb-0 p-0">Ad-Valorem <small style="margin-top: 2px;">(%)</small></label>
                <input type="text" id="static_pct_ad" name="VALOR1_PERCNOTA" data-type="numeric" class="form-control form-control-sm">
            </div>

            <div class="col">
                <label for="static_minimo_ad" class="mb-0">Mínimo</label>
                <input type="text" id="static_minimo_ad" name="VALOR10_ADVALOREM_MINIMO" data-type="numeric" class="form-control form-control-sm">
            </div>
        </div>
    </div>

    <div class="col-1 col-1-5">
        <div class="row pt-2 pb-2">
            <div class="col-12">
                <label for="static_pedagio" class="mb-0">Pedágio</label>
                <input type="text" id="static_pedagio" name="VALOR4_PEDAGIO" data-type="numeric" class="form-control form-control-sm">
            </div>
        </div>
    </div>

    <div class="col-3">
        <div class="row pt-2 pb-2 bg-cinza">
            <div class="col">
                <label for="static_pct_gris" class="mb-0">GRIS <small style="margin-top: 2px;">(%)</small></label>
                <input type="text" id="static_pct_gris" name="VALOR8_GRIS" data-type="numeric" class="form-control form-control-sm">
            </div>

            <div class="col">
                <label for="static_minimo_gris" class="mb-0 p-0">Mínimo</label>
                <input type="text" id="static_minimo_gris" name="VALOR9_GRIS_MINIMO" data-type="numeric" class="form-control form-control-sm">
            </div>
        </div>
    </div>

    <div class="col-2"></div>
    <!-- FIM DO ROW !-->
</div>

<div class="card-header card-header-divider p-0 m-0 pb-1">
    Tonelada
</div>

<div class="row mb-3" tonelada>
    <div class="col-1_5">
        <div class="row pt-2 pb-2">
            <div class="col">
                <label for="static_frete_peso" class="mb-0">Frete Peso <small>(Kg)</small></label>
                <input type="text" id="static_frete_peso" name="PESO_FRETE" data-type="numeric" class="form-control form-control-sm">
            </div>
        </div>
    </div>
    <div class="col-1_5">
        <div class="row pt-2 pb-2">
            <div class="col-12">
                <label for="static_taxa_coleta" class="mb-0">Taxa de Coleta</label>
                <input type="text" id="static_taxa_coleta" name="VALOR6_COLETA" data-type="numeric" class="form-control form-control-sm">
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="row pt-2 pb-2 bg-cinza">
            <div class="col">
                <label for="static_pct_ad_tonelada" class="mb-0 p-0">Ad-Valorem <small style="margin-top: 2px;">(%)</small></label>
                <input type="text" id="static_pct_ad_tonelada" name="VALOR1_PERCNOTA" data-type="numeric" class="form-control form-control-sm">
            </div>

            <div class="col">
                <label for="static_minimo_ad_tonelada" class="mb-0">Mínimo</label>
                <input type="text" id="static_minimo_ad_tonelada" name="VALOR10_ADVALOREM_MINIMO" data-type="numeric" class="form-control form-control-sm">
            </div>
        </div>
    </div>

    <div class="col-1 col-1-5">
        <div class="row pt-2 pb-2">
            <div class="col-12">
                <label for="static_sec_cat" class="mb-0">SEC/CAT</label>
                <input type="text" id="static_sec_cat" name="VALOR2_SEC_CAT" data-type="numeric" class="form-control form-control-sm">
            </div>
        </div>
    </div>

    <div class="col-3">
        <div class="row pt-2 pb-2 bg-cinza">
            <div class="col">
                <label for="static_pct_gris_tonelada" class="mb-0">GRIS <small style="margin-top: 2px;">(%)</small></label>
                <input type="text" id="static_pct_gris_tonelada" name="VALOR8_GRIS" data-type="numeric" class="form-control form-control-sm">
            </div>

            <div class="col">
                <label for="static_minimo_gris_tonelada" class="mb-0 p-0">Mínimo</label>
                <input type="text" id="static_minimo_gris_tonelada" name="VALOR9_GRIS_MINIMO" data-type="numeric" class="form-control form-control-sm">
            </div>
        </div>
    </div>


</div>

<div class="row mb-3" tonelada>
    <div class="col-1_5">
        <label for="static_taxa_entrega" class="mb-0">Taxa Entrega</label>
        <input type="text" id="static_taxa_entrega" name="VALOR7_ENTREGA" data-type="numeric" class="form-control form-control-sm">
    </div>

    <div class="col-1_5">
        <label for="static_despacho" class="mb-0">Despacho</label>
        <input type="text" id="static_despacho" name="VALOR3_DESPACHO" data-type="numeric" class="form-control form-control-sm">
    </div>

    <div class="col-1_5">
        <label for="static_pedagio" class="mb-0">Pedágio</label>
        <input type="text" id="static_pedagio" name="VALOR4_PEDAGIO" data-type="numeric" class="form-control form-control-sm">
    </div>

    <div class="col-1_5">
        <label for="static_val_minimo" class="mb-0">Valor Mínimo</label>
        <input type="text" id="static_val_minimo" name="VALOR5_MINIMO" data-type="numeric" class="form-control form-control-sm">
    </div>

    <div class="col-1_5 p-2 text-right border border-right-0" style="margin-top:-15px; margin-left:2px;">
        <label class="mb-0 w-100 text-primary">Base ICMS</label>
        <strong>0,00</strong>
    </div>

    <div class="col-1_5 p-2 text-right border" style="margin-top:-15px;">
        <label class="mb-0 w-100 text-primary">Total ICMS</label>
        <strong>0,00</strong>
    </div>

    <div class="col-1_5 p-2 text-right border border-left-0" style="margin-top:-15px;">
        <label class="mb-0 w-100 text-primary">Total Frete</label>
        <strong>0,00</strong>
    </div>
</div>

<script src="{{ asset('js/pages/cte/tabela_preco.js') }}"></script>
