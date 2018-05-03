$(function(){
    $('form#cadastro_cliente').each(function(){
        let Ed_ = $(this).getInput();

        Ed_.FILIAL.AutoComplete('filiais', 'CODIGO');
    });
});
