<?php 

namespace Solunes\Master\App\Middleware;

use Illuminate\Routing\UrlGenerator;
use Closure;

class CheckPermission {

	public function __construct(UrlGenerator $url) {
		$this->prev = $url->previous();
	}

    public function handle($request, Closure $next, $permission){
    	if (\Gate::denies($permission)) {
            return redirect('')->with('message_error', trans('master::admin.no_permission'));
        }

        return $next($request);
    }
}