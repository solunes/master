<div class="container">
    {!! Form::open(AdminItem::make_form($module, $model, $action, $files)) !!}
    @include('master::includes.form')
    <div>
        {!! Form::hidden('action', $action) !!}
        {!! Form::hidden('model_node', $model) !!}
        {!! Form::hidden('id', $id) !!}
        {!! Form::hidden('lang_code', \App::getLocale()) !!}
        <input type="button" onclick="submitForm('{{ url('process/save-model') }}')" value="{{ trans('admin.save') }} Borrador" class="btn btn-site" />
        {!! Form::submit(trans('admin.send').' (Ya no podrá editar el formulario)', array('class'=>'btn btn-site')) !!}
    </div>
    {!! Form::close() !!}
</div>
<script type="text/javascript">
    function submitForm(action) {
        document.getElementById("{{ $action.'_'.$model }}").action = action;
        document.getElementById("{{ $action.'_'.$model }}").submit();
    }
</script>