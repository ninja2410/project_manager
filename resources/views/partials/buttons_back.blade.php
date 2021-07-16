<div class="row">
    <div class="col-lg-4"></div>
    <div class="col-lg-4" style="text-align: center;">
        <div class="form-group">
                <button type="submit" class="btn btn-primary" @if(isset($submit_id)){{  $submit_id }} @else id="btn_save" @endif >
                    {{trans('button.save')}}
                </button>
                <a class="btn btn-danger" href="{{ URL::previous() }}">
                    {{trans('button.cancel')}}
                </a>
        </div>
    </div>
    <div class="col-lg-4"></div>
</div>
