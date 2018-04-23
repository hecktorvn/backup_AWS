var App = (function () {
    'use strict';

    App.uiNotifications = function( ){
        $.fn.notification = function(text, title, type){
            this.off('click').on('click', function(){
                App.notification(text, title, type);
            });
        };

        App.notification = function(text, title, type){
            type = typeof type == 'undefined' ? 'danger' : type;
            let defConfig = {title: '', text: '', class_name: 'color ' + type};

            if(typeof text == 'object') Object.assign(defConfig, text);
            else defConfig.title = title, defConfig.text = text;

            if(typeof defConfig.text == 'undefined') defConfig.text = '';
            if(typeof defConfig.title == 'undefined') defConfig.title = '';
            $.gritter.add(defConfig);
        };
    };

    App.alerta = function(msg, title, buttons, opt){
        return new App.alertClass(msg, title, buttons, opt);
    };

    App.alert = function(msg, title, element, func, buttons){
        let opt = {'element': element, 'func': func};
        return new App.alertClass(msg, title, buttons, opt);
    };

    App.alertClass = function(msg, title, buttons, opt){
        if(typeof opt != 'object' || opt === null) opt = {};
        let element = $('#modal_default')[0].outerHTML;
        let func = null, remove = false;

        //CRIANDO O ELEMENTO DO MODAL
        if(!$.isEmpty(opt.element)){
            element = $(opt.element);
        } else {
            remove = true;
            let parse = new DOMParser();
            element = parse.parseFromString(element, 'text/html').body.firstElementChild;
            element.id += new Date().getTime();

            element = $(element);
            $(document.body).append(element);
        }

        if(!$.isEmpty(opt.func)) func = opt.func;
        if($.isEmpty(title)) title = '';
        if($.isEmpty(msg)) msg = '';

        element.find('#titulo').html(title);
        element.find('#msg').html(msg);
        element.modal();

        if(typeof buttons != 'undefined') this.setNewButtons(element, buttons);
        else this.setButtonsDefault(element);

        //REMOVENDO AS CLASSES DE BORDA E DE EXIBIÇÃO
        element.find('.modal-footer, .modal-header').removeClass('border-bottom border-top');
        element.find('.text-danger').removeClass('hidden');

        //ADICIONANDO A CLASSE DE BORDA
        if(!$.isEmpty(opt.border) && opt.border === true){
            element.find('.modal-header').addClass('border-bottom');
            element.find('.modal-footer').addClass('border-top');
        }

        //ESCONDENDO O ICONE
        if(!$.isEmpty(opt.icon) && opt.icon === false){
            element.find('.text-danger').addClass('hidden');
        }

        //EXIBINDO O TITULO
        element.find('.modal-header .title_modal').remove();
        if(!$.isEmpty(opt.title) && opt.title === true){
            element.find('.modal-header').prepend('<h4 class="title_modal">' + title + '</h4>');
        }

        element.find('.modal-footer button').off('click').on('click', function(event){
            let dataset = this.dataset;
            let eventAlert = jQuery.Event('alert:callback', {'button': dataset.button});

            element.trigger(eventAlert);
            element.off('alert:callback');
            if(remove) element.parents('.modal-backdrop').remove();

            $(this).closest('.modal');
        });

        if(typeof func == 'function') func(element, this);
        return element;
    };

    // SETANDO OS BOTÕES
    App.alertClass.prototype.setNewButtons = function(element, obj) {
        element = $(element);
        let def = element[0].defaultButtons;
        if(typeof def == 'undefined') element[0].defaultButtons = element.find('.modal-footer')[0].innerHTML;

        element.find('.modal-footer').html('');
        let buttons = this.createButtons(obj);

        $.each(buttons, function(i, v){
            element.find('.modal-footer').append(v);
        });
    };

    // SETANDO OS BOTÕES PADRÕES
    App.alertClass.prototype.setButtonsDefault = function(element) {
        element = $(element);
        let def = element[0].defaultButtons;
        element.find('.modal-footer').html(def);
    };

    // CRIANDO OS ELEMENTOS DE BOTÃO
    App.alertClass.prototype.createButtons = function(obj){
        let buttons = [];
        $.each(obj, function(i, v){
            let elem = document.createElement('button');
            if(typeof v == 'object' && typeof v.class != 'undefined') $(elem).addClass('btn btn-space ' + v.class);
            else $(elem).addClass('btn btn-space btn-secondary');

            elem.dataset.button = i;
            elem.dataset.dismiss = 'modal';
            elem.type = 'button';

            if(typeof v == 'object') elem.innerText = v.text;
            else elem.innerText = v;

            buttons.push(elem);
        });

        return buttons;
    };

    App.alertForm = function(type, msg, form, titulo){
        let icon = '';

        if(typeof titulo == 'undefined') titulo = '';
        switch(type){
            case 'warning':
                titulo = titulo == '' ? 'Atenção' : titulo;
                icon = 'mdi-alert-triangle';
                break;
            case 'danger':
                titulo = titulo == '' ? 'Erro' : titulo;
                icon = 'mdi-close-circle-o';
                break;
            case 'info':
                titulo = titulo == '' ? 'Informação' : titulo;
                icon = 'mdi-info-outline';
                break;
            case 'success':
                titulo = titulo == '' ? 'Concluído' : titulo;
                icon = 'mdi-check';
                break;
            default:
                icon = 'mdi-close-circle-o';
                break;
        }

        let html = '<div role="alert" class="alert alert-{{ type }} alert-icon alert-icon-border alert-dismissible">';
        html += '<div class="icon"><span class="mdi {{ icon }}"></span></div>';
        html += '<div class="message">';
        html += '<button type="button" data-dismiss="alert" aria-label="Close" class="close">';
        html += '<span aria-hidden="true" class="mdi mdi-close"></span>';
        html += '</button>';
        html += '<strong>{{ titulo }}!</strong> {{ msg }}';
        html += '</div>';
        html += '</div>';

        let data = {'type':type, 'icon':icon, 'titulo':titulo, 'msg':msg};
        html = App.setValStr(html, data);

        if(typeof form == 'undefined'){
            return html;
        } else {
            $(form).find('[role=alert]:not([timing])').each(function(i,v){
                $(this).attr('timing', true);

                let role = $(this);
                setTimeout(function(){ role.fadeOut('slow'); }, 500 * (i+1));
            });

            $(form).prepend(html);
        }
    };

    App.setValStr = function(str, arr){
        $.each(arr, function(i, v){
            let regex = new RegExp('{{' + i + '}}|{{ ' + i + ' }}', 'g');
            str = str.replace(regex, v);
        });

        return str;
    };

return App;
})(App || {});
