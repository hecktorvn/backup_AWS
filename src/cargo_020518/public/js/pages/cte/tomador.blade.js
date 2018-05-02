$(function(){
    extend_cliente('#tomador form#cadastro_cliente', '#pesquisa_tomador', 'Tomador');
    let form = $('#pesquisa_tomador');
    let Ed_ = form.getInput();
    
    Ed_.consulta_tomador.AutoComplete('cliente', 'CNPJ_CPF');
});
