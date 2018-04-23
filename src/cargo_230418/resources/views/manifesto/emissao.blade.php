<div class="card-header card-header-divider mb-0">
    Dados do Conhecimento
    <span class="card-subtitle">Dados do conhecimento para a emissão do manifesto</span>
</div>

<div class="card-body p-5">
    <form id="emissao">
        <div class="row">
            <div class="col-5">
                <label for="static_filial" class="mb-0">Filial</label>
                <input type="text" id="static_filial" data-default="{{ Auth::user()->minhaFilial() }}" name="FILIAL" class="form-control form-control-sm">
            </div>

            @component('components.select_data', ['size'=>3])
            @endcomponent

            <div class="col">
                <label for="static_estado" class="mb-0">Estado de Entrega</label>
                <input type="text" id="static_estado" name="ESTADO" class="form-control form-control-sm">
            </div>
            <!-- FIM ROW !-->
        </div>

        <div class="row mb-3">
            <div class="col-5">
                <label for="static_consignatario" class="mb-0">Consignatário (Tomador do Frete)</label>
                <input type="text" id="static_consignatario" name="CONSIGNATARIO" class="form-control form-control-sm">
            </div>

            <div class="col-2">
                <label for="static_situacao" class="mb-0">Situação CTe</label>
                <select name="SITUACAO" id="static_situacao" size class="form-control form-control-sm">
                    <option value="0">Sem Manifesto</option>
                    <option value="1">Em Manifesto</option>
                </select>
            </div>

            <div class="col">
                <label for="static_chave_cte" class="mb-0">Chave ou Número do CTe</label>
                <input type="text" id="static_chave_cte" data-type="integer" name="CHAVE_CTE" class="form-control form-control-sm">
            </div>
            <!-- FIM ROW !-->
        </div>

        <div class="row">
            <div class="col-5">
                <label for="static_expedidor" class="mb-0">Expedidor (Redespacho)</label>
                <input type="text" id="static_expedidor" name="EXPEDIDOR" class="form-control form-control-sm">
            </div>

            <div class="col">
                <label for="static_chave_mdfe" class="mb-0">Chave ou Número do MDFe</label>
                <input type="text" id="static_chave_mdfe" name="CHAVE_MDFE" class="form-control form-control-sm">
            </div>

            <div class="col-3 pt-3" style="margin-top:2px;">
                <button type="button" id="btn_incluir" class="btn btn-space btn-success">
                    <i class="icon icon-left mdi mdi-plus-circle-o mr-2"></i>Incluir
                </button>

                <button type="button" disabled id="excluir" class="btn btn-space btn-danger">
                    <i class="icon icon-left mdi mdi-minus-circle-outline mr-2"></i>Excluir
                </button>
            </div>
            <!-- FIM ROW !-->
        </div>
    </form>
</div>

<div class="card-header card-header-divider m-0 pl-4 pt-0">
    Conhecimentos
    <span class="card-subtitle">Duplo clickpara selecionar o CTe que irá compor o manifestoou clicar no botão marcar abaixo para selecionar todos</span>
</div>

<div class="card-body border-bottom p-0">
    <table dbclick class="table table-striped table-hover table-condensed" scrolly="200" scrollx="1500" id="conhecimentos_menifesto">
        <thead>
            <tr>
                <th></th>
                <th>CTRC</th>
                <th>Emissão</th>
                <th width="300px">Remetente</th>
                <th width="300px">Destinatário</th>
                <th width="300px">Consignatário</th>
                <th>UF</th>
                <th>Entrega</th>
                <th>UF</th>
                <th>Coleta</th>
                <th>Volumes</th>
                <th>Peso</th>
                <th>Total</th>
                <th>Mercadoria</th>
                <th width="250px">Chave CTe</th>
                <th>Filial</th>
                <th>Expedidor</th>
                <th>Observação</th>
                <th></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<div class="card-header card-header-divider mb-0">
    Totais
    <span class="card-subtitle">Totais do manifesto</span>
</div>

<div class="card-body ml-3 mr-3" id="total_manifesto">
    <div class="row">
        <div class="col border p-2">
            <label class="text-primary mb-0 pl-0">Conhecimento...: </label>
            <strong CONHECIMENTO>000000</strong>
        </div>

        <div class="col border p-2 border-left-0">
            <label class="text-primary mb-0 pl-0">Peso Total...: </label>
            <strong FRETE_PESO>0,00</strong>
        </div>

        <div class="col border p-2 border-left-0">
            <label class="text-primary mb-0 pl-0">Total Mercadoria...: </label>
            <strong TOTAL_MERC>0,00</strong>
        </div>

        <div class="col border p-2 border-left-0">
            <label class="text-primary mb-0 pl-0">Total Frete...: </label>
            <strong FRETE>0,00</strong>
        </div>
        <!-- FIM DO ROW !-->
    </div>
</div>

<script src="{{ asset('js/pages/manifesto/emissao.js') }}"></script>
