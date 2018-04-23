$(function(){
    let box = $('#manifesto');
    let form = box.find('form#transporte');
    let Ed_ = form.getInput();
    let load = $('.main-content.be-loading');

    $.manifesto.transporte = {};
    Ed_.UF_ORIGEM.AutoComplete('uf_nome', 'ALFA1');
    Ed_.UF.AutoComplete('uf_nome', 'ALFA1');
    Ed_.CIDADE_ORIGEM.AutoComplete('cidades', 'DESCRICAO');
    Ed_.CIDADE.AutoComplete('cidades', 'DESCRICAO');
    Ed_.VEICULO.AutoComplete('veiculos', 'PLACA', {'label': 'PLACA'});
    Ed_.ROTA.AutoComplete('rotas', 'CODIGO');
    Ed_.MOTORISTA.AutoComplete('motorista', 'CPF');
    Ed_.MOTORISTA_2.AutoComplete('motorista', 'CPF');

    Ed_.UF_ORIGEM.on('get:selected', function(e){
        Ed_.CIDADE_ORIGEM.OptionComplete('data', {'uf':e.response.ALFA1});
    });

    Ed_.UF.on('get:selected', function(e){
        $.manifesto.transporte.UF = e.response;
        Ed_.CIDADE.OptionComplete('data', {'uf':e.response.ALFA1});
    });

    Ed_.CIDADE.on('get:selected', function(e){
        $.manifesto.transporte.CIDADE = e.response;
    });

    Ed_.ROTA.on('get:selected', function(e){
        $.manifesto.transporte.ROTA = e.response;
    });

    form.find('button#gravarMF').off('click').on('click', function(){
        if(!$.manifesto.action) return false;
        gravaMF();
        return false;
    });

    // GRAVA O MANIFESTO
    function gravaMF(valid){
        if(!form.checkRequired()) return false;

        let data = $.toObject(form.serializeArray());
        data.cte = $.manifesto.itens;
        data.seguro = $.manifesto.seguro;
        data.manifesto = $.manifesto.data;
        data.TOTAL_FRETE = $.manifesto.tot.FRETE;
        data.CIDADE_ABREVIATURA = $.manifesto.transporte.CIDADE.ABREVIATURA;
        data.ESTADO_COD3 = $.manifesto.transporte.UF.COD3;
        data.ROTA_UF = $.manifesto.transporte.ROTA.UF;

        if(valid < 1 && $.manifesto.action && data.CID_DEST != $.manifesto.data.CIDADE){
            let a =App.alerta('A CIDADE FAZ PARTE DA IDENTIFICAÇÃO DO MANIFESTO, A ALTERAÇÃO IMPLICA NO CANCELAMENTO DO ATUAL MANIFESTO E GERAÇÃO DE UM NOVO!<br>Deseja prosseguir?', 'A T E N Ç Ã O', ['Sim', 'Não']);
            a.on('alert:callback', function(e){
                if(e.button == 1) gravaMF(2);
            });

            return false;
        }

        load.loading(true);
        curl('manifesto/gravar', data, 'POST', function(e){
            load.loading(false);

            let a = null;
            if(e.status != 'OK') a = App.alerta(e.msg, e.titulo, ['Ok']);
            else a = App.alerta(e.msg, e.titulo, null, {'icon':false, 'title':true, 'border':true});

            if(!$.isEmpty(e.goto)) box.find(e.goto).click();
            if(!$.isEmpty(e.focus)){
                a.on('alert:callback', function(){
                    Ed_[e.focus].focusTime(50);
                });
            }

            if(e.status == 'OK'){
                box.block(true);
                $.manifesto.action = false;

                Ed_.CODIGO.val(e.response.CODIGO);
                $.manifesto.emissao.setTableData($.manifesto.itens);
                $.manifesto.data = e.response;
                $.manifesto.dados.setDate();
            }

        });
    }

    // PERCORRE OS DADOS E PREENCHE OS Ed_
    $.manifesto.transporte.setDate = function(){
        let types = form.getTypes();

        form.flush();
        $.each($.manifesto.data, function(i, v){
            if(!$.isEmpty(Ed_[i])){
                if(!$.isEmpty(types[i])) v = $.format(v, types[i]);
                if($.isEmpty(v)) v = '';
                if(!Ed_[i].is('[autocomplete]')) Ed_[i].val(v);
                else if(!$.isEmpty(v)){
                    setTimeout(function(){
                        Ed_[i].setComplete(v, null, true);
                    }, 1);
                }
            }
        });
    };
});
