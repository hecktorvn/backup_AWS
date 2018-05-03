$(function(){
    let box = $('div#extrato_carga');
    let box_botoes = box.find('#botoes');
    let Ed_ = box.getInput();
    let table = box.find('table#itens');
    let form = box.find('form#extrato_carga');

    $.extrato_carga = {};
    Ed_.FILIAL.AutoComplete('filiais', 'CODIGO');
    Ed_.CLIENTE.AutoComplete('cliente', 'CNPJ_CPF');
    Ed_.ENTREGA.AutoComplete('cidades', 'CODIGO');
    Ed_.VENDEDOR.AutoComplete('operador', 'CODIGO');
    Ed_.MOTORISTA.AutoComplete('motorista', 'CPF');
    Ed_.FORMAPAGTO.AutoComplete('formapagto', 'CODIGO');
    Ed_.UF.AutoComplete('uf', 'ALFA1');

    Ed_.UF.on('get:selected', function(e){
        Ed_.ENTREGA.OptionComplete('data', {'uf': e.response.ALFA1});
    });

    //COMANDO DOS CHECKBOX DE IMPRIMIR
    box.find('#imprimir_menu').on('click', '.dropdown-item', function(){
        if(!$(this).hasClass('view')) $(this).parents('#imprimir_menu, .btn-group').addClass('show');
        else{
            $(this).parents('#imprimir_menu, .btn-group').removeClass('show');
            $.extrato_carga.print();
        }

        return true;
    });


    //COMANDO DO BOTÃO DE PESQUISA
    box_botoes.find('button#pesquisar').off('click').on('click', function(){
        let data = $.toObject(form.serializeArray());
        console.log(data);
    });

    //FUNÇÃO PARA IMPRIMIR O EXTRATO DE CARGAS
    $.extrato_carga.print = function(){
        let itens = {};
        let ipts = box.find('#imprimir_menu input:checked');

        $.each(ipts, function(){
            if(!$.isEmpty(this.name)) itens[this.name] = this.checked;
        });

        
    };
});
