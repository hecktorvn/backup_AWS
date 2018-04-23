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

    App.alert = function(msg, title, element, func){
        element = $(element);
        element.find('#titulo').html(title);
        element.find('#msg').html(msg);
        element.modal();

        element.find('.modal-footer button').off('click').on('click', function(event){
            let dataset = this.dataset;
            if(typeof func == 'function' && typeof dataset.button != 'undefined') func(dataset.button);
            $(this).closest('.modal');
        });
    };

    App.alertForm = function(type, msg, form){
        let titulo = '';
        let icon = '';

        switch(type){
            case 'warning':
                titulo = 'Atenção';
                icon = 'mdi-alert-triangle';
                break;
            case 'danger':
                titulo = 'Erro';
                icon = 'mdi-close-circle-o';
                break;
            case 'info':
                titulo = 'Informação';
                icon = 'mdi-info-outline';
                break;
            case 'success':
                titulo = 'Concluído';
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
