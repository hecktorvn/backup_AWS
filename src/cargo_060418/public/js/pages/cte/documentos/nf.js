function startNfDocumentos(box, nameObj) {
    box = $(box);
    let form = box.find('form');
    let table = form.find('table');
    let modal = $('#modal_danger_remetente');
    let btn_incluir = form.find('button[incluir]');
    let btn_excluir = form.find('button[excluir]');
    let Ed_ = form.getInput();
    let btn_search = form.find('button[searchNF]');

    Ed_.CHAVE.blur(function(){
        let chave = $(this).val().replace(/[^0-9]/g, '');
        let modelo = chave.substr(20, 2);
        let serie = chave.substr(22, 3);
        let numero = chave.substr(25, 9);
        numero = String(numero).length <= 0 ? numero : parseInt(numero);

        Ed_.MODELO.val(modelo);
        Ed_.SERIE.val(serie);
        Ed_.NUMERO.val(numero);
    });

    form.submit(function(){
        return false;
    });

    $[nameObj] = [];
    btn_incluir.off('click').on('click', function() {
        let valid = form.checkRequired();

        if (!valid) return false;
        else {
            $('#documentos a[role=tab]').addClass('disabled');
            $[nameObj].push(form.serializeArray());
            form.flush();

            let eventInc = jQuery.Event(nameObj + ':incluir', {'response': $[nameObj]});
            box.trigger(eventInc);

            drawTable();
            return false;
        }
    });

    btn_excluir.off('click').on('click', function() {
        let tr = table.find('tr.selected');
        if (tr.length <= 0) return false;

        let index = tr[0].sectionRowIndex;
        let data = $.toObject($[nameObj][index]);
        let alert = App.alert('Deseja realmente exluir a nota <strong>' + data.NUMERO + '</strong>', 'A T E N Ç Ã O', modal, null, [{
            text: 'Sim',
            class: 'btn-success'
        }, 'Não']);

        alert.on('alert:callback', function(e) {
            if (e.button != 0) return false;
            delete $[nameObj].splice(index, 1);

            let eventInc = jQuery.Event(nameObj + ':excluir', {'response': $[nameObj]});
            box.trigger(eventInc);

            drawTable();
        });

        return false;
    });

    btn_search.off('click').on('click', function(){
        box.parents('.card.be-loading').loading(true);
        let chave = Ed_.CHAVE.val().replace(/[^0-9]/g, '');
        curl('http://unicanet.com.br/services/consulta_nfe.php', {'chave': chave}, 'POST', function(e){
            box.parents('.card.be-loading').loading(false);

            Ed_.EMISSAO.val(e.NFE.DT_EMISSAO).blur();
            Ed_.TOTAL.val(e.TOTAIS.ICMS.TOTAL_NF);
            Ed_.BASE_ICMS.val(e.TOTAIS.ICMS.BASE_ICMS);
            Ed_.VALOR_ICMS.val(e.TOTAIS.ICMS.ICMS);
            Ed_.ICMS_SUBSTITUTO.val(e.TOTAIS.ICMS.ICMST);
            Ed_.PESO.val(e.TRANSPORTE.VOLUMES.PESO_BRU);
            Ed_.VOLUMES.val(e.TRANSPORTE.VOLUMES.QTD);
            Ed_.CFOP.val(e.PRODUTOS[0].DADOS.CFOP);
        });
    });

    function drawTable() {
        let table = box.find('table');
        let api = table.dataTable().api();

        //LIMPANDO A TABLE
        api.clear();

        if (Object.keys($[nameObj]).length > 0) {
            //PRINTANDO NA TABLE
            let campos = [
                'NUMERO', 'MODELO', 'SERIE', 'CFOP',
                'EMISSAO', 'TOTAL', 'BASE_ICMS', 'VALOR_ICMS',
                'ICMS_SUBSTITUTO', 'PESO', 'VOLUMES', 'CHAVE'
            ];

            $.each($.toObject($[nameObj]), function(iO, vO) {
                let data = [];

                vO['CHAVE'] = String(vO['CHAVE']).replace(/[^0-9]/g, '');
                $.each(campos, function(iC, vC) {
                    data.push(vO[vC]);
                });

                data.push(0);
                api.row.add(data);
            });
        }
        api.draw(false);
    };
};
