$(function(){
    let table = $('form#dados_seguradora');
    let Ed_ = table.getInput();

    Ed_.SEGURADORA.AutoComplete('seguradoras', 'COD1');
});
