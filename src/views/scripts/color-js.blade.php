<script type="text/javascript">
window.addEventListener("load", function () {
    var pk = new Piklor(".color-picker", {!! config('solunes.colors') !!}, {
            open: ".picker-wrapper .btn"
        })
      , wrapperEl = pk.getElm(".picker-wrapper-color")
      ;
      colorInput = $("#hidden-color-field")

    pk.colorChosen(function (col) {
        wrapperEl.style.backgroundColor = col;
        colorInput.val(col);
    });
});
</script>
<style type="text/css">
  .picker-wrapper button.btn-site { margin-top: -18px !important; margin-bottom: 10px;}
  .picker-wrapper-color { width: 30px; height: 30px; display: inline-block; margin-right: 10px; border: 1px solid #000; border-radius: 50%; }
  .color-picker { background: rgba(255, 255, 255, 0.75); padding: 10px; border: 1px solid rgba(203, 203, 203, 0.6); border-radius: 2px; }
  .color-picker > div { width: 40px; display: inline-block; height: 40px; margin: 5px; border-radius: 100%; opacity: 0.7; }
</style>