<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class MonitoringController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
			$me = (new CommonController)->get_current_user();
            //$users = DB::table('users')->select('Id','Name','Email','Contact No','Address','DOB','IC No','Gender','Marital Status','Position','Emergency Contact Person','Emergency Contact No','Emergency Contact Relationship',
						//'Emergency Contact Address')
						$byuser = DB::table('pctrackings')
            ->leftJoin('users', 'users.Id', '=','pctrackings.UserId' )
            ->distinct()
            ->select('users.Id','users.StaffId','users.Name')
						->orderBy('users.Name')
						->where('users.Id','<>','0')
						->get();

            $byprogram = DB::table('pctrackings')
            ->select(DB::raw('`Process_Name`,count(`Process_Name`) as Count'))
                     ->groupBy('Process_Name')
                     ->orderBy(DB::raw('count(`Process_Name`)'),'desc')
                     ->get();

						//$column = $users[0];

            return view('monitoring', ['me' => $me, 'byuser' => $byuser, 'byprogram' => $byprogram]);

	}

  public function useractivity($Id,$start = null, $end = null)
	{
		$me = (new CommonController)->get_current_user();

		if ($start==null)
		{

			$start=date('d-M-Y', strtotime('today'));
		}

		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('today'));

		}
            //$users = DB::table('users')->select('Id','Name','Email','Contact No','Address','DOB','IC No','Gender','Marital Status','Position','Emergency Contact Person','Emergency Contact No','Emergency Contact Relationship',
						//'Emergency Contact Address')
						$byuser = DB::table('pctrackings')
            ->leftJoin('users', 'users.Id', '=','pctrackings.UserId' )
            ->distinct()
            ->select('users.Id','users.Name','Process_Name','Title','Active_Inactive','Date_Time')
						->where(DB::raw('str_to_date(pctrackings.Date_Time,"%d-%m-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
						->where(DB::raw('str_to_date(pctrackings.Date_Time,"%d-%m-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
						->orderBy('pctrackings.Id')
            ->where('users.Id', '=',$Id)
						->get();



            return view('useractivity', ['me' => $me, 'UserId'=>$Id,'byuser' => $byuser, 'start' => $start,'end' =>$end]);

	}

}
