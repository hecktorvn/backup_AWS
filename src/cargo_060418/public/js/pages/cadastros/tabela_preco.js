//CRIANDO VARIAVEL
$.tabela_preco = {'events':{}, 'tonelada':{}, 'action':false};
$(function(){
    let box = $('#cadastro_tabela_preco');
    let modal = box.find('#modal_tab_preco');
    let btn_gravar = box.find('button[gravar]');
    let btn_novo = box.find('button[desblock]');
    let btn_cancelar = box.find('button[cancelar]');
    let data_ = $.tabela_preco;
    let box_load = $('.be-loading');

    let form_dados = box.find('form#dados');
    let form_tonelada = box.find('form#tonelada');
    let form_produtos = box.find('form#produtos_form');
    let form_pacotinho = box.find('form#pacotinho_form');

    let box_dados = box.find('div.row#dados');
    let box_tonelada = box.find('#tonelada');
    let box_pacotinho = box.find('#pacotinho');
    let box_produtos = box.find('#produtos');

    let Ed_ = box.getInput();
    let Ed_dados = box_dados.getInput();
    let Ed_tonelada = form_tonelada.getInput();

    //SETNADO EVENTO PARA A PESQUISA
    let inpt = [Ed_.ESTADODEST, Ed_.ESTADOORIG, Ed_.ORIGEM, Ed_.DESTINO];
    $.each(inpt, function(i, v){
        v.on('get:selected', function(e){
            let Ed_ = box.getInput();
            if(!Ed_.ESTADODEST[0].value || !Ed_.ESTADODEST[0].value) return false;
            else if(!Ed_.ORIGEM[0].value || !Ed_.DESTINO[0].value) return false;

            capturaTabela(Ed_.DESTINO.val(), Ed_.ESTADODEST.val(), Ed_.ORIGEM.val(), Ed_.ESTADOORIG.val());
        });
    });

    //AO RECEBER O ESTADO DESBLOQUEIA CIDADE
    Ed_.ESTADOORIG.on('get:selected', function(e){
        Ed_.ORIGEM[0].complete.data.uf = e.response.ALFA1;
        Ed_.ORIGEM.attr('disabled', false);
    });

    //AO RECEBER O ESTADO DESBLOQUEIA CIDADE
    Ed_.ESTADODEST.on('get:selected', function(e){
        Ed_.DESTINO[0].complete.data.uf = e.response.ALFA1;
        Ed_.DESTINO.attr('disabled', false);
    });

    //BLOQUEANDO CIDAE CASO MUDE OS DADOS
    let ORIGEM = Ed_.ORIGEM;
    Ed_.ESTADOORIG.on('keydown', function(){
        let Ed_ = box.getInput();
        if(Ed_.ESTADOORIG.isEmpty()){
            ORIGEM.attr('disabled', true);
        }
    });

    //BLOQUEANDO CIDAE CASO MUDE OS DADOS
    let DESTINO = Ed_.DESTINO;
    Ed_.ESTADODEST.on('keydown', function(){
        let Ed_ = box.getInput();
        if(Ed_.ESTADODEST.isEmpty()){
            DESTINO.attr('disabled', true);
        }
    });

    //SETANDO O AUTOCOMPLETE
    Ed_.ESTADOORIG.AutoComplete('uf_nome', 'ALFA1');
    Ed_.ESTADODEST.AutoComplete('uf_nome', 'ALFA1');

    Ed_.ORIGEM.AutoComplete('cidades', 'DESCRICAO');
    Ed_.DESTINO.AutoComplete('cidades', 'DESCRICAO');
    Ed_.ORIGEM[0].complete.template = '<p>{{DESCRICAO}}</p>';
    Ed_.DESTINO[0].complete.template = '<p>{{DESCRICAO}}</p>';

    //BLOQUEANDO OS CAMPOS
    Ed_.ORIGEM.attr('disabled', true);
    Ed_.DESTINO.attr('disabled', true);

    //ALTERANDO COMANDO DE GRAVAÇÃO
    btn_gravar.off('click').on('click', function(){
        let empty = false;
        if(!data_.action) return false;
        if(!box_dados.checkRequired()) empty = true;
        else if(box_tonelada.allEmpty() && data_.pacotinho.length <= 0 && data_.produtos.length <= 0) empty = true;

        if(empty){
            App.alert('Para gravar é necessario informar algum dado!', 'A T E N Ç Ã O', modal, null, ['Ok']);
            return false;
        } else {
            let data = form_dados.serializeArray();
            data = $.toObject(data);
            data.tonelada = $.toObject(form_tonelada.serializeArray());
            data.pacotinho = data_.pacotinho;
            data.produtos = data_.produtos;

            //VEIFICA SE JÁ EXISTE CASO EXISTA INFORMA A SEQUENCIA
            //PARA DAR O UPDATE NA TABELA DE PRECO
            if(typeof data_.tonelada.SEQUENCIA != 'undefined'){
                data.tonelada.SEQUENCIA = data_.tonelada.SEQUENCIA;
            }

            //CAPTURANDO OS TYPES
            data.types = {
                'tonelada': form_tonelada.getTypes(),
                'dados': form_dados.getTypes(),
                'produtos': form_produtos.getTypes(),
                'pacotinho': form_pacotinho.getTypes()
            };

            box_load.loading(true);
            curl('ajax/saveTabelaPreco/naw', data, 'POST', function(e){
                box_load.loading(false);
                console.log(e);
            });
        }
    });

    btn_novo.off('click').on('click', function(){
        btn_cancelar.removeAttr('disabled');
        btn_gravar.removeAttr('disabled');

        $(this).attr('disabled', true);
        box.block(false);
    });

    btn_cancelar.off('click').on('click', function(){
        if(!data_.action) return false;
        data_.tonelada = {};
        data_.pacotinho.splice(0, Object.keys(data_.pacotinho).length);
        data_.produtos.splice(0, Object.keys(data_.produtos).length);
        data_.events.drawTable_pacotinho();
        data_.events.drawTable_produtos();
        drawTonelada();
    });

    //CAPTURA OS DADOS DA TABELA E PRINTA NA TELA
    function capturaTabela(dest, uf_dest, orig, uf_orig){
         let data = {
             'dest': dest,
             'uf_dest': uf_dest,
             'orig': orig,
             'uf_orig': uf_orig,
             'tab': 'TABELA_PADRAO'
        };

        data_.action = false;
        box_load.loading(true);
        checkAction();

        curl('ajax/getTabelaPreco/here', data, 'GET', function(e){
            if(e.status != 'OK'){
                App.notification(e.msg, 'ERRO AO CAPTURAR');
                return false;
            }

            data_.pacotinho.splice(0, Object.keys(data_.pacotinho).length);
            data_.produtos.splice(0, Object.keys(data_.produtos).length);
            data_.tonelada = {};

            if(Object.keys(e.response).length <= 0 ){
                App.alert('Tabela não cadastrada, deseja cadastra-la ?', 'A T E N Ç Ã O', modal, null, ['Sim', 'Não']);
                modal.on('alert:callback', function(e){
                    if(e.button == 0) data_.action = true;
                    else data_.action = false;
                    checkAction();
                });
            } else {
                data_.action = true;
                $.each(e.response, function(i, v){
                    if(v.TIPO == 1){
                        v = $.formatArray(v, form_pacotinho.getTypes());
                        data_.pacotinho.push(v);
                    }else if(v.TIPO == 2){
                        data_.tonelada = v;
                    }else if(v.TIPO == 3){
                        v = $.formatArray(v, form_produtos.getTypes());
                        data_.produtos.push(v);
                    }
                });
            }

            data_.events.drawTable_pacotinho();
            data_.events.drawTable_produtos();
            drawTonelada();

            box_load.loading(false);
            checkAction();
        });
    }

    //SETANDO OS DADOS DE TONELADA
    function drawTonelada(){
        form_tonelada.flush();

        let data = data_.tonelada;
        $.each(Ed_tonelada, function(i, v){
            //SE COLOCAR RETURN FALSE ELE PARA A EXECUÇÃO
            if(typeof data[v[0].name] == 'undefined') return;
            $(v).val( $.format(data[v[0].name], 'money') );
        });

        $.each(Ed_dados, function(i, v){
            //SE COLOCAR RETURN FALSE ELE PARA A EXECUÇÃO
            if(typeof data[v[0].name] == 'undefined'){ $(v).val(''); return; }
            if(v[0].name == 'ICMS_INCLUSO') v[0].checked = (data[v[0].name] == 1);
            $(v).val( $.format(data[v[0].name], 'money') );
        });
    }

    //VERIFICA SE É PARA BLOQUEAR A TELA OU NÃO
    function checkAction(){
        if(!data_.action){
            box.flush();
            box.block(true);
        } else {
            box.block(false);
        }
    }

    checkAction();
});
