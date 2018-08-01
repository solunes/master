{!! Form::open(['url'=>'admin/assign-indicators', 'method'=>'POST', 'class'=>'form-horizontal filter']) !!}
  <h3>Agregar Indicadores</h3>
  @if(isset($items)&&count($items)>0)
    <div class="row">
      {!! Field::form_input(0, 'create', ['name'=>'indicator_id','type'=>'checkbox','required'=>true, 'options'=>$items], ['cols'=>12,'label'=>'Indicadores para agregar en Dashboard propio']) !!}
    </div>
    <br><p>Seleccione todos los indicadores que desea agregar en su dashboard personal.<br>Estos cambios solo se aplicarán a su cuenta.</p>
  @endif
  {!! Form::submit(trans('master::admin.save'), array('class'=>'btn btn-site')) !!}
{!! Form::close() !!}