<?php namespace App\Http\Controllers\Api;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class HolidayController extends Controller {

	// public function __construct()
	// {
	// 	$this->middleware('auth');
	// }

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */

	public function index(Request $request)
	{
		$auth = JWTAuth::parseToken()->authenticate();
		$me = (new AuthController)->get_current_user($auth->Id);

		$input = $request->all();

	    $years = DB::table('holidays')
	    ->select(DB::raw('Distinct right(Start_Date,4) as yearname'))
	    ->orderBy('holidays.Start_Date','desc')
	    ->get();

		$holidays = DB::table('holidayterritorydays')
	    ->select('holidayterritorydays.Id','holidayterritorydays.Holiday','holidayterritorydays.Start_Date','holidayterritorydays.End_Date','holidayterritorydays.State','holidayterritorydays.Country')
			->leftJoin('holidayterritories','holidayterritorydays.HolidayTerritoryId','=','holidayterritories.Id')
			->leftJoin('users','users.HolidayTerritoryId','=','holidayterritories.Id')
	    ->orderBy(DB::raw('str_to_date(holidayterritorydays.Start_Date,"%d-%M-%Y")'),'asc')
			->where(DB::raw('YEAR(str_to_date(holidayterritorydays.Start_Date,"%d-%M-%Y"))'),"=", date('Y'))
			->where('users.Id','=',$me->UserId)
	    ->get();

	    $options= DB::table('options')
	    ->whereIn('Table', ["users","claims"])
	    ->orderBy('Table','asc')
	    ->orderBy('Option','asc')
	    ->get();

    	return json_encode($holidays);
	}

}
