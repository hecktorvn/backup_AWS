$(function(){
    let dadosCTe = $('#dadosCTe').getInput();
    let box = $('#emissao_cte');

    let form_cce = $('form#cce_form');

    let card = $('#emissao_cte > div:first');
    let card_load = $('.card.be-loading:first');
    let buttons = box.find('#buttonsCCe');

    let Ed_cce = form_cce.getInput();

    $.carta_correcao = {};
    Ed_cce.ENTREGA.AutoComplete('cidades', 'CODIGO');
    Ed_cce.COLETA.AutoComplete('cidades', 'CODIGO');
    Ed_cce.MOTORISTA.AutoComplete('motorista', 'CPF');
    Ed_cce.VEICULO.AutoComplete('veiculos', 'PLACA');
    Ed_cce = form_cce.getInput();

    //ADICIONANDO O CLICK
    buttons.find('#enviar').off('click').on('click', function(){
        enviarCCe();
    });

    //CAPTURANDO ALTERAÇÃO DE VALORES
    $.each(Ed_cce, function(i, ipt){
        if(!$.isEmpty(ipt[0].complete)) ipt_tx = $(ipt[0].complete.input);
        else ipt_tx = ipt;
        $(ipt_tx).on('change', function(){
            $.carta_correcao[ipt[0].name] = $(ipt).val();
        });
    });

    //FUNÇÃO RESPONSAVEL POR VALIDAR e ENVIAR O CCe
    function enviarCCe(valid){
        //CASO NÃO EXISTA O CTe
        if($.isEmpty($.dataCTE.data)) return false;
        if(Object.keys($.carta_correcao).length <= 0) return false;

        var cte = $.dataCTE.data.CODIGO;
        if($.isEmpty(valid) || valid < 1){
            let cte_cod = String(cte).padStart(6, '0');
            let ap = App.alerta('Carta Correção referente ao CTe ' + cte_cod, 'A T E N Ç Ã O', ['Ok']);
            ap.on('alert:callback', function(e){ enviarCCe(1); });
        } else if(valid == 1){
            let ap = App.alerta('Confirma emissão de Carta de correção', 'Carta de Correção.', ['Sim', 'Não']);
            ap.on('alert:callback', function(e){
                if(e.button == 0) enviarCCe(2);
            });
        } else if(valid == 2){
            card_load.loading(true);
            curl('emissao/cce', {'ctrc': $.dataCTE.data, 'correcao': $.carta_correcao}, 'GET', function(e){
                card_load.loading(false);
                if(e.status != 'OK') App.alerta(e.msg, 'Erro ao tentar enviar Carta de Correção.', ['Ok']);
                else App.alerta(e.msg, e.titulo, ['Ok'], {'icon':false, 'border':true, 'title':true});
            });
        }
    }

    setTimeout(function(){$.carta_correcao = {};}, 1000);
});
