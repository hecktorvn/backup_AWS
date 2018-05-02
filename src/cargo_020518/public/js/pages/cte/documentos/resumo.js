$(function(){
    let box_resumo = $('#resumo');
    let form_dados = $('form#dados_cte');
    let dadosCTe = $('#dadosCTe').getInput();

    let Ed_dados = form_dados.getInput();
    let Ed_dest = $('#emissao_cte #destinatario').getInput();

    //PREENCHENDO RESUMO
    dadosCTe.CHAVE.on('change', function(){
        box_resumo.find('*[dados] *[chave]').html( $(this).val() );
    });

    dadosCTe.CODIGO.on('change', function(){
        box_resumo.find('*[dados] *[numero]').html( $(this).val() );
    });

    Ed_dados.DT_ENTREGA.on('change', function(){
        box_resumo.find('*[dados] *[entrega]').html( $(this).val() );
    });

    Ed_dados.COLETA.on('change', function(){
        box_resumo.find('*[dados] *[origem]').html( form_dados.find('input#static_coleta').val() );
    });

    Ed_dados.ENTREGA.on('change', function(){
        box_resumo.find('*[dados] *[destino]').html( form_dados.find('input#static_entrega').val() );
    });

    $('#documentos #outros_docs').on('outros:incluir outros:excluir', function(e){
        drawDocs($.outros_documentos, 'doc');
        $.CalcularTotais();
    });

    $('#documentos #nota_fiscal').on('nota_fiscal:incluir nota_fiscal:excluir', function(e){
        drawDocs($.nota_fiscal, 'nota');
        $.CalcularTotais();
    });

    $('#emissao_cte #destinatario input#static-consulta_destinatario').on('get:selected', function(e){
        box_resumo.find('div[destinatario] strong[cnpj_cpf]').html(e.response.CNPJ_CPF);
        box_resumo.find('div[destinatario] strong[social]').html(e.response.SOCIAL);
    });

    $('#emissao_cte #remetente input#static-consulta_remetente').on('get:selected', function(e){
        box_resumo.find('div[remetente] strong[cnpj_cpf]').html(e.response.CNPJ_CPF);
        box_resumo.find('div[remetente] strong[social]').html(e.response.SOCIAL);
    });

    $('#emissao_cte #tomador input#static-consulta_tomador').on('get:selected', function(e){
        box_resumo.find('div[tomador] strong[cnpj_cpf]').html(e.response.CNPJ_CPF);
        box_resumo.find('div[tomador] strong[social]').html(e.response.SOCIAL);
    });

    function drawDocs(data, type){
        //CRIANDO TEMPLATE DO ROW
        let row = '<div class="row mb-3">{{&content}}</div>';
        row = Hogan.compile(row);

        //CRIANDO TEMPLATE DO COL
        let col = '<div class="col col-{{size}}">';
        col += '<label class="mb-0 w-100">{{title}}</label>';
        col += '<strong>{{var_name}}</strong>';
        col += '</div>';
        col = Hogan.compile(col);

        //COPIANDO OBJETO E TRANSFORMANDO EM ARRAY
        data = Object.assign({}, data);
        data = $.toObject(data);

        //CALCULANDO O TOTAL
        let Total = 0, Peso = 0;
        $.each(data, function(i,v){
            Total += $.format(v.TOTAL, 'float');
            Peso  += $.format(v.PESO, 'float');
        });

        //APAGANDO ITENS
        $('#resumo_dados div[documentos] #itens').html('');
        let qtd = col.render({'size': '2_5 text-right', 'title': 'Qtd Notas', 'var_name': Object.keys(data).length});
        let tot = col.render({'size': ' text-right', 'title': 'Valor Total', 'var_name': $.format(Total, 'money')});
        let pes = col.render({'size': ' text-right', 'title': 'Peso Total', 'var_name': $.format(Peso, 'money')});
        let row_val = row.render({'content': qtd + tot + pes});
        $('#resumo_dados div[documentos] #itens').html(row_val);

        /* MANEIRA ANTERIOR
        comentado pois agora exibe só (qtd notas, valor total mercadoria, peso total)
        if(type == 'doc'){
            let col_numero = col.render({'size': 2, 'title': 'Número', 'var_name': '{{NUMERO}}'});
            col_numero = Hogan.compile(col_numero);

            let col_descr = col.render({'size': 4, 'title': 'Descrição', 'var_name': '{{DESCRICAO}}'});
            col_descr = Hogan.compile(col_descr);

            let col_valor = col.render({'size': 2, 'title': 'Valor', 'var_name': '{{VALOR}}'});
            col_valor = Hogan.compile(col_valor);

            $.each(data, function(i, v){
                let content = '';
                content += col_numero.render(v);
                content += col_descr.render(v);
                content += col_valor.render(v);

                let row_val = row.render({'content': content});
                $('#resumo_dados div[documentos] #itens').append(row_val);
            });
        } else {
            let col_chave = col.render({'size': 5, 'title': 'Chave NFe', 'var_name': '{{CHAVE}}'});
            col_chave = Hogan.compile(col_chave);

            let col_peso = col.render({'size': 2, 'title': 'Peso', 'var_name': '{{PESO}}'});
            col_peso = Hogan.compile(col_peso);

            let col_valor = col.render({'size': 2, 'title': 'Valor', 'var_name': '{{TOTAL}}'});
            col_valor = Hogan.compile(col_valor);

            $.each(data, function(i, v){
                let content = '';

                v.CHAVE = String(v.CHAVE).replace(/[^0-9]/g, '');
                content += col_chave.render(v);
                content += col_peso.render(v);
                content += col_valor.render(v);

                let row_val = row.render({'content': content});
                $('#resumo_dados div[documentos] #itens').append(row_val);
            });
        }
        */
    }
});
