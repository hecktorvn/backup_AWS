$(function(){
    $.tabela_preco.pacotinho = [];
    let box = $('div#cadastro_tabela_preco');
    let form = box.find('form#pacotinho_form');
    let table = form.find('table');
    let btn_inserir = form.find('button[incluir]');
    let btn_excluir = form.find('button[excluir]');
    let data = $.tabela_preco.pacotinho;

    let Ed_ = form.getInput();
    form.find('[data-type=numeric]').maskMoney('option', 'allowZero', true);
    actionForm(form, {'add': btn_inserir, 'excluir': btn_excluir}, data, true);

    form.on(form[0].id+':insert', function(e){
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
        let data = $.tabela_preco.pacotinho;
        let api = table.dataTable().api();

        //LIMPANDO A TABLE
        api.clear();

        if(Object.keys(data).length > 0){
            //PRINTANDO NA TABLE
            let campos = ['PESO_FRETE', 'VALOR2_SEC_CAT', 'VALOR1_PERCNOTA', 'VALOR10_ADVALOREM_MINIMO', 'VALOR8_GRIS', 'VALOR9_GRIS_MINIMO', 'VALOR4_PEDAGIO', 'DATA', 'SEQUENCIA'];
            $.each($.toObject(data), function(iO, vO){
                let data = [];

                $.each(campos, function(iC, vC){
                    if(typeof vO[vC] == 'undefined' || vO[vC] == null) data.push(' ');
                    else data.push( vO[vC] );
                });

                api.row.add(data);
            });
        }
        api.draw(false);
    };

    $.tabela_preco.events.drawTable_pacotinho = drawTable;
});
