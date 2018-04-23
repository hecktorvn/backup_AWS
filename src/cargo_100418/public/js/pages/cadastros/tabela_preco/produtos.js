$(function(){
    $.tabela_preco.produtos = [];
    let box = $('div#cadastro_tabela_preco');
    let form = box.find('form#produtos_form');
    let table = form.find('table');
    let btn_inserir = form.find('button[incluir]');
    let btn_excluir = form.find('button[excluir]');
    let data = $.tabela_preco.produtos;

    let Ed_ = form.getInput();
    Ed_.PRODUTO.AutoComplete('produtos', 'CODIGO');
    Ed_.PRODUTO.on('get:selected', function(e){
        $.tabela_preco.TX_PRODUTO = e.response.DESCRICAO;
    });

    form.find('[data-type=numeric]').maskMoney('option', 'allowZero', true);
    let rt = new actionForm(form, {'add': btn_inserir, 'excluir': btn_excluir}, data);

    form.on(form[0].id+':insert', function(e){
        e.response.DESCRICAO = $.tabela_preco.TX_PRODUTO;
        $.tabela_preco.TX_PRODUTO = '';

        $(this).flush();
        drawTable();
    });

    form.on(form[0].id+':delete', function(e){
        let selected = table.find('tbody tr.selected');
        if(selected.length <= 0) return false;

        data.splice(selected[0].sectionRowIndex, 1);
        drawTable();
    });

    let drawTable = function(){
        let data = $.tabela_preco.produtos;
        let api = table.dataTable().api();

        //LIMPANDO A TABLE
        api.clear();

        if(Object.keys(data).length > 0){

            //CAPTURANDO OS TIPOS
            let tipos = {};
            form.find('select option').each(function(){
                tipos[this.value] = this.outerText;
            });

            //PRINTANDO NA TABLE
            let campos = ['PRODUTO', 'DESCRICAO', 'VALOR1_PERCNOTA', 'VALOR2_SEC_CAT', 'ITEM'];
            $.each($.toObject(data), function(iO, vO){
                let data = [];

                $.each(campos, function(iC, vC){
                    if(typeof vO[vC] == 'undefined') data.push(' ');
                    else if(vC != 'ITEM') data.push( vO[vC] );
                    else{
                        let tipo = $.isEmpty( vO[vC] ) ? 0 : vO[vC];
                        data.push( tipos[ tipo ] );
                    }
                });

                api.row.add(data);
            });
        }

        api.draw(false);
    };

    $.tabela_preco.events.drawTable_produtos = drawTable;
});
