@php
    if(!isset($form_name)) $form_name = 'nota_fiscal';
@endphp
<div class="col-12 p-4 pb-0 m-0">
    @include('cte.documentos.dados_nf', ['form_name'=>$form_name])
</div>

<script>
    $(function(){
        startNfDocumentos('#documentos #{{ $form_name }}', '{{ $form_name }}');
    });
</script>
