$(function(){
    let dadosCTe = $('#dadosCTe').getInput();
    let box = $('#emissao_cte.row');
    let box_remetente = $('#remetente');
    let box_destinatario = $('#destinatario');
    let box_tomador = $('#tomador');
    let box_nota = $('#emissao_cte #nota_fiscal');
    let box_resumo = $('#resumo');
    let form_dados = $('form#dados_cte');
    let form_seguro = $('form#dados_seguradora');
    let card = $('#emissao_cte > div:first');
    let card_load = $('.card.be-loading:first');

    let Ed_dados = form_dados.getInput();
    let Ed_remetente = box_remetente.getInput();

    // VARIÁVEL RESPONSAVEL POR ARMAZENAR
    // O REMETENTE, DESTINATARIO E TOMADOR
    let data_object = {'remetente':null, 'destinatario':null, 'tomador':null};

    // SETANDO CHANGE NAS PREVISÕES
    form_dados.find('input#static_previsao').on('change', function(){
        let hora = form_dados.find('input#static_previsao_hora').val();
        Ed_dados.DT_ENTREGA.val( $(this).val() + ' ' + hora );
    });

    form_dados.find('input#static_previsao_hora').on('change', function(){
        let data = form_dados.find('input#static_previsao').val();
        Ed_dados.DT_ENTREGA.val( data + ' ' + $(this).val());
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
    box.find('button#gravarCTe').on('click', function(){
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

        data.types = {'notas': box_nota.getTypes(), 'dados': dados_types};
        data[ nameItens ] = dataDoc;

        //CONVERTENDO OBJETO PARA SERIALIZE
        data = $.toObject(data, 'serialize');

        //ENVIANDO OS DADOS
        card_load.loading(true);
        curl('emissao/cte', data, 'POST', function(e){
            console.log(e);
            card_load.loading(false);
            if(e.status != 'OK'){
                App.alertForm('danger', e.msg, card);
            } else {
                $.cte = e.response;
                dadosCTe.CODIGO.val( e.response.CODIGO );
                if(e.action == 'insert') App.alertForm('success', 'CTe emitido com sucesso!', card);
                else App.alertForm('success', 'CTe alterado com sucesso!', card);
            }
        });

        return false;
    });

    //SETANDO AÇÃO DE ENVIO
    box.find('button#enviarCTe').on('click', function(){
        if($.cte == null) return false;

        card_load.loading(true);
        curl('emissao/cte/send', {'cte': $.cte.CODIGO}, 'POST', function(e){
            card_load.loading(false);
            if(e.status != 'OK'){
                App.alertForm('danger', e.msg, card, 'Erro de Envio');
            } else {
            }
        });

        return false;
    });
});
