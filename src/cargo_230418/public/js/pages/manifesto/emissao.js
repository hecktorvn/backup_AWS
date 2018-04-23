$(function(){
    let box = $('#manifesto');
    let form_emissao = box.find('form#emissao');
    let Ed_ = form_emissao.getInput();
    let load = $('.main-content.be-loading');
    let table = box.find('#conhecimentos_menifesto');

    $.manifesto.emissao = {};
    $.manifesto.itens = [];
    $.manifesto.ctrc = [];
    $.manifesto.data = [];
    $.manifesto.tot = {
        'FRETE_PESO':0,
        'TOTAL_MERC':0,
        'CONHECIMENTO':0,
        'FRETE':0,
        'PESO':0,
    };

    Ed_.FILIAL.AutoComplete('filiais', 'CODIGO');
    Ed_.CONSIGNATARIO.AutoComplete('cliente', 'CNPJ_CPF');
    Ed_.EXPEDIDOR.AutoComplete('cliente', 'CNPJ_CPF');
    Ed_.ESTADO.AutoComplete('cidades', 'CODIGO');

    //SETANDO A AÇÃO DE INCLUIR
    form_emissao.find('#btn_incluir').off('click').on('click', function(){
        if(!$.manifesto.action) return false;
        let data = form_emissao.serializeArray();
        $.manifesto.itens = [];
        $.manifesto.ctrc = [];

        load.loading(true);
        curl('/manifesto/incluir', data, 'POST', function(e){
            load.loading(false);
            if(e.status == 'OK') $.manifesto.emissao.setTableData(e.response);
            else App.alerta(e.msg, 'MANIFESTO');
        });
    });

    //CAPTURANDO O DOUBLE CLICK
    box.find('table#conhecimentos_menifesto').on('table:dblclick', function(e){
        if(!$.manifesto.action) return false;
        $.manifesto.itens = [];
        $.each(e.selecteds, function(i,v){
            $.manifesto.itens.push($.manifesto.ctrc[i]);
        });

        calcCTRC();
    });

    //FUNÇÃO RESPONSAVEL POR EXIBIR OS DADOS DO TABLE
    $.manifesto.emissao.setTableData = function(data){
        let types = {
            'EMISSAO': 'date',
            'VOLUMES': 'int',
            'PESO': 'money',
            'FRETE_PESO': 'money',
            'TOTAL_MERC': 'money'
        };

        let campos = [
            'aqui', 'CODIGO', 'EMISSAO', 'NMREMETE', 'NMDESTINO',
            'NMCONSIG', 'UFDESTINO', 'DESTINO', 'UFORIGEM',
            'ORIGEM', 'VOLUMES', 'PESO', 'FRETE_PESO', 'TOTAL_MERC',
            'NR_CTE', 'FILIAL', 'NMEXPEDIDOR', 'OBSERVACAO', 'SPC'
        ];

        $.manifesto.ctrc = data === false ? [] : data;
        $.setDataTable(table, data, types, campos);
        calcCTRC();
    };

    //FUNÇÃO RESPONSAVEL POR CALCULAR OS ITENS ADICIONADOS
    function calcCTRC(){
        let tot = {
            'FRETE_PESO':0,
            'TOTAL_MERC':0,
            'CONHECIMENTO':0,
            'FRETE':0,
            'PESO':0,
        };

        $.each($.manifesto.itens, function(i, v){
            tot.CONHECIMENTO++;
            tot.PESO += $.format(v.PESO, 'float');
            tot.FRETE += $.format(v.FRETE_VALOR, 'float');
            tot.FRETE_PESO += $.format(v.FRETE_PESO, 'float');
            tot.TOTAL_MERC += $.format(v.TOTAL_MERC, 'float');
        });

        $.each(tot, function(i, v){
            let val = i == 'CONHECIMENTO' ? String(v).padStart(6, '0') : $.format(v, 'money');
            box.find('#total_manifesto strong[' + i + ']').html( val );
        });

        $.manifesto.tot = tot;
    }
});
