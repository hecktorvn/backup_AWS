<style>#resumo_dados strong{display: block; height: 15px;}</style>
<div class="row p-4" id="resumo_dados">
    <div dados class="col-12 mb-4">
        <div class="card-header card-header-divider p-0 m-0 mb-3">
            Dados
        </div>
        <div class="row">
            <div class="col-5">
                <label class="mb-0 w-100">Chave de Acesso</label>
                <strong chave></strong>
            </div>

            <div class="col-1">
                <label class="mb-0 w-100">Número</label>
                <strong numero></strong>
            </div>

            <div class="col-2">
                <label class="mb-0 w-100">Cidade de Origem</label>
                <strong origem></strong>
            </div>

            <div class="col-2">
                <label class="mb-0 w-100">Cidade de Destino</label>
                <strong destino></strong>
            </div>

            <div class="col-2">
                <label class="mb-0 w-100">Previsão de Entrega</label>
                <strong entrega></strong>
            </div>
            <!-- FIM DO ROW !-->
        </div>
        <!-- FIM DO COL-12 !-->
    </div>

    <div documentos class="col-12 mb-4">
        <div class="card-header card-header-divider p-0 m-0 mb-3">
            Documentos
        </div>

        <div id="itens"></div>
        <!-- FIM DO COL !-->
    </div>

    <div remetente class="col-6 mb-4">
        <div class="card-header card-header-divider p-0 m-0 mb-3">
            Remetente
        </div>

        <div class="row">
            <div class="col-4">
                <label class="mb-0 w-100">CNPJ/CPF</label>
                <strong cnpj_cpf></strong>
            </div>

            <div class="col-8">
                <label class="mb-0 w-100">Razão Social</label>
                <strong social></strong>
            </div>
            <!-- FIM DO ROW !-->
        </div>
        <!-- FIM DO COL !-->
    </div>

    <div destinatario class="col-6 mb-4">
        <div class="card-header card-header-divider p-0 m-0 mb-3">
            Destinatário
        </div>

        <div class="row">
            <div class="col-4">
                <label class="mb-0 w-100">CNPJ/CPF</label>
                <strong cnpj_cpf></strong>
            </div>

            <div class="col-8">
                <label class="mb-0 w-100">Razão Social</label>
                <strong social></strong>
            </div>
            <!-- FIM DO ROW !-->
        </div>
        <!-- FIM DO COL !-->
    </div>

    <div tomador class="col-6 mb-4">
        <div class="card-header card-header-divider p-0 m-0 mb-3">
            Tomador
        </div>

        <div class="row">
            <div class="col-4">
                <label class="mb-0 w-100">CNPJ/CPF</label>
                <strong cnpj_cpf></strong>
            </div>

            <div class="col-8">
                <label class="mb-0 w-100">Razão Social</label>
                <strong social></strong>
            </div>
            <!-- FIM DO ROW !-->
        </div>
        <!-- FIM DO COL !-->
    </div>

    <!-- FIM DO ROW !-->
</div>

<script src="{{ asset('js/pages/cte/documentos/resumo.js') }}" charset="utf-8"></script>
