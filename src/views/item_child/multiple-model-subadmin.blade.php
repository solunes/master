<tr>
  <td class="table-counter" data-count="{{ $count+1 }}">{{ $count+1 }}</td>
  @foreach($field->subadmin_child_fields as $subfield)
    <td>{!! Field::form_input($si, $dt, $subfield->toArray(), $subfield->extras+['subtype'=>'multiple', 'subinput'=>$field->name, 'subcount'=>$count]) !!}</td>
  @endforeach
  @if($dt!='view')
    <td>
      {!! Field::form_input($si, $dt, ['name'=>'id', 'type'=>'hidden', 'required'=>true], ['subtype'=>'multiple', 'subinput'=>$field->name, 'subcount'=>$count]) !!}
      <a class="delete_row" rel="{{ $field->name }}" href="#">X</a>
    </td>
  @endif
</tr>