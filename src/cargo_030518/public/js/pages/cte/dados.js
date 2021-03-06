$(function(){
    let form = $('#dados_cte');
    let Ed_ = form.getInput();

    $.dados = {};
    /*Ed_.CID_ENTREGA.AutoComplete('uf', 'ALFA2');
    Ed_.CID_COLETA.AutoComplete('uf', 'ALFA2');*/

    Ed_.ENTREGA.AutoComplete('cidades', 'CODIGO');
    Ed_.COLETA.AutoComplete('cidades', 'CODIGO');
    Ed_.NATUREZA.AutoComplete('cfop', 'DESCR');
    Ed_.CFOP.AutoComplete('cfop', 'COD1', {'label':'COD1'});
    Ed_.MOTORISTA.AutoComplete('motorista', 'NOME');
    Ed_.VENDEDOR.AutoComplete('cliente', 'CNPJ_CPF');
    Ed_.SITUACAO_TRIBUTARIA.AutoComplete('cst', 'COD1');

    Ed_.ENTREGA.on('get:selected', function(e){
        $.dados.ENTREGA = e.response;
    });

    Ed_.COLETA.on('get:selected', function(e){
        $.dados.COLETA = e.response;
    });

    form.find('input#static_natureza').on('get:selected', function(data){
        let Ed_ = form.getInput();
        Ed_.CFOP.val(data.response.COD1);
        form.find('#static_cfop').val(data.response.COD1);
    });

    form.find('input#static_cfop').on('get:selected', function(data){
        let Ed_ = form.getInput();
        Ed_.NATUREZA.val(data.response.DESCR);
        form.find('#static_natureza').val(data.response.DESCR);
    });

    form.find('input#static_entrega, input#static_coleta').on('get:selected', function(data){
        let name = this.id.replace(/static_/g,'').toUpperCase();
        let ipt = Ed_['CID_' + name];
        if(ipt.length > 0){
            ipt.val(data.response.CODIGO);
            form.find('input#static_cid_' + String(name).toLowerCase()).val(data.response.ESTADO);
        }
    });
});
