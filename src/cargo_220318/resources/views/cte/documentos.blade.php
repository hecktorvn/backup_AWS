<div class="row p-0 m-0">
    <div class="col-12 tab-container p-0">
        <ul role="tablist" class="nav nav-tabs nav-tabs-default">
            <li class="nav-item"><a href="#nota_fiscal" data-toggle="tab" role="tab" class="nav-link active show" aria-selected="true">Nota Fiscal</a></li>
            <li class="nav-item"><a href="#outros_docs" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">Outros Documentos</a></li>
            <li class="nav-item"><a href="#nfe_diversas" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">NFe Diversas (CTe Grobalizado)</a></li>
        </ul>

        <div class="tab-content pb-0 mb-4">
            <div id="nota_fiscal" role="tabpanel" class="tab-pane active show">
                @include('cte.documentos.notafiscal')
            </div>

            <div id="outros_docs" role="tabpanel" class="tab-pane"></div>
            <div id="nfe_diversas" role="tabpanel" class="tab-pane"></div>
        </div>
    </div>
</div>
