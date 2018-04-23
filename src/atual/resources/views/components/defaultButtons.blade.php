@section('buttonsDefault')
    @parent
    <button type="button" onclick="window.location = '{{ url()->previous() }}';" class="btn btn-space btn-default"><i class="icon icon-left mdi mdi-undo mr-2"></i>Voltar</button>

    @if(!is_null($variable))
        <button type="button" desblock class="btn btn-space btn-dark"><i class="icon icon-left mdi mdi-edit mr-2"></i>Alterar</button>
        <button type="button" disabled cancelar class="btn btn-space btn-danger"><i class="icon icon-left mdi mdi-delete mr-2"></i>Cancelar</button>
        <button type="button" disabled gravar class="btn btn-space btn-success"><i class="icon icon-left mdi mdi-check mr-2"></i>Salvar</button>
    @else
        <button type="button" desblock class="btn btn-space btn-dark"><i class="icon icon-left mdi mdi-edit mr-2"></i>Novo</button>
        <button type="button" disabled cancelar class="btn btn-space btn-danger"><i class="icon icon-left mdi mdi-delete mr-2"></i>Cancelar</button>
        <button type="button" disabled gravar class="btn btn-space btn-success"><i class="icon icon-left mdi mdi-account-add mr-2"></i>Cadastrar</button>
    @endif
@show
