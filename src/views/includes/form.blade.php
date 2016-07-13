<div class="row flex">
  @foreach($fields as $field)
    @if($field->type=='subchild')
      </div>
      @if($field->multiple)
        <div id="field_{{ $field->name }}" class="child">
          <h3>{{ $field->label }}</h3>
          <div class="table-responsive">
            <table class="table" id="{{ $field->name }}">
              <thead><tr class="title">
                @foreach($field->child_fields as $subfield)
                  <td>
                    {{ $subfield->label }}
                    @if($subfield->required)
                     (*)
                    @endif
                  </td>
                @endforeach
                <td>X</td>
              </tr></thead>
              <tbody>
                <?php $field_name = $field->name; ?>
                @if($action=='edit'&&count($i->$field_name)>0)
                  @foreach($i->$field_name as $key => $si)
                    @include('master::item_child.multiple-model', ['count'=>$key])
                  @endforeach
                @else
                  @include('master::item_child.multiple-model', ['count'=>0, 'si'=>0])
                @endif
              </tbody>
            </table>
          </div>
          <a class="agregar_fila" rel="{{ $field->name }}" href="#" data-count="500">Añadir otra fila</a>
        </div>
      @else
        <div id="field_{{ $field->name }}">
          <h3>{{ $field->label }}</h3>
          <div class="single-child row flex">
            <?php $field_name = $field->name; ?>
            @if($action=='edit'&&$i->$field_name)
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