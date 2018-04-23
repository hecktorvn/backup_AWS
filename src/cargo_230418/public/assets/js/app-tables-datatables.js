var App = (function () {
  'use strict';

  App.dataTables = function( ){
        let options = window.__table['options'];
        //SETANDO OS DADOS DEFAULT DO dataTable
        $.extend(true, $.fn.dataTable.defaults, {
            processing: true,
            serverSide: true,
            scrollX: true,
            pageLength: 50,
            dom:
                "<'row be-datatable-header'<f><'col-sm'B><'col-sm text-right'l>>" +
                "<'row be-datatable-body'<'col-sm-12'tr>>" +
                "<'row be-datatable-footer'<'col-sm-5'i><'col-sm-7'p>>",
            language: {
                "paginate": {
                    "first":      "Primeiro",
                    "last":       "Último",
                    "next":       "Próximo",
                    "previous":   "Anterior"
                },
                "aria": {
                    "sortAscending": ": click para ordenar de forma ascendente",
                    "sortDescending": ": click para ordenar de forma descendente"
                },
                "decimal": ",",
                "thousands": ".",
                "search": "Pesquisar:",
                "loadingRecords": "Carregando...",
                "processing": "Processando...",
                "emptyTable": "Nenhum registro para está pesquisa",
                "lengthMenu": "Exibir _MENU_ registros por página",
                "zeroRecords": "Nenhum registro para essa tabela",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "Nenhum registro encontrado",
                "infoFiltered": "",
            }
        });

        //CRIANDO O BOTÃO DE Cadastrar
        let buttons = !$.isEmpty(options) ? window.__table['options'].buttons : {};
        let nameButton = '<span class="mdi mdi-account-add"></span>  Cadastrar ' + String(window.__table['name']).capitalize();
        let linkButton = 'cadastro/' + window.__table['name'];
        let dataEdit   = [window.__table['pk']];

        if(!$.isEmpty(buttons) && !$.isEmpty(options)){
            if(!$.isEmpty(buttons.new)) nameButton = buttons.new;
            if(!$.isEmpty(options.link)) linkButton = options.link;
        }

        $.fn.dataTable.ext.buttons.cadastrar = {
            text: nameButton,
            className: 'btn-primary',
            action: function ( e, dt, node, config ) {
                window.location.href = linkButton;
            }
        };

        nameButton = '<span class="mdi mdi-edit"></span> Editar';
        if(!$.isEmpty(buttons) && !$.isEmpty(options)){
            if(!$.isEmpty(buttons.edit)) nameButton = buttons.edit;
            if(!$.isEmpty(options.linkEdit)) linkButton = options.linkEdit;
            if(!$.isEmpty(options.dataEdit)) dataEdit = options.dataEdit;
        }

        //CRIANDO O BOTÃO DE ALTERAR
        $.fn.dataTable.ext.buttons.editar = {
            text: nameButton,
            className: 'editar',
            action: function ( e, dt, node, config ) {
                let tr = $(dt.body()).find('tr.selected');

                if(tr.length <= 0) return false;
                let data = $('table[datatablesList]')[0].dataTable[ tr[0].sectionRowIndex ];

                let urlData = '';
                $.each(dataEdit, function(i, v){
                    if(!$.isEmpty(urlData)) urlData += '/';
                    urlData += data[v];
                });

                window.location.href = linkButton + urlData;
            }
        };

        //CAMPOS E DADOS DEFAULT DO DATATABLE
        var campos = window.__table['campos'];
        let data = {};

        //SETANDO CONFIGURAÇÕES DO AJAX
        data.ajax = {
           url : window.__table['url'],
           method: 'POST',
        };

        data.buttons = [
            'cadastrar',
            'editar',
            /*{
                extend: 'copy',
                text: '<span class="mdi mdi-copy"></span> Copiar'
            },*/
            {
                extend: 'excel',
                text: '<span class="mdi mdi-grid"></span> Excel'
            },
            {
                extend: 'pdf',
                text: '<span class="mdi mdi-collection-pdf"></span> PDF'
            },
            {
                extend: 'print',
                text: '<span class="mdi mdi-print"></span> Imprimir'
            }
        ];

        data.ajax.dataSrc = function( json ) {
             var retorno = [];
             for (var i=0, ien=json.data.length ; i<ien ; i++ ) {
                 retorno[i] = [];
                 for(let x=0; x<campos.length; x++) retorno[i][x] = json.data[i][campos[x]];
             }

             $('table[datatablesList]')[0].dataTable = json.data;
             return retorno;
         };

        data.ajax.error = function(dt){
            console.log(dt);
        };

        //INICIANDO O DATATABLE
        if($('table[datatablesList]').length > 0){
            $('table[datatablesList]').each(function(){
                let myData = Object.assign({}, data);

                //ORDENANDO
                $(this).find('thead th[order]').each(function(){
                    let indexOrder = this.cellIndex;
                    myData.order = [[ indexOrder, 'asc' ]];
                });

                //INICIANDO DATATABLE
                let table = $(this).DataTable(myData);

                //SETANDO CLICK
                $(this).find('tbody').on('click', 'tr', function(){
                    if( $(this).hasClass('selected') ){
                        $(this).removeClass('selected');
                    } else {
                        table.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }

                    $('table[datatablesList]')[0].validEdit();
                });

                $(this)[0].validEdit = function(){
                    if(table.row('tr.selected').length <= 0) $(table.button('.editar')[0].node).css({'opacity': 0.5, 'cursor':'default'});
                    else $(table.button('.editar')[0].node).css({'opacity': 1, 'cursor':'pointer'});
                };

                $(this)[0].validEdit();
                $(this).on('draw.dt', function (){
                    this.validEdit();
                });
            });
        }
  };

  return App;
})(App || {});
