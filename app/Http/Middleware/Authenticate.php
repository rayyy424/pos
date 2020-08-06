<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\DB;

use Route;

class Authenticate {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

		if(!request()->headers->get('referer') && Route::getCurrentRoute()->getPath()!="home" && Route::getCurrentRoute()->getPath()!="/" && str_contains(Route::getCurrentRoute()->getPath(),"handsontable")==false)
		{

			if ($this->auth->guest())
			{
				if ($request->ajax())
				{
					return response('Unauthorized.', 401);
				}
				else
				{
					return redirect()->guest('auth/login');
				}
			}

			$me = (new CommonController)->get_current_user();
			return view('errors.denied', ['me' => $me]);

		}

		if ($this->auth->guest())
		{
			if ($request->ajax())
			{
				return response('Unauthorized.', 401);
			}
			else
			{
				return redirect()->guest('auth/login');
			}
		}

		if ($this->auth->viaRemember()) {
		    //
				$me = (new CommonController)->get_current_user();

				$loginlog= DB::table('logintrackings')
							->insert(array(
							'Event' => "Remember Login",
							'UserId' => $me->UserId
						));
		}

		return $next($request);
	}

}
