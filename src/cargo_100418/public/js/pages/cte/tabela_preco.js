$(function(){
    let box = $('div#emissao_cte');
    let box_tabela = box.find('#tabela_preco');
    let box_pacotinho = box_tabela.find('[pacotinho]');
    let box_tonelada = box_tabela.find('[tonelada]');
    let box_load = $('.card.be-loading');

    let btn_recalc = box.find('button#recalcularCTe');
    let form_dados = box.find('form#dados_cte');
    let form_destino = box.find('#destinatario');
    let peso_frete = 0;

    box_tabela.block(true);
    box_tabela.find('input').attr('notdesblock', true);
    btn_recalc.on('click', function(){
        recalc();
    });

    box_tabela.find('input[type=text]').each(function(){
        $(this).val( $.format(0, 'money') );
    });

    $.tabela_preco = {'data':{}};
    $.tabela_preco.where = {
        'orig':'',
        'dest':'',
        'uf_orig':'',
        'uf_dest':'',
        'tab':'TABELA_PADRAO'
    };

    let where = $.tabela_preco.where;
    let Ed_ = form_dados.getInput();

    form_dados.find('input#static_coleta').on('get:selected', function(e){
        where.orig = e.response.DESCRICAO;
        where.uf_orig = e.response.ESTADO;
        getTabela();
    });

    form_dados.find('input#static_entrega').on('get:selected', function(e){
        where.dest = e.response.DESCRICAO;
        where.uf_dest = e.response.ESTADO;
        getTabela();
    });

    form_destino.find('input#static-consulta_destinatario').on('get:selected', function(e){
        where.tab = e.response.CNPJ_CPF;
        getTabela();
    });

    box_pacotinho.find('[data-type=numeric]').maskMoney('option', 'allowZero', true);
    box_tonelada.find('[data-type=numeric]').maskMoney('option', 'allowZero', true);

    function getTabela(){
        let empty = false;
        $.each(where, function(i, v){
            if(i != 'tab' && $.isEmpty(v)) empty = true;
        });

        if(empty) return false;
        box_load.loading(true);
        box_tabela.find('input[type=text]').val($.format(0, 'money'));

        curl('ajax/getTabelaPreco/here', where, 'GET', function(e){
            box_load.loading(false);
            $.tabela_preco.data = e.response;

            if(Object.keys(e.response).length <= 0 && where.tab != 'TABELA_PADRAO'){
                where.tab = 'TABELA_PADRAO';
                getTabela();

                return false;
            }

            let dados = {}, pacotinhos = [], produtos = [];
            $.each(e.response, function(i,v){
                if(v.TIPO == 2) dados = v;
                if(v.TIPO == 1) pacotinhos.push(v);
                if(v.TIPO == 3) produtos.push(v);
            });

            let types = box_tabela.find('[dados]').getTypes();
            $.each(box_tabela.find('[dados]').getInput(), function(i, v){
                if(v[0].type != 'checkbox') v.val( $.format(dados[i], types[i]) );
                else v.prop('checked', dados[i] == 1);
            });

            types = box_pacotinho.getTypes();
            $.each(pacotinhos, function(i, pacotinho){
                if(peso_frete <= pacotinho.PESO_FRETE){
                    $.each(box_pacotinho.getInput(), function(i, v){
                        if(v[0].type != 'checkbox') v.val( $.format(pacotinho[i], types[i]) );
                        else v.prop('checked', pacotinho[i] == 1);
                    });
                    return false;
                }
            });

            types = box_tonelada.getTypes();
            $.each(box_tonelada.getInput(), function(i, v){
                if(v[0].type != 'checkbox') v.val( $.format(dados[i], types[i]) );
                else v.prop('checked', dados[i] == 1);
            });

            $.CalcularTotais();
        });
    }

    //FUNÇÃO PARA RECALCULAR OS VALORES
    let recalc = function(){
        let tot = {};
        let documento = [];

        //CAPTURANDO O DOCUMENTO
        if($.outros_documentos.length > 0) documento = $.outros_documentos;
        else if($.nfe_diversas.length > 0) documento = $.nfe_diversas;
        else if($.nota_fiscal.length > 0) documento = $.nota_fiscal;

        //CAPTURANDO VOLUMES
        tot.volumes = documento.length;

        //CAPTURANDO O PESO E A MERCADORIA
        tot.mercadoria = 0, tot.peso = 0;
        $.each(documento, function(i, v){
            let data = Object.assign({}, $.toObject(v));
            if(typeof data.VALOR != 'undefined') tot.mercadoria += $.format(data.VALOR, 'float');
            else if(typeof data.TOTAL != 'undefined') tot.mercadoria += $.format(data.TOTAL, 'float');

            if(typeof data.PESO != 'undefined') tot.peso += $.format(data.PESO, 'float');
        });

        return tot;
    };

    $.tabela_preco.recalc = recalc;
});
