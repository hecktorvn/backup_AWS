var App = (function () {
    'use strict';

    App.masks = function( ){
        $("[data-mask='phone']").ifMask('(99) 99999-9999', '(99) 9999-9999');
        $("[data-mask='cnpj_cpf']").ifMask('99.999.999/9999-99', '999.999.999-99');
        $("[data-mask='percent']").ifMask('99', '9');

        $("[data-mask='cep']").mask("99999-999");
        $("[data-mask='date'], [data-type='date']").mask("99/99/9999");
        $("[data-mask='phone-ext']").mask("(999) 999-9999? x99999");
        $("[data-mask='phone-int']").mask("+33 999 999 999");
        $("[data-mask='taxid']").mask("99-9999999");
        $("[data-mask='ssn']").mask("999-99-9999");
        $("[data-mask='product-key']").mask("a*-999-a999");
        $("[data-mask='currency']").mask("$999,999,999.99");
        $("[data-mask='cpf']").mask("999.999.999-99");
        $("[data-mask='cnpj']").mask("99.999.999/9999-99");

        $("[data-type='integer'], [data-mask='integer']").on('keypress', function(e){
            return (String('0123456789-+.').search(e.key) > -1);
        }).on('blur', function(){
            $(this).val($(this).val().replace(/[^0-9+-.]/g, ''));
        });
    };

    return App;
})(App || {});
