<?php namespace App\Http\Controllers\Api;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use Input;
use DateTime;

class AvailabilityController extends Controller {

	public function getavailability()
  {
    $me = JWTAuth::parseToken()->authenticate();

    $users = DB::table('users')->select('Available')
    ->where('Id','=',$me->Id)
		->get();

    return json_encode($users);
  }


	public function setavailability(Request $request)
  {
    $me = JWTAuth::parseToken()->authenticate();
    $input = $request->all();

		$result= DB::table('users')
		->where('Id', '=',$me->Id)
		->update(array(
		  'Available' =>  $input["Available"],
    ));
  return $result;
  }
  
}
