<script type="text/javascript">  
  $(function() {
    //hang on event of form with id=myform
    $(".page-child form").submit(function(e) {
      //prevent Default functionality
      e.preventDefault();
      //get the action-url of the form
      var actionurl = e.currentTarget.action;
      //do your own request an handle the results
      console.log('actionurl' + actionurl);
      $.ajax({
        url: actionurl,
        type: 'POST',
        //dataType: 'json',
        data: $(".page-child form").serialize(),
        success: function(data) {
          console.log('success');
          if($.isPlainObject(data)){
            console.log('cerrar ventana y recargar con:' + data);
            parent.location.reload();
          } else {
            console.log('redireccionar lightbox');
            $('.jquery-lightbox-html').html(data);
            $('.jquery-lightbox-html').animate({ scrollTop: 0 }, 600);
          }
        }, error: function(error) {
          console.log('error' + error);
        }
      });
    });
  });
  $('.child').on('click', 'a.delete_row', function(e){
    e.preventDefault();
    var rel = $(this).attr('rel');
    var count = $('#'+rel+'>tbody>tr').size();
    if(count>1){
      $(this).parent().parent().remove();
    }
    return false;
  });
</script>