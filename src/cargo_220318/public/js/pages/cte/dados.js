$(function(){
    let form = $('#dados_cte');
    let Ed_ = form.getInput();

    Ed_.CID_ENTREGA.AutoComplete('uf', 'ALFA2');
    Ed_.CID_COLETA.AutoComplete('uf', 'ALFA2');

    Ed_.ENTREGA.AutoComplete('cidades', 'CODIGO');
    Ed_.COLETA.AutoComplete('cidades', 'CODIGO');

    form.find('input#static_entrega, input#static_coleta').on('get:selected', function(data){
        let ipt = Ed_['CID_' + this.id.replace(/static_/g,'').toUpperCase()];
        if(ipt.length > 0) ipt.val(data.response.ESTADO);
    });
});
