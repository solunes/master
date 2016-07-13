@if(count($nodes_array)>0)
  @foreach($nodes_array as $node)
    <?php $count++; ?>
    <h3>{{ $last_count.$count.'. '.trans_choice('model.'.$node->name, 0) }}</h3>
    <p>El nodo 
      @if($node->permission)
        puede ser accedido por 
        @foreach(\App\Permission::where('name', $node->permission)->first()->permission_role as $key => $role)
          @if($key>0)
            , 
          @else
            <?php $key = 1; ?>
          @endif
          {{ $role->display_name }}
        @endforeach
        . 
      @else
      no tiene ningún permiso específico. 
      @endif
      @if($node->translation)
        Es posible traducir algunos campos de este nodo si es que se requiere. 
      @endif
      @if(count($node->children)>0)
        El nodo tiene {{ count($node->children) }} nodos relacionados al mismo de menor nivel. Estos incluyen 
        @foreach($node->children as $key => $child)
          @if($key>0)
            , 
          @else
            <?php $key = 1; ?>
          @endif
          {{ trans_choice('model.'.$child->name, 0) }}
        @endforeach
        .
      @endif
    </p>
    <h4>{{ trans('admin.fields') }}</h4>
    @foreach($node->fields()->where('type', '!=', 'child')->where('display_item', '!=', 'none')->get() as $field)
      <ul>
        <li><strong>{{ trans('fields.'.$field->trans_name) }}: </strong>
          Campo de {{ trans('admin.'.$field->type) }}
          @if($field->required)
           requerido
          @else
           opcional
          @endif
          @if($field->multiple)
           y que puede ser multiple
          @endif
          . 
          @if($field->translation)
            La plataforma acepta que este campo sea traducible en caso de que hayan múltiples idiomas. 
          @endif
          @if($field->preset)
            El campo debe ser preselccionado antes de poder acceder al formulario. 
          @endif
          @if($field->type=='select')
            Puede elegirse entre las siguientes opciones:
            <?php $subcount = 0; ?>
            @foreach($field->options as $key => $val)
              @if($subcount>0)
                , 
              @else
                <?php $subcount++; ?>
              @endif
              {{ $val }}
            @endforeach
            .
          @elseif($field->type=='image')
            Sólamente se aceptan imagenes en formato JPG, PNG o GIF.
          @elseif($field->type=='file')
            Se aceptan todo tipo de archivos incluyendo JPG, PNG, GIF, XLS, XLSX, DOC, DOCX y PDF.
          @endif
          @if(count($field->field_conditionals)>0)
            <br>Condicionantes:
            @foreach($field->field_conditionals as $conditional)
              <br>- Se mostrará si es que "{{ trans('fields.'.$conditional->trigger_field) }}" {{ strtolower(trans('admin.'.$conditional->trigger_show)).' '.trans('admin.'.$conditional->trigger_value) }}.
            @endforeach 
          @endif
          @if($field->tooltip)
            <br>Adicionalmente tiene un mensaje de ayuda que indica:<br>{!! trans('tooltips.'.$field->name) !!}.  
          @endif
        </li>
      </ul>
    @endforeach
    @if(count($node->children)>0)
      @include('pdf.manual-node', ['nodes_array'=>$node->children, 'last_count'=>$last_count.$count.'.', 'count'=>0])
    @endif
  @endforeach
@endif