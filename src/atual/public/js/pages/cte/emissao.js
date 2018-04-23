$(function(){
    let dadosCTe = $('#dadosCTe').getInput();
    let box = $('#emissao_cte');
    let box_remetente = $('#remetente');
    let box_destinatario = $('#destinatario');
    let box_tomador = $('#tomador');
    let box_nota = $('#emissao_cte #nota_fiscal');
    let box_cubagem = $('#emissao_cte #cubagem');
    let box_resumo = $('#resumo');

    let form_dados = $('form#dados_cte');
    let form_seguro = $('form#dados_seguradora');
    let form_cce = $('form#cce_form');

    let card = $('#emissao_cte > div:first');
    let card_load = $('.card.be-loading:first');

    let modal = $('#modal_danger_remetente');
    let Ed_dados = form_dados.getInput();
    let Ed_remetente = box_remetente.getInput();
    let Ed_tabela = $('#tabela_preco').getInput();
    let Ed_cce = form_cce.getInput();

    Ed_cce.ENTREGA.AutoComplete('cidades', 'CODIGO');
    Ed_cce.COLETA.AutoComplete('cidades', 'CODIGO');

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
        if(e.visible) box.find('button#enviarCTe').removeClass('hidden');
        else box.find('button#enviarCTe').addClass('hidden');
    });

    $('#valores').visibilityChanged({'previousVisibility':false});
    $('#valores').on('visible_change', function(e){
        if(e.visible) box.find('button#recalcularCTe').removeClass('hidden');
        else box.find('button#recalcularCTe').addClass('hidden');
    });

    $('#cce_page').visibilityChanged({'previousVisibility':false});
    $('#cce_page').on('visible_change', function(e){
        if(e.visible) box.find('button#cce').addClass('hidden');
        else box.find('button#cce').removeClass('hidden');
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

    setEventButtons();
    function setEventButtons(){
        //SETANDO O COMANDO DE RECALCULAR
        box.find('button#recalcularCTe').off('click').on('click', function(){
            $.CalcularTotais();
        });

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

            //VERIFICANDO SE TEM CALCULO E ENVIANDO PARA SALVAR
            if(Object.keys($.total_calculado).length > 0 && !$.isEmpty($.total_calculado)){
                data.totais = $.total_calculado;
            }

            //ENVIANDO OS DADOS
            card_load.loading(true);
            curl('emissao/cte', data, 'POST', function(e){
                card_load.loading(false);
                if(e.status != 'OK'){
                    App.alertForm('danger', e.msg, box);
                } else {
                    $.dataCTE.data = e.response;
                    $.dataCTE.codigo = e.response.CODIGO;
                    $.dataCTE.filial = e.response.FILIAL;
                    dadosCTe.CODIGO.val( e.response.CODIGO );
                    dadosCTe.CHAVE.val( e.response.NR_CTE );

                    if(e.action == 'insert') App.alertForm('success', 'CTe emitido com sucesso!', box);
                    else App.alertForm('success', 'CTe alterado com sucesso!', box);
                }
            });

            return false;
        });

        //SETANDO AÇÃO DE ENVIO
        box.find('button#enviarCTe').off('click').on('click', function(){
            if($.isEmpty($.dataCTE.data) || !data_object.action) return false;

            card_load.loading(true);
            curl('emissao/cte/send', {'cte': $.dataCTE.data.CODIGO}, 'POST', function(e){
                card_load.loading(false);
                if(e.status != 'OK'){
                    App.alertForm('danger', e.msg, box, 'Erro de Envio');
                } else {
                    box.block(true);
                    App.alertForm('success', 'CTe enviado com sucesso!', box, 'Envio de CTe');

                    //IMPRIMINDO CTe e DESATIVANDO OS COMANDOS
                    box.find('button#reimpressao').click();
                    data_object.action = false;

                    box.find('#buttons').html('');
                    let btns = {
                        'Cancelar': {'id':'cancelar', 'class':'danger'},
                        'Re-imprimir': {'id':'reimpressao', 'class':'secondary'},
                        'Enviar por E-mail': {'id':'email'},
                        'Carta de Correção': {'id':'cce'}
                    };

                    //CRIANDO BOTÕES
                    $.each(btns, function(i, v){
                        let btn = document.createElement('button');
                        btn.innerHTML = i;
                        btn.id = v.id;

                        if($.isEmpty(v.class)) btn.classList.add('btn', 'btn-space', 'btn-primary');
                        else btn.classList.add('btn', 'btn-space', 'btn-' + v.class);
                        box.find('#buttons').append(btn);
                    });

                    box.find('#correcao_tablist').removeClass('hidden');
                    setEventButtons();
                }
            });

            return false;
        });

        //SETANDO A RE-IMPRESSAO
        box.find('button#reimpressao').off('click').on('click', function(){
            if($.isEmpty($.dataCTE.data)) return false;
            let cancelado = $.isEmpty($.dataCTE.data.OPERADOR_CANC) ? 0 : 1;
            let cce = box.find('#cce_page').is(':visible');

            curl('/emissao/cte/print', {'cte':$.dataCTE.data, 'valid': true, 'cce':cce}, 'GET', function(e){
                let data = {'cte':$.dataCTE.data, 'cce':cce, 'cancelado': cancelado};
                data = $.toObject(data, 'serialize');
                if(e.status == 'OK') ajaxForm('/emissao/cte/print', data, 'POST');
                else App.alerta(e.msg, e.titulo, ['Ok'], {'icon':false, 'border':true, 'title':true});
            });
        });

        //CANCELAMENTO DE CTE
        box.find('button#cancelar').off('click').on('click', function(){
            if($.isEmpty($.dataCTE.codigo) || $.isEmpty($.dataCTE.data)) return false;

            let html = '<div class="col-12">';
            html += '<label for="just_canc" class="mb-0 text-left w-100">Justificativa</label>';
            html += '<input type="text" class="form-control form-control-sm" id="just_canc" name="justificativa">';
            html += '</div>';

            let btns = [
                {
                    'text': 'Cancelar',
                    'class': ' btn-danger'
                },
                'Desistir'
            ];

            let modal = App.alerta(html, '<b>Cancelamento de CTe</b>', btns, {'icon':false, 'title':true, 'border':true});
            modal.on('alert:callback', function(e){
                if(e.button != 0) return false;
                let data = {
                    'motivo': $(this).getInput().justificativa.val(),
                    'filial': $.dataCTE.filial,
                    'codigo': $.dataCTE.codigo,
                    'ctrc': $.dataCTE.data,
                };

                cancelarCTe(data);
            });
        });

        //SETAND ENVIO DE EMAIL
        box.find('button#email').off('click').on('click', function(){
            if($.isEmpty($.dataCTE.data)) return false;

            let html = '<div class="col-12">';
            html += '<label for="email_send" class="mb-0 text-left w-100">Email</label>';
            html += '<input type="text" class="form-control form-control-sm" id="email_send" name="email">';
            html += '</div>';

            let btns = [
                {
                    'text': 'Enviar',
                    'class': ' btn-success'
                },
                'Desistir'
            ];

            let modal = App.alerta(html, '<b>Envio de CTe para E-mail</b>', btns, {'icon':false, 'title':true, 'border':true});
            modal.on('alert:callback', function(e){
                if(e.button != 0) return false;

                let data = {
                    'destino': $(this).getInput().email.val(),
                    'cte':$.dataCTE.data.CODIGO
                };

                card_load.loading(true);
                curl('/emissao/cte/mail', data, 'POST', function(e){
                    card_load.loading(false);
                    if(!$.isEmpty(e.msg) && e.status != 'OK') App.alerta(e.msg, e.titulo, ['Ok'], {'title':true, 'border':true});
                    else if(!$.isEmpty(e.msg)) App.alerta(e.msg, e.titulo, ['Ok'], {'icon':false, 'title':true, 'border':true});
                });
            });
        });

        //SETAND ENVIO DE CCe
        box.find('button#cce').off('click').on('click', function(){
            $('#cce_a').click();
        });
    }

    //FUNÇÃO PARA CALCULAR OS TOTAIS
    $.total_calculado = {};
    $.CalcularTotais = function(method){
        //CAPTURANDO OS DADOS
        let dados = $.dataCTE;
        if($.isEmpty($.dataCTE.data)){
            dados.NR_CTE = dadosCTe.CHAVE.val();
            dados.CODIGO = dadosCTe.CODIGO.val();
        } else dados = $.dataCTE.data;

        let dados_form = form_dados.serializeArray();
        $.each($.toObject(dados_form), function(i, v){
            dados[i] = v;
        });

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
    $.dataCTE = {};
    $.drawCTE = function(filial, codigo, prot){
        card_load.loading(true);

        $.dataCTE.filial = filial;
        $.dataCTE.codigo = codigo;

        if(!$.isEmpty(prot)) box.block(true);
        curl('ajax/getCTE/' + codigo, {'filial':filial, 'codigo':codigo}, 'POST', function(e){
            $.dataCTE.filial = e.cte.FILIAL;
            if(!$.isEmpty(prot)) data_object.action = false;

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
            $.dataCTE.data = e.cte;
            $.each(e.cte, function(i, v){
                types['previsao'] = 'date';
                if(typeof Ed_dados[i] != 'undefined'){
                    let iD = i;
                    if(i == 'DT_ENTREGA') iD = 'previsao';
                    let ipt = form_dados.find('#static_' + String(iD).toLowerCase());

                    if(ipt.is('[autocomplete]')) ipt.setComplete($.format(v, types[iD]), null, true);
                    else ipt.val( $.format(v, types[iD]) );
                }

                if(typeof Ed_cce[i] != 'undefined'){
                    let ipt = form_cce.find('#static_' + String(i).toLowerCase());

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

    //FUNÇÃO PARA CANCELAMENTO DE CTe
    function cancelarCTe(data, confirm){
        if(typeof data != 'object' || $.isEmpty(data.ctrc)) return false;
        if($.isEmpty(confirm)) confirm = -1;

        //VERIFICANDO SE TEM MANIFESTO
        if(!$.isEmpty(data.ctrc.MANIFESTO) && confirm < 1){
            let alert = App.alerta('ATENÇÃO: CTe encontra-se no manifesto <b>' + data.ctrc.MANIFESTO + '</b> deseja cancelar?!', 'Cancelamento CTe', ['Sim', 'Não']);
            alert.on('alert:callback', function(e){
                if(e.button != 0) return false;
                cancelarCTe(data, 1);
            });

            return false;
        }

        //VERIFICANDO SE TEM FATURA
        if(!$.isEmpty(data.ctrc.FATURA) && confirm < 2){
            let alert = App.alerta('Conhecimento encontra-se faturado. Deseja Cancelar?', 'Cancelamento CTe', ['Sim', 'Não']);
            alert.on('alert:callback', function(e){
                if(e.button != 0) return false;
                cancelarCTe(data, 2);
            });

            return false;
        }

        card_load.loading(true);
        curl('emissao/cte/cancelar', data, 'POST', function(e){
            card_load.loading(false);
            if(!$.isEmpty(e.msg)) App.alerta(e.msg, e.titulo, ['Ok'], {'title':true, 'border':true});
            if(e.status == 'OK') box.find('button#cancelar').attr('disabled', true);
        });
    }
});
