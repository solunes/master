<tr>
  <td class="table-counter" data-count="{{ $count+1 }}">{{ $count+1 }}</td>
  {!! AdminList::make_fields_values_rows([], 'admin', $field->value, $si, $field->child_fields, [], [], []) !!}
  <td class="edit"><a href="{{ url('admin/child-model/'.$field->value.'/edit/'.$si->id.'/es?lightbox[width]=1200&lightbox[height]=700') }}" class="lightbox">Editar</a></td>
  <td class="delete"><a href="{{ url('admin/model/'.$field->value.'/delete/'.$si->id) }}" onclick="return confirm('¿Está seguro que desea eliminar este item?');">Borrar</a></td>
</tr>