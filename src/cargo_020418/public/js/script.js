(function( $ ) {
    //SETANDO OS DADOS DEFAULT DO dataTable
    $.extend(true, $.fn.dataTable.defaults, {
        language: {
            "paginate": {
                "first":      "Primeiro",
                "last":       "Último",
                "next":       "Próximo",
                "previous":   "Anterior"
            },
            "aria": {
                "sortAscending": ": click para ordenar de forma ascendente",
                "sortDescending": ": click para ordenar de forma descendente"
            },
            "decimal": ",",
            "thousands": ".",
            "search": "Pesquisar:",
            "loadingRecords": "Carregando...",
            "processing": "Processando...",
            "emptyTable": "Nenhum registro para está pesquisa",
            "lengthMenu": "Exibir _MENU_ registros por página",
            "zeroRecords": "Nenhum registro para essa tabela",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "Nenhum registro encontrado",
            "infoFiltered": "",
        }
    });

    //ALTERANDO O EVENTO DE VAL
    $.fn.valChange = function(v){
        $(this).val(v).trigger('change');
    };

    //IFMASK verifica se o mask é diferente e altera o mask
    $.fn.ifMask = function(mask, nMask){
        this.each(function(){
            this.myMask = mask;
            this.addEventListener('keypress', function(e){
                let val = $(this).val().replace(/[^0-9]/g, '');
                let nMaskLen = String(nMask).replace(/[^0-9]/g, '').length;

                if(String(val).length+1 > nMaskLen){
                    if(this.myMask !== mask){
                        $(this).mask(mask);
                        $(this).val( String(val).replace(/[^0-9]/g, '') );
                    }
                    this.myMask = mask;
                } else if(this.myMask !== nMask) {
                    $(this).mask(nMask);
                    $(this).val( String(val).replace(/[^0-9]/g, '') );
                    this.myMask = nMask;
                }
            });
        });
    };

    //FOCA NO OBJETO DE ACORDO COM O TEMPO INFORMADO
    //UTILIZANDO O setTimeout
    $.fn.focusTime = function(time){
        let element = this;
        setTimeout(function(){
            element.focus();
        }, time);
    };

    //CASO (TRUE) BLOQUEIA TODOS OS CAMPOS QUE NÃO TIVEREM O
    //ATRIBUTO NOTBLOCK, E CASO SEJA (FALSE) DESBLOQUEIA TODOS
    //OS CAMPOS QUE NÃO TENHAM O ATRIBUTO NOTDESBLOCK
    $.fn.block = function(block, afterBlock, more){
        if(block === true){
            $(this).find('input:not([notbock]), select:not([notbock]), textarea:not([notbock])').attr('disabled', true);
            if(typeof more != 'undefined') $(this).find(more).attr('disabled', true);
        } else {
            $(this).find('input:not([notdesbock]), select:not([notdesbock]), textarea:not([notdesbock])').attr('disabled', false);
            if(typeof more != 'undefined') $(this).find(more).attr('disabled', false);
        }

        if(typeof afterBlock == 'function') setTimeout(function(){ afterBlock(); }, 50);
    };

    //LIMPA TODOS OS INPUTS, SELECT, TEXTAREA QUE NÃO TIVER
    //O ATRIBUTO NOTFLUSH
    $.fn.flush = function(){
        $(this).find('input:not([type=radio]):not([type=checkbox]), select, textarea').val('');
        $(this).find('input[type=radio], input[type=checkbox]').removeAttr('checked');
    };

    //CAPTURA TODOS OS INPUTS DO ELEMENTO INFORMADO
    //E RETORNA ELES EM UM OBJETO COM SEUS RESPECTIVOS NOMES
    $.fn.getInput = function(){
        let form = $(this);
        let data = form.find('input[name], select[name], textarea[name]');
        let rt = {};

        data.each(function(i, v){
            let name = this.name;
            let eu = $(this);
            if(typeof rt[name] == 'object'){
                let newVal = Object.assign([], rt[name]);
                newVal.push(eu);
                rt[name] = newVal;
            } else {
                rt[name] = eu;
            }
        });

        return rt;
    };

    //SETA OS CLICKS NOS BOTÕES PADRÕES DO SISTEMA
    //TAMBEM SETA A VALIDAÇÃO DE FORMULÁRIO
    $.fn.startButton = function(){
        let form = this;
        let btn_novo = $(form).find('button[novo]');
        let btn_desblock = $(form).find('button[desblock]');
        let btn_cancelar = $(form).find('button[cancelar]');
        let btn_gravar = $(form).find('button[gravar]');

        $(form).find('button').each(function(){
            this.addEventListener('click', function(){
                return false; //desativando o submit ao clicar no botao
            });
        });

        btn_desblock.on('click', function(){
            $(form).find('input, select, textarea').removeAttr('disabled');
            $(form).find('input:not([disabled]):not([readonly])')[0].focus();

            this.disabled = true;
            if(btn_cancelar.length >= 1) btn_cancelar[0].disabled = false;
            if(btn_gravar.length >= 1) btn_gravar[0].disabled = false;
        });

        btn_cancelar.on('click', function(){
            $(form).find('input, select, textarea').attr('disabled', true);
            form[0].reset();

            this.disabled = true;
            if(btn_gravar.length >= 1) btn_gravar[0].disabled = true;
            if(btn_desblock.length >= 1) btn_desblock[0].disabled = false;
        });

        btn_gravar.on('click', function(){
            $(form).submit();
        });

        $(form).submit(function(){
            let valid = this.checkValidity();
            if(!valid) $(this).checkRequired();
            else{
                let form = this;
                $.each(this.dataTypes, function(i, v){
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'dataType[' + i + ']';
                    input.value = v;

                    form[0].appendChild(input);
                });
            }

            return valid;
        });

        return this;
    };

    //INICIANDO AS FUNÇÕES E COMANDOS DO FORMULARIO
    $.fn.startForm = function(){
        this.startButton();
        this.startTypes();

        this.block(true);
    };


    $.fn.startTypes = function(){
        let data = {};
        let inputs = this.find('input, select, textarea');

        inputs.each(function(){
            let dataset = this.dataset;

            if(typeof dataset.type == 'undefined' || dataset.type == '') return; //NÃO COLOCAR FALSE NO RETURN POIS É A MESMA COISA DE continue
            else{
                data[this.name] = dataset.type;
                delete this.dataset.type;
            }
        });

        //ADICIONANDO VARIAVEL DATATYPES EM FORM
        this[0].dataTypes = data;
    };

    //CAPTURANDO OS CAMPOS REQUIREDS NÃO PREENCHIDOS
    $.fn.checkRequired = function(){
        let empty = [];
        let campos = $(this).find('input:invalid, select:invalid, textarea:invalid');
        let form = $(this);

        //LISTANDO OS CAMPOS VAZIOS
        campos.each(function(){
            if($(this).val() == '') empty.push(this);
        });

        $.each(empty, function(i, v){
            if(i === 0) this.focus();
            let name = form.find('label[for*="' + String(this.name).toLowerCase() + '"]');
            if(name.length > 0) name = name[0].textContent;

            App.notification('<b>' + name + '</b> é campo obrigatório, preencha-o.', 'Dados Insuficientes', 'danger');
        });

        if(Object.keys(empty).length > 0) return false;
        else return true;
    };

    let btnCEP = $('button[getcep]').on('click', getCEP);
    function getCEP(){
        let content = $(this).parents('.main-content');
        let body = $(this).parents('.card-body');
        let input = {};

        input['cep'] = body.find('input[CEP]');
        input['endereco'] = body.find('input[ENDERECO]');
        input['lograd'] = body.find('input[LOGRAD]');
        input['cidade'] = body.find('input[CIDADE]');
        input['bairro'] = body.find('input[BAIRRO]');
        input['uf'] = body.find('input[UF]');

        let cep = input['cep'].val().replace(/[^0-9]/g, '');

        $.each(input, function(i, v){
            if(i != 'cep') input[i].val('');
        });

        content.loading(true);
        curl('http://unicanet.com.br/services/cep.php', {'cep':cep, 'formato':'json'}, 'GET', function(data){
            content.loading(false);
            if(data.resultado != '1'){
                App.notification('Nenhum resultado para este <b>CEP</b>!', 'Consulta de CEP', 'danger');
                input['cep'].focus();
            } else {
                input['endereco'].val(data.logradouro);
                input['lograd'].val( String(data.tipo_logradouro).toUpperCase() );
                input['bairro'].val(data.bairro);
                input['cidade'].val(data.cidade);
                input['uf'].val(data.uf);
            }
        }, function(){
            content.loading(false);
            App.notification('Erro ao tentar capturar CEP!', 'ATENÇÃO');
        }, {'dataType': 'json'});
    }

    //DANDO LOAD NO CONTEUDO
    $('[data-load]').each(function(i,v){
        var data = this.dataset;
        //$(this).load(data.load);
    });

    //SETANDO O TOKEN EM TODOS OS Ajax
    //DENTRO DO ajaxComplete POIS CASO PRECISE FAZER REQUISIÇÃO
    //EM OUTRO LINK ELE REMOVE O CSRF PARA PODER PASSAR
    setCSRF_Token_ajax();
    $(document).ajaxComplete(function(){
        setCSRF_Token_ajax();
    });

    //SETANDO O DATATABLE NOS TABLES
    let dataTableScroll = {scrollCollapse: false, paging: false, searching: false, info: false};
    $('table:not([datatablesList]):visible').each(function(){
        $(this).addClass('table table-striped table-hover table-condensed');

        let options = Object.assign({}, dataTableScroll);
        if(typeof $(this).attr('scrolly') != 'undefined') options.scrollY = $(this).attr('scrolly');
        if(typeof $(this).attr('scrollx') != 'undefined') options.scrollX = $(this).attr('scrollx');

        $(this).dataTable(options);
    });

    $('table:not([datatablesList]):not(visible)').visibilityChanged({'previousVisibility':false});
    $('table:not([datatablesList]):not(visible)').each(function(){
        $(this).addClass('table table-striped table-hover table-condensed');
        $(this).on('visible_change', function(e){
            if(e.visible){
                let options = Object.assign({}, dataTableScroll);
                if(typeof $(this).attr('scrolly') != 'undefined') options.scrollY = $(this).attr('scrolly');
                if(typeof $(this).attr('scrollx') != 'undefined') options.scrollX = $(this).attr('scrollx');

                $(this).dataTable(options);
                $(this).off('visible_change');
            }
        });
    });

    $('table[click]:not([datatablesList])').on('click', 'tr', function(){
        if( $(this).hasClass('selected') ){
            $(this).removeClass('selected');
        } else {
            $(this).find('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });

    //SETANDO O VALOR DO SELECT CASO SEJA VAZIOS
    $('select[value!=""]').each(function(){
        if($(this).val() == '') $(this).val( $(this).attr('value') );
    });

    //CAPTURANDO OS TYPES
    $.fn.getTypes = function(){
        let rt = {};
        $(this).find('[name][data-type]').each(function(){
            rt[this.name] = this.dataset.type;
        });

        return rt;
    };

    //SETANDO O CSRF EM TODOS OS Ajax
    function setCSRF_Token_ajax(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    }

    // FUNÇÃO PARA CONVERTER SERIALIZE EM OBJECT
    $.toObject = function(obj, type){
        let rt = {};
        $.each(obj, function(i,v){
            if(typeof v == 'object' && !Object(v).hasOwnProperty('name')) rt[i] = $.toObject(v);
            else if(typeof v == 'object' && Object(v).hasOwnProperty('name')) rt[v.name] = v.value;
            else rt[i] = v;
        });

        if(type == 'serialize'){
            let nRt = [];
            $.each(rt, function(i, v){
                if(typeof v != 'object') nRt.push({'name':i, 'value':v});
                else{
                    $.each(v, function(iV, vV){
                        let index = i + '[' + iV + ']';
                        if(typeof vV == 'object'){
                            $.each(vV, function(nI, nV){
                                nRt.push({'name': index + '[' + nI + ']', 'value': nV});
                            });
                        } else {
                            nRt.push({'name': index, 'value':vV});
                        }
                    });
                }
            });

            rt = nRt;
        }

        return rt;
    };


    //INICIANDO APP E SEUS COMPONENTES
    App.init();
    App.masks();
    App.uiNotifications();
    App.loaders();
    App.formElements();
}( jQuery ));

//CRIANDO FUNÇÃO PARA CONSULTA
function curl(url, data, method, func, funcError, addOpt){
    method = typeof method == 'undefined' ? 'POST' : method;

    //ARMAZENANDO O LOG DE RETORNOS
    //VAMOS VER SE FUNFA KKK
    $.fn.log_curl =
    typeof $.fn.log_curl != 'object' ?  {'success':[], 'error':[]} : $.fn.log_curl;

    if(parse_url(url).host != window.location.host){
        $.ajaxSettings.headers = {};
    }

    var ajaxOptions = {
        'url': url,
        'data': data,
        'method': method,
        'success': function(data){
            $.fn.log_curl.success.push(data);
            if(typeof func == 'function') func(data);
        },
        'error': function(e){
            $.fn.log_curl.error.push(data);
            if(typeof funcError == 'function') func(e);
            App.notification('Erro ao tentar executar AJAX!', 'ERRO');
        }
    };

    if(typeof addOpt == 'object') ajaxOptions = Object.assign(ajaxOptions, addOpt);
    $.ajax(ajaxOptions);
};

//SEPARANDO AS INFORMÇÕES DE UMA url
function parse_url(url) {
    let key = {'hostname':'host', 'protocol':'', 'pathname':'path', 'search':'', 'hash':''};
    let a = $('<a>', {href: url});
    let rt = {};

    $.each(key, function(i, v){
        if(v != '') rt[v] = a.prop(i);
        else rt[i] = a.prop(i);
    });

    return rt;
}

String.prototype.capitalize = function() {
    return this.replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });
};

//ALTERANDO A FUNÇÃO VAL
(function ($) {
    var originalVal = $.fn.val;
    $.fn.val = function (value) {
        if (arguments.length >= 1) {
            // setter invoked, do processing
            return originalVal.call(this, value).trigger('change');
        }
        //getter invoked do processing
        return originalVal.call(this);
    };
})(jQuery);
