@section('buttonsDefault')
    <button type="button" disabled cancelar class="btn btn-space btn-danger">
        <i class="icon icon-left mdi mdi-delete mr-2"></i>Cancelar
    </button>

    <button type="button" disabled gravar class="btn btn-space btn-success">
        <i class="icon icon-left mdi mdi-account-add mr-2"></i>Cadastrar
    </button>
@endsection

<div class="row p-5 pb-0" style="padding-bottom:0 !important;">
    <div class="col-6" id="pesquisa_tomador">
        <label for="static-consulta_tomador" class="mb-0">Digite o <b>Nome</b> ou o <b>CNPJ/CPF</b> para a consulta.</label>
        <input type="text" name="consulta_tomador" class="form-control form-control-sm" id="static-consulta_tomador">
    </div>
</div>

@include('cadastros.cliente', ['not_extend'=>true])
<script type='text/javascript' src="{{ asset('js/pages/cte/tomador.blade.js') }}"></script>
