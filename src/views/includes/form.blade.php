<div class="row flex">
  @foreach($fields as $field)
    @if($field->type=='child')
      </div>
      <div id="field_{{ $field->name }}">
        @if($action=='edit')
          <h3>{{ $field->label }} | <a href="{{ url('admin/child-model/'.$field->value.'/create?parent_id='.$i->id.'&lightbox[width]=1000&lightbox[height]=600') }}" class="lightbox">Crear item</a></h3>
          @if($field->message)
            <label><div class="field-message">{{ $field->message }}</div></label>
          @endif
          <table class="admin-table table table-striped table-bordered table-hover dt-responsive dataTable no-footer dtr-inline" id="{{ $field->name }}">
            <thead><tr class="title">
              <td>Nº</td>
              @foreach($field->child_fields as $subfield)
                <td>{{ $subfield->label }}</td>
              @endforeach
              @if($action!='view')
                <td>Editar</td>
                <td>Borrar</td>
              @endif
            </tr></thead>
            <tbody>
              <?php $field_name = $field->name; ?>
              @foreach($i->$field_name as $key => $si)
                @include('master::item_child.multiple-child', ['count'=>$key])
              @endforeach
            </tbody>
          </table>
        @else
          <h3>{{ $field->label }}</h3>
          <p>Podrá llenar este campo una vez cree el formulario principal.</p>
        @endif
      </div>
      <div class="row flex">
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
                @foreach($field->child_fields as $subfield)
                  <td>
                    {{ $subfield->label }}
                    @if($subfield->required)
                     (*)
                    @endif
                  </td>
                @endforeach
                @if($action!='view')
                  <td>X</td>
                @endif
              </tr></thead>
              <tbody>
                <?php $field_name = $field->name; ?>
                @if(($action=='edit'||$action=='view')&&count($i->$field_name)>0)
                  @foreach($i->$field_name as $key => $si)
                    @include('master::item_child.multiple-model', ['count'=>$key])
                  @endforeach
                @else
                  @include('master::item_child.multiple-model', ['count'=>0, 'si'=>0])
                @endif
              </tbody>
              <tfoot>
                @if($action!='view')
                  <tr><td colspan="{{ count($field->child_fields)+2 }}">
                    <a class="agregar_fila" rel="{{ $field->name }}" href="#" data-count="500">+ Añadir otra fila</a>
                  </td></tr>
                @endif
                @foreach($node->fields()->where('child_table', $field_name)->get() as $extra_field)
                  <tr>
                    <td colspan="{{ count($field->child_fields) }}" class="right">{{ $extra_field->label }} (Se calculará al guardar)</td>
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
      {!! Field::form_input($i, $dt, $field->toArray(), $field->extras) !!}
    @endif
  @endforeach
</div>