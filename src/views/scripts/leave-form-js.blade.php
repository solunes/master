<script type="text/javascript"> 
  $(document).ready(function() {
    formmodified=0;
    $('form.prevent-double-submit *').change(function(){
        formmodified=1;
    });
    window.onbeforeunload = confirmExit;
    function confirmExit() {
        if (formmodified == 1) {
            return "New information not saved. Do you wish to leave the page?";
        }
    }
    $("form.prevent-double-submit input[type='submit']").click(function() {
        formmodified = 0;
    });
    $("form.prevent-double-submit input[type='button']").click(function() {
        formmodified = 0;
    });
    $("form.prevent-double-submit button[type='submit']").click(function() {
        formmodified = 0;
    });
  });
</script>