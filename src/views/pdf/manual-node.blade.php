@if(count($nodes_array)>0)
  @foreach($nodes_array as $node)
    <?php $count++; ?>
    <h3>{{ $last_count.$count.'. '.$node->singular }}</h3>
    <p>El nodo 
      @if($node->permission)
        puede ser accedido por 
        @foreach(\Solunes\Master\App\Permission::where('name', $node->permission)->first()->permission_role as $key => $role)
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
          {{ $child->singular }}
        @endforeach
        .
      @endif
    </p>
    <h4>{{ trans('master::admin.fields') }}</h4>
    @foreach($node->fields()->where('display_item', '!=', 'none')->get() as $field)
      <ul>
        <li><strong>{{ $field->label }}: </strong>
          Campo de {{ trans('master::admin.'.$field->type) }}
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
              @if($key<10)
                @if($subcount>0)
                  , 
                @else
                  <?php $subcount++; ?>
                @endif
                {{ $val }}
              @elseif($key==10)
                 , etc. 
              @endif
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
              <br>- Se mostrará si es que "{{ trans('master::fields.'.$conditional->trigger_field) }}" {{ strtolower(trans('master::admin.'.$conditional->trigger_show)).' '.trans('master::admin.'.$conditional->trigger_value) }}.
            @endforeach 
          @endif
          @if($field->tooltip)
            <br>Adicionalmente tiene un mensaje de ayuda que indica:<br>{!! trans('tooltips.'.$field->name) !!}.  
          @endif
        </li>
      </ul>
    @endforeach
  @endforeach
@endif