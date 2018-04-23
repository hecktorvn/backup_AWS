@extends('layouts.principal')
@section('content')
@parent
<div class="main-content container-fluid pt-0">
    <div class="row">
        <div>
            @section('updateMensage', @$msg['updateMensage'])
            @section('sucessMensage', @$msg['sucessMensage'])
            @section('msgModal', @$msg['msgModal'])
            @include('layouts.status_save')

            @section('sucessFunction')
                @php
                    $idReturn = Session::get('genId');
                    if($idReturn > 0) $data = DefRequestController::listReturn($table['name'], 0, 1, [$table['primary_key']=>$idReturn])[0];
                @endphp
            @endsection

            <div class="card card-border-color card-border-color-primary">
                <div class="main-content container-fluid">
                    @yield('content_card')
                    <!-- FIM DO FLUID !-->
                </div>
                <!-- FIM DO CARD !-->
            </div>
            <!-- FIM DO COL-LG-6 !-->
        </div>
        <!-- FIM DO ROW !-->
    </div>
    <!-- FIM DO CARD !-->
</div>
<!-- FIM DO MAIN !-->

<script>
    $(function(){ $('form#{{ $table['form'] }}').startForm(); });
</script>
@endsection
