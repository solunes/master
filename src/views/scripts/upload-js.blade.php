<script type="text/javascript"> 
  createFileUploads();
  function createFileUploads() {
    var $container = $('.grid');
    $('.fileupload').each(function () {
      var $item = $(this);
      var type = $item.data('type');
      var folder = $item.data('folder');
      var count = $item.data('count');
      var multiple = $item.data('multiple');
      var width = $item.data('width');
      var height = $item.data('height');
      var extension = $item.data('extension');
      var file_name = $item.attr('name').replace("uploader_", "");
      var $fcontainer = $item.parent().find('.file_container');
      var $fprogress = $item.parent().find('.progress_bar');
      var $ferror = $item.parent().find('.error_bar');
      $item.fileupload({
        url: '{{ url("asset/upload") }}',
        dataType: 'json',
        paramName: 'file',
        formData: [{name: 'type', value: type}, {name: 'folder', value: folder}, {name: 'width', value: width}, {name: 'height', value: height}, {name: 'extension', value: extension}, {name: '_token', value: "{{ csrf_token() }}" }],
        dropZone: $(this),
        add: function (e, data) {
          $fprogress.show('fast');
          xhr = data.submit();
          $ferror.empty();
        },
        progressall: function (e, data) {
          var progress = parseInt(data.loaded / data.total * 100, 10);
          $('.progress_bar .bar').css('width', progress + '%');
        },
        done: function (e, data) {
            if(multiple=='0'){
              $fcontainer.empty();
            }
            $.each(data.result.files, function (index, file) {
            if(type=='image'){
              var file_response = '<div class="upload_thumb image_thumb"><a data-featherlight="image" class="lightbox" href="'+ file.url +'"><img src="'+ file.thumbUrl +'" /></a>';
            } else {
              var file_response = '<div class="upload_thumb file_thumb"><a href="'+ file.url +'" target="_blank">' + file.name + '</a>';
            }
            if(multiple=='1'){
              file_response += '<input type="hidden" name="'+ file_name +'['+count+']" value="'+ file.name +'" rel="'+ file_name +'" />';
            } else {
              file_response += '<input type="hidden" name="'+ file_name +'" value="'+ file.name +'" rel="'+ file_name +'" />';
            }
            file_response += '<a class="delete_temp" data-folder="'+ folder +'" data-action="temp" data-file="'+ file.name +'" data-type="'+ type +'" href="#">X</a>';
            file_response += '</div>';
            $(file_response).appendTo($fcontainer);
            count++;
          });
          $fprogress.hide('fast');
          $container.isotope( 'layout' );
        },
        fail: function (e, data) {
          if (data.errorThrown === 'abort') {
            var file_response = 'La carga de archivos fue cancelada.';
          } else {
            var file_response = JSON.parse(data.jqXHR.responseText).error;
          }
          $('<div class="error">'+file_response+'</div>').appendTo($ferror);
          $fprogress.hide('fast');
        }
      });
    });
  }
  $('form').on('click', '.file_container a.delete_temp', function(e){
    e.preventDefault();
    $(this).parent().remove();
    $container.isotope( 'layout' );
    $.ajax({
      method: "POST",
      url: "{{ url('asset/delete') }}",
      data: { folder: $(this).data('folder'), action: $(this).data('action'), file: $(this).data('file'), type: $(this).data('type') }
    });
    return false;
  });
  $('form').on('click', '.progress_bar a.cancel_upload_button', function(e){
    e.preventDefault();
    xhr.abort();
    return false;
  });
</script>