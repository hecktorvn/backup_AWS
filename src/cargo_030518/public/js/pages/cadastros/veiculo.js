$(function(){
    $('form#cadastro_veiculo').each(function(){
        let Ed_ = $(this).getInput();

        Ed_.MOTORISTA.AutoComplete('motorista', 'CODIGO');
    });
});
