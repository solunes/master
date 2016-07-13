<div data-accordion-group>
  @foreach($parent_nodes as $subnode)
    <div class="parent_node accordion" data-accordion>
      <div data-control><h4><i class="fa fa-caret-down"></i> {{ $node->singular }}</h4></div>
      <div data-content>
        <div class="accordion-content">
          <div class="row flex">
            <?php $node_name = $subnode['iname']; ?>
            @foreach($subnode['fields'] as $field)
              @if($field->new_row)
                </div>
                <div class="row flex">
              @endif
              {!! Field::form_input($i->$node_name, 'view', $field->toArray(), $field->extras) !!}
            @endforeach
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>