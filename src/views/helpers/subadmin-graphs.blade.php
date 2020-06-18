@if(config('solunes.customer_dashboard_graphs.'.$model))
<section id="dashboard-analytics">
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between pb-0">
                    <h3 class="card-title"> {{ config('solunes.customer_dashboard_graphs.'.$model)['title'] }} - Año {{ date('Y') }}</h3>
                </div> 
                <div class="card-content">
                    <div class="card-body">
                        <div class="row avg-sessions pt-50">
                          <figure class="highcharts-figure">
                              <div id="list-graph-product" style="height: 400px;"></div>
                              <p class="highcharts-description">
                                {{ config('solunes.customer_dashboard_graphs.'.$model)['description'] }}
                              </p>
                          </figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif