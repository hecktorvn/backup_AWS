<div class="row p-0 m-0">
    <div class="col-12 tab-container p-0">
        <ul role="tablist" class="nav nav-tabs nav-tabs-default">
            <li class="nav-item"><a href="#cubagem" data-toggle="tab" role="tab" class="nav-link active show" aria-selected="true">Cubagem</a></li>
            <li class="nav-item"><a href="#tabela_preco" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">Tabela de Preço</a></li>
            <li class="nav-item"><a href="#partilha" data-toggle="tab" role="tab" class="nav-link" aria-selected="false">Dados Partilha</a></li>
        </ul>

        <div class="tab-content p-4 m-0">
            <div id="cubagem" role="tabpanel" class="tab-pane active show">
                @include('cte.valores.cubagem')
            </div>

            <div id="tabela_preco" role="tabpanel" class="tab-pane">
                @include('cte.valores.tabela_preco')
            </div>

            <div id="partilha" role="tabpanel" class="tab-pane">
                @include('cte.valores.partilha')
            </div>
        </div>
    </div>
</div>
