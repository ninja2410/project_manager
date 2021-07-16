<div class="modal-header" style="background-color:  #d9534f;color: white;">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h4 class="modal-title" id="user_delete_confirm_title">
    @if (isset($titulo))
      {{$titulo}}
    @else
      Eliminar
    @endif
  </h4>
</div>
<div class="modal-body">
    @if($error)
        <div>{!! $error !!}</div>
    @else
        <h3>
          @if(isset($mensaje))
            {{$mensaje}}
          @else
            Esta seguro que desea eliminar el elemento?
          @endif
        </h3>
    @endif
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
  @if(!$error)
    <a href="{{ $confirm_route }}" type="button" class="btn btn-success">Aceptar</a>
  @endif
</div>
