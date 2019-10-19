<div class="row flex">
  @foreach($fields as $field)
    @if($field->type=='child')
    @elseif($field->type=='subchild')
    @else
      @if($field->new_row)
        </div>
        <div class="row flex">
      @endif
      {!! Field::form_input($i, $dt, $field->toArray(), $field->extras, [], 'customer-admin') !!}
    @endif
  @endforeach
</div>