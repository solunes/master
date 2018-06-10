@foreach($field->child_fields as $subfield)
    @if($subfield->new_row)
        </div>
        <div class="row flex">
    @endif
  	{!! Field::form_input($si, $dt, $subfield->toArray(), $subfield->extras+['subtype'=>'single', 'subinput'=>$field->name, 'subcount'=>0]) !!}
@endforeach
{!! Field::form_input($si, $dt,['name'=>'id', 'type'=>'hidden', 'required'=>true], ['subtype'=>'single', 'subinput'=>$field->name, 'subcount'=>0]) !!}