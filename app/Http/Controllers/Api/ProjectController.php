<?php namespace App\Http\Controllers\Api;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Input;

class ProjectController extends Controller {

  public function getprojects()
	{

		$me = JWTAuth::parseToken()->authenticate();

    if($me->Department)
    {
    	$projects = DB::table('projects')
    ->orderBy('Id','Asc')
      	->where('projects.Project_Name', '=',$me->Department)
    ->get();
    }

    else
    {
      $projects = DB::table('projects')
    ->orderBy('Id','Asc')
        ->where('projects.Project_Name', 'like','%Department%')
    ->get();
    }

		return json_encode($projects);
	}


  public function getdepartments()
	{

    	$auth = JWTAuth::parseToken()->authenticate();
		$me = (new AuthController)->get_current_user($auth->Id);

	    if($me->Department)
	    {
	  		$projects = DB::table('projects')
			->orderBy('Id','Asc')
	      	->where('projects.Project_Name', '=',$me->Department)
			->get();
	    }

	    else
	    {
	  		$projects = DB::table('projects')
			->orderBy('Id','Asc')
	      	->where('projects.Project_Name', 'like','%Department%')
			->get();
	    }

		return json_encode($projects);
	}


  public function getprojectcodes()
	{

		$me = JWTAuth::parseToken()->authenticate();

		$radius = DB::table('radius')
		->orderBy('Id','Asc')
		->where('radius.Location_Name', '!=', '')
		->groupBy('radius.Location_Name')
		->get();

		return json_encode($radius);
	}

}
