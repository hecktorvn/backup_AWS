var App = (function() {
    'use strict';

    App.formElements = function() {
        //SETANDO DATE PT-BR
        $.fn.datetimepicker.dates['pt-BR'] = {
            days: ["Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado", "Domingo", "Segunda"],
            daysShort: ["Seg", "Ter", "Qua", "Qui", "Sex", "Sáb", "Dom", "Seg"],
            daysMin: ["Se", "Te", "Qua", "Qui", "Se", "Sá", "Do", "Se"],
            months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
            monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
            meridiem: ['am', 'pm'],
            today: "Hoje",
            clear: "Limpar",
            format: "mm/dd/yyyy",
            titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
            weekStart: 0
        };

        $("[data-mask='date'], [data-type='date']").addClass('datetimepicker');
        $("[data-mask='time'], [data-type='time']").addClass('datetimepicker');
        $("[data-mask='datetime'], [data-type='timestamp']").addClass('datetimepicker');

        //Js Code
        let defaultPicker = {
            language: "pt-BR",
            autoclose: true,
            componentIcon: '.mdi.mdi-calendar',
            navIcons: {
                rightIcon: 'mdi mdi-chevron-right',
                leftIcon: 'mdi mdi-chevron-left'
            },
        };

        let onlyDate = Object.assign({minView:2}, defaultPicker);
        onlyDate.format = 'dd/mm/yyyy';

        let onlyTime = Object.assign({startView:1}, defaultPicker);
        onlyTime.format = 'hh:ii';

        let dateTime = Object.assign({}, defaultPicker);
        dateTime.format = 'dd/mm/yyyy hh:ii';

        $(".datetimepicker[data-mask='date'], .datetimepicker[data-type='date']").datetimepicker(onlyDate);
        $(".datetimepicker[data-mask='time'], .datetimepicker[data-type='time']").datetimepicker(onlyTime);
        $(".datetimepicker[data-mask='datetime'], .datetimepicker[data-type='timestamp']").datetimepicker(dateTime);

        //Select2
        $(".select2").select2({
            width: '100%'
        });

        //Select2 tags
        $(".tags").select2({
            tags: true,
            width: '100%'
        });

    };

    return App;
})(App || {});
