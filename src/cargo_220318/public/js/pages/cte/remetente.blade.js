$(function(){
    extend_cliente('#remetente form#cadastro_cliente', '#pesquisa_remetente', 'Remetente');
    let form = $('#pesquisa_remetente');
    let Ed_ = form.getInput();
    
    Ed_.consulta_remetente.AutoComplete('cliente', 'CNPJ_CPF');
});
