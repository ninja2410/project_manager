@if (Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif
@if (count($errors) > 0)
<div class="alert alert-danger">
    {!! Html::ul($errors->all()) !!}
</div>
@endif
