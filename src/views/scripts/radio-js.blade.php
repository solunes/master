<script type="text/javascript">
  $('body').on('click', 'a.unselect-radio', function() {
    var radio_rel = $(this).attr('rel');
    $('input[name="'+radio_rel+'"]').prop('checked', false);
  });
</script>