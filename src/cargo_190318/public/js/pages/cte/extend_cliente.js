function extend_cliente(form, form_search, namePage){
    form = $(form);
    form_search = $(form_search);
    let btn_search = form_search.find('button');
    let btn_cadastrar = form.find('button[gravar]');
    let card = form_search.parents('.be-loading');
    let modal = $('#modal_danger_remetente');

    //CAPTURANDO O INPUT DE CONSULTA
    let input_consulta = form_search.getInput();
    input_consulta = input_consulta[ Object.keys(input_consulta)[0] ];

    form.startForm();
    form.flush();

    //SETANDO O CLICK NO BOTÃO DE PESQUISA
    btn_search.on('click', function(){
        let val = input_consulta.val();
        if(val == '') return false;

        card.loading(true);
        form.flush();
        form.block(true, null, 'button');

        form.find('button[gravar], button[cancelar]').attr('disabeld', true);
        curl('/auto/CLIENTE/' + val, {}, 'POST', function(data){
            card.loading(false);
            if(data.status == 'OK' && data.response.length > 0) printData(data.response[0]);
            else App.alert(namePage + ' não cadastrado!', 'Dados da Pesquisa', modal, callbackModal);
        }, function(){
            card.loading(false);
        });
    });

    //RECEBE O CALLBACK DO MODAL ASSIM DECIDINDO
    //QUAL COMANDO DE EXECUTAR
    function callbackModal(btn){
        let input = form.getInput();
        if(btn == 'cadastrar'){
            input.CNPJ_CPF.val(form_search.find('input').val());

            form_search.flush();
            form.block(false, function(){
                form.find('input:invalid')[0].focus();
                form.find('button[gravar], button[cancelar]').removeAttr('disabled');
            });
        } else if(btn == 'ok'){
            input_consulta.focusTime(50);
        }
    };

    //EXIBE OS DADOS RECEBIDOS DO curl
    //AO CONSULTAR
    function printData(data){
        let input = form.getInput();
        $.each(input, function(i,v){
            if(typeof data != 'object') return;

            let name = v[0].name;
            let length = input[i].length;
            let val = typeof data[name] == 'undefined' ? '' : data[name];

            if(length == 1) {
                input[i].val(val);
            } else if(length > 1 && typeof name == 'string') {
                form.find('*[name="' + name + '"][value="' + val + '"]').attr('checked', true);
            }
        });

        form_search.find('input').val('');
    }

    //ADICIONANDO COMANDO PARA AUTOCOMPLETE
    form_search.find('input').on('get:selected', function(e){
        printData(e.response);
        input_consulta.typeahead('val', '');
    });

    //MUDANDO O COMANDO DO FORM PARA ajax
    form.submit(function(){ return false; });
    form[0].action = form[0].action.replace('defreq', 'ajax');

    //SETANDO O curl NO BOTÃO DE Cadastrar
    btn_cadastrar.on('click', function(){
        let data = form.serialize();
        let tap_pane = form_search.parents('.tab-pane');

        card.loading(true);
        curl(form[0].action, data, 'POST', function(data){
            card.loading(false);
            form.find('input[name*=dataSet]').remove();

            if(data.status == 'ERRO') App.alertForm('danger', data.msg, tap_pane);
            else{
                App.alertForm('success', namePage + ' cadastrado com sucesso!', tap_pane);
                input_consulta.val(data.msg);
                btn_search.click();
            }
        });
    });
};
