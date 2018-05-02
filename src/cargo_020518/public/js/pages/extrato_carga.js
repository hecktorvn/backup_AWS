$(function(){
    let box = $('#extrato_carga');
    let box_botoes = box.find('#botoes');
    let Ed_ = box.getInput();
    let table = box.find('table#itens');


    Ed_.FILIAL.AutoComplete('filiais', 'CODIGO');
    Ed_.CLIENTE.AutoComplete('clientes', 'CNPJ_CPF');
    Ed_.ENTREGA.AutoComplete('cidades', 'CODIGO');
    Ed_.VENDEDOR.AutoComplete('operador', 'CODIGO');
    Ed_.MOTORISTA.AutoComplete('motorista', 'CPF');
    Ed_.FORMAPAGTO.AutoComplete('formapagto', 'CODIGO');
    Ed_.UF.AutoComplete('uf', 'ALFA1');

    Ed_.UF.on('get:selected', function(e){
        Ed_.ENTREGA.OptionComplete('data', {'uf': e.response.ALFA1});
    });

    box.find('#imprimir_menu').on('click', '.dropdown-item', function(){
        if(!$(this).hasClass('view')) $(this).parents('#imprimir_menu').addClass('show');
        else $(this).parents('#imprimir_menu').removeClass('show');
        return true;
    });
})
