<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */

	public function handle($request, Closure $next)
	{
		// return parent::handle($request, $next);
		if ( ! $request->is('api/*'))
	 {
			 return parent::handle($request, $next);
	 }

	 return $next($request);
	}

	protected $except = [
			 'api/*',
			 'tracker/submitdocument'
	 ];

}
