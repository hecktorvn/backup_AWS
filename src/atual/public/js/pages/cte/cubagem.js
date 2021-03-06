$(function(){
    let box = $('#valores #cubagem');
    let form = box.find('form#cubagem_valores');
    let modal = $('#modal_danger_remetente');
    let btn_incluir = box.find('button[incluirCub]');
    let btn_excluir = box.find('button[excluirCub]');
    let table = box.find('table#itens_cubagem');

    let Ed_ = form.getInput();

    $.cubagem = [];
    $.cubagem_methods = {};
    let methods = $.cubagem_methods;

    btn_incluir.on('click', function(){
        if(!form.checkRequired() || !$.dtObject.action) return false;

        let data = form.serializeArray();
        data = $.toObject(data);

        //TRANSFORMANDO VALOR EM FLOAT
        //PARA CALCULAR O TOTAL
        let qtd = $.format(data.QUANT, 'float');
        let alt = $.format(data.ALTURA, 'float');
        let lar = $.format(data.LARGURA, 'float');
        let com = $.format(data.COMPRIMENTO, 'float');

        //CALCULANDO O TOTAL DA CUBAGEM
        data.TOTAL = (alt * lar * com) * qtd;
        data.TOTAL = $.format(data.TOTAL, 'money');
        $.cubagem.push(data);

        let eventInc = jQuery.Event('cubagem:incluir', {'response': $.cubagem});
        box.trigger(eventInc);

        form.flush();
        drawTable();

        return false;
    });

    btn_excluir.on('click', function(){
        let tr = table.find('tr.selected');
        if(tr.length <= 0 || !$.dtObject.action) return false;

        let index = tr[0].sectionRowIndex;
        let data = $.toObject($.cubagem[ index ]);
        let alert = App.alert('Deseja realmente exluir a Cubagem selecionada?', 'A T E N Ç Ã O', modal, null, [{text:'Sim', class:'btn-success'}, 'Não']);

        alert.on('alert:callback', function(e){
            if(e.button != 0) return false;
            delete $.cubagem.splice(index, 1);

            let eventExcluir = jQuery.Event('cubagem:excluir', {'response': $.cubagem});
            box.trigger(eventExcluir);
            drawTable();
        });

        return false;
    });

    let drawTable = function(setTypes){
        let api = table.dataTable().api();
        let types = form.getTypes();

        //LIMPANDO A TABLE
        api.clear();
        if(Object.keys($.cubagem).length > 0){
            //PRINTANDO NA TABLE
            let campos = ['SEQUENCIA', 'QUANT', 'ALTURA', 'LARGURA', 'COMPRIMENTO', 'TOTAL'];
            $.each($.toObject($.cubagem), function(iO, vO){
                let data = [];

                $.each(campos, function(iC, vC){
                    if(setTypes) vO[vC] = $.format(vO[vC], types[vC]);
                    else if(iC == 'TOTAL') vO[vC] = $.format(vO[vC], 'money');

                    if(vC != 'SEQUENCIA') data.push( vO[vC] );
                    else data.push( parseInt(iO)+1 );
                });

                api.row.add(data);
            });
        }

        api.draw(false);
    };

    methods.drawTable = drawTable;
    methods.startEvent = function(name, value){
        let eventInc = jQuery.Event(name, value);
        box.trigger(eventInc);
    };
});
