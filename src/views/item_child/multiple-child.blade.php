<tr>
  	<td class="table-counter" data-count="{{ $count+1 }}">{{ $count+1 }}</td>
  	{!! AdminList::make_fields_values_rows([], $module, $field->value, $si, $field->child_fields, $field->child_field_options, [], ['child_field'=>$field->value]) !!}
</tr>