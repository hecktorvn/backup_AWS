$(function(){
    let box = $('#manifesto');
    let form = box.find('form#seguro');
    let table = box.find('table#seguro_table');
    let Ed_ = form.getInput();

    $.manifesto.seguro = [];
    $.manifesto.seguro_tx = {};
    Ed_.RESPONSAVEL.AutoComplete('filiais', 'CNPJ');
    Ed_.SEGURADORA.AutoComplete('seguradora', 'CNPJ');

    Ed_.RESPONSAVEL.on('get:selected', function(e){
        $.manifesto.seguro_tx.RESPONSAVEL = e.response[Ed_.RESPONSAVEL[0].complete.label];
    });

    Ed_.SEGURADORA.on('get:selected', function(e){
        $.manifesto.seguro_tx.SEGURADORA = e.response[Ed_.SEGURADORA[0].complete.label];
    });

    Ed_.TIPO.on('change', function(){
        let opt = {};
        if($(this).val() == 0){
            opt.template = "<p>{{CNPJ}} - {{SOCIAL}}</p>";
            opt.table = 'filiais';
            opt.label = 'SOCIAL';
            opt.key = 'CNPJ';
        } else {
            opt.template = "<p>{{CNPJ_CPF}} - {{FANTASIA}}</p>";
            opt.table = 'cliente';
            opt.label = 'FANTASIA';
            opt.key = 'CNPJ_CPF';
        }

        opt.url = 'auto/' + String(opt.table).toUpperCase() + '/';
        Ed_.RESPONSAVEL.OptionComplete(opt);
        Ed_.RESPONSAVEL.val('');
    });

    //SETANDO A AÇÃO DE INCLUIR
    form.find('button#incluir_seguro').off('click').on('click', function(){
        if(!$.manifesto.action) return false;
        if(!form.checkRequired()) return false;
        let data = $.toObject(form.serializeArray());
        let tipos = {};

        box.find('select[name=TIPO] option').each(function(){
            tipos[this.value] = this.innerHTML;
        });

        data.TIPO_TX = tipos[data.TIPO];
        data.RESPONSAVEL_TX = $.manifesto.seguro_tx.RESPONSAVEL;
        data.SEGURADORA_TX = $.manifesto.seguro_tx.SEGURADORA;

        $.manifesto.seguro.push(data);
        $.manifesto.seguro_table($.manifesto.seguro);
        form.flush();
        return false;
    });

    //SETANDO A AÇÃO DE EXCLUIR
    form.find('#excluir_seguro').off('click').on('click', function(){
        if(!$.manifesto.action) return false;
        table.find('tr.selected').each(function(){
            let idx = this.sectionRowIndex;
            $.manifesto.seguro.splice(idx, 1);
            $.manifesto.seguro_table($.manifesto.seguro);
        });
    });

    $.manifesto.seguro_table = function(data){
        let types = {};
        let campos = [
            'TIPO_TX', 'RESPONSAVEL', 'RESPONSAVEL_TX',
            'SEGURADORA_TX', 'APOLICE', 'AVERBACAO'
        ];

        $.setDataTable(table, data, types, campos);
    };
});
