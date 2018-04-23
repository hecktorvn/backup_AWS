<div class="modal-open">
    <div id="@yield('id', 'modal_danger')" tabindex="-1" role="dialog" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="mdi mdi-close"></span></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div class="text-danger"><span class="modal-main-icon mdi mdi-close-circle-o"></span></div>
                        <h3 id="title">@yield('title')</h3>
                        <h4 id="msg"><p>@yield('msg')</h4>
                    </div>
                </div>
                <div class="modal-footer" id="buttons">
                    @section('buttons')
                        <button type="button" data-dismiss="modal" class="btn btn-space btn-secondary">Cancel</button>
                        <button type="button" data-dismiss="modal" class="btn btn-space btn-danger">Proceed</button>
                    @show
                </div>
            </div>
        </div>
    </div>
</div>

@if($__env->yieldContent('open') == 'true')
    <script>$(function(){ $('#@yield('id')').modal(); });</script>
@endif
