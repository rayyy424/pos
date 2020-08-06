<?php namespace App\Http\Controllers\Api;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;

class SiteController extends Controller {

	public function getsitedepartment()
	{

    	$auth = JWTAuth::parseToken()->authenticate();
		$me = (new AuthController)->get_current_user($auth->Id);

	    if($me->Department)
	    {
	  		$projects = DB::table('projects')
			->orderBy('Id','Asc')
			// ->select('Id','projects.Customer')
	      	// ->where('projects.Project_Name', '=',$me->Department)
	      	->where('projects.Customer', 'NOT LIKE','%Department%')

			->get();
	    }

	    else
	    {
	  		$projects = DB::table('projects')
			->orderBy('Id','Asc')
			->select('Id','Project_Name')
	      	->where('projects.Project_Name', 'not like','%Department%')
			->get();
	    }

		return json_encode($projects);
	}

	public function getsiteprojectcodes($id = null)
	{

		$me = JWTAuth::parseToken()->authenticate();

		if ($id) {
		$track = DB::table('tracker')
		->orderBy('Id','Asc')
		->select('Id','Site_Name','Site_Id','Project_Code','Unique ID','Site_Name as Site_Name_Original')
		// ->where('tracker.Project_Code', '!=', '')
		->where(function($q) {
			$q->where('tracker.Project_Code', '!=', '');
			$q->orWhere('tracker.Site_Name', '!=', '');

		})
		->where('tracker.ProjectId', $id)
		// ->groupBy('tracker.Site_Name')
		->get();

		} else {

			$track = DB::table('tracker')
			->orderBy('Id','Asc')
			->select('Id','Site_Name','Site_Id','Project_Code','Site_Name as Site_Name_Original','Unique ID')
			->where('tracker.Site_Name', '!=', '')
			// ->where('tracker.ProjectId', $id)
			// ->groupBy('tracker.Site_Name')
			->get();


		}

		return json_encode($track);
	}

	public function getewalletoptions()
	{

		$me = JWTAuth::parseToken()->authenticate();

		$options= DB::table('options')
		->whereIn('Table', ["users","ewallet"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		return json_encode($options);
	}

}