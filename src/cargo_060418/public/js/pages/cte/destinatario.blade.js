$(function(){
    extend_cliente('#destinatario form#cadastro_cliente', '#pesquisa_destinatario', 'Destinatário');
    let form = $('#pesquisa_destinatario');
    let Ed_ = form.getInput();

    Ed_.consulta_destinatario.AutoComplete('cliente', 'CNPJ_CPF');
});
