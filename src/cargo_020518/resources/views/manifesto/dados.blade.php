<div class="col-12" id="dados_manifesto">
    <div class="card-header pb-0 pt-2 card-header-divider m-1 mb-2">
        Dados
    </div>

    <div class="card-body m-0 row p-0">
        <div class="col-5 pl-1">
            <label class="mb-0 w-100">Chave de Acesso</label>
            <strong NR_MDFE></strong>
        </div>

        <div class="col-1">
            <label class="mb-0 w-100">Número</label>
            <strong MANIFESTO></strong>
        </div>

        <div class="col-1_5">
            <label class="mb-0 w-100">Emissão</label>
            <strong DATA></strong>
        </div>

        <div class="col-1_5">
            <label class="mb-0 w-100">Autorização</label>
            <strong AUTORIZACAO></strong>
        </div>

        <div class="col-2">
            <label class="mb-0 w-100">Protocolo</label>
            <strong PROTOCOLO></strong>
        </div>

        <div class="col-1 p-0">
            <label class="mb-0 w-100">Envio</label>
            <strong ENVIO></strong>
        </div>
        <!-- FIM DO ROW !-->
    </div>
    <!-- FIM DO COL-12 !-->
</div>

<div class="col-12" id="conhecimentos">
    <div class="card-header pb-0 card-header-divider pt-2 m-1 mb-2">
        Conhecimentos
    </div>

    <div class="card-body m-0 row p-0" item>
        <div class="col-1 pl-1">
            <label class="mb-0 w-100">Código</label>
        </div>

        <div class="col-4">
            <label class="mb-0 w-100">Remetente</label>
        </div>

        <div class="col-4">
            <label class="mb-0 w-100">Destinatário</label>
        </div>

        <div class="col-1_5">
            <label class="mb-0 w-100">Coleta</label>
        </div>

        <div class="col-1_5">
            <label class="mb-0 w-100">Entrega</label>
        </div>
        <!-- FIM DO ROW !-->
    </div>

    <div id="itens">
        <div class="card-body m-0 row p-0" item>
            <div class="col-1 pl-1">
                <strong CODIGO></strong>
            </div>

            <div class="col-4">
                <strong NMREMETE></strong>
            </div>

            <div class="col-4">
                <strong NMDESTINO></strong>
            </div>

            <div class="col-1_5">
                <strong COLETA></strong>
            </div>

            <div class="col-1_5">
                <strong ENTREGA></strong>
            </div>
            <!-- FIM DO ROW !-->
        </div>
    </div>
    <!-- FIM DO COL-12 !-->
</div>

<div class="col-12 mt-3 mb-3">
    <div class="border" id="totais">
        <div class="card-header m-0 p-2 border-bottom">Totais</div>

        <div class="custom-control-inline w-100">
            <div class="col-1_5 text-right p-2 border-bottom border-right">
                <label class="text-primary mb-0 w-100">Conhecimentos</label>
                <strong CONHECIMENTO>0</strong>
            </div>

            <div class="col-1 text-right p-2 border-bottom border-right">
                <label class="text-primary mb-0 w-100">Peso</label>
                <strong PESO>0,00</strong>
            </div>

            <div class="col-1_5 text-right p-2 border-bottom border-right">
                <label class="text-primary mb-0 w-100">Mercadoria</label>
                <strong TOTAL_MERC>0,00</strong>
            </div>

            <div class="col-1 text-right p-2 border-bottom border-right">
                <label class="text-primary mb-0 w-100">Frete</label>
                <strong FRETE>0,00</strong>
            </div>
            <!-- FIM DO ROW !-->
        </div>
    </div>
</div>

<div id="botoes" class="card-body d-table">
    <button id="alterarMF" class="float-left hidden btn btn-space btn-secondary mb-0">Alterar</button>
    <button id="desistirMF" class="float-left hidden btn btn-space btn-danger mb-0">Desistir</button>
    <button id="gravarMF" class="float-left btn btn-space btn-success mb-0">Gravar</button>
    <button id="enviarMF" class="float-left hidden btn btn-space btn-primary mb-0">Enviar</button>
    <button id="imprimirMF" class="float-left hidden btn btn-space btn-secondary mb-0">Imprimir</button>
    <button id="cancelarMF" class="float-left hidden btn btn-space btn-danger mb-0">Cancelar</button>

    <div class="form-group float-left m-0">
        <input type="text" data-type="date" data-format_picker="dd/mm/yyyy" data-position="top-right" class="invisible" id="encerramento" style="width:0; margin-left:-75px;">
        <button id="encerrarMF" class="hidden float-left btn btn-space btn-success mb-0">Encerrar</button>
    </div>
</div>
