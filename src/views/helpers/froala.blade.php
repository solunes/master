<script src="{{ url(elixir("assets/js/froala.js")) }}"></script>
<script type="text/javascript"> 
    $(function() {
        $('.textarea').froalaEditor({
            key: "{{ Config::get('services.froala.key') }}",
            heightMin: 200,
            heightMax: 500,
            toolbarSticky: false,
            fileUploadURL: '{{ url("asset/froala-file-upload") }}',
            fileUploadParams: {_token: '{{{ csrf_token() }}}'},
            imageUploadURL: '{{ url("asset/froala-image-upload") }}',
            imageUploadParams: {_token: "{{{ csrf_token() }}}" },
        })
    });
</script>