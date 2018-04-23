@component('components.alert', ['type'=>'danger'])
    {!! Session::get('erro') !!}
@endcomponent

@php
    $idReturn = Session::get('genId');
@endphp

@component('components.alert', ['type'=>'success'])
    @if($idReturn > 0)
        @yield('sucessMensage')
        @yield('sucessFunction')
        <script>window.history.pushState("alterando link para exibir o codigo", "OrionWeb", window.location.pathname + '/{{ $idReturn }}');</script>
    @elseif($idReturn === 0)
        @yield('updateMensage')
    @endif
@endcomponent

@if(@$invalidSearch == 'false')
    @section('id', 'modal_danger')
    @section('title', 'ATENÇÃO')
    @section('msg', $__env->yieldContent('msgModal', 'Registro não Encontrado!'))
    @section('open', 'true')

    @section('buttons')
        <button type="button" data-dismiss="modal" class="btn btn-space btn-secondary">Ok</button>
    @endsection

    @include('layouts.modal')
@endif
