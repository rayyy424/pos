<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\CommonController;

class ProjectController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function project()
	{

		$me = (new CommonController)->get_current_user();

		$projects = DB::table('projects')
		->leftJoin('users', 'projects.Project_Manager', '=', 'users.Id')
		->select('projects.Id','projects.Project_Name','projects.Country','projects.Customer','projects.Operator','projects.Region','projects.Type','projects.Scope','users.Name As Project_Manager','projects.Project_Description','projects.Remarks','projects.Active')
		->orderBy('projects.Project_Name','asc')
		->get();

		$projectmanager = DB::table('users')
		->leftJoin('accesscontroltemplates', 'accesscontroltemplates.Id', '=', 'users.AccessControlTemplateId')
		->select('users.Id','users.Name')
		->where('accesscontroltemplates.Project_Manager', '=',1)
		->get();

		$options= DB::table('options')
		->whereIn('Table', ["projects"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

  	return view('project', ['me' => $me,'projects' => $projects,'projectmanagers' => $projectmanager,'options' =>$options]);

	}

	public function projectcode()
	{
		$me = (new CommonController)->get_current_user();

		$projectids = explode("|",$me->ProjectIds);

		$projects = DB::table('projects')
		->whereIn('Id',$projectids)
		->get();

		$projectcodes = DB::table('projectcodes')
		->select('projectcodes.Id','projects.Project_Name','projectcodes.Project_Code','projectcodes.Site_ID','projectcodes.Site_Name','projectcodes.Description','users.Name as Created_By')
		->leftJoin('users','users.Id','=','projectcodes.Created_By')
		->leftJoin('projects','projectcodes.ProjectId','=','projects.Id')
		->whereIn('projectcodes.ProjectId',$projectids)
		->get();

		$projectids=implode(",",$projectids);

  	return view('projectcode', ['me' => $me,'projectcodes' => $projectcodes,'projects'=>$projects,'projectids'=>$projectids]);

	}

}
