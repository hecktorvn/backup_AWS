@php
    if(!isset($type)) $type = 'warning';
    if($slot == '') return false;

    if(!isset($titulo)):
        switch($type):
            case 'warning':
                $titulo = 'Atenção';
                $icon = 'mdi-alert-triangle';
                break;
            case 'danger':
                $titulo = 'Erro';
                $icon = 'mdi-close-circle-o';
                break;
            case 'info':
                $titulo = 'Informação';
                $icon = 'mdi-info-outline';
                break;
            case 'success':
                $titulo = 'Concluído';
                $icon = 'mdi-check';
                break;
            default:
                $icon = 'mdi-close-circle-o';
                break;
        endswitch;
    endif;
@endphp

<div role="alert" class="alert alert-{{ $type }} alert-icon alert-icon-border alert-dismissible">
    <div class="icon"><span class="mdi {{ $icon }}"></span></div>
    <div class="message">
        <button type="button" data-dismiss="alert" aria-label="Close" class="close">
            <span aria-hidden="true" class="mdi mdi-close"></span>
        </button>
        <strong>{{ ucfirst($titulo) }}!</strong> {{ $slot }}
    </div>
</div>
