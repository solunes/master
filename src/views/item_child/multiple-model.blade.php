<tr>
  @foreach($field->child_fields as $subfield)
    <td>{!! Field::form_input($si, $dt, $subfield->toArray(), $subfield->extras+['subtype'=>'multiple', 'subinput'=>$field->name, 'subcount'=>$count]) !!}</td>
   @endforeach
  <td>
  	{!! Field::form_input($si, $dt, ['name'=>'id', 'type'=>'hidden', 'required'=>true], ['subtype'=>'multiple', 'subinput'=>$field->name, 'subcount'=>$count]) !!}
  	<a class="delete_row" rel="{{ $field->name }}" href="#">X</a>
  </td>
</tr>