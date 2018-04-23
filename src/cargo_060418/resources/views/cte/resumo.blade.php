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

    <div class="col-12 pr-0">
        <div class="row col-6 float-left pr-0">
            <div remetente class="col-12 mb-4 p-0">
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

            <div destinatario class="col-12 mb-4 p-0">
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

            <div tomador class="col-12 p-0">
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
        </div>

        <div class="row col-6 float-left ml-4 pr-0">
            <div documentos class="col-12 mb-4 p-0">
                <div class="card-header card-header-divider p-0 m-0 mb-3">
                    Documentos
                </div>

                <div id="itens">
                    <div class="row">
                        <div class="col-2_5 text-right">
                            <label class="mb-0 w-100">Qtd Notas</label>
                            <strong>0</strong>
                        </div>

                        <div class="col text-right">
                            <label class="mb-0 w-100">Valor Total</label>
                            <strong>0,00</strong>
                        </div>

                        <div class="col text-right">
                            <label class="mb-0 w-100">Peso Total</label>
                            <strong>0,00</strong>
                        </div>
                    </div>
                </div>
                <!-- FIM DO COL !-->
            </div>

            <div totais class="col-12 p-0">
                <div class="border">
                    <div class="card-header m-0 p-2 border-bottom">Totais</div>

                    <div class="custom-control-inline w-100">
                        <div class="col-2 text-right p-2 border-bottom border-right">
                            <label class="text-primary mb-0 w-100">Volume</label>
                            <strong class="">0</strong>
                        </div>

                        <div class="col-2 text-right p-2 border-bottom border-right">
                            <label class="text-primary mb-0 w-100">Peso</label>
                            <strong class="">0,00</strong>
                        </div>

                        <div class="col-2 text-right p-2 border-bottom border-right">
                            <label class="text-primary mb-0 w-100">Mercadoria</label>
                            <strong class="">0,00</strong>
                        </div>

                        <div class="col-2 text-right p-2 border-bottom border-right">
                            <label class="text-primary mb-0 w-100">Total (m3)</label>
                            <strong class="">0,00</strong>
                        </div>

                        <div class="col-2 text-right p-2 border-bottom border-right">
                            <label class="text-primary mb-0 w-100">Frete Peso</label>
                            <strong class="">0,00</strong>
                        </div>

                        <div class="col text-right p-2 border-bottom text-right">
                            <label class="text-primary mb-0 w-100">Ad Valorem</label>
                            <strong class="">0,00</strong>
                        </div>
                        <!-- FIM DO ROW !-->
                    </div>

                    <div class="custom-control-inline w-100">
                        <div class="col-2 text-right p-2 border-bottom border-right">
                            <label class="text-primary mb-0 w-100">GRIS</label>
                            <strong class="">0,00</strong>
                        </div>

                        <div class="col-2 text-right p-2 border-bottom border-right">
                            <label class="text-primary mb-0 w-100">Despacho</label>
                            <strong class="">0,00</strong>
                        </div>

                        <div class="col-2 text-right p-2 border-bottom border-right">
                            <label class="text-primary mb-0 w-100">SEC_CAT</label>
                            <strong class="">0,00</strong>
                        </div>

                        <div class="col-2 text-right p-2 border-bottom border-right">
                            <label class="text-primary mb-0 w-100">Coleta</label>
                            <strong class="">0,00</strong>
                        </div>

                        <div class="col-2 text-right p-2 border-bottom border-right">
                            <label class="text-primary mb-0 w-100">Entrega</label>
                            <strong class="">0,00</strong>
                        </div>

                        <div class="col text-right p-2 border-bottom">
                            <label class="text-primary mb-0 w-100">Pedágio</label>
                            <strong class="">0,00</strong>
                        </div>
                        <!-- FIM DO ROW !-->
                    </div>

                    <div class="custom-control-inline w-100">
                        <div class="col-2 text-right p-2 border-bottom border-right">
                            <label class="text-primary mb-0 w-100">Outros</label>
                            <strong class="">0,00</strong>
                        </div>

                        <div class="col-2 text-right p-2 border-bottom border-right">
                            <label class="text-primary mb-0 w-100">Base ICMS</label>
                            <strong class="">0,00</strong>
                        </div>

                        <div class="col-2 text-right p-2 border-bottom border-right">
                            <label class="text-primary mb-0 w-100">Total ICMS</label>
                            <strong class="">0,00</strong>
                        </div>

                        <div class="col-2 text-right p-2 border-bottom border-right">
                            <label class="text-primary mb-0 w-100">Total Frete</label>
                            <strong class="">0,00</strong>
                        </div>

                        <div class="col-2 text-right p-2 border-bottom border-right">
                            <label class="text-primary mb-0 w-100">A Receber</label>
                            <strong class="">0,00</strong>
                        </div>
                        <!-- FIM DO ROW !-->
                    </div>
                </div>
                <!-- FIM DO COL !-->
            </div>
        </div>
    </div>

    <!-- FIM DO ROW !-->
</div>

<script src="{{ asset('js/pages/cte/documentos/resumo.js') }}" charset="utf-8"></script>
