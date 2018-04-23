@section('buttonsDefault')
    <button type="button" disabled cancelar class="btn btn-space btn-danger">
        <i class="icon icon-left mdi mdi-delete mr-2"></i>Cancelar
    </button>

    <button type="button" disabled gravar class="btn btn-space btn-success">
        <i class="icon icon-left mdi mdi-account-add mr-2"></i>Cadastrar
    </button>
@endsection

<div class="row">
    <div class="col-6" id="pesquisa_remetente">
        <label for="static-consulta_remetente" class="mb-0">Digite o <b>Nome</b> ou o <b>CNPJ/CPF</b> para a consulta.</label>
        <input type="text" name="consulta_remetente" class="form-control form-control-sm" id="static-consulta_remetente">
    </div>
</div>

@include('cadastros.cliente', ['not_extend'=>true, 'not_script'=>true])
<script type='text/javascript' src="{{ asset('js/pages/cte/remetente.blade.js') }}"></script>
