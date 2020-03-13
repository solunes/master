<script type="text/javascript">  
$(document).ready(function(){
  var dateconfig = {
    format: 'yyyy-mm-dd',
    formatSubmit: 'yyyy-mm-dd',
    selectYears: 10,
    selectMonths: true,
    hiddenName: true,
  }
  var dateinput = $('.date-control').pickadate(dateconfig);
  var datepicker = dateinput.pickadate('picker');
  var timeconfig = {
    format: 'HH:i',
    formatSubmit: 'HH:i',
    interval: 5,
    min: [6,0],
    max: [18,0],
    hiddenName: true,
  }
  var timeinput = $('.time-control').pickatime(timeconfig);
  var timepicker = timeinput.pickatime('picker');
  $(document.body).on('click', '.child a.agregar_fila',function(e){
    e.preventDefault();
    var rel = $(this).attr('rel');
    var count = $(this).data('count')+1;
    var maxrows = $(this).data('maxrows');
    if(maxrows){
      real_count = $('#'+rel+'>tbody>tr').length;
      maxrows = parseInt(maxrows);
      if(maxrows>0&&real_count>0&&maxrows<=real_count){
        alert('Alcanzó el límite de filas permitido para esta sección.')
        return false;
      }
    }
    $(this).data('count', count);
    $('#'+rel+'>tbody>tr:last').clone().insertAfter('#'+rel+'>tbody>tr:last');
    $('#'+rel+'>tbody>tr:last input, #'+rel+'>tbody>tr:last select, #'+rel+'>tbody>tr:last textarea').each(function(){
      var new_rel = $(this).attr('rel');
      new_rel = new_rel+'['+count+']';
      if($(this).attr('data-checkbox')){
        new_rel = new_rel + '[]';
      }
      $(this).attr({
        'name': function(_, name) { return new_rel },
      });
    });
    $('#'+rel+'>tbody>tr:last input.text-control, #'+rel+'>tbody>tr:last textarea.text-control').each(function(){
      $(this).val('');
    });
    $('#'+rel+'>tbody>tr:last input.hidden-control').each(function(){
      $(this).val('0');
    });
    // Correcciones del contador automático
    var realcount = $('#'+rel+'>tbody>tr:last td.table-counter').data('count')+1;
    $('#'+rel+'>tbody>tr:last td.table-counter').attr('data-count', realcount);
    $('#'+rel+'>tbody>tr:last td.table-counter').html(realcount);
    // Remover y resetear datos de imagen cargada
    if ($(".file_container")[0]){
      $('#'+rel+'>tbody>tr:last td .file_container').html('<div class="file_limitations"><p>La imagen debe ser: JPG, JPEG, PNG o GIF.</p></div>');
      createFileUploads();
    }
    // Remover uno al counter_val si el contador si existe
    /*var counter_val = $('#'+rel+'>tfoot .calculate-count').val();
    $('#'+rel+'>tfoot .calculate-count').val(parseInt(counter_val)+1);*/
    // Borrar datos del campo anterior
    $('#'+rel+'>tbody>tr:last .empty-field').each(function(){
      var text = $(this).data('text');
      $(this).html(text);
    });
    $('#'+rel+'>tbody>tr:last a.lightbox').each(function(){
      var new_rel = $(this).attr('rel');
      var new_value = $(this).data('value');
      $(this).attr({
        'href': function(_, href) { return "{{ url('admin/modal-map') }}/"+new_rel+"["+count+"]/"+new_value+"?lightbox[width]=800&lightbox[height]=500" },
        'id': function(_, id) { return 'link-'+new_rel+'['+count+']' },
      });
    });
    $('#'+rel+'>tbody>tr:last .picker').remove();
    //$('#'+rel+' tbody>tr:last input[type=hidden]').remove();
    timeinput = $('.time-control').pickatime(timeconfig);
    timepicker = timeinput.pickatime('picker');
    dateinput = $('.date-control').pickadate(dateconfig);
    datepicker = dateinput.pickadate('picker');
    return false;
  });
  $('.child').on('click', 'a.delete_row', function(e){
    e.preventDefault();
    var rel = $(this).attr('rel');
    var count = $('#'+rel+'>tbody>tr').length;
    // Remover uno al counter_val si el contador si existe
    /*var counter_val = $('#'+rel+'>tfoot .calculate-count').val();
    $('#'+rel+'>tfoot .calculate-count').val(parseInt(counter_val)-1);*/
    // Remover campo completo
    if(count>1){
      $(this).parent().parent().remove();
    }
    return false;
  });
});
</script>