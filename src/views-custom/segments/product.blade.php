<div class="container">
  <div masonry class="row">
    <div class="masonry-brick col-sm-4 col-xs-6" ng-repeat="product in products">
      @include('singles.product', ['model'=>'product'])
    </div>
  </div>
</div>