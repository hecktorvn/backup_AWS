$(function(){
    //TEMPLATES DEFAULT PARA O AutoComplete
    var templates = {
        'cliente': {'label': 'SOCIAL', 'template': '<p>{{CNPJ_CPF}} - {{SOCIAL}}</p>'},
        'filiais': {'label': 'SOCIAL', 'template': '<p>{{CODIGO}} - {{SOCIAL}}</p>', 'minLength': 1},
        'motorista': {'label': 'NOME', 'template': '<p>{{CPF}} - {{NOME}}</p>', 'minLength': 1},
        'cidades': {'label': 'DESCRICAO', 'template': '<p>{{DESCRICAO}} - {{ESTADO}}</p>'},
        'uf': {'label': 'ALFA1', 'template': '<p>{{ALFA1}}</p>', 'minLength': 1},
        'seguradoras': {'label': 'DESCR', 'template': '<p>{{COD1}} - {{DESCR}}</p>', 'minLength': 1},
        'cfop': {'label': 'DESCR', 'template': '<p>{{COD1}} - {{DESCR}}</p>', 'minLength':2},
    };

    //INICIANDO O AUTOCOMPLETE NO jQuery
    $.fn.AutoComplete = function(table, key, opt){
        let dataset = this[0].dataset;
        let options = {};

        table = typeof table == 'undefined' ? dataset.autocomplete : table;
        key = typeof key == 'undefined' ? dataset.autocomplete_key : key;

        options.key = key;
        options.table = table;
        options.input = this;

        if(typeof templates[table] != 'undefined'){
            options = Object.assign(options, templates[table]);
        }

        if(typeof opt == 'object'){
            options = Object.assign(options, opt);
        }

        let complete = new AutoComplete(options);
        if(typeof dataset.default != 'undefined'){
            let input = this;
            let hidden = this[0].complete.hidden;
            $(this).setComplete(dataset.default, function(cod, label){
                $(input).attr('value', label);
                $(hidden).attr('value', cod);
            });
        }

        return complete;
    };

    //ALTERA AS OPÇÕES DO AutoComplete
    $.fn.OptionComplete = function(name, option){
        if(typeof this[0].complete == 'undefined') return false;
        if(typeof name == 'object') this[0].complete = Object.assign(this[0].complete, name);
        if(typeof name == 'string') this[0].complete[name] = option;
        $(this).AutoComplete();
    };

    //EXECUTA O AUTOCOMPLETE E CAPTURA O PRIMEIRO ITEM RETORNADO
    $.fn.setComplete = function(valueKey, func){
        $(this).typeahead('val', valueKey);
        $(this).on('typeahead:render', function(e, data){
            if(typeof data != 'object') return false;
            let keyVal = data[this.complete.key];
            let labelVal = data[this.complete.label];

            this.complete.hidden.val(keyVal);
            this.complete.input.typeahead('val', labelVal);
            $(this).off('typeahead:render');

            if(typeof func == 'function') func(keyVal, labelVal);
        });
    };

    //INICIANDO CLASSE E SUAS FUNÇÕES
    class AutoComplete {
        constructor(options) {
            let opt = options;
            if(this.isNull(opt.input[0].complete)){
                this.options = Object.assign({}, options);
                this.options.input[0].complete = this.options;
            } else {
                this.options = opt.input[0].complete;
            }

            this.createHidden();
            let blood = this.createBlood();
            this.start(blood);
        }

        createBlood() {
            let opt = this.options;
            let url = 'auto/' + String(opt.table).toUpperCase() + '/%QUERY';
            if(!this.isNull(opt.url)){
                let data = {
                    'table' : String(opt.table).toUpperCase(),
                    'key' : opt.key
                };

                url = Hogan.compile(opt.url).render(data);
            }

            opt.url = url;
            let blood = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace(opt.key),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    wildcard: '%QUERY',
                    url: opt.url,
                    transform: function(response) {
                        if(typeof opt.transform == 'function') opt.transform(response);
                        return response.response;
                    }
                }
            });

            return blood;
        }

        start(blood) {
            let opt = this.options;
            let data = [];
            let ipt = opt.input;

            //CONTEM AS CONFIGURAÇÕES PADRÕES
            data[0] = {
                hint: this.isNull(opt.hint) ? true : opt.hint,
                highlight: this.isNull(opt.highlight) ? true : opt.highlight,
                minLength: this.isNull(opt.minLength) ? 3 : opt.minLength,
                classNames: {
                    menu: 'col-12 dropdown-menu',
                    dataset: 'p-0 dropdown-item',
                    suggestion: 'p-1 pl-3 pr-3 m-0 suggestion',
                    selectable: 'actived',
                    hint: 'style-hint'
                }
            };

            //SETANDO O LIMITE DE EXIBIÇÃO DE ITENS
            if(!this.isNull(opt.limit)) data[0].limit = opt.limit;

            //SETANDO A MENSAGEM DE VAZIO
            var empty = opt.empty;
            if(this.isNull(opt.empty) || typeof opt.empty !== 'object'){
                empty = [
                  '<div class="empty-message p-1 pl-3 pr-3 m-0">',
                  'Nenhum resultado para esta pesquisa.',
                  '</div>'
                ];
            };

            //GRAVANDO O EMPTY NO INPUT
            opt.empty = empty;

            //SETANDO O LABEL CASO NÃO EXISTA
            if(this.isNull(opt.label)) opt.label = opt.key;

            //CONTEM AS CONFIGURAÇÕES DE consulta
            //E RETORNO DOS DADOS
            data[1] = {
                limit: this.isNull(opt.limit) ? 50 : opt.limit,
                name: opt.key,
                displayKey: opt.key,
                display: function(data){
                    return Hogan.compile('{{' + opt.label + '}}').render(data);
                },
                source: blood.ttAdapter(),
                templates: {
                    'empty': opt.empty.join('\n'),
                    suggestion: function(data){
                        let strTemp = opt.template;

                        if(typeof strTemp == 'undefined'){
                            strTemp = Hogan.compile('<p>{{key}}</p>');
                            strTemp = strTemp.render({'key': '{{' + opt.key + '}}'});
                        }

                        let template = Hogan.compile(strTemp);
                        return template.render(data);
                    }
                }
            };

            //INICIALIZANDO O BLOOD E O TYPEHEAD
            blood.initialize();
            this.options.blood = blood;

            $(ipt).typeahead('destroy');
            $(ipt).typeahead(data[0], data[1]);

            $(ipt).off('typeahead:open');
            $(ipt).on('typeahead:open', function(){
                if($(this).is('[readonly]')) $(ipt).typeahead('close');
            });

            $(ipt).off('typeahead:selected');
            $(ipt).on('typeahead:selected', function(event, data){
                opt.hidden.val(data[opt.key]);
                let e = jQuery.Event('get:selected', {'response':data});
                opt.input.trigger(e);
            });


            $(ipt).off('typeahead:asyncrequest').on('typeahead:asyncrequest', function(xrs, response){
                $(this).parents('.twitter-typeahead').loading(true);
            });

            $(ipt).off('typeahead:asyncreceive').on('typeahead:asyncreceive', function(xrs, response){
                $(this).parents('.twitter-typeahead').loading(false);
            });

            let ttypeahead = $(ipt).parents('.twitter-typeahead');
            ttypeahead.addClass('col p-0 be-loading');
            ttypeahead.appendSVG_load();
        }

        createHidden() {
            let ipt = this.options.input;
            let name = $(ipt)[0].name;
            let hidden = document.createElement('input');

            hidden.name = name;
            hidden.type = 'hidden';

            ipt.removeAttr('name');
            ipt.before(hidden);
            this.options.hidden = ipt.prev('input[name=' + name + '][type=hidden]');
        }

        isNull(val) {
            if(typeof val == 'undefined' || val == null) return true;
            else false;
        }
    };

    //FUNÇÃO QUE CAPTURA OS DADOS PARA O AUTOCOMPLETE
    $('input[data-autocomplete]:not(noLoad)').each(function(){
        $(this).AutoComplete();
    });
});
