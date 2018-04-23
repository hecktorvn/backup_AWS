$(function(){
    let box = $('#manifesto');
    $.manifesto = {action: true, dados: {}};

    //SETANDO OS DADOS
    $.manifesto.dados.setDate = function(){
        $.each($.manifesto.data, function(i, v){
            if(i == 'DATA' || i == 'EMISSAO' || i == 'ENVIO') v = $.format(v, 'date');
            if(v === null || v == 'null') v = '';
            box.find('#dados_manifesto strong[' + String(i).toLowerCase() + ']').html(v);
        });
    }

    // FUNÇÃO RESPONSAVEL POR EXIBIR TODOS OS DADOS
    $.manifesto.drawData = function(){
        $.manifesto.dados.setDate();
        $.manifesto.transporte.setDate();
        $.manifesto.seguro_table($.manifesto.seguro);
        $.manifesto.emissao.setTableData($.manifesto.itens);
    };
});
