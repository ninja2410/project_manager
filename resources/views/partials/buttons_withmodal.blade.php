<div class="row">
  <div class="col-lg-4"></div>
  <div class="col-lg-4" style="text-align: center;">
    <div class="form-group">
      <button type="button" class="btn btn-primary"  id="btn_save" onclick="showConf()" >
        {{trans('button.save')}}
      </button>
      <a class="btn btn-danger" href="{{ url($cancel_url) }}">
        {{trans('button.cancel')}}
      </a>
    </div>
  </div>
  <div class="col-lg-4"></div>
</div>
