$(function(){
    let dadosCTe = $('#dadosCTe').getInput();
    let box = $('#emissao_cte.row');
    let box_remetente = $('#remetente');
    let box_destinatario = $('#destinatario');
    let box_tomador = $('#tomador');
    let box_nota = $('#emissao_cte #nota_fiscal');
    let box_cubagem = $('#emissao_cte #cubagem');
    let box_resumo = $('#resumo');
    let form_dados = $('form#dados_cte');
    let form_seguro = $('form#dados_seguradora');
    let card = $('#emissao_cte > div:first');
    let card_load = $('.card.be-loading:first');

    let modal = $('#modal_danger_remetente');
    let Ed_dados = form_dados.getInput();
    let Ed_remetente = box_remetente.getInput();
    let Ed_tabela = $('#tabela_preco').getInput();

    // VARIÁVEL RESPONSAVEL POR ARMAZENAR
    // O REMETENTE, DESTINATARIO E TOMADOR
    let data_object = {'remetente':null, 'destinatario':null, 'tomador':null, 'action':true};
    $.dtObject = data_object;

    // SETANDO CHANGE NAS PREVISÕES
    form_dados.find('input#static_previsao').on('change', function(){
        let hora = form_dados.find('input#static_previsao_hora').val();
        Ed_dados.DT_ENTREGA.val( $(this).val() + ' ' + hora );
    });

    form_dados.find('input#static_previsao_hora').on('change', function(){
        let data = form_dados.find('input#static_previsao').val();
        Ed_dados.DT_ENTREGA.val( data + ' ' + $(this).val());
    });

    $('#resumo').visibilityChanged({'previousVisibility':false});
    $('#resumo').on('visible_change', function(e){
        if(e.visible) $('button#enviarCTe').removeClass('hidden');
        else $('button#enviarCTe').addClass('hidden');
    });

    $('#valores').visibilityChanged({'previousVisibility':false});
    $('#valores').on('visible_change', function(e){
        if(e.visible) $('button#recalcularCTe').removeClass('hidden');
        else $('button#recalcularCTe').addClass('hidden');
    });

    $('button#recalcularCTe').on('click', function(){
        $.CalcularTotais();
    });

    // CAPTURANDO OS DADOS DE REMETENTE, DESTINATARIO E TOMADOR
    let inputs = {};
    inputs['remetente'] = box_remetente.find('input#static-consulta_remetente');
    inputs['destinatario'] = box_destinatario.find('input#static-consulta_destinatario');
    inputs['tomador'] = box_tomador.find('input#static-consulta_tomador');

    Object.values(inputs).forEach(function(v, i){
        v.off('extend:print').on('extend:print', function(e){
            data_object[ Object.keys(inputs)[i] ] = e.cliente;
        });
    });

    $.cte = null;

    //SETANDO AS AÇÕES DOS BOTÕES
    box.find('button#gravarCTe').off('click').on('click', function(){
        if(!data_object.action) return false;

        //  VALIDAÇÃO DE DADOS
        if(!form_dados.checkRequired()){
            $('a#dados_a').click();
            return false;
        }else if(data_object.remetente === null){
            App.notification('Dado obrigatório, favor preenche-lo.', 'Remetente');
            $('a#remetente_a').click();
            $('#remetente input[type=text].tt-input:first').focusTime(50);
            return false;
        } else if(data_object.destinatario === null) {
            App.notification('Dado obrigatório, favor preenche-lo.', 'Destinatário');
            $('a#destinatario_a').click();
            $('#destinatario input[type=text].tt-input:first').focusTime(50);
            return false;
        } else if(data_object.tomador === null) {
            App.notification('Dado obrigatório, favor preenche-lo.', 'Tomador');
            $('a#tomador_a').click();
            $('#tomador input[type=text].tt-input:first').focusTime(50);
            return false;
        } else if(!form_seguro.checkRequired()) {
            $('a#seguro_a').click();
            return false;
        }

        // CAPTURANDO O ENVIANDO OS DADOS
        let data = form_dados.serializeArray();
        var nameItens = 'itens';

        if($.outros_documentos.length <= 0 && $.nota_fiscal.length <= 0 && $.nfe_diversas.length <= 0){
            App.notification('Favor informar um documento.', 'Documentos');
            $('a#documentos_a').click();

            return false;
        } else if($.nota_fiscal.length > 0 || $.nfe_diversas.length > 0){
            var dataDoc = {};
            if($.nfe_diversas.length > 0) dataDoc =  $.nfe_diversas;
            else dataDoc = $.nota_fiscal;

            nameItens = 'notas';
        } else if($.outros_documentos.length > 0){
            var dataDoc = $.outros_documentos;
            nameItens = 'docs';
        }

        //TRANSFORMANDO O SERIALIZE EM OBJETO e
        //ADICIONANDO O SEGURO AO DATA
        let data_seguro = form_seguro.serializeArray();
        let dados_types = form_dados.getTypes();

        data = $.toObject(data);
        data_seguro = $.toObject(data_seguro);
        data = Object.assign(data_seguro, data);
        dados_types = Object.assign(dados_types, form_seguro.getTypes());

        //SETANDO OS DADOS DO REMETENTE, TOMADOR E DESTINATÁRIO
        data.CODIGO = dadosCTe.CODIGO.val();
        data.REMETENTE = data_object.remetente.CNPJ_CPF;
        data.CONSIGNATARIO = data_object.tomador.CNPJ_CPF;
        data.DESTINATARIO = data_object.destinatario.CNPJ_CPF;

        let tpCubagem = box_cubagem.getTypes();
        tpCubagem.TOTAL = 'float';

        data.types = {'notas': box_nota.getTypes(), 'dados': dados_types, 'cubagem': tpCubagem};
        data[ nameItens ] = dataDoc;
        data.cubagem = $.cubagem;

        //CONVERTENDO OBJETO PARA SERIALIZE
        data = $.toObject(data, 'serialize');

        //ENVIANDO OS DADOS
        card_load.loading(true);
        curl('emissao/cte', data, 'POST', function(e){
            card_load.loading(false);
            if(e.status != 'OK'){
                App.alertForm('danger', e.msg, card);
            } else {
                $.cte = e.response;
                dadosCTe.CODIGO.val( e.response.CODIGO );
                dadosCTe.CHAVE.val( e.response.CHAVE );

                if(e.action == 'insert') App.alertForm('success', 'CTe emitido com sucesso!', card);
                else App.alertForm('success', 'CTe alterado com sucesso!', card);
            }
        });

        return false;
    });

    //SETANDO AÇÃO DE ENVIO
    box.find('button#enviarCTe').off('click').on('click', function(){
        if($.cte == null || !data_object.action) return false;

        card_load.loading(true);
        curl('emissao/cte/send', {'cte': $.cte.CODIGO}, 'POST', function(e){
            card_load.loading(false);
            if(e.status != 'OK'){
                App.alertForm('danger', e.msg, card, 'Erro de Envio');
            } else {
                App.alertForm('success', 'CTe enviado com sucesso!', card, 'Envio de CTe');
                data_object.action = false;
                box.block(true);
            }
        });

        return false;
    });

    //FUNÇÃO PARA CALCULAR OS TOTAIS
    $.total_calculado = {};
    $.CalcularTotais = function(method){
        //CAPTURANDO OS DADOS
        let dados = form_dados.serializeArray();
        dados = $.toObject(dados);

        if($.isEmpty(method)) method = 'POST';

        //CAPTURANDO DADOS DAS CIDADES
        if(typeof $.dados.COLETA != 'undefined'){
            dados.TX_COLETA = $.dados.COLETA.DESCRICAO;
            dados.TX_UF_COLETA = $.dados.COLETA.ESTADO;
        } else {
            dados.TX_COLETA = '';
            dados.TX_UF_COLETA = '';
        }

        if(typeof $.dados.ENTREGA != 'undefined'){
            dados.TX_ENTREGA = $.dados.ENTREGA.DESCRICAO;
            dados.TX_UF_ENTREGA = $.dados.ENTREGA.ESTADO;
        } else {
            dados.TX_ENTREGA = '';
            dados.TX_UF_ENTREGA = '';
        }

        //CAPTURANDO O CONSIGNATARIO
        if(data_object.tomador !== null){
            dados.CONSIGNATARIO = data_object.tomador.CNPJ_CPF;
            dados.CONDICAO_TRIBUTARIA = data_object.tomador.CONDICAO_TRIBUTARIA;
        }else dados.CONSIGNATARIO = '';

        //PEGANDO AS NOTAS
        dados.notas = $.toObject($.nota_fiscal);

        //PEGANDO AS CUBAGEM
        dados.cubagem = $.toObject($.cubagem);

        //PEGANDO ICMS
        dados.ALIQUOTA_ICMS = $.format(Ed_tabela.ICMS.val(), 'float');

        //ENVIANDO REQUISIÇÃO
        card_load.loading(true);
        curl('ajax/CalcularTotaisTP/1', dados, method, function(e){
            card_load.loading(false);
            if(e.status != 'OK') App.alert(e.msg, 'A T E N Ç Ã O', modal, null, ['Ok']);

            $.total_calculado = e.response;
            let partinlha = box.find('#partilha_valores');
            let Ed_part = partinlha.getInput();
            $.each(Ed_part, function(i, v){
                let val = e.response[i];
                if(!$.isEmpty(val)) v.val($.format(val, 'moeny'));
                else v.val('');
            });

            setaTotais();
        });
    };

    //EXIBE OS VALORES EM TELA
    let setaTotais = function(){
        let types = box_resumo.find('[totais]').getTypes();
        $.each($.total_calculado, function(i, v){
            let type = $.isEmpty(types[i]) ? 'money' : types[i];
            box_resumo.find('[totais] ['+i+']').html($.format(v, type));
        });
    };

    //EXIBINDO O CTe CAPTURADO
    $.drawCTE = function(filial, codigo){
        card_load.loading(true);

        box.block(true);
        curl('ajax/getCTE/' + codigo, {'filial':filial, 'codigo':codigo}, 'POST', function(e){
            data_object.action = false;

            let types = form_dados.getTypes();
            card_load.loading(false);

            //SETANDO AS NOTAS FISCAIS
            $.nota_fiscal = e.notas;
            $.nota_fiscal_methods.drawTable(true);
            $.nota_fiscal_methods.startEvent('nota_fiscal:incluir', {'response':e.notas});

            //SETANDO AS DOCUMENTOS
            $.outros_documentos = e.doc_ctrc;
            $.outros_documentos_methods.drawTable(true);

            if(Object.keys(e.doc_ctrc).length > 0){
                $.outros_documentos_methods.startEvent('outros:incluir', {'response':e.doc_ctrc});
                $('#documentos a[role=tab]')[1].click();
            }

            //SETANDO CUBAGEM
            $.cubagem = e.cubagem;
            $.cubagem_methods.drawTable(true);
            $.cubagem_methods.startEvent('cubagem:incluir', {'response':e.cubagem});

            //BLOQUEANDO BOT~ES DE TELA
            $('#documentos a[role=tab]').addClass('disabled');

            //SETANDO DADOS DO CTe
            $.each(e.cte, function(i, v){
                if(typeof Ed_dados[i] != 'undefined'){
                    let ipt = form_dados.find('#static_' + String(i).toLowerCase());

                    if(ipt.is('[autocomplete]')) ipt.setComplete($.format(v, types[i]), null, true);
                    else ipt.val( $.format(v, types[i]) );
                }
            });

            //SETANDO REMETENTE, DESTINATÁRIO E TOMADOR
            box.find('input#static-consulta_remetente').setComplete(e.cte['REMETENTE'], null, true);
            box.find('input#static-consulta_destinatario').setComplete(e.cte['DESTINATARIO'], null, true);
            box.find('input#static-consulta_tomador').setComplete(e.cte['CONSIGNATARIO'], null, true);

            //SETANDO DADOS DA SEGURADORA
            let Ed_seguro = form_seguro.getInput();
            let types_seguro = form_seguro.getTypes();

            $.each(e.cte, function(i, v){
                if(typeof Ed_seguro[i] != 'undefined'){
                    let ipt = form_seguro.find('#static_' + String(i).toLowerCase());

                    if(ipt.is('[autocomplete]')) ipt.setComplete(v, null, true);
                    else ipt.val($.format(v, types_seguro[i]));
                }
            });

            //SETANDO DADOS PRIMARIOS
            let Ed_ = dadosCTe;
            Ed_.CHAVE.val(e.cte.NR_CTE).blur();
            Ed_.CODIGO.val(e.cte.CODIGO);
        });
    };
});
