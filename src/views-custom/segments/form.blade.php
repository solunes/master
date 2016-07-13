<div class="container">
    {!! Form::open(AdminItem::make_form($module, $model, $action, $files)) !!}
    @include('master::includes.form')
    {!! Field::form_submit($i, $model, 'send') !!}
    {!! Form::close() !!}
</div>