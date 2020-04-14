<div class="row flex">
  @foreach($fields as $field)
    @if($field->type=='child')
      @if(isset(config('solunes.customer_dashboard_nodes.'.$model)[$field->name]))
        </div>
        <div class="admin-child-table subadmin-child-table" id="field_{{ $field->name }}">
          @if($action=='edit'||$action=='view')
            @if($action=='view')
              <h3>{{ $field->label }}</h3>
            @else
              {!! \AdminList::child_list_header($module, $field->value, $field->label, $i->id) !!}
            @endif
            @if($field->message)
              <label><div class="field-message">{{ $field->message }}</div></label>
            @endif
            <table class="admin-table table table-striped table-bordered table-hover dt-responsive dataTable no-footer dtr-inline" id="{{ $field->name }}">
              <thead><tr class="title"><td>#</td>
                {!! \AdminList::make_fields([], $field->child_fields, ['subadmin_child_field'=>$field->value]) !!}
              </tr></thead>
              <tbody>
                <?php $field_name = $field->name; ?>
                @foreach($i->$field_name as $key => $si)
                  @include('master::item_child.multiple-child-subadmin', ['count'=>$key])
                @endforeach
              </tbody>
            </table>
          @else
            <h3>{{ $field->label }}</h3>
            <p>Podrá llenar este campo una vez cree el formulario principal.</p>
          @endif
        </div>
        <div class="row flex">
      @endif
    @elseif($field->type=='subchild')
      </div>
      @if($field->multiple)
        <div id="field_{{ $field->name }}" class="child">
          <h3>{{ $field->label }}</h3>
          @if($field->message)
            <label><div class="field-message">{{ $field->message }}</div></label>
          @endif
          <div class="table-responsive">
            <table class="table" id="{{ $field->name }}">
              <thead><tr class="title">
                <td>Nº</td>
                @foreach($field->subadmin_child_fields as $subfield)
                  <td>
                    {{ $subfield->label }}
                    @if($subfield->required)
                     (*)
                    @endif
                  </td>
                @endforeach
                @if($dt!='view')
                  <td>X</td>
                @endif
              </tr></thead>
              <tbody>
                <?php $field_name = $field->name; ?>
                @if(($action=='edit'||$action=='view')&&count($i->$field_name)>0)
                  @foreach($i->$field_name as $key => $si)
                    @include('master::item_child.multiple-model-subadmin', ['count'=>$key])
                  @endforeach
                @elseif($action=='create'&&$dt=='view'&&$rows = $field->field_extras()->where('type','rows')->first())
                  @foreach(range(0, ($rows->value - 1)) as $key)
                    @include('master::item_child.multiple-model-subadmin', ['count'=>$key, 'si'=>0])
                  @endforeach
                @else
                  @include('master::item_child.multiple-model-subadmin', ['count'=>0, 'si'=>0])
                @endif
              </tbody>
              <tfoot>
                @if($dt!='view')
                  <tr><td colspan="{{ count($field->subadmin_child_fields)+2 }}">
                    <a class="agregar_fila" rel="{{ $field->name }}" href="#" data-count="500" @if($maxrows = $field->field_extras()->where('type','maxrows')->first()) data-maxrows="{{ $maxrows->value }}" @endif >+ Añadir otra fila</a>
                  </td></tr>
                @endif
                @foreach($node->fields()->where('child_table', $field_name)->get() as $extra_field)
                  <tr>
                    <td colspan="{{ count($field->subadmin_child_fields) }}" class="right">{{ $extra_field->label }} (Se calculará al guardar)</td>
                    <td colspan="2" class="extra_table_field">
                      {!! Field::form_input($i, $dt, $extra_field->toArray(), $extra_field->extras) !!}
                    </td>
                  </tr>
                @endforeach
              </tfoot>
            </table>
          </div>
        </div>
      @else
        <div id="field_{{ $field->name }}">
          <h3>{{ $field->label }}</h3>
          <div class="single-child row flex">
            <?php $field_name = $field->name; ?>
            @if(($action=='edit'||$action=='view')&&$i->$field_name)
              @include('master::item_child.single-model', ['si'=>$i->$field_name])
            @else
              @include('master::item_child.single-model', ['si'=>0])
            @endif
          </div>
        </div>
      @endif
      <div class="row flex">
    @else
      @if($field->new_row)
        </div>
        <div class="row flex">
      @endif
      {!! Field::form_input($i, $dt, $field->toArray(), $field->extras, [], 'customer-admin') !!}
    @endif
  @endforeach
</div>