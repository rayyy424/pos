<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\CommonController;

class ApprovalController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */

	public function Index($type)
	{

		$me = (new CommonController)->get_current_user();

		if ($me -> Web_Path=="")
		{
				$me -> Web_Path = URL::to('/') ."/img/default-user.png" ;
		}

    $countries = DB::table('options')
    ->select('options.Id','options.Table','options.Field','options.Option')
    ->where('options.Field', '=','Country')
    ->orderBy('options.Option', 'asc')
    ->get();

    $projects = DB::table('projects')
		->where('projects.Project_Name','like','%Department%')
    ->get();

    $approvalsettings=DB::table('approvalsettings')
    ->select('approvalsettings.Id','approvalsettings.Type','users.Name as Approver','approvalsettings.Level','projects.Project_Name')
    ->leftJoin('projects', 'approvalsettings.ProjectId', '=', 'projects.Id')
    ->leftJoin('users', 'approvalsettings.UserId', '=', 'users.Id')
    ->where('approvalsettings.Type', '=',$type)
    ->get();

		$missedproject=DB::table('projects')
		->select(DB::raw('COUNT(*) AS COUNT'))
		->whereNotIn('projects.Id', function($q) use ($type){
          $q->select('approvalsettings.ProjectId')
            ->from('approvalsettings')
						->where('approvalsettings.Type', '=',$type);
      })
		->first();

		if($type=="Claim")
		{

			$approver = DB::table('users')
			->select('users.Id','users.Name')
			->leftJoin('accesscontroltemplates', 'accesscontroltemplates.Id', '=', 'users.AccessControlTemplateId')
			->orderBy('Name','asc')
			->where('accesscontroltemplates.Approve_Claim', '=','1')
			->get();

		}
		else if($type=="Timesheet")
		{

			$approver = DB::table('users')
			->select('users.Id','users.Name')
			->leftJoin('accesscontroltemplates', 'accesscontroltemplates.Id', '=', 'users.AccessControlTemplateId')
			->orderBy('Name','asc')
			->where('accesscontroltemplates.Approve_Timesheet', '=','1')
			->get();

		}else if($type=="Leave")
		{

			$approver = DB::table('users')
			->select('users.Id','users.Name')
			->leftJoin('accesscontroltemplates', 'accesscontroltemplates.Id', '=', 'users.AccessControlTemplateId')
			->orderBy('Name','asc')
			->where('accesscontroltemplates.Approve_Leave', '=','1')
			->get();

		}else if($type=="Deduction")
		{

			$approver = DB::table('users')
			->select('users.Id','users.Name')
			->leftJoin('accesscontroltemplates', 'accesscontroltemplates.Id', '=', 'users.AccessControlTemplateId')
			->orderBy('Name','asc')
			// ->where('accesscontroltemplates.Approve_Timesheet', '=','1')
			->get();

		}else if($type=="Loan")
        {

            $approver = DB::table('users')
            ->select('users.Id','users.Name')
            ->leftJoin('accesscontroltemplates', 'accesscontroltemplates.Id', '=', 'users.AccessControlTemplateId')
            ->orderBy('Name','asc')
            // ->where('accesscontroltemplates.Approve_Timesheet', '=','1')
            ->get();

        }else if($type=="Request")
        {

            $approver = DB::table('users')
            ->select('users.Id','users.Name')
            ->leftJoin('accesscontroltemplates', 'accesscontroltemplates.Id', '=', 'users.AccessControlTemplateId')
            ->orderBy('Name','asc')
            // ->where('accesscontroltemplates.Approve_Timesheet', '=','1')
            ->get();

				}
				// else if($type == "MR"){
				// 	$approver = DB::table('users')
        //     ->select('users.Id','users.Name')
        //     ->leftJoin('accesscontroltemplates', 'accesscontroltemplates.Id', '=', 'users.AccessControlTemplateId')
        //     ->orderBy('Name','asc')
        //     // ->where('accesscontroltemplates.Approve_Timesheet', '=','1')
        //     ->get();
				// }


		// $category=['Claim','Leave','Deduction','Timesheet','Loan','Request','MR'];
		$category=['Claim','Leave','Deduction','Timesheet','Loan','Request'];

		return view('approval', ['me' => $me,'type'=>$type,'approvalsettings' => $approvalsettings,'approver' =>$approver,'category' =>$category,'countries' =>$countries,'projects' =>$projects,'missedproject'=>$missedproject]);

	}

	public function missedproject($type)
	{
		$missedproject=DB::table('projects')
		->select('projects.Project_Name')
		->whereNotIn('projects.Id', function($q) use ($type){
          $q->select('approvalsettings.ProjectId')
            ->from('approvalsettings')
						->where('approvalsettings.Type', '=',$type);
      })
		->get();

		return json_encode($missedproject);
	}

}
