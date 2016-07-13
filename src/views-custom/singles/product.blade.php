  <div class="product">
    <div class="mask">
      <div class="mask-hover">
        <h3><span ng-bind="product.name"></span></h3>
        <h5><span ng-bind="product.price"></span> Bs | <span ng-bind="product.price_usd"></span> US$
          @if(Auth::check())
            <a href="{{ url('admin/product/edit/'.$page->id) }}/<% product.id %>"></span><i class="fa fa-pencil"></i></a>
            <a href="{{ url('admin/product/delete/'.$page->id) }}/<% product.id %>"></span><i class="fa fa-trash"></i></a>
          @endif
        </h5>
        <div class="row buttons">
          <form name="cart" ng-submit="submitCartItem(product)">
            <div class="button">
              <input type="text" class="form-control" name="quantity" ng-init="product.quantity=0" ng-model="product.quantity" placeholder="Cantidad" />
              <button type="submit" class="btn btn-site btn-sm" ng-disabled="product.quantity<1 || buttonDisabled">
                {{ trans('master.add_to_cart') }}
              </button>     
            </div>
          </form>
        </div>
      </div>
      <img class="img-responsive" ng-src="<% product.image %>" />
    </div>
  </div>