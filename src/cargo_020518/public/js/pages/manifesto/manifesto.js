$(function(){
    let box = $('#manifesto');
    let box_load = $('.card.be-loading');
    let box_botoes = box.find('#botoes');
    let btn_enviar = box.find('button#enviarMF');
    let btn_cancelar = box.find('button#cancelarMF');
    let btn_imprimir = box.find('button#imprimirMF');
    let btn_encerrar = box.find('button#encerrarMF');
    let btn_gravar = box.find('button#gravarMF');
    let btn_alterar = box.find('button#alterarMF');
    let btn_desistir = box.find('button#desistirMF');

    $.manifesto = {action: true, dados: {}, temp: {}};
    $.manifesto.temp.ctrc = box.find('#conhecimentos #itens *[item]')[0].outerHTML;

    //SETANDO O COMANDO DE ENVIO
    $.manifesto.setClickButton = function(){
        btn_enviar.off('click').on('click', function(){
            if(!$.isEmpty($.manifesto.data.PROTOCOLO)) return false;
            let data = {'filial': $.manifesto.data.FILIAL, 'codigo': $.manifesto.data.MANIFESTO};

            box_load.loading(true);
            curl('/manifesto/send', data, 'POST', function(e){
                box_load.loading(false);
                if(e.status == 'OK'){
                    if(Object.keys(e.response).length > 0) $.manifesto.data = e.response;
                    if(!$.isEmpty(e.msg)){
                        App.alerta(e.msg, e.titulo, ['Ok'], {'icon':false, 'title':true, 'border': true});
                    }

                    btn_imprimir.click();
                    $.manifesto.drawData();
                } else if(!$.isEmpty(e.msg)) App.alerta(e.msg, e.titulo, ['Ok']);
            });
        });

        btn_imprimir.off('click').on('click', function(){
            if($.isEmpty($.manifesto.data.PROTOCOLO)) return false;
            let data = {'filial': $.manifesto.data.FILIAL, 'codigo': $.manifesto.data.MANIFESTO};

            box_load.loading(true);
            curl('/manifesto/imprimir', data, 'GET', function(e){
                box_load.loading(false);
                if(e.status != 'OK') App.alerta(e.msg, 'A T E N Ç Ã O', ['Ok']);
                else ajaxForm('/manifesto/imprimir', data, 'POST', '_BLANK');
            });
        });

        btn_cancelar.off('click').on('click', function(){
            if($.isEmpty($.manifesto.data.PROTOCOLO)) return false;
            let data = {'filial': $.manifesto.data.FILIAL, 'codigo': $.manifesto.data.MANIFESTO};
            let html = '<div class="col-12">';
            html += '<label for="static_motivo" class="mb-0 text-left w-100">Motivo do cancelamento</label>';
            html += '<textarea class="form-control form-control-sm" id="static_motivo" name="motivo" style="resize:none;"></textarea>';
            html += '</div>';

            let a = App.alerta(html, 'Cancelamento do Manifesto <b>(' + data.codigo + ')</b>', {
                'desistir':'Desistir',
                'cancelar':{'text':'Cancelar', 'class':'btn-danger'}
            }, {'title':true, 'icon':false, 'border':true});

            a.on('alert:callback', function(e){
                if(e.button != 'cancelar') return false;
                data.motivo = $(this).getInput().motivo.val();

                box_load.loading(true);
                curl('manifesto/cancelar', data, 'POST', function(e){
                    box_load.loading(false);
                    if(e.status != 'OK') App.alerta(e.msg, e.titulo, ['Ok']);
                    else{
                        App.alerta('Manifesto cancelado com sucesso!', 'Cancelamento de Manifesto', ['Ok'], {'icon':false});
                        if(Object.keys(e.response).length > 0) $.manifesto.data = e.response;
                        $.manifesto.drawData();
                    }
                });
            });
        });

        btn_alterar.off('click').on('click', function(){
            if(!$.isEmpty($.manifesto.data.PROTOCOLO)) return false;

            btn_gravar.attr('disabled', false);
            btn_enviar.attr('disabled', true);
            $.manifesto.action = true;
            box.block(false);
        });

        btn_desistir.off('click').on('click', function(){
            if(!$.isEmpty($.manifesto.data.PROTOCOLO) && !$.manifesto.action) return false;

            btn_enviar.attr('disabled', false);
            btn_gravar.attr('disabled', true);
            $.manifesto.action = false;
            $.manifesto.drawData();
            box.block(true);
        });

        let tm = null;
        btn_encerrar.off('click').on('click', function(){
            if($.isEmpty($.manifesto.data.PROTOCOLO)) return false;
            box.find('input#encerramento[data-type=date]').data("datetimepicker").show();
            box.find('input#encerramento[data-type=date]').off('change').on('change', function(){
                clearTimeout(tm);
                let val = $(this).val();
                tm = setTimeout(function(){ $.manifesto.dados.encerramento( val ); }, 50);
            });
        });
    }();

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

        box_botoes.find('button').attr('disabled', false);
        btn_enviar.removeClass('hidden');

        if(!$.isEmpty($.manifesto.data.PROTOCOLO)){
            btn_enviar.attr('disabled', true);
            if($.manifesto.data.SITUACAO == '9' || $.manifesto.data.SITUACAO == '1'){
                box_botoes.find('button').attr('disabled', true).addClass('hidden');
                btn_imprimir.removeClass('hidden').attr('disabled', false);
            } else {
                btn_cancelar.removeClass('hidden');
                btn_imprimir.removeClass('hidden');
                btn_encerrar.removeClass('hidden');
                btn_desistir.addClass('hidden');
                btn_alterar.addClass('hidden');
                btn_gravar.attr('disabled', true).addClass('hidden');
            }
        } else {
            btn_cancelar.attr('disabled', true).addClass('hidden');
            btn_imprimir.attr('disabled', true).addClass('hidden');
            btn_encerrar.attr('disabled', true).addClass('hidden');
            btn_desistir.removeClass('hidden');
            btn_alterar.removeClass('hidden');
            btn_gravar.attr('disabled', true).removeClass('hidden');
        }
    };

    //CAPTURANDO OS DADOS DO MANIFESTO
    $.manifesto.prepare = function(conf, c){
        $.manifesto.action = false;
        curl('/manifesto/get', conf, 'POST', function(e){
            $.manifesto.data = e.mdfe;
            $.manifesto.seguro = e.seguro;
            $.manifesto.itens = e.cte;
            $.manifesto.drawData();

            box.block(true);
        });
    };

    //ENCERRANDO O MANIFESTO
    $.manifesto.dados.encerramento = function(dt){
        if($.isEmpty($.manifesto.data.FILIAL)) return false;
        let msg = 'Deseja realmente encerrar o manifesto <b>' + $.manifesto.data.MANIFESTO + '</b> em <b>' + dt + '</b>?';
        let button = [{'text':'Sim', 'class':'btn-success'}, {'text':'Não', 'class':'btn-danger'}];
        let a = App.alerta(msg, 'Encerramento de manifesto', button, {'border':true, 'icon':false, 'title':true});

        a.on('alert:callback', function(e){
            if(e.button != 0) return false;

            box_load.loading(true);
            curl('manifesto/encerrar', {'date': dt, 'filial': $.manifesto.data.FILIAL, 'codigo': $.manifesto.data.MANIFESTO}, 'POST', function(e){
                box_load.loading(false);
                if(!$.isEmpty(e.msg) && e.status != 'OK') App.alerta(e.msg, 'A T E N Ç Ã O', ['Ok']);
                else if(!$.isEmpty(e.msg)) App.alerta(e.msg, 'Retorno da SEFAZ', ['Ok'], {'title':true, 'border':true, 'icon':false});
                if(!$.isEmpty(e.response)){
                    $.manifesto.data = e.response;
                    $.manifesto.drawData();
                }
            });
        })
    };

    //EXIBE OS CTRC NO RESUMO
    $.manifesto.dados.drawResumo = function(data){
        data = $.isEmpty(data) ? $.manifesto.ctrc : data;

        box.find('#conhecimentos #itens').html('');
        $.each(data, function(dI, dV){
            let par = new DOMParser();
            let doc = par.parseFromString($.manifesto.temp.ctrc, 'text/html').body.firstElementChild;
            $.each(dV, function(i, v){
                $(doc).find('*[' + String(i).toLowerCase() + ']').html(v);
            });

            box.find('#conhecimentos #itens').append(doc);
        });

        if(box.find('#conhecimentos #itens').html() == ''){
            box.find('#conhecimentos #itens').html($.manifesto.temp.ctrc);
        }

        //SETANDO OS TOTAIS
        $.each($.manifesto.tot, function(i, v){
            if(i != 'CONHECIMENTO') v = $.format(v, 'money');
            box.find('#totais *[' + String(i).toLowerCase() + ']').html(v);
        });
    };
});
