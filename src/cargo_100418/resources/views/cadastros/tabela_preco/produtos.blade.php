<form id="produtos_form">
    <div class="row m-0 border-bottom p-0 pb-2 col-12 col-lg-12">
        <div class="col-4">
            <label for="static_produto" class="mb-0">Produto</label>
            <input type="text" id="static_produto" name="PRODUTO" class="form-control form-control-sm">
        </div>

        <div class="col">
            <div class="row">
                <div class="col-6">
                    <label for="static_perc" class="mb-0">Perc. (%)</label>
                    <input type="text" id="static_perc" data-type="numeric" name="VALOR1_PERCNOTA" class="form-control form-control-sm">
                </div>

                <div class="col-6">
                    <label for="static_valor" class="mb-0">Valor</label>
                    <input type="text" id="static_valor" data-type="numeric" name="VALOR2_SEC_CAT" class="form-control form-control-sm">
                </div>
            </div>
        </div>

        <div class="col">
            <div class="row">
                <div class="col">
                    <label class="mb-0"></label>
                    <select name="ITEM" size class="form-control form-control-sm">
                        <option value="0">Marcadoria</option>
                        <option value="1">Peso</option>
                        <option value="2">Volumes</option>
                    </select>
                </div>

                <div class="col-8 p-0 pt-3 pb-2" style="margin-top:3px;">
                    <button type="button" incluir class="btn btn-space btn-success m-0">
                        <i class="icon icon-left mdi mdi-account-add mr-2"></i>
                        Incluir
                    </button>

                    <button type="button" excluir class="btn btn-space btn-danger m-0">
                        <i class="icon icon-left mdi mdi-delete mr-2"></i>
                        Excluir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row m-0">
        <table click scrolly="150" style="width:80%;">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Produtos</th>
                    <th>Percentual (%)</th>
                    <th>Valor</th>
                    <th>Cálculo Sobre</th>
                </tr>
            </thead>
        </table>
    </div>
</form>

<script src="{{ asset('js/pages/cadastros/tabela_preco/produtos.js') }}"></script>
