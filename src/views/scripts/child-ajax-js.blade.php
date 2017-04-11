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
          console.log('success' + data);
          $('.jquery-lightbox-html').html(data);
          $('.jquery-lightbox-html').animate({ scrollTop: 0 }, 600);
        }, error: function(error) {
          console.log('error' + error);
        }
      });
    });
  });
</script>