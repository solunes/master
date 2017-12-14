<script type="text/javascript">  
  $(document).ready(function() {
    var actionurl = "{{ url('admin/generate-item-field') }}";
    $('.editable-list').on( 'click', 'tbody td:not(.ineditable)', function (e) {
      var $field = $(this);
      var node_name = $field.parent().parent().data('node');
      var field_name = $field.data('field');
      var item_id = $field.data('id');
      $field = $(this).addClass('ineditable');
      $field = $(this).data('backup', $field.html());
      $.ajax({
        url: actionurl + "/" + node_name + "/" + field_name + "/" + item_id,
        type: 'GET',
        success: function(data) {
          var id = '#'+data.name;
          $field.html(data.html);
          $(id).focus();
          setFocus(id)
        }, error: function(error) {
          console.log('error' + error);
        }
      });
    });
  });
  function setFocus(id){
    $(id).change(function() {
      console.log('changed');
      $(this).data('changed', true);
    });
    $(id).focusout(function() {
      console.log('focusout');
      processInput(id);
    });
    $(id).on('keydown', function(e) {
      if(e.keyCode == 13||e.which == 13||e.which == 9) {
        e.preventDefault();
        $(this).data('changed', true);
        console.log('enter o tab');
        processInput(id);
        return false;
      }
    });
  }
  function processInput(id){
    var value = $(id).val();
    $field = $(id).parent();
    $field.removeClass('ineditable');
    var node_name = $field.parent().parent().data('node');
    var field_name = $field.data('field');
    var item_id = $field.data('id');
    var new_value = $field.data('backup');
    if($(id).data('changed')===true){
      $.ajax({
        url: "{{ url('admin/item-field-update') }}",
        type: 'POST',
        //dataType: 'json',
        data: {'node_name':'banner','field_name':field_name,'item_id':item_id,'value':value},
        success: function(data) {
          console.log('success');
          if(data.done){
            new_value = data.new_value;
          }
          $field.html(new_value);
        }, error: function(error) {
          $field.html(new_value);
        }
      });
    } else {
      $field.html(new_value);
    }
  }
</script>