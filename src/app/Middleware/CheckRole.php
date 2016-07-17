<?php 

namespace Solunes\Master\App\Middleware;

use Illuminate\Routing\UrlGenerator;
use Closure;

class CheckRole {

	public function __construct(UrlGenerator $url) {
		$this->prev = $url->previous();
	}

    public function handle($request, Closure $next, $role){
        if (! $request->user()->hasRole($role)) {
            return redirect('')->with('message_error', trans('admin.no_permission'));
        }

        return $next($request);
    }
}