$(function(){
    let box = $('#documentos #outros_docs');
    let form = box.find('form');
    let table = form.find('table');
    let modal = $('#modal_danger_remetente');
    let btn_incluir = form.find('button[incluir]');
    let btn_excluir = form.find('button[excluir]');
    let Ed_ = form.getInput();

    $.outros_documentos = [];
    $.outros_documentos_methods = {};
    let methods = $.outros_documentos_methods;

    btn_incluir.on('click', function(){
        if(!$.dtObject.action) return false;
        let valid = form.checkRequired();

        if(!valid) return false;
        else{
            $('#documentos a[role=tab]').addClass('disabled');
            $.outros_documentos.push( $.toObject(form.serializeArray()) );
            form.flush();

            methods.startEvent('outros:incluir', {'response': $.outros_documentos});
            drawTable();
            
            return false;
        }
    });

    btn_excluir.on('click', function(){
        let tr = table.find('tr.selected');
        if(tr.length <= 0 || !$.dtObject.action) return false;

        let index = tr[0].sectionRowIndex;
        let data = $.toObject($.outros_documentos[ index ]);
        let alert = App.alert('Deseja realmente exluir o Documento <strong>' + data.NUMERO + '</strong>', 'A T E N Ç Ã O', modal, null, [{text:'Sim', class:'btn-success'}, 'Não']);

        alert.on('alert:callback', function(e){
            if(e.button != 0) return false;
            delete $.outros_documentos.splice(index, 1);

            ethods.startEvent('outros:excluir', {'response': $.outros_documentos});
            drawTable();
        });

        return false;
    });

    let drawTable = function(setTypes){
        let table = box.find('table#itens_doc');
        let api = table.dataTable().api();

        //LIMPANDO A TABLE
        api.clear();

        if(Object.keys($.outros_documentos).length > 0){
            //CAPTURANDO OS TIPOS
            let tipos = [];
            Ed_.TIPO.find('option').each(function(){
                tipos[ this.value ] = this.text;
            });

            //PRINTANDO NA TABLE
            let campos = ['TIPO_STR', 'NUMERO', 'EMISSAO', 'VALOR', 'DESCRICAO', 'TIPO'];
            let types = box.getTypes();

            $.each($.outros_documentos, function(iO, vO){
                let data = [];

                $.each(campos, function(iC, vC){
                    if(setTypes) vO[vC] = $.format(vO[vC], types[vC]);
                    if(vC != 'TIPO_STR') data.push( vO[vC] );
                    else data.push( tipos[vO['TIPO']] );
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
