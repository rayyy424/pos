<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use DateTime;
use Carbon\Carbon;
class TimesheetController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function mytimesheet($start = null, $end = null)
	{

		$me = (new CommonController)->get_current_user();
		// $auth = Auth::user();
		//
		// $me = DB::table('users')
		// ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By TargetId) as max'), 'max.TargetId', '=', 'users.Id')
		// ->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		// // ->leftJoin('accesscontrols', 'accesscontrols.UserId', '=', 'users.Id')
		// ->where('users.Id', '=',$auth -> Id)
		// ->first();

		// $showleave = DB::table('leaves')
		// ->leftJoin('leavestatuses', 'leaves.Id', '=', 'leavestatuses.LeaveId')
		// ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
		// ->leftJoin('accesscontrols', 'accesscontrols.UserId', '=', 'applicant.Id')
		// ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
		// ->select('leaves.Id','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.Reason','leaves.created_at as Application_Date','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.updated_at as Review_Date','leavestatuses.Comment')
		// ->where('accesscontrols.Show_Leave_To_Public', '=', 1)
		// ->orderBy('leaves.Id','desc')
		// ->get();
		$d=date('d');

		if ($start==null)
		{

			if($d>=21)
			{

				$start=date('d-M-Y', strtotime('first day of this month'));
				$start = date('d-M-Y', strtotime($start . " +20 days"));

			}
			else {

				$start=date('d-M-Y', strtotime('first day of last month'));
				$start = date('d-M-Y', strtotime($start . " +20 days"));

			}
		}

		if ($end==null)
		{
			if($d>=21)
			{

				$end=date('d-M-Y', strtotime('first day of next month'));
				$end = date('d-M-Y', strtotime($end . " +19 days"));
			}
			else {

				$end=date('d-M-Y', strtotime('first day of this month'));
				$end = date('d-M-Y', strtotime($end . " +19 days"));

			}

		}

		$mytimesheet = DB::table('timesheets')
		->select('timesheets.Id','timesheets.UserId','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheets.Date',DB::raw('"" as Day'),'holidays.Holiday','timesheets.Site_Name','timesheets.Check_In_Type','leaves.Leave_Type','leavestatuses.Leave_Status',
		'timesheets.Time_In','timesheets.Time_Out','timesheets.Leader_Member','timesheets.Next_Person','projectcodes.Project_Code','projects.Project_Name','timesheets.State','timesheets.Work_Description','timesheets.Remarks','approver.Name as Approver','timesheetstatuses.Status','leaves.Leave_Type','timesheetstatuses.Comment','timesheetstatuses.updated_at as Updated_At','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Timesheet" Group By TargetId) as maxfile'), 'maxfile.TargetId', '=', 'timesheets.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('maxfile.`maxid` and files.`Type`="Timesheet"'))
		->leftJoin('projectcodes', 'timesheets.Project_Code_Id', '=', 'projectcodes.Id')
		->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
		// ->leftJoin('leaves','leaves.UserId','=',DB::raw('timesheets.Id AND str_to_date(timesheets.Date,"%d-%M-%Y") Between str_to_date(leaves.Start_Date,"%d-%M-%Y") and str_to_date(leaves.End_Date,"%d-%M-%Y")'))
		->leftJoin('leaves','leaves.UserId','=',DB::raw($me->UserId.' AND str_to_date(timesheets.Date,"%d-%M-%Y") Between str_to_date(leaves.Start_Date,"%d-%M-%Y") and str_to_date(leaves.End_Date,"%d-%M-%Y")'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as maxleave'), 'maxleave.LeaveId', '=', 'leaves.Id')
		->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('maxleave.`maxid`'))
		->leftJoin('holidays',DB::raw('1'),'=',DB::raw('1 AND str_to_date(timesheets.Date,"%d-%M-%Y") Between str_to_date(holidays.Start_Date,"%d-%M-%Y") and str_to_date(holidays.End_Date,"%d-%M-%Y")'))
		->where('timesheets.UserId', '=', $me->UserId)
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->orderBy('timesheets.Id','desc')
		->get();

		$user = DB::table('users')->select('users.Id','StaffId','Name','Password','User_Type','Company_Email','Personal_Email','Contact_No_1','Contact_No_2','Nationality','Permanent_Address','Current_Address','Home_Base','DOB','NRIC','Passport_No','Gender','Marital_Status','Department','Position','Emergency_Contact_Person',
		'Emergency_Contact_No','Emergency_Contact_Relationship','Emergency_Contact_Address','files.Web_Path')
		// ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->where('users.Id', '=', $me->UserId)
		->first();

		$arrDate = array();
		$arrDate2 = array();
		$arrInsert = array();

		foreach ($mytimesheet as $timesheet) {
			# code...
			array_push($arrDate,$timesheet->Date);
		}

		$startTime = strtotime($start);
		$endTime = strtotime($end);

		// Loop between timestamps, 1 day at a time
		do {

			 if (!in_array(date('d-M-Y', $startTime),$arrDate))
			 {
				 array_push($arrDate2,date('d-M-Y', $startTime));
			 }

			 $startTime = strtotime('+1 day',$startTime);

		} while ($startTime <= $endTime);

		foreach ($arrDate2 as $date) {
			# code...
			array_push($arrInsert,array('UserId'=>$me->UserId, 'Date'=> $date, 'updated_at'=>date('Y-m-d H:i:s')));

		}

		DB::table('timesheets')->insert($arrInsert);

		$projects = DB::table('projects')
		->select('projects.Id','projects.Project_Name','users.Name')
		->leftJoin('users','users.Id','=','projects.Project_Manager')
		->get();

		$projectcodes = DB::table('projectcodes')
		->get();

		$options= DB::table('options')
		->whereIn('Table', ["users","timesheets"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

      // $mytimesheet = DB::table('timesheets')
      // ->leftJoin('users as submitter', 'timesheets.UserId', '=', 'submitter.Id')
      // ->select('timesheets.Id','submitter.Name','timesheets.Timesheet_Name','timesheets.Date','timesheets.Remarks')
      // ->where('timesheets.UserId', '=', $me->UserId)
			// ->orderBy('timesheets.Id','desc')
      // ->get();

			// $hierarchy = DB::table('users')
			// ->select('L2.Id as L2Id','L2.Name as L2Name','L2.Timesheet_1st_Approval as L21st','L2.Timesheet_2nd_Approval as L22nd',
			// 'L3.Id as L3Id','L3.Name as L3Name','L3.Timesheet_1st_Approval as L31st','L3.Timesheet_2nd_Approval as L32nd')
			// ->leftJoin(DB::raw("(select users.Id,users.Name,users.SuperiorId,accesscontrols.Timesheet_1st_Approval,accesscontrols.Timesheet_2nd_Approval,accesscontrols.Timesheet_Final_Approval from users left join accesscontrols on users.Id=accesscontrols.UserId) as L2"),'L2.Id','=','users.SuperiorId')
			// ->leftJoin(DB::raw("(select users.Id,users.Name,users.SuperiorId,accesscontrols.Timesheet_1st_Approval,accesscontrols.Timesheet_2nd_Approval,accesscontrols.Timesheet_Final_Approval from users left join accesscontrols on users.Id=accesscontrols.UserId) as L3"),'L3.Id','=','L2.SuperiorId')
			// ->where('users.Id', '=', $me->UserId)
			// ->get();
			//
			// $final = DB::table('users')
			// ->select('users.Id','users.Name')
			// ->leftJoin('accesscontrols', 'accesscontrols.UserId', '=', 'users.Id')
			// ->where('Timesheet_Final_Approval', '=', 1)
			// ->get();

			return view('mytimesheet', ['me' => $me, 'user' =>$user, 'mytimesheet' => $mytimesheet,'start' =>$start,'end'=>$end, 'projects' =>$projects,'projectcodes' =>$projectcodes,'options' =>$options]);

			//return view('mytimesheet', ['me' => $me,'showleave' =>$showleave, 'mytimesheet' => $mytimesheet, 'hierarchy' => $hierarchy, 'final' => $final]);

	}

	public function mytimesheet2()
	{

		$me = (new CommonController)->get_current_user();

		$users=DB::table('users')
		->orderBy('Id','ASC')
		->get();

		$start='01-Nov-2017';
		$end='30-Nov-2017';

		foreach ($users as $user) {
			# code...

			$mytimesheet = DB::table('timesheets')
			->select('timesheets.Id','timesheets.UserId','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheets.Date',DB::raw('"" as Day'),'timesheets.Check_In_Type',
			'timesheets.Time_In','timesheets.Time_Out','timesheets.Leader_Member','timesheets.Next_Person','projectcodes.Project_Code','projects.Project_Name','timesheets.Site_Name','timesheets.State','timesheets.Work_Description','timesheets.Remarks','approver.Name as Approver','timesheetstatuses.Status','timesheetstatuses.Comment','timesheetstatuses.updated_at as Updated_At','files.Web_Path')
			->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Timesheet" Group By TargetId) as maxfile'), 'maxfile.TargetId', '=', 'timesheets.Id')
			->leftJoin('files', 'files.Id', '=', DB::raw('maxfile.`maxid` and files.`Type`="Timesheet"'))
			->leftJoin('projectcodes', 'timesheets.Project_Code_Id', '=', 'projectcodes.Id')
			->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
			->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
			->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
			->where('timesheets.UserId', '=', $user->Id)
			->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
			->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
			->orderBy('timesheets.Id','desc')
			->get();

			$arrDate = array();
			$arrDate2 = array();
			$arrInsert = array();

			foreach ($mytimesheet as $timesheet) {
				# code...
				array_push($arrDate,$timesheet->Date);
			}

			$startTime = strtotime($start);
			$endTime = strtotime($end);

			// Loop between timestamps, 1 day at a time
			do {

				 if (!in_array(date('d-M-Y', $startTime),$arrDate))
				 {
					 array_push($arrDate2,date('d-M-Y', $startTime));
				 }

				 $startTime = strtotime('+1 day',$startTime);

			} while ($startTime <= $endTime);

			foreach ($arrDate2 as $date) {
				# code...
				array_push($arrInsert,array('UserId'=>$user->Id, 'Date'=> $date, 'updated_at'=>date('Y-m-d H:i:s')));

			}

			DB::table('timesheets')->insert($arrInsert);

		}

			return 1;

	}

	public function calculateallowance(Request $request)
	{

		$me = (new CommonController)->get_current_user();

		$input = $request->all();

		$Id=$input["TimesheetId"];
		$Date=$input["D"];
		$Time_In=$input["Time_In"];
		$Time_Out=$input["Time_Out"];
		$State=$input["State"];
		$Check_In_Type=$input["Check_In_Type"];
		$ProjectId=$input["ProjectId"];
		$Home_Base=$input["State"];

		$holiday=DB::table('holidays')
		->where(DB::raw('str_to_date(holidays.Start_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$Date.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(holidays.End_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$Date.'","%d-%M-%Y")'))
		->get();

		$timesheetdate=new DateTime($Date);

		if (count($holiday)>0)
		{
			$day_type="Public Holiday";
		}
		else {

			$day_type=$timesheetdate->format('l');
		}

		$allowance=0;

		// $threemonth=date($me->Joining_Date, strtotime('+3 months'));
		// $twoweek=date($me->Joining_Date, strtotime('+2 weeks'));
		//
		// $today=date("d-M-Y");
		//
		// if($threemonth>=$today && $user->Scheme_Name=="Engineer Scheme")
		// {
		// 	//engineer within 3 month technician rate
		// 	$usetechnicianscheme=true;
		//
		// }
		// else if($twoweek>=$today && $user->Scheme_Name=="Engineer Scheme")
		// {
		// 	//2 weeks no allowance
		// 	$result= DB::table('timesheets')
		// 				->where('Id', $input["TimesheetId"])
		// 				->update(array(
		// 				'Allowance' =>  0,
		// 			));
		//
		// 			return 0;
		//
		// }

		switch ($day_type)
		{

			case "Monday":
			case "Tuesday":
			case "Wednesday":
			case "Thursday":
			case "Friday":
				$schemeitems = DB::table('users')
				->leftJoin('allowanceschemes', 'users.AllowanceSchemeId', '=', 'allowanceschemes.Id')
				->leftJoin('allowanceschemeitems', 'allowanceschemes.Id', '=', 'allowanceschemeitems.AllowanceSchemeId')
				->select('allowanceschemeitems.Id','allowanceschemeitems.AllowanceSchemeId','allowanceschemeitems.Day_Type','allowanceschemeitems.Start','allowanceschemeitems.End','allowanceschemeitems.Minimum_Hour','allowanceschemeitems.Currency','allowanceschemeitems.Home_Base','allowanceschemeitems.Outstation','allowanceschemeitems.Subsequent_Home_Base','allowanceschemeitems.Subsequent_Outstation','allowanceschemeitems.Remarks')
				->orderBy('allowanceschemeitems.Day_Type','asc')
				->orderBy(db::raw('str_to_date(allowanceschemeitems.Start,"%h:%i:%s %p")'),'asc')
				->where('allowanceschemeitems.Day_Type', '=',"Weekday")
				->where('users.Id', '=',$me->UserId)
				->get();
				break;

			case "Saturday":
				$schemeitems = DB::table('users')
				->leftJoin('allowanceschemes', 'users.AllowanceSchemeId', '=', 'allowanceschemes.Id')
				->leftJoin('allowanceschemeitems', 'allowanceschemes.Id', '=', 'allowanceschemeitems.AllowanceSchemeId')
				->select('allowanceschemeitems.Id','allowanceschemeitems.AllowanceSchemeId','allowanceschemeitems.Day_Type','allowanceschemeitems.Start','allowanceschemeitems.End','allowanceschemeitems.Minimum_Hour','allowanceschemeitems.Currency','allowanceschemeitems.Home_Base','allowanceschemeitems.Outstation','allowanceschemeitems.Subsequent_Home_Base','allowanceschemeitems.Subsequent_Outstation','allowanceschemeitems.Remarks')
				->orderBy('allowanceschemeitems.Day_Type','asc')
				->orderBy(db::raw('str_to_date(allowanceschemeitems.Start,"%h:%i:%s %p")'),'asc')
				->where('users.Id', '=',$me->UserId)
				->where('allowanceschemeitems.Day_Type', '=',"Saturday")
				->get();
				break;

			case "Sunday":
				$schemeitems = DB::table('users')
				->leftJoin('allowanceschemes', 'users.AllowanceSchemeId', '=', 'allowanceschemes.Id')
				->leftJoin('allowanceschemeitems', 'allowanceschemes.Id', '=', 'allowanceschemeitems.AllowanceSchemeId')
				->select('allowanceschemeitems.Id','allowanceschemeitems.AllowanceSchemeId','allowanceschemeitems.Day_Type','allowanceschemeitems.Start','allowanceschemeitems.End','allowanceschemeitems.Minimum_Hour','allowanceschemeitems.Currency','allowanceschemeitems.Home_Base','allowanceschemeitems.Outstation','allowanceschemeitems.Subsequent_Home_Base','allowanceschemeitems.Subsequent_Outstation','allowanceschemeitems.Remarks')
				->orderBy('allowanceschemeitems.Day_Type','asc')
				->orderBy(db::raw('str_to_date(allowanceschemeitems.Start,"%h:%i:%s %p")'),'asc')
				->where('users.Id', '=',$me->UserId)
				->where('allowanceschemeitems.Day_Type', '=',"Sunday")
				->get();
				break;

			case "Public Holiday":
				$schemeitems = DB::table('users')
				->leftJoin('allowanceschemes', 'users.AllowanceSchemeId', '=', 'allowanceschemes.Id')
				->leftJoin('allowanceschemeitems', 'allowanceschemes.Id', '=', 'allowanceschemeitems.AllowanceSchemeId')
				->select('allowanceschemeitems.Id','allowanceschemeitems.AllowanceSchemeId','allowanceschemeitems.Day_Type','allowanceschemeitems.Start','allowanceschemeitems.End','allowanceschemeitems.Minimum_Hour','allowanceschemeitems.Currency','allowanceschemeitems.Home_Base','allowanceschemeitems.Outstation','allowanceschemeitems.Subsequent_Home_Base','allowanceschemeitems.Subsequent_Outstation','allowanceschemeitems.Remarks')
				->orderBy('allowanceschemeitems.Day_Type','asc')
				->orderBy(db::raw('str_to_date(allowanceschemeitems.Start,"%h:%i:%s %p")'),'asc')
				->where('users.Id', '=',$me->UserId)
				->where('allowanceschemeitems.Day_Type', '=',"Public Holiday")
				->get();
				break;

		}

		$In = strtotime($Date ." ".$Time_In);


		// $Out = strtotime($Date ." ".$Time_Out);

		if (strpos(strtoupper($Time_In), 'PM') !== false && strpos(strtoupper($Time_Out), 'AM') !== false)
		{
			$day2=date('d-M-Y',strtotime($Date . "+1 days"));
			$Out = strtotime($day2 ." ".$Time_Out);

		}
		elseif (strpos(strtoupper($Time_In), 'AM') !== false && strpos(strtoupper($Time_Out), 'AM') !== false && strpos(strtoupper($Time_Out), '12:') !== false)
		{

			$day2=date('d-M-Y',strtotime($Date . "+1 days"));
			$Out = strtotime($day2 ." ".$Time_Out);

		}
		elseif (strpos(strtoupper($Time_In), 'PM') !== false && strpos(strtoupper($Time_Out), 'PM') !== false && strpos(strtoupper($Time_Out), '12:') !== false)
		{

			$day2=date('d-M-Y',strtotime($Date . "+1 days"));
			$Out = strtotime($day2 ." ".$Time_Out);

		}
		else {
			# code...
			$day2=$Date;
			$Out = strtotime($day2 ." ".$Time_Out);
		}

		$interval = abs($Out - $In);
		$minutes   = round($interval / 60);
		$duration = floor($minutes/30) * 0.5;

		foreach ($schemeitems as $item) {

			$start= new DateTime($item->Start);
		 	$end= new DateTime($item->End);

			$startampm=(string)($start->format('A'));
			$endampm=(string)($end->format('A'));

			$starttime=date("d-M-Y H:i:s", mktime($start->format('H'), $start->format('i'), 0, $timesheetdate->format('m'), $timesheetdate->format('d'), $timesheetdate->format('Y')));
			$endtime=date("d-M-Y H:i:s", mktime($end->format('H'), $end->format('i'), 0, $timesheetdate->format('m'), $timesheetdate->format('d'), $timesheetdate->format('Y')));

			$starttime2=date("d-M-Y H:i:s", mktime($start->format('H'), $start->format('i'), 0, $timesheetdate->format('m'), $timesheetdate->format('d'), $timesheetdate->format('Y')));
			$endtime2=date("d-M-Y H:i:s", mktime($end->format('H'), $end->format('i'), 0, $timesheetdate->format('m'), $timesheetdate->format('d'), $timesheetdate->format('Y')));

			if (strcmp($startampm,"PM")==0 && strcmp($endampm,"AM")==0)
			{

					$endtime=date('d-M-Y H:i:s',strtotime($endtime . "+1 days"));

					$endtime2=date('d-M-Y H:i:s',strtotime($endtime . "+1 days"));
			}
			elseif (strcmp($startampm,"AM")==0 && strcmp($endampm,"AM")==0)
			{

					$starttime=date('d-M-Y H:i:s',strtotime($starttime));
					$endtime=date('d-M-Y H:i:s',strtotime($endtime));

					$starttime2=date('d-M-Y H:i:s',strtotime($starttime . "+1 days"));
					$endtime2=date('d-M-Y H:i:s',strtotime($endtime . "+1 days"));
			}

			$start=strtotime($starttime);
			$end=strtotime($endtime);

			$start2=strtotime($starttime2);
			$end2=strtotime($endtime2);

			if ($In<=$start && $Out>=$end || $In<=$start2 && $Out>=$end2)
			{

				if ($Home_Base==$me->Home_Base)
				{
					$allowance=$allowance+$item->Home_Base;

				}
				else {
					$allowance=$allowance+$item->Outstation;
				}
			}

		}

		$result= DB::table('timesheets')
					->where('Id', $input["TimesheetId"])
					->update(array(
					'Allowance' =>  $allowance,
				));

				return $allowance;

	}

	public function submitforapproval(Request $request)
	{

		$me = (new CommonController)->get_current_user();
		$emaillist=array();
		array_push($emaillist,$me->UserId);

		$input = $request->all();

		$timesheetIds = explode(",", $input["TimesheetIds"]);

		$timesheets = DB::table('timesheets')
		->select('timesheets.Id','timesheets.UserId As SubmitterId','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheets.Date','timesheets.Leader_Member','timesheets.Next_Person','projectcodes.Project_Code','projects.Project_Name','timesheets.Site_Name','timesheets.State','timesheets.Work_Description','timesheets.Check_In_Type',
		'timesheets.Time_In','timesheets.Time_Out','timesheets.Remarks','approver.Name','timesheetstatuses.UserId','timesheetstatuses.Status','timesheetstatuses.Comment','timesheetstatuses.updated_at as Updated_At')
		->leftJoin('projectcodes', 'timesheets.Project_Code_Id', '=', 'projectcodes.Id')
		->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
		->whereIn('timesheets.Id', $timesheetIds)
		->orderBy('timesheets.Id','desc')
		->get();

		$mylevel = DB::table('approvalsettings')
		->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
		->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
		->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
		->where('approvalsettings.Type', '=', 'Timesheet')
		->where('approvalsettings.UserId', '=', $me->UserId)
		->orderBy('approvalsettings.Country','asc')
		->orderBy('projects.Project_Name','asc')
		// ->orderBy('approvalsettings.Level','Final Approval","5th Approval","4th Approval","3rd Approval","2nd Approval","1st Approval")
		->orderByRaw("FIELD(approvalsettings.Level , 'Final Approval', '5th Approval', '4th Approval','3rd Approval','2nd Approval','1st Approval') ASC")
		->get();

		$approver = DB::table('approvalsettings')
		->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
		->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
		->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
		->where('approvalsettings.Type', '=', 'Timesheet')
		// ->where(function ($query) {
    //             $query->where('approvalsettings.Level', '=', '1st Approval')
    //                   ->orWhere('approvalsettings.Level', '=', 'Final Approval');
    //         })
		// // ->where('approvalsettings.Level', '=', '1st Approval')
		// // ->orWhere('approvalsettings.Level', '=', 'Final Approval')
		->where('approvalsettings.ProjectId', '<>', '0')
		->orderBy('approvalsettings.Country','asc')
		->orderBy('projects.Project_Name','asc')
		->orderByRaw("FIELD(approvalsettings.Level , '1st Approval', '2nd Approval', '3rd Approval','4th Approval','5th Approval','Final Approval') ASC")
		->get();

		// $countryapprover = DB::table('approvalsettings')
		// ->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
		// ->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
		// ->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
		// ->where('approvalsettings.Type', '=', 'Timesheet')
		// // ->orWhere(function ($query) {
    // //             $query->where('approvalsettings.Level', '=', '1st Approval')
    // //                   ->orWhere('approvalsettings.Level', '=', 'Final Approval');
    // //         })
		// // // ->where('approvalsettings.Level', '=', '1st Approval')
		// // // ->orWhere('approvalsettings.Level', '=', 'Final Approval')
		// ->where('approvalsettings.ProjectId', '=', '0')
		// ->orderBy('approvalsettings.Country','asc')
		// ->orderBy('projects.Project_Name','asc')
		// ->orderByRaw("FIELD(approvalsettings.Level , '1st Approval', '2nd Approval', '3rd Approval','4th Approval','5th Approval','Final Approval') ASC")
		// ->get();

		$subscribers = DB::table('notificationtype')
		->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
		->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
		->where('notificationtype.Id','=',32)
		->get();

		$emails = array();

		foreach ($subscribers as $subscriber) {
			$NotificationSubject=$subscriber->Notification_Subject;
			if ($subscriber->Company_Email!="")
			{
				array_push($emails,$subscriber->Company_Email);
			}

			else
			{
				array_push($emails,$subscriber->Personal_Email);
			}

		}

		foreach ($timesheets as $timesheet) {
			# code...

			foreach ($mylevel as $level) {

				if ($level->Project_Name==$timesheet->Project_Name)
				{

					if($level->Level=="Final Approval")
					{
						$level->Level="6 Final Approval";
					}

					break;
				}

			}

			if(!$mylevel)
			{

				$level = (object) ['Id'=>0,'Name'=>"",'Level'=>0,'Country'=>'','Project_Name'=>''];
			}

			$submitted=false;

			foreach ($approver as $user) {

				if($user->Level=="Final Approval")
				{
					$user->Level="6 Final Approval";
				}

					if (!empty($user->Id) && $user->Project_Name==$timesheet->Project_Name && $timesheet->UserId != $user->Id && filter_var($level->Level, FILTER_SANITIZE_NUMBER_INT)<filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT))
					{

						DB::table('timesheetstatuses')->insert(
							['TimesheetId' => $timesheet->Id,
							 'UserId' => $user->Id,
							 'Status' => "Pending Approval"
							]
						);

						DB::table('timesheets')
									->where('Id', '=',$timesheet->Id)
									->update(array(
									'submitted_at' =>  DB::raw('now()'),
								));

						$submitted=true;
						array_push($emaillist,$user->Id);

						break;
					}
					elseif (!empty($user->Id) && $user->Project_Name==$timesheet->Project_Name && $timesheet->SubmitterId == $user->Id && $level->Id == $user->Id && $level->Level=="6 Final Approval")
					{

						DB::table('timesheetstatuses')->insert(
							['TimesheetId' => $timesheet->Id,
							 'UserId' => $user->Id,
							 'Status' => "Pending Approval"
							]
						);

						DB::table('timesheets')
									->where('Id', '=',$timesheet->Id)
									->update(array(
									'submitted_at' =>  DB::raw('now()'),
								));

						$submitted=true;
						array_push($emaillist,$user->Id);

						break;
					}
					elseif (!empty($user->Id) && $user->Project_Name==$timesheet->Project_Name && $timesheet->UserId == $user->Id) {
						# code...
						if(str_contains($timesheet->Status, 'Rejected') || str_contains($timesheet->Status, 'Recalled'))
						{
							DB::table('timesheetstatuses')->insert(
								['TimesheetId' => $timesheet->Id,
								 'UserId' => $user->Id,
								 'Status' => "Pending Approval"
								]
							);

							DB::table('timesheets')
										->where('Id', '=',$timesheet->Id)
										->update(array(
										'submitted_at' =>  DB::raw('now()'),
									));
						}
							$submitted=true;
							array_push($emaillist,$user->Id);
							break;
					}
				}

			// if ($submitted==false)
			// {
			// 			// DB::table('timesheetstatuses')->insert(
			// 			// 	['TimesheetId' => $timesheet->Id,
			// 			// 	 'UserId' => $me->SuperiorId,
			// 			// 	 'Status' => "Pending Approval"
			// 			// 	]
			// 			// );
			// 			// array_push($emaillist,$me->SuperiorId);
			// 			// break;
			//
			// 	foreach ($countryapprover as $user) {
			//
			// 		if ($timesheet->UserId != $user->Id)
			// 		{
			// 			DB::table('timesheetstatuses')->insert(
			// 				['TimesheetId' => $timesheet->Id,
			// 				 'UserId' => $user->Id,
			// 				 'Status' => "Pending Approval"
			// 				]
			// 			);
			// 			array_push($emaillist,$user->Id);
			// 			break;
			// 		}
			// 		elseif ($timesheet->UserId == $user->Id)
			// 		{
			// 			array_push($emaillist,$user->Id);
			// 			break;
			// 		}
			//
			// 	}
			// }

		}

		if (count($emaillist)>1)
		{
			$notify = DB::table('users')
			->whereIn('Id', $emaillist)
			->get();

			$emails = array();

			foreach ($notify as $user) {
				if ($user->Company_Email!="")
				{
					array_push($emails,$user->Company_Email);
				}

				else
				{
					array_push($emails,$user->Personal_Email);
				}

			}

			$timesheets = DB::table('timesheets')
			->select('timesheets.Id','timesheets.UserId','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheets.Date','timesheets.Leader_Member','timesheets.Next_Person','projectcodes.Project_Code','projects.Project_Name','timesheets.Site_Name','timesheets.State','timesheets.Work_Description','timesheets.Check_In_Type',
			'timesheets.Time_In','timesheets.Time_Out','timesheets.Remarks','approver.Name as Approver','timesheetstatuses.UserId','timesheetstatuses.Status','timesheetstatuses.Comment','timesheetstatuses.updated_at as Updated_At')
			->leftJoin('projectcodes', 'timesheets.Project_Code_Id', '=', 'projectcodes.Id')
			->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
			->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
			->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
			->whereIn('timesheets.Id', $timesheetIds)
			->orderBy('timesheets.Date','ASC')
			->get();


			Mail::send('emails.timesheetapproval', ['me' => $me,'timesheets' => $timesheets], function($message) use ($emails,$me,$NotificationSubject)
			{
					$emails = array_filter($emails);
					array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
					$message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');
			});

			return 1;
		}
		else {
			return 0;
		}

	}

	public function mytimesheetdetail($id)
	{
		$auth = Auth::user();

		$me = DB::table('users')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By TargetId) as max'), 'max.TargetId', '=', 'users.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		// ->leftJoin('accesscontrols', 'accesscontrols.UserId', '=', 'users.Id')
		->where('users.Id', '=',$auth -> Id)
		->first();

		$hierarchy = DB::table('users')
		->select('L2.Id as L2Id','L2.Name as L2Name','L2.Timesheet_1st_Approval as L21st','L2.Timesheet_2nd_Approval as L22nd',
		'L3.Id as L3Id','L3.Name as L3Name','L3.Timesheet_1st_Approval as L31st','L3.Timesheet_2nd_Approval as L32nd')
		// ->leftJoin(DB::raw("(select users.Id,users.Name,users.SuperiorId,accesscontrols.Timesheet_1st_Approval,accesscontrols.Timesheet_2nd_Approval,accesscontrols.Timesheet_Final_Approval from users left join accesscontrols on users.Id=accesscontrols.UserId) as L2"),'L2.Id','=','users.SuperiorId')
		// ->leftJoin(DB::raw("(select users.Id,users.Name,users.SuperiorId,accesscontrols.Timesheet_1st_Approval,accesscontrols.Timesheet_2nd_Approval,accesscontrols.Timesheet_Final_Approval from users left join accesscontrols on users.Id=accesscontrols.UserId) as L3"),'L3.Id','=','L2.SuperiorId')
		->where('users.Id', '=', $me->UserId)
		->get();

		$final = DB::table('users')
		->select('users.Id','users.Name')
		->leftJoin('accesscontrols', 'accesscontrols.UserId', '=', 'users.Id')
		->where('Timesheet_Final_Approval', '=', 1)
		->get();

		if ($me -> Web_Path=="")
		{
				$me -> Web_Path = URL::to('/') ."/img/default-user.png" ;
		}

		$mytimesheet = DB::table('timesheets')
		->leftJoin('users as submitter', 'timesheets.UserId', '=', 'submitter.Id')
		->select('timesheets.Id','submitter.Name','timesheets.Timesheet_Name','timesheets.Date','timesheets.Remarks')
		->where('timesheets.Id', '=', $id)
		->orderBy('timesheets.Id','Desc')
		->get();

		$projects = DB::table('projects')
		->get();

		$projectcodes = DB::table('projectcodes')
		->get();

			$mytimesheetdetail = DB::table('timesheetitems')
			->leftJoin('projects', 'timesheetitems.ProjectId', '=', 'projects.Id')
			->leftJoin('projectcodes', 'timesheetitems.Project_Code_Id', '=', 'projectcodes.Id')
			->leftJoin('users as pm', 'projects.Project_Manager', '=', 'pm.Id')
			->leftJoin('timesheetitemstatuses', 'timesheetitems.Id', '=', 'timesheetitemstatuses.TimesheetItemId')
			->leftJoin('users as approver', 'timesheetitemstatuses.UserId', '=', 'approver.Id')
			->select('timesheetitems.Id','timesheetitems.Date','timesheetitems.Leader_Member','projectcodes.Project_Code','projects.Project_Name','timesheetitems.Site_Name','timesheetitems.State','timesheets.Work_Description','pm.Name as Project_Manager','timesheetitems.Check_In_Type','timesheetitems.Time_In','timesheetitems.Time_Out','timesheetitems.Reason','timesheetitems.Remarks','approver.Name AS Approver','timesheetitemstatuses.Status','timesheetitemstatuses.Comment')
			//->select('timesheetitems.Id','timesheetitems.Date_Time','timesheetitems.Leader_Member','timesheetitems.Project_Code','timesheetitems.Project','timesheetitems.Site_Name','timesheetitems.State','timesheetitems.Check_In_Type','timesheetitems.Time_In','timesheetitems.Time_Out','timesheetitems.Allowance','timesheetitems.Reason','timesheetitems.Remarks','timesheetitems.Checked_Date')
			->where('timesheetitems.TimesheetId', '=', $id)
			->orderBy('timesheetitems.Date','Asc')
			->get();

			return view('mytimesheetdetail', ['me' => $me, 'mytimesheet' => $mytimesheet, 'mytimesheetdetail' => $mytimesheetdetail, 'projects' =>$projects,'projectcodes' =>$projectcodes, 'hierarchy' => $hierarchy, 'final' => $final]);
			//return view('mytimesheet', ['me' => $me,'showleave' =>$showleave, 'mytimesheet' => $mytimesheet, 'hierarchy' => $hierarchy, 'final' => $final]);

	}

	public function timesheetdetail($id,$viewall=null,$start = null, $end = null)
	{

		$me = (new CommonController)->get_current_user();

		$user = DB::table('users')->select('users.Id','StaffId','Name','Password','User_Type','Company_Email','Personal_Email','Contact_No_1','Contact_No_2','Nationality','Permanent_Address','Current_Address','Home_Base','DOB','NRIC','Passport_No','Gender','Marital_Status','Department','Position','Emergency_Contact_Person',
		'Emergency_Contact_No','Emergency_Contact_Relationship','Emergency_Contact_Address','Joining_Date','allowanceschemes.Scheme_Name','files.Web_Path')
		// ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
    ->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->leftJoin('allowanceschemes', 'users.AllowanceSchemeId', '=', 'allowanceschemes.Id')
		->where('users.Id', '=', $id)
		->first();

		$projects = DB::table('projects')
		->get();

		$projectcodes = DB::table('projectcodes')
		->get();

		$options= DB::table('options')
		->whereIn('Table', ["users","timesheets"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		if ($viewall==null)
		{
			$viewall=false;
		}

		// $timesheetdetail = DB::table('timesheets')
		// ->select('timesheetstatuses.Id','timesheets.Id as TimesheetId','timesheets.Latitude','timesheets.Longitude','timesheets.Date',DB::raw('"" as Day'),'timesheets.Check_In_Type',
		// 'timesheets.Time_In','timesheets.Time_Out','timesheets.Allowance','timesheets.Leader_Member','timesheets.Next_Person','projectcodes.Project_Code','projects.Project_Name','timesheets.Site_Name','timesheets.State','timesheets.Work_Description','timesheets.Reason','timesheets.Remarks','approver.Name as Approver','timesheetstatuses.Status','timesheetstatuses.Comment','timesheetstatuses.updated_at as Review_Date','files.Web_Path')
		//
		// ->leftJoin('projectcodes', 'timesheets.Project_Code_Id', '=', 'projectcodes.Id')
		// ->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
		// ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Timesheet" Group By TargetId) as maxfile'), 'maxfile.TargetId', '=', 'timesheets.Id')
		// ->leftJoin('files', 'files.Id', '=', DB::raw('maxfile.`maxid` and files.`Type`="Timesheet"'))
		// ->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		// ->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		// ->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
		// ->where('timesheets.UserId', '=', $id)
		// // ->where('timesheetstatuses.UserId', '=', $me->UserId)
		// ->where('timesheets.Date', '>=', $start)
		// ->where('timesheets.Date', '<=', $end)
		// ->orderBy('timesheets.Id','desc')
		// ->get();

		if ($start==null || $end==null)
		{
			$timesheetdetail = DB::table('timesheets')
			->select('timesheetstatuses.Id','timesheets.Id as TimesheetId','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheetstatuses.Status','timesheets.Date',DB::raw('"" as Day'),'timesheets.Check_In_Type',
			 'timesheets.Time_In','timesheets.Time_Out','timesheets.State',DB::raw('"" AS Position'),DB::raw('"" AS Home_Base'),'timesheets.Allowance','timesheets.Monetary_Comp','timesheets.OT1','timesheets.OT2','timesheets.OT3','timesheets.Leader_Member','timesheets.Next_Person','projectcodes.Project_Code','projects.Project_Name','timesheets.Site_Name','timesheets.Work_Description','timesheets.Remarks','approver.Name as Approver','timesheetstatuses.Comment','timesheetstatuses.updated_at as Review_Date','timesheetchecked.Timesheet_Status','timesheetchecked.Payment_Status','checker.Name as Checked_By','timesheetchecked.Updated_At','files.Web_Path')

			 ->leftJoin( DB::raw('(select Max(Id) as maxid2,TimesheetId from timesheetchecked Group By TimesheetId) as max2'), 'max2.TimesheetId', '=', 'timesheets.Id')
			 ->leftJoin('timesheetchecked', 'timesheetchecked.Id', '=', DB::raw('max2.`maxid2`'))

			 ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Timesheet" Group By TargetId) as maxfile'), 'maxfile.TargetId', '=', 'timesheets.Id')
			->leftJoin('files', 'files.Id', '=', DB::raw('maxfile.`maxid` and files.`Type`="Timesheet"'))
			->leftJoin('projectcodes', 'timesheets.Project_Code_Id', '=', 'projectcodes.Id')
			->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
			->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
			->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
			->leftJoin('users as checker', 'timesheetchecked.UserId', '=', 'checker.Id')
			->where('timesheets.UserId', '=', $id)
			->orderBy('timesheets.Id','desc')
			->get();
		}
		else {
			# code...
			$timesheetdetail = DB::table('timesheets')
			->select('timesheetstatuses.Id','timesheets.Id as TimesheetId','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheetstatuses.Status','timesheets.Date',DB::raw('"" as Day'),'timesheets.Check_In_Type',
			 'timesheets.Time_In','timesheets.Time_Out','timesheets.State',DB::raw('"" AS Position'),DB::raw('"" AS Home_Base'),'timesheets.Allowance','timesheets.Monetary_Comp','timesheets.OT1','timesheets.OT2','timesheets.OT3','timesheets.Allowance','timesheets.Leader_Member','timesheets.Next_Person','projectcodes.Project_Code','projects.Project_Name','timesheets.Site_Name','timesheets.Work_Description','timesheets.Remarks','approver.Name as Approver','timesheetstatuses.Comment','timesheetstatuses.updated_at as Review_Date','timesheetchecked.Timesheet_Status','timesheetchecked.Payment_Status','checker.Name as Checked_By','timesheetchecked.Updated_At','files.Web_Path')

			 ->leftJoin( DB::raw('(select Max(Id) as maxid2,TimesheetId from timesheetchecked Group By TimesheetId) as max2'), 'max2.TimesheetId', '=', 'timesheets.Id')
			 ->leftJoin('timesheetchecked', 'timesheetchecked.Id', '=', DB::raw('max2.`maxid2`'))

			 ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Timesheet" Group By TargetId) as maxfile'), 'maxfile.TargetId', '=', 'timesheets.Id')
			->leftJoin('files', 'files.Id', '=', DB::raw('maxfile.`maxid` and files.`Type`="Timesheet"'))
			->leftJoin('projectcodes', 'timesheets.Project_Code_Id', '=', 'projectcodes.Id')
			->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')

			->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
			->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))

			->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
			->leftJoin('users as checker', 'timesheetchecked.UserId', '=', 'checker.Id')

			->where('timesheets.UserId', '=', $id)
			->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
			->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
			->orderBy('timesheets.Id','desc')
			->get();
		}

		$approver = DB::table('users')
		->leftJoin('accesscontroltemplates', 'users.AccessControlTemplateId', '=', 'accesscontroltemplates.Id')
		->select('users.Id','users.Name')
		->where('accesscontroltemplates.Approve_Timesheet', '=', 1)
		->get();

		$mylevel = DB::table('approvalsettings')
		->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
		->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
		->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
		->where('approvalsettings.Type', '=', 'Timesheet')
		->where('approvalsettings.UserId', '=', $me->UserId)
		->orderBy('approvalsettings.Country','asc')
		->orderBy('projects.Project_Name','asc')
		// ->orderBy('approvalsettings.Level','Final Approval","5th Approval","4th Approval","3rd Approval","2nd Approval","1st Approval")
		->orderByRaw("FIELD(approvalsettings.Level , 'Final Approval', '5th Approval', '4th Approval','3rd Approval','2nd Approval','1st Approval') ASC")
		->first();

		$startmonth=date('d-M-Y', strtotime('-5 months'));
		$endmonth = date('d-M-Y', strtotime("today"));

		$startTime = strtotime($startmonth);
		$endTime = strtotime($endmonth);

		$months=array();

		do {

			$month=date("M",$startTime);
			$year=date("Y",$startTime);

			array_push($months,$month." ".$year);
			 $startTime = strtotime('+1 months',$startTime);

		} while ($startTime <= $endTime);

		return view('timesheetdetail', ['me' => $me, 'viewall'=>$viewall,'UserId' =>$id, 'user' =>$user,'start'=>$start,'end'=>$end,'timesheetdetail' => $timesheetdetail,'projects' =>$projects,'projectcodes' =>$projectcodes, 'options' =>$options,'mylevel' => $mylevel,
		'approver' =>$approver,'months'=>$months]);
			//return view('mytimesheet', ['me' => $me,'showleave' =>$showleave, 'mytimesheet' => $mytimesheet, 'hierarchy' => $hierarchy, 'final' => $final]);

	}

	public function engineerlocationtracking($start = null, $end = null, $includeResigned = 'false')
	{
		$me = (new CommonController)->get_current_user();
		$today = date('d-M-Y', strtotime('today'));

		if ($start==null)
		{

			$start=date('d-M-Y', strtotime('today'));
		}

		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('today'));

		}

		$hod = DB::table('projects')
		->select('Project_Name')
		->where('projects.Project_Manager', '=', $me->UserId)
		->get();
		$arrdepartment=array();

		if($hod && !$me->Admin)
		{


			foreach ($hod as $department) {
				# code...
				array_push($arrdepartment,$department->Project_Name);
			}

			$timesheetdetail = DB::table('timesheets')
			->select('timesheets.Id','timesheets.Id as TimesheetId','users.StaffId','users.Name','users.Resignation_Date','users.Company','users.Department','users.Category','users.Position','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheets.Date',DB::raw('"" as Day'),'timesheets.Site_Name','timesheets.Code','users.Available', DB::raw('(TimeDIFF(STR_TO_DATE(Time_Out,"%h:%i %p"),STR_TO_DATE(Time_In,"%h:%i %p"))) as timediff'),'timesheets.total_distance','timesheets.Check_In_Type','leaves.Leave_Type','leavestatuses.Leave_Status',
			 'timesheets.Time_In','timesheets.Time_Out','timesheets.Remarks','timesheets.Deduction','files.Web_Path')
			->leftJoin('users', 'timesheets.UserId', '=', DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")'))
			->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
			->leftJoin('leaves','leaves.UserId','=',DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y") Between str_to_date(leaves.Start_Date,"%d-%M-%Y") and str_to_date(leaves.End_Date,"%d-%M-%Y")'))
			->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as maxleave'), 'maxleave.LeaveId', '=', 'leaves.Id')
			->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('maxleave.`maxid`'))
			->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as maxuser'), 'maxuser.TargetId', '=', 'users.Id')
			->leftJoin('files', 'files.Id', '=', DB::raw('maxuser.`maxid` and files.`Type`="User"'))
			->where('users.Name','<>','')
			// ->whereRaw('str_to_date(users.Resignation_Date,"%d-%M-%Y") > str_to_date("'.$end.'","%d-%M-%Y")')
			->whereIn('users.Department',$arrdepartment)
			->whereNotIn('users.Id',array(855, 883,902))
			->orderBy('users.Name','asc');

			if (! ($includeResigned == 'true')) {
				$timesheetdetail->whereRaw('(users.Resignation_Date = "" OR str_to_date(users.Resignation_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y"))');
			}

			$timesheetdetail = $timesheetdetail->get();

		}
		else {
			# code...
			$timesheetdetail = DB::table('timesheets')
			->select('timesheets.Id','timesheets.Id as TimesheetId','users.StaffId','users.Name','users.Resignation_Date','users.Company','users.Department','users.Category','users.Position','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheets.Date',DB::raw('"" as Day'),'timesheets.Site_Name','timesheets.Code','users.Available',DB::raw('(TimeDIFF(STR_TO_DATE(Time_Out,"%h:%i %p"),STR_TO_DATE(Time_In,"%h:%i %p"))) as timediff'),'timesheets.total_distance','timesheets.Check_In_Type','leaves.Leave_Type','leavestatuses.Leave_Status',
			 'timesheets.Time_In','timesheets.Time_Out','timesheets.Remarks','timesheets.Deduction','files.Web_Path')
			->leftJoin('users', 'timesheets.UserId', '=', DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")'))
			->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
			->leftJoin('leaves','leaves.UserId','=',DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y") Between str_to_date(leaves.Start_Date,"%d-%M-%Y") and str_to_date(leaves.End_Date,"%d-%M-%Y")'))
			->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as maxleave'), 'maxleave.LeaveId', '=', 'leaves.Id')
			->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('maxleave.`maxid`'))
			->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as maxuser'), 'maxuser.TargetId', '=', 'users.Id')
			->leftJoin('files', 'files.Id', '=', DB::raw('maxuser.`maxid` and files.`Type`="User"'))
			->where('users.Name','<>','')
			// ->whereRaw('str_to_date(users.Resignation_Date,"%d-%M-%Y") > str_to_date("'.$end.'","%d-%M-%Y")')
			->whereNotIn('users.Id',array(855, 883,902))
			->orderBy('users.Name','asc');

			if (! ($includeResigned == 'true')) {
				$timesheetdetail->whereRaw('(users.Resignation_Date = "" OR str_to_date(users.Resignation_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y"))');
			}

			$timesheetdetail = $timesheetdetail->get();
		}


		$codes = DB::table('scopeofwork')
		->orderBy('scopeofwork.Code')
		->get();

		return view('engineerlocation', ['me' => $me, 'start'=>$start,'end'=>$end,'timesheetdetail' => $timesheetdetail,'codes'=>$codes, 'includeResigned' => $includeResigned, 'arrdepartment' => $arrdepartment]);
	}

	public function sitevisitsummary($start = null, $end = null,$dept='false',$client='false')
	{
		$me = (new CommonController)->get_current_user();

		if ($start==null)
		{

			$start=date('d-M-Y', strtotime('first day of january this year'));
		}

		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('today'));

		}
		$condition = '1';

		if($client != 'false')
		{
			$condition="radius.Client='".$client."'";
		}

			# code...
			if($dept != 'false')
			{
				$summary = DB::table('timesheets')
				->select('timesheets.Site_Name','timesheets.Code',DB::raw('SEC_TO_TIME(SUM(Time_Diff)) as time'),DB::raw('COUNT(DISTINCT concat(timesheets.Date,timesheets.UserId)) as Visits'))
				->leftJoin('radius','timesheets.Site_Name','=',DB::raw("radius.Location_Name AND timesheets.Code like CONCAT('%', radius.Code ,'%')"))
				->whereRaw('str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")')
				->where('timesheets.Site_Name', '!=','')
				->where('timesheets.Time_In', '!=','')
				->whereRaw('timesheets.UserId IN (select Id from users where Department="'.$dept.'")')
				->whereRaw($condition)
				->orderBy('timesheets.Site_Name','asc')
				->groupBy(DB::raw('TRIM(REPLACE(timesheets.Site_Name, "\n",""))'))
				->groupBy('timesheets.Code')
				->groupBy('radius.Code')
				->get();
			}
			else {
				// code...
				$summary = DB::table('timesheets')
				->select('timesheets.Site_Name','timesheets.Code',DB::raw('SEC_TO_TIME(SUM(Time_Diff)) as time'),DB::raw('COUNT(DISTINCT concat(timesheets.Date,timesheets.UserId)) as Visits'))
				->leftJoin('radius','timesheets.Site_Name','=',DB::raw("radius.Location_Name AND timesheets.Code like CONCAT('%', radius.Code ,'%')"))
				->whereRaw('str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")')
				->where('timesheets.Site_Name', '!=','')
				->where('timesheets.Time_In', '!=','')
				->whereRaw($condition)
				->orderBy('timesheets.Site_Name','asc')
				->groupBy(DB::raw('TRIM(REPLACE(timesheets.Site_Name, "\n",""))'))
				->groupBy('timesheets.Code')
				->get();
			}

			$clients= DB::table('radius')
			->distinct('Client')
			->select('Client')
			->orderBy('Client','asc')
			->get();


			$department = DB::table('users')
			->Distinct('users.Department')
			->select('users.Department')
			->where('users.Department','like','%Department%')
			->get();

		return view('sitevisitsummary', ['me' => $me, 'start'=>$start,'end'=>$end,'summary' => $summary,'department'=>$department,'dept'=>$dept, 'clients' => $clients, 'client' => $client]);
	}

	public function sitevisitdetail($sitename,$code,$start, $end)
	{
		$me = (new CommonController)->get_current_user();

			# code...
			if($code=="null")
			{
				$summary = DB::table('timesheets')
				->select('users.StaffId','users.Name','timesheets.Code','timesheets.Date',DB::raw('DATE_FORMAT(str_to_date(timesheets.Date,"%d-%M-%Y"), "%a") as Day'),'timesheets.Site_Name','timesheets.Check_In_Type',
				 'timesheets.Time_In','timesheets.Time_Out','timesheets.Remarks')
				->leftJoin('users','timesheets.UserId','=','users.Id')
				->whereRaw('str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")')
				->where('timesheets.Site_Name', '=',$sitename)
				->where('timesheets.Code', '=','')
				->groupBy('timesheets.Date','timesheets.UserId')
				->orderBy('timesheets.Id','asc')
				->get();
			}
			else {
				// code...
				$summary = DB::table('timesheets')
				->select('users.StaffId','users.Name','timesheets.Code','timesheets.Date',DB::raw('DATE_FORMAT(str_to_date(timesheets.Date,"%d-%M-%Y"), "%a") as Day'),'timesheets.Site_Name','timesheets.Check_In_Type',
				 'timesheets.Time_In','timesheets.Time_Out','timesheets.Remarks')
				->leftJoin('users','timesheets.UserId','=','users.Id')
				->whereRaw('str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")')
				->where('timesheets.Site_Name', '=',$sitename)
				->where('timesheets.Code', 'like',DB::raw('"%'.$code.'%"'))
				->groupBy('timesheets.Date','timesheets.UserId')
				->orderBy('timesheets.Id','asc')
				->get();
			}


		return view('sitevisitdetail', ['me' => $me, 'summary'=>$summary]);
	}

	public function timesheetmanagement($start = null, $end = null)
	{
		$me = (new CommonController)->get_current_user();

		$d=date('d');

		if ($start==null)
		{

			if($d>=21)
			{

				$start=date('d-M-Y', strtotime('first day of this month'));
				$start = date('d-M-Y', strtotime($start . " +20 days"));

			}
			else {

				$start=date('d-M-Y', strtotime('first day of last month'));
				$start = date('d-M-Y', strtotime($start . " +20 days"));

			}
		}

		if ($end==null)
		{
			if($d>=21)
			{

				$end=date('d-M-Y', strtotime('first day of next month'));
				$end = date('d-M-Y', strtotime($end . " +19 days"));
			}
			else {

				$end=date('d-M-Y', strtotime('first day of this month'));
				$end = date('d-M-Y', strtotime($end . " +19 days"));

			}

		}

		$showleave = DB::table('leaves')
		->leftJoin('leavestatuses', 'leaves.Id', '=', 'leavestatuses.LeaveId')
		->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
		// ->leftJoin('accesscontrols', 'accesscontrols.UserId', '=', 'applicant.Id')
		->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
		->select('leaves.Id','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.Reason','leaves.created_at as Application_Date','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.updated_at as Review_Date','leavestatuses.Comment')
		// ->where('accesscontrols.Show_Leave_To_Public', '=', 1)
		->orderBy('leaves.Id','desc')
		->get();

		$timesheets = DB::table('timesheets')
		->select(DB::raw('submitter.Id,submitter.StaffId as Staff_ID,submitter.Name as Submitter,timesheetstatuses.Status'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as submitter', 'timesheets.UserId', '=', 'submitter.Id')
		->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
		->where('timesheetstatuses.UserId', '=', $me->UserId)
		->orderBy('submitter.Name','asc')
		->groupBy('submitter.Name','timesheetstatuses.Status')
		->get();

		$alltimesheets = DB::table('timesheets')
		->select(DB::raw('submitter.Id,submitter.StaffId as Staff_ID,submitter.Name as Submitter,approver.Name as Approver,timesheetstatuses.Status'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as submitter', 'timesheets.UserId', '=', 'submitter.Id')
		->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->whereNotNull('timesheetstatuses.UserId')
		->orderBy('submitter.Name','asc')
		->groupBy('submitter.Name')
		->get();

		$allfinaltimesheets = DB::table('timesheets')
		->select(DB::raw('submitter.Id,submitter.StaffId as Staff_ID,submitter.Name as Submitter,approver.Name as Approver,timesheetstatuses.Status,SUM(timesheets.Allowance) as Allowance,timesheetchecked.Timesheet_Status,checker.Name as Checked_By,timesheetchecked.Updated_At'))
		->leftJoin( DB::raw('(select Max(Id) as maxid2,TimesheetId from timesheetchecked Group By TimesheetId) as max2'), 'max2.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetchecked', 'timesheetchecked.Id', '=', DB::raw('max2.`maxid2`'))

		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as submitter', 'timesheets.UserId', '=', 'submitter.Id')
		->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
		->leftJoin('users as checker', 'timesheetchecked.UserId', '=', 'checker.Id')
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->where('timesheetstatuses.Status', 'like','%Final Approved%')
		->orderBy('submitter.Name','asc')
		->groupBy('submitter.Name')
		->get();

		return view('timesheetmanagement', ['me' => $me,'showleave' => $showleave, 'alltimesheets' => $alltimesheets,'allfinaltimesheets' => $allfinaltimesheets,'timesheets' => $timesheets,'start'=>$start,'end'=>$end ]);
	}

	public function newtimesheetitemstatus(Request $request)
	{

		$input = $request->all();

		DB::table('timesheetitemstatuses')->insert(
			['TimesheetItemId' => $input["TimesheetItemId"],
			 'UserId' => $input["UserId"],
			 'Status' => $input["Status"]
			]
		);

		return 1;

	}

	public function newtimesheet(Request $request)
	{
		$auth = Auth::user();

		$me = DB::table('users')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By TargetId) as max'), 'max.TargetId', '=', 'users.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->leftJoin('accesscontrols', 'accesscontrols.UserId', '=', 'users.Id')
		->where('users.Id', '=',$auth -> Id)
		->first();

		if ($me -> Web_Path=="")
		{
				$me -> Web_Path = URL::to('/') ."/img/default-user.png" ;
		}

			$input = $request->all();

			$rules = array(
				'Date'     => 'Required',
				'Check_In_Type'       => 'Required',
				'Project_Manager'       => 'Required'
				);

				$messages = array(
					'Date.required'     => 'The Date Time field is required',
					'Project_Manager.required'       => 'The Project Manager field is required',
					'Check_In_Type.required'       => 'The Check In Type field is required'
					);

			$validator = Validator::make($input, $rules,$messages);

			if ($validator->passes())
			{
					DB::table('timesheets')->insert(
						['UserId' => $me->UserId,
						 'Date' => $input["Date"],
						 'Time_In' => $input["Time_In"],
						 'Time_Out' => $input["Time_Out"],
						 'Leader_Member' => $input["Leader_Member"],
						 'Project_Code_Id' => $input["Project_Code_Id"],
						 'Project' => $input["Project"],
						 'Site_Name' => $input["Site_Name"],
						 'State' => $input["State"],
						 'Project_Manager' => $input["Project_Manager"],
						 'Check_In_Type' => $input["Check_In_Type"],
						 'Reason' => $input["Reason"],
						 'Remarks' => $input["Remarks"]
					 	]
					);

					return 1;
			}
			else {

				return json_encode($validator->errors()->toArray());
			}


	}

	public function submit(Request $request)
	{

		$me = (new CommonController)->get_current_user();
		$emaillist=array();
		$timesheetids=array();
		array_push($emaillist,$me->UserId);

		$input = $request->all();

		$Ids = explode(",", $input["StatusIds"]);

		$timesheets = DB::table('timesheets')
		->select('timesheets.Id','timesheets.UserId as SubmitterId','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheets.Date','timesheets.Leader_Member','timesheets.Next_Person','projectcodes.Project_Code','projects.Project_Name','timesheets.Site_Name','timesheets.State','timesheets.Work_Description','timesheets.Check_In_Type',
		'timesheets.Time_In','timesheets.Time_Out','timesheets.Remarks','approver.Name','timesheetstatuses.UserId','timesheetstatuses.Status','timesheetstatuses.Comment','timesheetstatuses.updated_at as Updated_At')
		->leftJoin('projectcodes', 'timesheets.Project_Code_Id', '=', 'projectcodes.Id')
		->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
		->whereIn('timesheetstatuses.Id', $Ids)
		->orderBy('timesheets.Date','asc')
		->get();

		$approver = DB::table('approvalsettings')
		->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
		->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
		->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
		->where('approvalsettings.Type', '=', 'Timesheet')
		->where('approvalsettings.ProjectId', '<>', '0')
		->orderBy('approvalsettings.Country','asc')
		->orderBy('projects.Project_Name','asc')
		->orderByRaw("FIELD(approvalsettings.Level , '1st Approval','2nd Approval','3rd Approval','4th Approval','5th Approval','Final Approval') ASC")
		->get();

		// $countryapprover = DB::table('approvalsettings')
		// ->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
		// ->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
		// ->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
		// ->where('approvalsettings.Type', '=', 'Timesheet')
		// ->where('approvalsettings.ProjectId', '=', '0')
		// ->orderBy('approvalsettings.Country','asc')
		// ->orderBy('projects.Project_Name','asc')
		// ->orderByRaw("FIELD(approvalsettings.Level , '1st Approval','2nd Approval','3rd Approval','4th Approval','5th Approval','Final Approval') ASC")
		// ->get();

		$final=false;

		foreach ($timesheets as $timesheet) {
			# code...
			$submitted=false;
			array_push($emaillist,$timesheet->UserId);
			array_push($timesheetids,$timesheet->Id);

			if ((strpos($timesheet->Status, 'Rejected') === false) && $timesheet->Status!="Final Approved")
			{

				foreach ($approver as $user) {

						if (!empty($user->Id) && $user->Project_Name==$timesheet->Project_Name && $timesheet->UserId != $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($timesheet->Status, FILTER_SANITIZE_NUMBER_INT))
						{

							DB::table('timesheetstatuses')->insert(
								['TimesheetId' => $timesheet->Id,
								 'UserId' => $user->Id,
								 'Status' => "Pending Approval"
								]
							);
							$submitted=true;
							array_push($emaillist,$user->Id);

							break;
						}
						elseif (!empty($user->Id) && $user->Project_Name==$timesheet->Project_Name && $timesheet->UserId == $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($timesheet->Status, FILTER_SANITIZE_NUMBER_INT))
						{
							# code...
								$submitted=true;
								array_push($emaillist,$user->Id);
						}
						elseif (!empty($user->Id) && $user->Project_Name==$timesheet->Project_Name && $timesheet->UserId != $user->Id && $user->Level=="Final Approval")
						{

							DB::table('timesheetstatuses')->insert(
								['TimesheetId' => $timesheet->Id,
								 'UserId' => $user->Id,
								 'Status' => "Pending Approval"
								]
							);
							$submitted=true;
							array_push($emaillist,$user->Id);

							break;
						}
					}

				// if ($submitted==false)
				// {
				// 	foreach ($countryapprover as $user)
				// 	{
				//
				// 		if (!empty($user->Id) && $timesheet->UserId != $user->Id  && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($timesheet->Status, FILTER_SANITIZE_NUMBER_INT))
				// 		{
				// 			DB::table('timesheetstatuses')->insert(
				// 				['TimesheetId' => $timesheet->Id,
				// 				 'UserId' => $user->Id,
				// 				 'Status' => "Pending Approval"
				// 				]
				// 			);
				// 			array_push($emaillist,$user->Id);
				// 			break;
				// 		}
				// 		elseif (!empty($user->Id) && $timesheet->UserId == $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($timesheet->Status, FILTER_SANITIZE_NUMBER_INT))
				// 		{
				// 			array_push($emaillist,$user->Id);
				// 			break;
				// 		}
				// 		elseif (!empty($user->Id) && $timesheet->UserId != $user->Id && $user->Level=="Final Approval")
				// 		{
				//
				// 			DB::table('timesheetstatuses')->insert(
				// 				['TimesheetId' => $timesheet->Id,
				// 				 'UserId' => $user->Id,
				// 				 'Status' => "Pending Approval"
				// 				]
				// 			);
				// 			$submitted=true;
				// 			array_push($emaillist,$user->Id);
				//
				// 			break;
				// 		}
				//
				// 	}
				// }

			}
			elseif ((strpos($timesheet->Status, 'Rejected') !== false))
			{

				array_push($emaillist,$claim->UserId);
			}
			elseif ($timesheet->Status=="Final Approved" || $timesheet->Status=="Final Rejected")
			{
				$final=true;
				array_push($emaillist,$timesheet->UserId);
			}

		}

		if (count($emaillist)>0)
		{

			$notify = DB::table('users')
			->whereIn('Id', $emaillist)
			->get();

			if($final)
			{

				$subscribers = DB::table('notificationtype')
				->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
				->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
				->where('notificationtype.Id','=',41)
				->get();

			}
			else {
				# code...

				$subscribers = DB::table('notificationtype')
				->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
				->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
				->where('notificationtype.Id','=',33)
				->get();
			}

			$emails = array();

			foreach ($subscribers as $subscriber) {
				$NotificationSubject=$subscriber->Notification_Subject;
				if ($subscriber->Company_Email!="")
				{
					array_push($emails,$subscriber->Company_Email);
				}

				else
				{
					array_push($emails,$subscriber->Personal_Email);
				}

			}

			foreach ($notify as $user) {
				if ($user->Company_Email!="")
				{
					array_push($emails,$user->Company_Email);
				}

				else
				{
					array_push($emails,$user->Personal_Email);
				}

			}

			$timesheets = DB::table('timesheets')
			->select('timesheets.Id','timesheets.UserId','submitter.Name as Submitter','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheets.Date','timesheets.Leader_Member','timesheets.Next_Person','projectcodes.Project_Code','projects.Project_Name','timesheets.Site_Name','timesheets.State','timesheets.Work_Description','timesheets.Check_In_Type',
			'timesheets.Time_In','timesheets.Time_Out','timesheets.Remarks','approver.Name as Approver','timesheetstatuses.UserId','timesheetstatuses.Status','timesheetstatuses.Comment','timesheetstatuses.updated_at as Updated_At')
			->leftJoin('projectcodes', 'timesheets.Project_Code_Id', '=', 'projectcodes.Id')
			->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
			->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
			->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
			->leftJoin('users as submitter', 'timesheets.UserId', '=', 'submitter.Id')
			->whereIn('timesheets.Id', $timesheetids)
			->orderBy('timesheets.Date','asc')
			->get();

			Mail::send('emails.timesheetapproval2', ['me' => $me,'timesheets' => $timesheets], function($message) use ($emails,$timesheets,$NotificationSubject)
			{
					$emails = array_filter($emails);
					array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
					$message->to($emails)->subject($NotificationSubject.' ['.$timesheets[0]->Submitter.']');

			});

			return 1;
		}
		else {
			return 0;
		}

	}

	public function approve(Request $request)
	{

		$me = (new CommonController)->get_current_user();

		$input = $request->all();

		$Ids = explode(",", $input["StatusIds"]);

		$timesheets = DB::table('timesheets')
		->select('timesheets.Id','timesheetstatuses.Id as StatusId','timesheetstatuses.UserId')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->whereIn('timesheetstatuses.Id', $Ids)
		->orderBy('timesheets.Date','asc')
		->get();

		foreach ($timesheets as $timesheet) {
			# code...
			if ($timesheet->UserId!=$me->UserId)
			{
				$id=DB::table('timesheetstatuses')->insertGetId(
					['TimesheetId' => $timesheet->Id,
					 'UserId' => $me->UserId,
					 'Status' => $input["Status"],
					 'updated_at' => DB::raw('now()')
					]
				);


			}
			else {

				$result= DB::table('timesheetstatuses')
							->where('Id', '=',$timesheet->StatusId)
							->update(array(
							'Status' =>  $input["Status"],
						));

			}
		}

		return 1;

	}

	public function updatechecked(Request $request)
	{

		$me = (new CommonController)->get_current_user();

		$input = $request->all();

		$Ids = explode(",", $input["StatusIds"]);

		$timesheets = DB::table('timesheets')
		->select('timesheets.Id','timesheetstatuses.Id as StatusId','timesheetstatuses.UserId')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->whereIn('timesheetstatuses.Id', $Ids)
		->orderBy('timesheets.Date','asc')
		->get();

		foreach ($timesheets as $timesheet) {
			# code...

			if($input["Status"]=="Reset")
			{
				DB::table('timesheetchecked')
				->where('TimesheetId', '=', $timesheet->Id)
				->delete();

			}
			else {
				$id=DB::table('timesheetchecked')->insertGetId(
					['TimesheetId' => $timesheet->Id,
					 'UserId' => $me->UserId,
					 'Timesheet_Status' => $input["Status"],
					 'Updated_At' => DB::raw('now()')
					]
				);

			}

		}

		return 1;

	}

	public function updatechecked2(Request $request)
	{

		$me = (new CommonController)->get_current_user();

		$input = $request->all();

		$Ids = explode(",", $input["StatusIds"]);

		$timesheets = DB::table('timesheets')
		->select('timesheets.Id','timesheetstatuses.Id as StatusId','timesheetstatuses.UserId','timesheetchecked.Id as TimesheetcheckedId')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))

		->leftJoin( DB::raw('(select Max(Id) as maxid2,TimesheetId from timesheetchecked Group By TimesheetId) as max2'), 'max2.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetchecked', 'timesheetchecked.Id', '=', DB::raw('max2.`maxid2`'))

		->whereIn('timesheetstatuses.Id', $Ids)
		->orderBy('timesheets.Date','asc')
		->get();

		foreach ($timesheets as $timesheet) {
			# code...

			if($input["Status"]=="Reset")
			{
				// DB::table('timesheetchecked')
				// ->where('TimesheetId', '=', $timesheet->Id)
				// ->delete();

				DB::table('timesheetchecked')
							->where('Id', $timesheet->TimesheetcheckedId)
							->update(array(
							'Payment_Status' =>  "",
							'UserId2' =>$me->UserId
						));

			}
			else {
				// $id=DB::table('timesheetchecked')->insertGetId(
				// 	['TimesheetId' => $timesheet->Id,
				// 	 'UserId' => $me->UserId,
				// 	 'Timesheet_Status' => $input["Status"],
				// 	 'Updated_At' => DB::raw('now()')
				// 	]
				// );

				DB::table('timesheetchecked')
							->where('Id', $timesheet->TimesheetcheckedId)
							->update(array(
							'Payment_Status' => $input["PaymentStatus"] ,
							'UserId2' =>$me->UserId
						));

			}

		}

		return 1;

	}

	public function redirect(Request $request)
	{

		$arrTimesheetId = array();

		$me = (new CommonController)->get_current_user();

		$input = $request->all();

		$Ids = explode(",", $input["StatusIds"]);

		$timesheets = DB::table('timesheets')
		->select('timesheets.Id','timesheets.UserId','submitter.Name as Submitter','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheets.Date','timesheets.Leader_Member','timesheets.Next_Person','projectcodes.Project_Code','projects.Project_Name','timesheets.Site_Name','timesheets.State','timesheets.Work_Description','timesheets.Check_In_Type',
		'timesheets.Time_In','timesheets.Time_Out','timesheets.Remarks','approver.Name as Approver','timesheetstatuses.UserId','timesheetstatuses.Status','timesheetstatuses.Comment','timesheetstatuses.updated_at as Updated_At')
		->leftJoin('projectcodes', 'timesheets.Project_Code_Id', '=', 'projectcodes.Id')
		->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
		->leftJoin('users as submitter', 'timesheets.UserId', '=', 'submitter.Id')
		->whereIn('timesheetstatuses.Id', $Ids)
		->orderBy('timesheets.Date','asc')
		->get();

		$subscribers = DB::table('notificationtype')
		->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
		->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
		->where('notificationtype.Id','=',34)
		->get();

		$emails = array();

		foreach ($subscribers as $subscriber) {
			$NotificationSubject=$subscriber->Notification_Subject;
			if ($subscriber->Company_Email!="")
			{
				array_push($emails,$subscriber->Company_Email);
			}

			else
			{
				array_push($emails,$subscriber->Personal_Email);
			}

		}

		foreach ($timesheets as $item) {

			# code...
			$id=DB::table('timesheetstatuses')->insertGetId(
				['TimesheetId' => $item->Id,
				 'UserId' => $input["Approver"],
				 'Status' => "Pending Approval"
				]
			);

			array_push($arrTimesheetId,$item->Id);
		}

		if ($arrTimesheetId)
		{

			$timesheets = DB::table('timesheets')
			->select('timesheets.Id','timesheets.UserId','submitter.Name as Submitter','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheets.Date','timesheets.Leader_Member','timesheets.Next_Person','projectcodes.Project_Code','projects.Project_Name','timesheets.Site_Name','timesheets.State','timesheets.Work_Description','timesheets.Check_In_Type',
			'timesheets.Time_In','timesheets.Time_Out','timesheets.Remarks','approver.Name as Approver','timesheetstatuses.UserId','timesheetstatuses.Status','timesheetstatuses.Comment','timesheetstatuses.updated_at as Updated_At')
			->leftJoin('projectcodes', 'timesheets.Project_Code_Id', '=', 'projectcodes.Id')
			->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
			->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
			->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
			->leftJoin('users as submitter', 'timesheets.UserId', '=', 'submitter.Id')
			->whereIn('timesheets.Id', $arrTimesheetId)
			->orderBy('timesheets.Date','asc')
			->get();

			$notify = DB::table('users')
			->whereIn('Id', [$me->UserId, $input["Approver"]])
			->get();

			$emails = array();

			foreach ($notify as $user) {
				if ($user->Company_Email!="")
				{
					array_push($emails,$user->Company_Email);
				}
				else if($user->Personal_Email!="")
				{
					array_push($emails,$user->Personal_Email);
				}

			}

			Mail::send('emails.timesheetredirected', ['me'=>$me,'timesheets' => $timesheets], function($message) use ($emails,$me,$NotificationSubject)
			{
					$emails = array_filter($emails);
					array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
					$message->to($emails)->subject($NotificationSubject);
			});

			return 1;
		}
		else {
			return 0;
		}

	}

	public function export($id,$start = null, $end = null)
	{
		$me = (new CommonController)->get_current_user();

		$user = DB::table('users')->select('users.Id','StaffId','Name','Password','User_Type','Company_Email','Personal_Email','Contact_No_1','Contact_No_2','Nationality','Permanent_Address','Current_Address','Home_Base','DOB','NRIC','Passport_No','Gender','Marital_Status','Department','Position','Emergency_Contact_Person',
		'Emergency_Contact_No','Emergency_Contact_Relationship','Emergency_Contact_Address','files.Web_Path')
		// ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
    	->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->where('users.Id', '=', $id)
		->first();

		$projects = DB::table('projects')
		->get();

		$projectcodes = DB::table('projectcodes')
		->get();

		$options= DB::table('options')
		->whereIn('Table', ["users","timesheets"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		if ($start==null)
		{

			$start=date('d-M-Y', strtotime('first day of this month'));

		}

		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('last day of this month'));

		}

		$timesheetdetail = DB::table('timesheets')
		->select('timesheets.Date',DB::raw('"" as Day'),'timesheets.Check_In_Type',
		'timesheets.Time_In','timesheets.Time_Out','timesheets.Allowance','timesheets.Monetary_Comp','timesheets.OT1','timesheets.OT2','timesheets.OT3','timesheets.Leader_Member','timesheets.Next_Person','projectcodes.Project_Code','projects.Project_Name','timesheets.Site_Name','timesheets.State','timesheets.Work_Description','timesheets.Remarks','approver.Name as Approver','timesheetstatuses.Status','timesheetstatuses.Comment','timesheetstatuses.updated_at as Review_Date')

		->leftJoin('projectcodes', 'timesheets.Project_Code_Id', '=', 'projectcodes.Id')
		->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Timesheet" Group By TargetId) as maxfile'), 'maxfile.TargetId', '=', 'timesheets.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('maxfile.`maxid` and files.`Type`="Timesheet"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
		->where('timesheets.UserId', '=', $id)
		// ->where('timesheetstatuses.UserId', '=', $me->UserId)
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->orderBy(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),'asc')
		->get();

		$mylevel = DB::table('approvalsettings')
		->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
		->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
		->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
		->where('approvalsettings.Type', '=', 'Timesheet')
		->where('approvalsettings.UserId', '=', $me->UserId)
		->orderBy('approvalsettings.Country','asc')
		->orderBy('projects.Project_Name','asc')
		// ->orderBy('approvalsettings.Level','Final Approval","5th Approval","4th Approval","3rd Approval","2nd Approval","1st Approval")
		->orderByRaw("FIELD(approvalsettings.Level , 'Final Approval', '5th Approval', '4th Approval','3rd Approval','2nd Approval','1st Approval') ASC")
		->first();

		$total = DB::table('timesheets')
		->select(DB::Raw('sum(timesheets.Allowance) As TotalAllowance,sum(timesheets.Monetary_Comp) As TotalMonetary,sum(timesheets.OT1) As OT1,sum(timesheets.OT2) As OT2,sum(timesheets.OT3) As OT3'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->where('timesheets.UserId', '=' , $id)
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->get();

		$html = view('exporttimesheet', ['me' => $me, 'UserId' =>$id, 'user' =>$user,'start'=>$start,'end'=>$end,'timesheetdetail' => $timesheetdetail,'projects' =>$projects,'projectcodes' =>$projectcodes, 'options' =>$options,'mylevel' => $mylevel,'total' => $total]);
		(new ExportPDFController)->ExportLandscape($html);
	}

	public function claimtimesheetsummary($start = null, $end = null)
	{
		$me = (new CommonController)->get_current_user();

		$d=date('d');

		if ($start==null)
		{

			if($d>=21)
			{

				$start=date('d-M-Y', strtotime('first day of this month'));
				$start = date('d-M-Y', strtotime($start . " +20 days"));

			}
			else {

				$start=date('d-M-Y', strtotime('first day of last month'));
				$start = date('d-M-Y', strtotime($start . " +20 days"));

			}
		}

		if ($end==null)
		{
			if($d>=21)
			{

				$end=date('d-M-Y', strtotime('first day of next month'));
				$end = date('d-M-Y', strtotime($end . " +19 days"));
			}
			else {

				$end=date('d-M-Y', strtotime('first day of this month'));
				$end = date('d-M-Y', strtotime($end . " +19 days"));

			}

		}

		$summary =DB::select("
				SELECT users.Id,c.ClaimsheetId,c.ClaimsheetStatusesId,c.submitted_at AS 'Claim Submitted Date',users.StaffId, users.Name,c.Claim_Sheet_Name,users.Position,
				case
					when c.ClaimsheetId is null then 'No Claim'
					when c.ClaimsheetId is not null and c.ClaimstatusId is null then 'Pending Submission'
					when c.ClaimsheetId is not null and c.ClaimstatusId is not null then 'Yes'
				End As 'Claim Submitted',
				c.Status AS 'Claim Status',
				c.Claim_Status as 'Claim Process Status',
				c.Remarks AS 'Claim_Remarks',
				case
					when ts.TimesheetId is null then 'No Timesheet'
					when ts.TimesheetId is not null and ts.TimesheetstatusId is null then 'Pending Submission'
					when ts.TimesheetId is not null and ts.TimesheetstatusId is not null then 'Yes'
				End As 'Timesheet Submitted',
				ts.Status AS 'Timesheet Status',
				ts.Timesheet_Status as 'Timesheet Process Status',
				ts.Payment_Status as 'Timesheet Payment Status',
				ts.submitted_at AS 'Timesheet Submitted Date'
				FROM users

				LEFT JOIN (Select users.Id as UserId,timesheets.Id as TimesheetId,timesheetstatuses.Id As TimesheetstatusId,timesheetstatuses.Status,timesheetstatuses.updated_at,timesheets.submitted_at,timesheetchecked.Timesheet_Status,timesheetchecked.Payment_Status FROM users
				LEFT JOIN timesheets ON users.Id=timesheets.UserId
				LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
				LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid` WHERE timesheets.Id IS NOT NULL  AND str_to_date(timesheets.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') ORDER BY FIELD(timesheetstatuses.Status , 'Pending Approval', '1st Approved', '2nd Approved','3rd Approved','Final Approved') ASC ) as ts on users.Id=ts.UserId

				LEFT JOIN (Select users.Id as UserId,claimsheets.Id as ClaimsheetId,claimsheets.Claim_Sheet_Name,claimstatuses.Id as ClaimstatusId,claimsheetstatuses.Id ClaimsheetStatusesId,claimstatuses.Status,claimsheets.submitted_at,claimsheetstatuses.Claim_Status,claimsheetstatuses.Remarks,claimsheetstatuses.updated_at FROM users
				LEFT JOIN claimsheets ON users.Id=claimsheets.UserId
				LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
				LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
				LEFT JOIN claims on claimsheets.Id=claims.ClaimSheetId
				LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max2 on max2.ClaimId=claims.Id
				LEFT JOIN claimstatuses on claimstatuses.Id=max2.`maxid` WHERE claimsheets.Id IS NOT NULL AND str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') ORDER BY FIELD(claimsheetstatuses.Claim_Status , 'Pending Approval', '1st Approved', '2nd Approved','3rd Approved','Final Approved') ASC ) as c on users.Id=c.UserId
				WHERE users.Active=1
				GROUP BY users.Id,ClaimsheetId
		");

		return view('claimtimesheetsummary', ['me' => $me, 'start' => $start,'end' => $end, 'summary' => $summary]);
	}

	public function claimtimesheetsummary2($month = null, $year = null)
	{
		$me = (new CommonController)->get_current_user();

		$d=date('d');

		if ($year==null)
		{
			$year=date('Y');
		}

		if ($month==null)
		{

			$month=date('F');

			if($d>=29)
			{

				$start=date('d-M-Y', strtotime('first day of this month'));
				$start = date('d-M-Y', strtotime($start . " +20 days"));

			}
			else {

				$start=date('d-M-Y', strtotime('first day of last month'));
				$start = date('d-M-Y', strtotime($start . " +20 days"));

			}

			if($d>=29)
			{

				$end=date('d-M-Y', strtotime('first day of next month'));
				$end = date('d-M-Y', strtotime($end . " +19 days"));
			}
			else {

				$end=date('d-M-Y', strtotime('first day of this month'));
				$end = date('d-M-Y', strtotime($end . " +19 days"));

			}

		}
		else {
			$start = strtotime('01 '.$month.' '.$year);
			$start = date('d F Y', $start);
			$start = date('d F Y', strtotime('-1 month',strtotime($start)));
			$start = date('d-M-Y', strtotime($start . " +20 days"));

			$end = strtotime('01 '.$month.' '.$year);
			$end = date('d F Y', $end);
			$end = date('d-M-Y', strtotime($end . " +19 days"));

		}

		$shortmonth=substr($month,0,3);

		$summary =DB::select("
				SELECT users.Id,c.ClaimsheetId,c.ClaimsheetStatusesId,c.submitted_at AS 'Claim Submitted Date',users.StaffId, users.Name,c.Claim_Sheet_Name,users.Position,
				case
					when c.ClaimsheetId is null then 'No Claim'
					when c.ClaimsheetId is not null and c.ClaimstatusId is null then 'Pending Submission'
					when c.ClaimsheetId is not null and c.ClaimstatusId is not null then 'Yes'
				End As 'Claim Submitted',
				c.Status AS 'Claim Status',
				c.updated_at AS 'Claim Review Date',
				c.Claim_Status as 'Claim Process Status',
				c.Remarks AS 'Claim_Remarks',
				case
					when ts.TimesheetId is null then 'No Timesheet'
					when ts.TimesheetId is not null and ts.TimesheetstatusId is null then 'Pending Submission'
					when ts.TimesheetId is not null and ts.TimesheetstatusId is not null then 'Yes'
				End As 'Timesheet Submitted',
				ts.Status AS 'Timesheet Status',
				ts.updated_at AS 'Timesheet Review Date',
				ts.Timesheet_Status as 'Timesheet Process Status',
				ts.Payment_Status as 'Timesheet Payment Status',
				ts.submitted_at AS 'Timesheet Submitted Date'
				FROM users

				LEFT JOIN (Select users.Id as UserId,timesheets.Id as TimesheetId,timesheetstatuses.Id As TimesheetstatusId,timesheetstatuses.Status,timesheetstatuses.updated_at,timesheets.submitted_at,timesheetchecked.Timesheet_Status,timesheetchecked.Payment_Status FROM users
				LEFT JOIN timesheets ON users.Id=timesheets.UserId
				LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
				LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid` WHERE timesheets.Id IS NOT NULL AND timesheetstatuses.Id IS NOT NULL AND str_to_date(timesheets.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') ORDER BY FIELD(timesheetstatuses.Status , 'Pending Approval', '1st Approved', '2nd Approved','3rd Approved','Final Approved') ASC ) as ts on users.Id=ts.UserId

				LEFT JOIN (Select users.Id as UserId,claimsheets.Id as ClaimsheetId,claimsheets.Claim_Sheet_Name,claimstatuses.Id as ClaimstatusId,claimsheetstatuses.Id ClaimsheetStatusesId,claimstatuses.Status,claimsheets.submitted_at,claimsheetstatuses.Claim_Status,claimsheetstatuses.Remarks,claimsheetstatuses.updated_at FROM users
				LEFT JOIN claimsheets ON users.Id=claimsheets.UserId
				LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
				LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
				LEFT JOIN claims on claimsheets.Id=claims.ClaimSheetId
				LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max2 on max2.ClaimId=claims.Id
				LEFT JOIN claimstatuses on claimstatuses.Id=max2.`maxid` WHERE claimsheets.Id IS NOT NULL AND Claim_Sheet_Name like '%".$shortmonth."%' AND str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') ORDER BY FIELD(claimsheetstatuses.Claim_Status , 'Pending Approval', '1st Approved', '2nd Approved','3rd Approved','Final Approved') ASC ) as c on users.Id=c.UserId

				WHERE users.Active=1
				GROUP BY users.Id,c.ClaimsheetId,c.submitted_at
		");

		return view('claimtimesheetsummary2', ['me' => $me, 'month' => $month , 'year' =>$year ,'start' => $start,'end' => $end, 'summary' => $summary]);
	}

	public function claimtimesheetnotify(Request $request)
	{
		$me = (new CommonController)->get_current_user();

		$input = $request->all();

		$year=$input["Year"];
		$month=$input["Month"];

		$start=$input["Start"];
		$end=$input["End"];

		$summary =DB::select("
				SELECT users.Id,c.submitted_at AS 'Claim Submitted Date',users.StaffId, users.Name,c.Claim_Sheet_Name,users.Position,
				case
					when c.ClaimsheetId is null then 'No Claim'
					when c.ClaimsheetId is not null and c.ClaimstatusId is null then 'Pending Submission'
					when c.ClaimsheetId is not null and c.ClaimstatusId is not null then 'Yes'
				End As 'Claim Submitted',
				c.Status AS 'Claim Status',
				c.Claim_Status as 'Claim Process Status',
				c.Remarks AS 'Claim_Remarks',
				case
					when ts.TimesheetId is null then 'No Timesheet'
					when ts.TimesheetId is not null and ts.TimesheetstatusId is null then 'Pending Submission'
					when ts.TimesheetId is not null and ts.TimesheetstatusId is not null then 'Yes'
				End As 'Timesheet Submitted',
				ts.Status AS 'Timesheet Status',
				ts.Timesheet_Status as 'Timesheet Process Status',
				ts.Payment_Status as 'Timesheet Payment Status',
				ts.submitted_at AS 'Timesheet Submitted Date'
				FROM users

				LEFT JOIN (Select users.Id as UserId,timesheets.Id as TimesheetId,timesheetstatuses.Id As TimesheetstatusId,timesheetstatuses.Status,timesheetstatuses.updated_at,timesheets.submitted_at,timesheetchecked.Timesheet_Status,timesheetchecked.Payment_Status FROM users
				LEFT JOIN timesheets ON users.Id=timesheets.UserId
				LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
				LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid` WHERE timesheets.Id IS NOT NULL AND timesheetstatuses.Id IS NOT NULL AND str_to_date(timesheets.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') ORDER BY FIELD(timesheetstatuses.Status , 'Pending Approval', '1st Approved', '2nd Approved','3rd Approved','Final Approved') ASC ) as ts on users.Id=ts.UserId

				LEFT JOIN (Select users.Id as UserId,claimsheets.Id as ClaimsheetId,claimsheets.Claim_Sheet_Name,claimstatuses.Id as ClaimstatusId,claimsheetstatuses.Id ClaimsheetStatusesId,claimstatuses.Status,claimsheets.submitted_at,claimsheetstatuses.Claim_Status,claimsheetstatuses.Remarks,claimsheetstatuses.updated_at FROM users
				LEFT JOIN claimsheets ON users.Id=claimsheets.UserId
				LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
				LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
				LEFT JOIN claims on claimsheets.Id=claims.ClaimSheetId
				LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max2 on max2.ClaimId=claims.Id
				LEFT JOIN claimstatuses on claimstatuses.Id=max2.`maxid` WHERE claimsheets.Id IS NOT NULL AND str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') ORDER BY FIELD(claimsheetstatuses.Claim_Status , 'Pending Approval', '1st Approved', '2nd Approved','3rd Approved','Final Approved') ASC ) as c on users.Id=c.UserId

				WHERE users.Active=1 and (c.ClaimsheetId is not null OR ts.TimesheetId is not null) AND ((c.Claim_Status='' OR c.Claim_Status IS NULL) and (ts.Payment_Status='' or ts.Payment_Status IS NULL))
				GROUP BY users.Id,c.submitted_at
		");

		$subscribers = DB::table('notificationtype')
		->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
		->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
		->where('notificationtype.Id','=',48)
		->get();

		foreach ($summary as $entry) {
			# code...

				$emails = array();


				foreach ($subscribers as $subscriber) {
					$NotificationSubject=$subscriber->Notification_Subject;
					if ($subscriber->Company_Email!="")
					{

						if (filter_var($subscriber->Company_Email, FILTER_VALIDATE_EMAIL)) {
							array_push($emails,$subscriber->Company_Email);
						}

					}

					else
					{

							if (filter_var($subscriber->Personal_Email, FILTER_VALIDATE_EMAIL)) {
								array_push($emails,$subscriber->Personal_Email);
							}

					}

				}

				$notify = DB::table('users')
				->where('Id', $entry->Id)
				->get();

				foreach ($notify as $user) {
					if ($user->Company_Email!="")
					{

							if (filter_var($user->Company_Email, FILTER_VALIDATE_EMAIL)) {
								array_push($emails,$user->Company_Email);
							}


					}
					else if($user->Personal_Email!="")
					{
						if (filter_var($user->Personal_Email, FILTER_VALIDATE_EMAIL)) {
							array_push($emails,$user->Personal_Email);
						}

					}

				}

					Mail::send('emails.claimtimesheetnotify', ['me' => $me,'month'=>$month,'year'=>$year,'start'=>$start,'end'=>$end,'entry'=>$entry], function($message) use ($emails,$month,$year,$start,$end,$NotificationSubject)
					{
							$emails = array_filter($emails);
							array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
							$message->to($emails)->subject($NotificationSubject. ' '.$month.' '.$year.'[From '.$start.' To '.$end.']');

					});

		}

		return 1;

	}

	public function summary($start = null, $end = null)
	{
		$me = (new CommonController)->get_current_user();

		$d=date('d');

		if ($start==null)
		{

			if($d>=21)
			{

				$start=date('d-M-Y', strtotime('first day of this month'));
				$start = date('d-M-Y', strtotime($start . " +20 days"));

			}
			else {

				$start=date('d-M-Y', strtotime('first day of last month'));
				$start = date('d-M-Y', strtotime($start . " +20 days"));

			}
		}

		if ($end==null)
		{
			if($d>=21)
			{

				$end=date('d-M-Y', strtotime('first day of next month'));
				$end = date('d-M-Y', strtotime($end . " +19 days"));
			}
			else {

				$end=date('d-M-Y', strtotime('first day of this month'));
				$end = date('d-M-Y', strtotime($end . " +19 days"));

			}

		}
		//
		// $startTime = strtotime($start);
		// $endTime = strtotime($end);
		// $query="";
		//
		// $startTime=strtotime("+1 days",$startTime);
		//
		//  while ($startTime <= $endTime){
		//
		//  	$query.="SELECT '" . date('d-M-Y', $startTime) . "' UNION ALL ";
		//
		// 	$startTime=strtotime("+1 days",$startTime);
		//  }
		//
		// $query=substr($query,0,strlen($query)-10);
		//
		// $summary = DB::select("
		// 		SELECT MonthDate.Date, Count(*) As 'Total Submitted', Count(IF(timesheetstatus.Status = 'Pending Approval', 1, NULL)) AS 'Total Pending Approval', Count(IF(timesheetstatus.Status = 'Approved', 1, NULL)) AS 'Total Approved', Count(IF(timesheetstatus.Status = 'Rejected', 1, NULL)) AS 'Total Rejected',Count(IF(timesheetstatus.Status = 'Standby', 1, NULL)) AS 'Total Standby',Count(IF(timesheetstatus.Status = 'On Duty', 1, NULL)) AS 'Total On Duty'
		// 		FROM (SELECT '".$start."' AS Date UNION ALL
		// 			".$query.") AS MonthDate
		// 		INNER JOIN timesheets AS timesheet
		// 		ON (str_to_date(MonthDate.Date,'%d-%M-%Y') = str_to_date(timesheet.Date,'%d-%M-%Y') )
		// 		INNER JOIN timesheetstatuses As timesheetstatus ON (timesheetstatus.TimesheetId = timesheet.Id)
		//
		// 		GROUP BY MonthDate.Date
		// ");

		$summary =DB::select("
				SELECT users.Id,users.StaffId, users.Name,
				SUM(case when timesheetstatuses.Status <>'' then 1 else 0 end) As 'Total_Submitted',
				Count(IF(timesheetstatuses.Status = 'Pending_Approval', 1, NULL)) AS 'Total_Pending_Approval',
				Count(IF(timesheetstatuses.Status = 'Final Approved', 1, NULL)) AS 'Total_Approved',
				Count(IF(timesheetstatuses.Status like '%Rejected', 1, NULL)) AS 'Total_Rejected',
				Count(IF(timesheetstatuses.Status <>'' AND timesheets.Check_In_Type = 'On Duty', 1, NULL)) AS 'Total_On_Duty',
				Count(IF(timesheetstatuses.Status <>'' AND timesheets.Check_In_Type = 'On Leave', 1, NULL)) AS 'Total_On_Leave',
				Count(IF(timesheetstatuses.Status <>'' AND timesheets.Check_In_Type = 'Weekend', 1, NULL)) AS 'Total_Weekend',
				Count(IF(timesheetstatuses.Status <>'' AND timesheets.Check_In_Type = 'Standby', 1, NULL)) AS 'Total_Standby'
				FROM users
				LEFT JOIN timesheets ON users.Id=timesheets.UserId AND str_to_date(timesheets.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y')
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				LEFT JOIN accesscontroltemplates on users.AccessControlTemplateId=accesscontroltemplates.Id
				WHERE accesscontroltemplates.Timesheet_Required=1
				GROUP BY users.Id
		");

		$totalallowance = DB::table('timesheets')
		->select('users.StaffId','users.Name', DB::Raw('sum(timesheets.Allowance) As Total_Allowance'))
		->leftJoin('users','users.Id','=','timesheets.UserId')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->where('users.Id', '<>', '0')
		->groupBy('timesheets.UserId')
		->get();


		return view('timesheetsummary', ['me' => $me, 'start' => $start,'end' => $end, 'summary' => $summary, 'totalallowance'=>$totalallowance]);
	}

	function viewlist(Request $request)
	{
		$input = $request->all();

			$viewlist = DB::table('timesheets')
			->leftJoin('timesheetstatuses', 'timesheetstatuses.TimesheetId', '=', 'timesheets.Id')
			->leftJoin('users', 'users.Id', '=', 'timesheets.UserId')
			->select('timesheets.Id','timesheetstatuses.UserId','users.Name as Name')
			->where('timesheetstatuses.Status', '=', $input["Status"])
			->where(DB::raw('str_to_date("'.$input["Date"].'","%d-%M-%Y")'), '=', DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'))
			->get();

		return json_encode($viewlist);
	}

	function test()
	{
		$total = DB::table('timesheets')
		->select(DB::Raw('sum(timesheets.Allowance) As TotalAllowance'))
		->where('timesheets.UserId', '=' , 562)
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("17-Dec-2016","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("15-Jan-2017","%d-%M-%Y")'))
		->get();

		dd($total);
	}


	public function viewclaim(Request $request)
	{

		$input = $request->all();

		$viewclaim = DB::table('claimsheets')
    ->select('projects.Project_Name','claims.Site_Name','claims.State','claims.Work_Description','claims.Next_Person','claims.Car_No','claims.Mileage','claims.Expenses_Type','claims.Total_Expenses','claims.Petrol_SmartPay','claims.Advance','claims.Total_Amount','claims.GST_Amount','claims.Total_Without_GST','claims.Receipt_No','claims.Company_Name','claims.GST_No','claims.Remarks','approver.Name as Approver','claimstatuses.Status as Status','claimstatuses.Comment as Comment','claimstatuses.updated_at as updated_at')
		->leftJoin('claims', 'claimsheets.Id', '=', 'claims.ClaimsheetId')
		->leftJoin('projectcodes', 'claims.Project_Code_Id', '=', 'projectcodes.Id')
		->leftJoin('projects', 'claims.ProjectId', '=', 'projects.Id')
		->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
    ->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
		->where('claimsheets.UserId', '=',  $input["UserId"])
		// ->where('claims.Next_Person', '=',  $input["Next_Person"])
		->where('claims.Date', '=',  $input["Date"])
		->orderBy('claims.Id','desc')
    ->get();

		return json_encode($viewclaim);

	}

	public function submitpending(Request $request){

		$me = (new CommonController)->get_current_user();

		$input = $request->all();

		$UserIds = explode(",", $input["UserIds"]);

		$start = $input["Start"];
		$end = $input["End"];

		$namelist = DB::table('users')
		->select('users.Id as userid','users.StaffId','users.Name')
		->orderBy('users.Id','desc')
		->whereIn('users.Id', $UserIds)
		->get();

		$subscribers = DB::table('notificationtype')
		->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
		->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
		->where('notificationtype.Id','=',30)
		->get();

		$emails = array();

		foreach ($subscribers as $subscriber) {
			$NotificationSubject=$subscriber->Notification_Subject;
			if ($subscriber->Company_Email!="")
			{
				array_push($emails,$subscriber->Company_Email);
			}

			else
			{
				array_push($emails,$subscriber->Personal_Email);
			}

		}

		if ($namelist>0)
		{

		$notify = DB::table('users')
		->whereIn('Id', $UserIds)
		->get();



		foreach ($notify as $user) {
			if ($user->Company_Email!="")
			{
				if (filter_var($user->Company_Email, FILTER_VALIDATE_EMAIL)) {
					array_push($emails,$user->Company_Email);
				}

			}
			else if($user->Personal_Email!="")
			{
				if (filter_var($user->Personal_Email, FILTER_VALIDATE_EMAIL)) {
					array_push($emails,$user->Personal_Email);
				}
			}

		}

			Mail::send('emails.pendingtimesheet', ['me' => $me, 'start'=>$start, 'end'=>$end, 'namelist'=>$namelist], function($message) use ($emails,$start,$end,$NotificationSubject)
			{
					$emails = array_filter($emails);
					array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
					$message->to($emails)->subject($NotificationSubject. ' From '.$start.' To '.$end.'');

			});

			return 1;
		}

		else {
			return 0;
		}

	}

	function GetDateString()
	{

			date_default_timezone_set('Asia/Kuala_Lumpur');
			return date("d-M-Y");

	}

	public function pendingsubmitalert(Request $request){

		$me = (new CommonController)->get_current_user();

		$start=date('d-M-Y', strtotime('first day of this month'));

		$start = date('d-M-Y', strtotime($start . " +20 days"));
		$end = $this->GetDateString();

		$summary =DB::select("
				SELECT users.Id,users.StaffId, users.Name,users.Personal_Email,users.Company_Email, SUM(case when timesheetstatuses.Status <>'' then 1 else 0 end) As 'Total_Submitted',Count(IF(timesheetstatuses.Status = 'Pending_Approval', 1, NULL)) AS 'Total_Pending_Approval',Count(IF(timesheetstatuses.Status = 'Final Approved', 1, NULL)) AS 'Total_Approved',Count(IF(timesheetstatuses.Status like '%Rejected', 1, NULL)) AS 'Total_Rejected',Count(IF(timesheets.Check_In_Type = 'On Duty', 1, NULL)) AS 'Total_On_Duty',Count(IF(timesheets.Check_In_Type = 'On Leave', 1, NULL)) AS 'Total_On_Leave',Count(IF(timesheets.Check_In_Type = 'Weekend', 1, NULL)) AS 'Total_Weekend',Count(IF(timesheets.Check_In_Type = 'Standby', 1, NULL)) AS 'Total_Standby'
				FROM users
				LEFT JOIN timesheets ON users.Id=timesheets.UserId AND str_to_date(timesheets.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y')
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				LEFT JOIN accesscontroltemplates on users.AccessControlTemplateId=accesscontroltemplates.Id
				WHERE accesscontroltemplates.Timesheet_Required=1
				GROUP BY users.Id
		");

		$subscribers = DB::table('notificationtype')
		->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
		->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
		->where('notificationtype.Id','=',30)
		->get();

		$emails = array();

		foreach ($subscribers as $subscriber) {
			$NotificationSubject=$subscriber->Notification_Subject;
			if ($subscriber->Company_Email!="")
			{
				array_push($emails,$subscriber->Company_Email);
			}

			else
			{
				array_push($emails,$subscriber->Personal_Email);
			}

		}

		if ($summary)
		{

		foreach ($summary as $user) {
			if ($user->Company_Email!="")
			{
				if (filter_var($user->Company_Email, FILTER_VALIDATE_EMAIL)) {
					array_push($emails,$user->Company_Email);
				}

			}
			else if($user->Personal_Email!="")
			{
				if (filter_var($user->Personal_Email, FILTER_VALIDATE_EMAIL)) {
					array_push($emails,$user->Personal_Email);
				}
			}

		}

			Mail::send('emails.pendingtimesheet2', ['me' => $me, 'start'=>$start, 'end'=>$end, 'summary'=>$summary], function($message) use ($emails,$start,$end,$NotificationSubject)
			{
					$emails = array_filter($emails);
					array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
					$message->to($emails)->subject($NotificationSubject.' From '.$start.' To '.$end.'');

			});

			return 1;
		}

		else {
			return 0;
		}

	}

	public function incompletealert(Request $request){

		$me = (new CommonController)->get_current_user();

		$start=date('d-M-Y', strtotime('first day of this month'));
		// $start=date('d-M-Y', strtotime($start,' +16 days'));
		$start = date('d-M-Y', strtotime($start . " +20 days"));
		$end = $this->GetDateString();

		$date1 = new DateTime($start);
		$date2 = new DateTime($end);
		$diff = $date2->diff($date1)->format("%a")+1;

		$summary =DB::select("
				SELECT users.Id,users.StaffId, users.Name,users.Personal_Email,users.Company_Email, SUM(case when timesheetstatuses.Status <>'' then 1 else 0 end) As 'Total_Submitted',Count(IF(timesheetstatuses.Status = 'Pending_Approval', 1, NULL)) AS 'Total_Pending_Approval',Count(IF(timesheetstatuses.Status = 'Final Approved', 1, NULL)) AS 'Total_Approved',Count(IF(timesheetstatuses.Status like '%Rejected', 1, NULL)) AS 'Total_Rejected',Count(IF(timesheets.Check_In_Type = 'On Duty', 1, NULL)) AS 'Total_On_Duty',Count(IF(timesheets.Check_In_Type = 'On Leave', 1, NULL)) AS 'Total_On_Leave',Count(IF(timesheets.Check_In_Type = 'Weekend', 1, NULL)) AS 'Total_Weekend',Count(IF(timesheets.Check_In_Type = 'Standby', 1, NULL)) AS 'Total_Standby'
				FROM users
				LEFT JOIN timesheets ON users.Id=timesheets.UserId AND str_to_date(timesheets.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y')
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				LEFT JOIN accesscontroltemplates on users.AccessControlTemplateId=accesscontroltemplates.Id
				WHERE accesscontroltemplates.Timesheet_Required=1
				GROUP BY users.Id
		");

		$subscribers = DB::table('notificationtype')
		->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
		->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
		->where('notificationtype.Id','=',31)
		->get();

		$emails = array();

		foreach ($subscribers as $subscriber) {
			$NotificationSubject=$subscriber->Notification_Subject;
			if ($subscriber->Company_Email!="")
			{
				array_push($emails,$subscriber->Company_Email);
			}

			else
			{
				array_push($emails,$subscriber->Personal_Email);
			}

		}

		if ($summary)
		{


		foreach ($summary as $user) {
			if ($user->Company_Email!="")
			{
				if (filter_var($user->Company_Email, FILTER_VALIDATE_EMAIL)) {
					array_push($emails,$user->Company_Email);
				}

			}
			else if($user->Personal_Email!="")
			{
				if (filter_var($user->Personal_Email, FILTER_VALIDATE_EMAIL)) {
					array_push($emails,$user->Personal_Email);
				}
			}
		}

		Mail::send('emails.incompletetimesheet2', ['me' => $me, 'start'=>$start, 'end'=>$end, 'summary'=>$summary,'diff'=>$diff], function($message) use ($emails,$start,$end,$NotificationSubject)
		{
				$emails = array_filter($emails);
				array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
				$message->to($emails)->subject($NotificationSubject. ' From '.$start.' To '.$end.'');

		});

			return 1;
		}

		else {
			return 0;
		}

	}

	public function submitincomplete(Request $request){

		$me = (new CommonController)->get_current_user();

		$input = $request->all();

		$UserIds = explode(",", $input["UserIds"]);

		$start = $input["Start"];
		$end = $input["End"];

		$date1 = new DateTime($start);
		$date2 = new DateTime($end);
		$diff = $date2->diff($date1)->format("%a")+1;

		$namelist = DB::table('users')
		->select('users.Id as userid','users.StaffId','users.Name')
		->orderBy('users.Id','desc')
		->whereIn('users.Id', $UserIds)
		->get();

		$subscribers = DB::table('notificationtype')
		->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
		->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
		->where('notificationtype.Id','=',31)
		->get();

		$emails = array();

		foreach ($subscribers as $subscriber) {
			$NotificationSubject=$subscriber->Notification_Subject;
			if ($subscriber->Company_Email!="")
			{
				array_push($emails,$subscriber->Company_Email);
			}

			else
			{
				array_push($emails,$subscriber->Personal_Email);
			}

		}

		if ($namelist>0)
		{

		$notify = DB::table('users')
		->whereIn('Id', $UserIds)
		->get();


		foreach ($notify as $user) {
			if ($user->Company_Email!="")
			{
				if (filter_var($user->Company_Email, FILTER_VALIDATE_EMAIL)) {
					array_push($emails,$user->Company_Email);
				}

			}
			else if($user->Personal_Email!="")
			{
				if (filter_var($user->Personal_Email, FILTER_VALIDATE_EMAIL)) {
					array_push($emails,$user->Personal_Email);
				}
			}

		}

			Mail::send('emails.incompletetimesheet', ['me' => $me, 'start'=>$start, 'end'=>$end, 'namelist'=>$namelist], function($message) use ($emails,$start,$end,$NotificationSubject)
			{
					$emails = array_filter($emails);
					array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
					$message->to($emails)->subject($NotificationSubject.' From'.$start.' To '.$end.'');

			});

			return 1;
		}

		else {
			return 0;
		}

	}

	public function newtimesheetallowance(Request $request)
	{

		$me = (new CommonController)->get_current_user();

		$input = $request->all();

		$id=DB::table('timesheets')->insertGetId(
			['UserId' => $input["UserId"],
			 'Date' => $input["Date"],
			 'ProjectId' => $input["ProjectId"],
			 'Check_In_Type' => "On Duty"
		 ]

		);

		if($id>0)
		{
			$status = DB::table('timesheetstatuses')->insert(
				['TimesheetId' => $id,
				 'UserId' =>$me->UserId,
				 'Status' => "Final Approved with Special Attention",
				 'Comment' =>$input["Comment"],
				 'updated_at' => DB::raw('now()')
				]
			);

			return 1;

		}

		else {
			return 0;
		}


	}

	public function deletetimesheet(Request $request)
	{

		$me = (new CommonController)->get_current_user();

		$input = $request->all();

		$timesheetIds = explode(",", $input["TimesheetIds"]);

		$timesheets = DB::table('timesheets')
		->select('timesheets.Id','timesheetstatuses.Id as StatusId','timesheetstatuses.UserId')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->whereIn('timesheetstatuses.Id', $timesheetIds)
		->orderBy('timesheets.Date','asc')
		->get();

		foreach ($timesheets as $timesheet) {

			$delete = DB::table('timesheets')
			->where('Id', '=', $timesheet->Id)
			->delete();

		}
		return 1;




	}

	public function radiusmanagement()
	{
		$me = (new CommonController)->get_current_user();

		$radius = DB::table('radius')
		->leftJoin('projects','projects.Id','=','radius.ProjectId')
		->select('radius.Id','radius.Client','projects.Project_Name','radius.Code','radius.Area','radius.Location_Name','radius.Latitude','radius.Longitude','radius.Start_Date','radius.Completion_Date')
		->get();

		$codes = DB::table('scopeofwork')
		->orderBy('scopeofwork.Code')
		->get();

		// $clients= DB::table('options')
		// ->whereIn('Table', ["projects"])
		// ->where('Field','=','Client')
		// ->orderBy('Table','asc')
		// ->orderBy('Option','asc')
		// ->get();
		$clients = DB::table('companies')
        ->select('companies.Id','companies.Company_Name','companies.Company_Code')
        ->where('companies.Client','=','Yes')
        ->get();

		$area = DB::table('options')
		->whereIn('Table', ["timesheets"])
		->where('Field', '=', 'Zone')
		->orderBy('Table', 'asc')
		->orderBy('Option', 'asc')
		->get();

		$projects = DB::table('projects')
		->select('Id','Project_Name')
		->get();

		return view('radiusmanagement',['me'=>$me, 'radius'=>$radius,'codes'=>$codes,'clients'=>$clients,'area'=>$area,'projects'=>$projects]);
	}

	public function deliverylocation()
	{
		$me = (new CommonController)->get_current_user();

		$charges = DB::table('deliverylocation')
		->leftJoin('users', 'users.Id', '=', 'deliverylocation.created_by')
		->select('deliverylocation.Id','deliverylocation.type','deliverylocation.area','deliverylocation.price_2ton_to_5ton','deliverylocation.price_5ton_crane','deliverylocation.price_10ton','deliverylocation.price_10ton_crane','users.Name')
		->where('deliverylocation.type','=','charges')
		->get();

		$incentive = DB::table('deliverylocation')
		->leftJoin('users', 'users.Id', '=', 'deliverylocation.created_by')
		->select('deliverylocation.Id','deliverylocation.type','deliverylocation.area','deliverylocation.price_2ton_to_5ton','deliverylocation.price_5ton_crane','deliverylocation.price_10ton','deliverylocation.price_10ton_crane','users.Name')
		->where('deliverylocation.type','=','incentive')
		->get();

		$lorry_size=DB::table('options')
		->distinct('options.Option')
		->select('options.Option')
		->where('options.Table', '=', 'assets')
		->where('options.Field', '=', 'Lorry Size')
		->get();

		$area = DB::table('options')
		->whereIn('Table', ["timesheets"])
		->where('Field', '=', 'Zone')
		->orderBy('Table', 'asc')
		->orderBy('Option', 'asc')
		->get();

		return view('deliverylocation', ['me'=>$me, 'charges'=>$charges,'incentive'=>$incentive, 'area'=>$area]);
	}

	public function incentivesummary($start=null,$end=null,$includeResigned = 'false',$client=null){

		$me=(new CommonController)->get_current_user();

		if ($start==null)
		{
			$start=date('d-M-Y', strtotime('first day of last month'));
			// $start=date('d-M-Y', strtotime($start,' +16 days'));
			$start = date('d-M-Y', strtotime($start . " +20 days"));
		}
		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('first day of this month'));
			$end = date('d-M-Y', strtotime($end . " +19 days"));
		}

		$year = date('Y');

		$condition="1";
		if($client)
		{
			$condition="radius.Client='".$client."'";
		}
		else {
			// code...
		}

		$summary = DB::table('users')
		->select('users.Id','users.StaffID','users.Name','users.Category','radius.Location_Name as Site_Name','radius.Code','scopeofwork.KPI',DB::raw('"" as Incentive_Entitled'),
		DB::raw('(SELECT COUNT(distinct a.Date) FROM timesheets a WHERE a.Site_Name=timesheets.Site_Name AND replace(a.Code," ","")=replace(timesheets.Code," ","") AND str_to_date(a.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y")) as "Total Visit"'),
		DB::raw('(SELECT COUNT(distinct a.Date) FROM timesheets a WHERE a.Site_Name=timesheets.Site_Name and a.UserId=timesheets.UserId AND replace(a.Code," ","")=replace(timesheets.Code," ","") and str_to_date(a.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y")) as "Number of Own Visit"'),
		DB::raw('(SELECT COUNT(distinct concat(a.UserId,"|",a.Date)) FROM timesheets a WHERE a.Site_Name=timesheets.Site_Name AND replace(a.Code," ","")=replace(timesheets.Code," ","") AND str_to_date(a.Date,"%d-%M-%Y") Between str_to_date(radius.Start_Date,"%d-%M-%Y") and str_to_date(radius.Completion_Date,"%d-%M-%Y")) as "Visit Count"'),
		DB::raw('"Incentive" as Incentive'),'scopeofwork.Incentive_1','scopeofwork.Incentive_2','scopeofwork.Incentive_3','scopeofwork.Incentive_4','scopeofwork.Incentive_5')
		->leftJoin('timesheets','users.Id','=','timesheets.UserId')
		->leftJoin('radius','timesheets.Site_Name','=',DB::raw("radius.Location_Name AND replace(timesheets.Code,' ','') like CONCAT('%', radius.Code ,'%')"))
		->leftJoin('scopeofwork','radius.Code','=','scopeofwork.Code')
		->whereRaw("str_to_date(radius.Completion_Date,'%d-%M-%Y') between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y')")
		->whereRaw("str_to_date(timesheets.Date,'%d-%M-%Y') Between str_to_date(radius.Start_Date,'%d-%M-%Y') and str_to_date(radius.Completion_Date,'%d-%M-%Y')")
		->whereRaw('timesheets.Code !=""')
		->whereRaw($condition)
		->groupBy('timesheets.UserId')
		->groupBy('timesheets.Site_Name')
		->groupBy('radius.Code');

		if (! ($includeResigned == 'true')) {
			$today = date('d-M-Y', strtotime('today'));
			$summary->whereRaw('(users.Resignation_Date = "" OR (str_to_date(users.Resignation_Date,"%d-%M-%Y") >= str_to_date("'.$start.'","%d-%M-%Y") and str_to_date(users.Resignation_Date,"%d-%M-%Y") <= str_to_date("'.$end.'","%d-%M-%Y")) )');
		}

		$summary = $summary->get();


		$clients= DB::table('radius')
		->distinct('Client')
		->select('Client')
		->orderBy('Client','asc')
		->get();


		$years= DB::select("
			SELECT Year(Now())-1 as yearname UNION ALL
			SELECT Year(Now()) UNION ALL
			SELECT Year(Now())+1
			");

		return view('incentivesummary',['me'=>$me, 'start'=>$start,'end'=>$end,'client'=>$client,'summary'=>$summary,'clients'=>$clients, 'includeResigned' => $includeResigned]);

	}

	public function driverincentivesummary($start=null,$end=null){

		$me=(new CommonController)->get_current_user();

		if ($start==null)
		{
			$start=date('d-M-Y', strtotime('first day of last month'));
			// $start=date('d-M-Y', strtotime($start,' +16 days'));
			$start = date('d-M-Y', strtotime($start . " +20 days"));
		}
		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('first day of this month'));
			$end = date('d-M-Y', strtotime($end . " +19 days"));
		}

		$year = date('Y');


		$years= DB::select("
			SELECT Year(Now())-1 as yearname UNION ALL
			SELECT Year(Now()) UNION ALL
			SELECT Year(Now())+1
			");

		$summary = DB::table('deliveryform')
		->leftJoin( DB::raw('(select Max(Id) as maxid,deliveryform_Id from deliverystatuses Group By deliveryform_Id) as max'), 'max.deliveryform_Id', '=', 'deliveryform.Id')
        ->leftJoin('deliverystatuses', 'deliverystatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users','users.Id','=','deliveryform.DriverId')
		->leftJoin('radius','radius.Id','=','deliveryform.Location')
		->leftJoin('deliverylocation','deliverylocation.area','=','radius.Area')
		->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
		->select('deliveryform.Id','users.Name','deliveryform.DO_No','radius.Location_Name','deliverylocation.area','roadtax.Lorry_Size',
			DB::raw('(CASE WHEN roadtax.Lorry_Size = "10 TAN CRANE" THEN price_10ton_crane WHEN roadtax.Lorry_Size = "10 TAN" THEN price_10ton WHEN roadtax.Lorry_Size = "5 TAN CRANE" THEN price_5ton_crane ELSE price_2ton_to_5ton END) AS driverincentive'),'deliveryform.incentive', 'price_10ton_crane', 'price_10ton','price_5ton_crane','price_2ton_to_5ton')
		->where('deliverylocation.type','=','incentive')
		->whereRaw('deliverystatuses.delivery_status = "Completed" AND deliverystatuses.delivery_status_details != "-" ')
		->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
        ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
        ->whereRaw('roadtax.Type != "TRUCK" AND deliveryform.roadtaxId != 0 AND (roadtax.Lorry_Size != "" || roadtax.Lorry_Size != NULL) ')
        // ->groupBy('roadtaxId')
		->get();
		// dd($summary);

		return view('driverincentivesummary',['me'=>$me, 'start'=>$start,'end'=>$end,'summary'=>$summary]);

	}

		public function sitedriverincentivesummary($start=null,$end=null,$site=null){

		$me=(new CommonController)->get_current_user();

		if ($start==null)
		{
			$start=date('d-M-Y', strtotime('first day of last month'));
			// $start=date('d-M-Y', strtotime($start,' +16 days'));
			$start = date('d-M-Y', strtotime($start . " +20 days"));
		}
		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('first day of this month'));
			$end = date('d-M-Y', strtotime($end . " +19 days"));
		}

		$year = date('Y');


		$years= DB::select("
			SELECT Year(Now())-1 as yearname UNION ALL
			SELECT Year(Now()) UNION ALL
			SELECT Year(Now())+1
			");
		if($site != null)
		{
		$summary = DB::table('deliveryform')
		->leftJoin('users','users.Id','=','deliveryform.DriverId')
		->leftJoin('radius','radius.Id','=','deliveryform.Location')
		->leftJoin('deliverylocation','deliverylocation.area','=','radius.Area')
		->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
		->select('deliveryform.Id','users.Name','deliveryform.DO_No','radius.Location_Name','deliverylocation.area','roadtax.Lorry_Size',
			DB::raw('(CASE WHEN roadtax.Lorry_Size = "10 Tan Crane" THEN price_10ton_crane WHEN roadtax.Lorry_Size = "10 Tan" THEN price_10ton WHEN roadtax.Lorry_Size = "5 Tan Crane" THEN price_5ton_crane ELSE price_2ton_to_5ton END) AS driverincentive'),'deliveryform.incentive', 'price_10ton_crane', 'price_10ton','price_5ton_crane','price_2ton_to_5ton')
		->where('deliverylocation.type','=','incentive')
		->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
        ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
        ->where('radius.Client','=',$site)
		->get();
		}
		else{
		$summary = DB::table('deliveryform')
		->leftJoin('users','users.Id','=','deliveryform.DriverId')
		->leftJoin('radius','radius.Id','=','deliveryform.Location')
		->leftJoin('deliverylocation','deliverylocation.area','=','radius.Area')
		->leftJoin('roadtax','roadtax.Id','=','deliveryform.roadtaxId')
		->select('deliveryform.Id','users.Name','deliveryform.DO_No','radius.Location_Name','deliverylocation.area','roadtax.Lorry_Size',
			DB::raw('(CASE WHEN roadtax.Lorry_Size = "10 Tan Crane" THEN price_10ton_crane WHEN roadtax.Lorry_Size = "10 Tan" THEN price_10ton WHEN roadtax.Lorry_Size = "5 Tan Crane" THEN price_5ton_crane ELSE price_2ton_to_5ton END) AS driverincentive'),'deliveryform.incentive', 'price_10ton_crane', 'price_10ton','price_5ton_crane','price_2ton_to_5ton')
		->where('deliverylocation.type','=','incentive')
		->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
        ->where(DB::raw('str_to_date(deliveryform.delivery_date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->get();
		}

		return view('sitedriverincentivesummary',['me'=>$me, 'start'=>$start,'end'=>$end,'summary'=>$summary]);

	}



	public function otmanagementhr($start = null, $end = null, $includeResigned = 'false')
	{
		$me = (new CommonController)->get_current_user();
		$today = date('d-M-Y', strtotime('today'));

		if ($start==null)
		{

			$start=date('d-M-Y', strtotime('today'));
		}

		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('today'));

		}

		$hod = DB::table('projects')
		->select('Project_Name')
		->where('projects.Project_Manager', '=', $me->UserId)
		->get();

		if($hod && !$me->Admin)
		{

			$arrdepartment=array();

			foreach ($hod as $department) {
				# code...
				array_push($arrdepartment,$department->Project_Name);
			}



			$timesheetdetail = DB::table('timesheets')
			->select('timesheets.Id','timesheets.Id as TimesheetId','users.StaffId','users.Name','users.Resignation_Date','users.Company','users.Department','users.Category','users.Position','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheets.Date',DB::raw('"" as Day'),'timesheets.Site_Name','timesheets.Code','users.Available','timesheets.Check_In_Type','leaves.Leave_Type','leavestatuses.Leave_Status',
			 'timesheets.Time_In','timesheets.Time_Out','timesheets.OT1','timesheets.OT2','timesheets.OT3','timesheets.OT_HOD_Verified','timesheets.OT_Verified','timesheets.Remarks','timesheets.Deduction','files.Web_Path')
			->leftJoin('users', 'timesheets.UserId', '=', DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")'))
			->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
			->leftJoin('leaves','leaves.UserId','=',DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y") Between str_to_date(leaves.Start_Date,"%d-%M-%Y") and str_to_date(leaves.End_Date,"%d-%M-%Y")'))
			->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as maxleave'), 'maxleave.LeaveId', '=', 'leaves.Id')
			->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('maxleave.`maxid`'))
			->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as maxuser'), 'maxuser.TargetId', '=', 'users.Id')
			->leftJoin('files', 'files.Id', '=', DB::raw('maxuser.`maxid` and files.`Type`="User"'))
			->where('users.Name','<>','')
			// ->whereRaw('str_to_date(users.Resignation_Date,"%d-%M-%Y") > str_to_date("'.$end.'","%d-%M-%Y")')
			->whereIn('users.Department',$arrdepartment)
			->whereNotIn('users.Id',array(855, 883,902))
			->orderBy('users.Name','asc');

			if (! ($includeResigned == 'true')) {
				$timesheetdetail->whereRaw('(users.Resignation_Date = "" OR str_to_date(users.Resignation_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y"))');
			}

			$timesheetdetail = $timesheetdetail->get();

		}
		else {
			# code...
			$timesheetdetail = DB::table('timesheets')
			->select('timesheets.Id','timesheets.Id as TimesheetId','users.StaffId','users.Name','users.Resignation_Date','users.Company','users.Department','users.Position','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheets.Date',DB::raw('"" as Day'),'timesheets.Site_Name','timesheets.Code','users.Available','timesheets.Check_In_Type','leaves.Leave_Type','leavestatuses.Leave_Status',
			 'timesheets.Time_In','timesheets.Time_Out','timesheets.OT1','timesheets.OT2','timesheets.OT3','timesheets.OT_HOD_Verified','timesheets.OT_Verified','timesheets.Remarks','timesheets.Deduction','files.Web_Path')
			->leftJoin('users', 'timesheets.UserId', '=', DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")'))
			->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
			->leftJoin('leaves','leaves.UserId','=',DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y") Between str_to_date(leaves.Start_Date,"%d-%M-%Y") and str_to_date(leaves.End_Date,"%d-%M-%Y")'))
			->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as maxleave'), 'maxleave.LeaveId', '=', 'leaves.Id')
			->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('maxleave.`maxid`'))
			->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as maxuser'), 'maxuser.TargetId', '=', 'users.Id')
			->leftJoin('files', 'files.Id', '=', DB::raw('maxuser.`maxid` and files.`Type`="User"'))
			->where('users.Name','<>','')
			// ->whereRaw('str_to_date(users.Resignation_Date,"%d-%M-%Y") > str_to_date("'.$end.'","%d-%M-%Y")')
			->whereNotIn('users.Id',array(855, 883,902))
			->whereIn('users.Department', ['MY_Department_FAB','MY_Department_MDO'])
			->orderBy('users.Name','asc');

			if (! ($includeResigned == 'true')) {
				$timesheetdetail->whereRaw('(users.Resignation_Date = "" OR str_to_date(users.Resignation_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y"))');
			}

			$timesheetdetail = $timesheetdetail->get();
		}


		$codes = DB::table('scopeofwork')
		->orderBy('scopeofwork.Code')
		->get();

		return view('otmanagementhr', ['me' => $me, 'start'=>$start,'end'=>$end,'timesheetdetail' => $timesheetdetail,'codes'=>$codes, 'includeResigned' => $includeResigned]);
	}

	public function otmanagementhod($start = null, $end = null, $includeResigned = 'false')
	{
		$me = (new CommonController)->get_current_user();
		$today = date('d-M-Y', strtotime('today'));

		if ($start==null)
		{

			$start=date('d-M-Y', strtotime('today'));
		}

		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('today'));

		}

		$hod = DB::table('projects')
		->select('Project_Name')
		->where('projects.Project_Manager', '=', $me->UserId)
		->get();

		$arrdepartment=array();

		if($hod)
		{



			foreach ($hod as $department) {
				# code...
				array_push($arrdepartment,$department->Project_Name);
			}



			$timesheetdetail = DB::table('timesheets')
			->select('timesheets.Id','timesheets.Id as TimesheetId','users.StaffId','users.Name','users.Resignation_Date','users.Company','users.Department','users.Category','users.Position','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheets.Date',DB::raw('"" as Day'),'timesheets.Site_Name','timesheets.Code','users.Available','timesheets.Check_In_Type','leaves.Leave_Type','leavestatuses.Leave_Status',
			 'timesheets.Time_In','timesheets.Time_Out','timesheets.OT1','timesheets.OT2','timesheets.OT3','timesheets.OT_Verified','timesheets.OT_HOD_Verified','timesheets.Remarks','timesheets.Deduction','files.Web_Path')
			->leftJoin('users', 'timesheets.UserId', '=', DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")'))
			->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
			->leftJoin('leaves','leaves.UserId','=',DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y") Between str_to_date(leaves.Start_Date,"%d-%M-%Y") and str_to_date(leaves.End_Date,"%d-%M-%Y")'))
			->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as maxleave'), 'maxleave.LeaveId', '=', 'leaves.Id')
			->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('maxleave.`maxid`'))
			->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as maxuser'), 'maxuser.TargetId', '=', 'users.Id')
			->leftJoin('files', 'files.Id', '=', DB::raw('maxuser.`maxid` and files.`Type`="User"'))
			->where('users.Name','<>','')
			// ->whereRaw('str_to_date(users.Resignation_Date,"%d-%M-%Y") > str_to_date("'.$end.'","%d-%M-%Y")')
			->whereIn('users.Department',$arrdepartment)
			->whereNotIn('users.Id',array(855, 883,902))
			->orderBy('users.Name','asc');

			if (! ($includeResigned == 'true')) {
				$timesheetdetail->whereRaw('(users.Resignation_Date = "" OR str_to_date(users.Resignation_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y"))');
			}

			$timesheetdetail = $timesheetdetail->get();

		}
		else {
			# code...
			$timesheetdetail = DB::table('timesheets')
			->select('timesheets.Id','timesheets.Id as TimesheetId','users.StaffId','users.Name','users.Resignation_Date','users.Company','users.Department','users.Position','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheets.Date',DB::raw('"" as Day'),'timesheets.Site_Name','timesheets.Code','users.Available','timesheets.Check_In_Type','leaves.Leave_Type','leavestatuses.Leave_Status',
			 'timesheets.Time_In','timesheets.Time_Out','timesheets.OT1','timesheets.OT2','timesheets.OT3','timesheets.OT_Verified','timesheets.OT_HOD_Verified','timesheets.Remarks','timesheets.Deduction','files.Web_Path')
			->leftJoin('users', 'timesheets.UserId', '=', DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")'))
			->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
			->leftJoin('leaves','leaves.UserId','=',DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y") Between str_to_date(leaves.Start_Date,"%d-%M-%Y") and str_to_date(leaves.End_Date,"%d-%M-%Y")'))
			->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as maxleave'), 'maxleave.LeaveId', '=', 'leaves.Id')
			->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('maxleave.`maxid`'))
			->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as maxuser'), 'maxuser.TargetId', '=', 'users.Id')
			->leftJoin('files', 'files.Id', '=', DB::raw('maxuser.`maxid` and files.`Type`="User"'))
			->where('users.Name','<>','')
			// ->whereRaw('str_to_date(users.Resignation_Date,"%d-%M-%Y") > str_to_date("'.$end.'","%d-%M-%Y")')
			->whereNotIn('users.Id',array(855, 883,902))
			->whereIn('users.Department', ['MY_Department_FAB','MY_Department_MDO'])
			->orderBy('users.Name','asc');

			if (! ($includeResigned == 'true')) {
				$timesheetdetail->whereRaw('(users.Resignation_Date = "" OR str_to_date(users.Resignation_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y"))');
			}

			$timesheetdetail = $timesheetdetail->get();
		}


		$codes = DB::table('scopeofwork')
		->orderBy('scopeofwork.Code')
		->get();

		return view('otmanagementhod', ['me' => $me, 'start'=>$start,'end'=>$end,'timesheetdetail' => $timesheetdetail,'codes'=>$codes, 'includeResigned' => $includeResigned, 'arrdepartment' => $arrdepartment]);
	}

	public function MIAlist()
	{
		$me = (new CommonController)->get_current_user();

		$list = DB::table('mia')
		->leftJoin('users','users.Id','=','mia.UserId')
		->select('mia.Id','users.StaffId','users.Name','users.Company','mia.Ban_Date')
		->whereRaw('exclude = 0 AND users.Id NOT IN (562,1193,855) AND users.Resignation_Date = "" ') // exclude admin ,admin2 ,boss
		->get();

		$excluded = DB::table('mia')
		->leftJoin('users','users.Id','=','mia.UserId')
		->select('mia.Id','users.StaffId','users.Name','users.Company','mia.Ban_Date','mia.Remarks',DB::raw('(SELECT COUNT(UserId) FROM mia as a WHERE a.UserId = mia.UserId GROUP BY UserId) as count'))
		->whereRaw('mia.exclude = 1 AND users.Id NOT IN (562,1193,855)') //exclude admin, admin2, boss
		->get();

		return view('mialist', ['me' => $me,'list' => $list,'excluded' => $excluded]);
	}

	public function excludeMIA(Request $request)
	{
		$input = $request->all();

		if($input['remarks'] != "" || $input['remarks'] != null)
		{
			DB::table('mia')
			->where('Id','=',$input['id'])
			->update([
				'exclude' => 1,
				'Remarks' => $input['remarks']
			]);

			$userid = DB::table('mia')
			->where('Id','=',$input['id'])
			->select('UserId')
			->first();

			DB::table('users')
			->where('Id','=',$userid->UserId)
			->update([
				'mia' => 0
			]);

			return 1;
		}
		else
		{
			return 0;
		}

	}

	public function markResign(Request $request)
	{
		$input = $request->all();
		$userid = DB::table('mia')
			->where('Id','=',$input['id'])
			->select('UserId')
			->first();
		$today = date('d-M-Y',strtotime("today"));

		DB::table('users')
		->where('Id','=',$userid->UserId)
		->update([
			'Resignation_Date' => $today
		]);

		return 1;
	}

	public function tododashboard()
	{
		$me = (new CommonController)->get_current_user();
		$today = Carbon::now()->format('d-M-Y');

		if ($me->UserId == 855)
		{
			$user = DB::table('users')
			->select('Name','Id')
			->where('Active','=',1)
			->get();
		}
		elseif($me->Project_Manager)
        {
        	$projects = DB::Table('projects')
        	->where('projects.Project_Manager','=',$me->UserId)
        	->select('Id','projects.Project_Name')
        	->get();

        	$projectlist = array();

        	foreach ($projects as $key => $value) {
        		array_push($projectlist,$value->Project_Name);
        	}

			$user = DB::table('users')
			->select('Name','Id')
			->where('Active','=',1)
			->whereIn('Department',$projectlist)
			->groupby('Id')
			->get();

		}
		else
		{
			$user = DB::table('users')
			->select('Name','Id')
			->where('Id','=',$me->UserId)
			->get();
		}

		if(!$user)
		{
			$user = DB::table('users')
			->select('Name','Id')
			->where('Id','=',$me->UserId)
			->get();
		}

		$userlist = array();
		foreach ($user as $key => $value) {
			array_push($userlist, $value->Id);
		}

		$cond = implode(',', $userlist);

		$count = DB::table('taskstatuses')
		->select(DB::raw('(SELECT COUNT(taskstatuses.Id) FROM `taskstatuses`
					LEFT JOIN tasks ON taskstatuses.TaskId = tasks.Id
					WHERE Status = "Assigned" AND tasks.type = "Todo" AND tasks.UserId IN ('.$cond.') and taskstatuses.Id IN (select max(taskstatuses.Id) from taskstatuses left join tasks on tasks.Id=taskstatuses.TaskId group by tasks.Id)) as assigned'),
			DB::raw('(SELECT COUNT(taskstatuses.Id) FROM `taskstatuses`
					LEFT JOIN tasks ON taskstatuses.TaskId = tasks.Id
					WHERE Status = "In Progress" AND tasks.type = "Todo" AND tasks.UserId IN ('.$cond.') and taskstatuses.Id IN (select max(taskstatuses.Id) from taskstatuses left join tasks on tasks.Id=taskstatuses.TaskId group by tasks.Id)) as inprogress'),
			DB::raw('(SELECT COUNT(taskstatuses.Id) FROM `taskstatuses`
					LEFT JOIN tasks ON taskstatuses.TaskId = tasks.Id
					WHERE Status = "Rejected" AND tasks.type = "Todo" AND tasks.UserId IN ('.$cond.') and taskstatuses.Id IN (select max(taskstatuses.Id) from taskstatuses left join tasks on tasks.Id=taskstatuses.TaskId group by tasks.Id)) as rejected'),
			DB::raw('(SELECT COUNT(taskstatuses.Id) FROM `taskstatuses`
					LEFT JOIN tasks ON taskstatuses.TaskId = tasks.Id
					WHERE Status = "Completed" AND tasks.type = "Todo" AND tasks.UserId IN ('.$cond.') and taskstatuses.Id IN (select max(taskstatuses.Id) from taskstatuses left join tasks on tasks.Id=taskstatuses.TaskId group by tasks.Id)) as completed'))
		->first();

		$overduecompleted = DB::table('tasks')
		->select(DB::raw('COUNT(Id) as overduecompleted'))
		->whereRaw('complete_date !="" AND type = "Todo" AND tasks.UserId IN ('.$cond.') AND (str_to_date(concat(complete_date," ",complete_time),"%d-%M-%Y %H:%i:%s") > str_to_date(concat(target_date," ",target_time),"%d-%M-%Y %H:%i:%s"))')
		->first();

		$overdue = DB::table('tasks')
		->select(DB::raw('COUNT(Id) as overdue'))
		->whereRaw('target_date != "" AND complete_date = "" AND type = "Todo" AND tasks.UserId IN ('.$cond.') AND (str_to_date(NOW(),"%Y-%m-%d %H:%i:%s") > str_to_date(concat(target_date," ",target_time),"%d-%M-%Y %H:%i:%s"))')
		->first();

		$showtasks = DB::table('tasks')
			->leftJoin(DB::raw('(SELECT MAX(Id) as maxid,TaskId FROM taskstatuses GROUP BY TaskId) as max'),'tasks.Id','=','max.TaskId')
			->leftJoin('taskstatuses','taskstatuses.Id','=',DB::raw('max.`maxid`'))
			->leftJoin('users','users.Id','=','tasks.UserId')
			->leftJoin('users as assignby','assignby.Id','=','tasks.assign_by')
			->select('tasks.Id','users.Name','assignby.Name as assignby','tasks.Current_Task','tasks.Threshold','tasks.assign_date','tasks.target_date','tasks.complete_date','taskstatuses.Status')
			->where('tasks.type','=','Todo')
			->get();

		return view('tododashboard',['me'=> $me , 'count' => $count, 'overdue' => $overdue , 'showtasks' => $showtasks , 'overduecompleted' => $overduecompleted]);
	}

	public function cmedashboard($userid = null)
	{
		$me = (new CommonController)->get_current_user();
		$today = Carbon::now()->format('d-M-Y');
		if($userid == null)
		{
		$count = DB::table('taskstatuses')
		->select(DB::raw('(SELECT COUNT(distinct taskstatuses.Id) FROM `taskstatuses`
					LEFT JOIN tasks ON taskstatuses.TaskId = tasks.Id
					WHERE Status = "Assigned" AND tasks.type != "Todo" and taskstatuses.Id IN (select max(taskstatuses.Id) from taskstatuses left join tasks on tasks.Id=taskstatuses.TaskId group by tasks.Id)) as assigned'),
			DB::raw('(SELECT COUNT(distinct taskstatuses.Id) FROM `taskstatuses`
					LEFT JOIN tasks ON taskstatuses.TaskId = tasks.Id
					WHERE Status = "In Progress" AND tasks.type != "Todo" and taskstatuses.Id IN (select max(taskstatuses.Id) from taskstatuses left join tasks on tasks.Id=taskstatuses.TaskId group by concat(tasks.Project_Code,Current_Task))) as inprogress'),
			DB::raw('(SELECT COUNT(taskstatuses.Id) FROM `taskstatuses`
					LEFT JOIN tasks ON taskstatuses.TaskId = tasks.Id
					WHERE Status = "Rejected" AND tasks.type != "Todo") as rejected'),
			DB::raw('(SELECT COUNT(taskstatuses.Id) FROM `taskstatuses`
					LEFT JOIN tasks ON taskstatuses.TaskId = tasks.Id
					WHERE Status = "Completed" AND tasks.type != "Todo" and taskstatuses.Id IN (select max(taskstatuses.Id) from taskstatuses left join tasks on tasks.Id=taskstatuses.TaskId group by concat(tasks.Project_Code,Current_task))) as completed'))
		->first();

		$overduecompleted = DB::table('tasks')
		->select(DB::raw('COUNT(Id) as overduecompleted'))
		->whereRaw('complete_date !="" AND type != "Todo" AND (str_to_date(concat(complete_date," ",complete_time),"%d-%M-%Y% H:%i:%s") > str_to_date(concat(target_date," ",target_time),"%d-%M-%Y %H:%i:%s"))')
		->first();

		$overdue = DB::table('tasks')
		->select(DB::raw('COUNT(Id) as overdue'))
		->whereRaw('target_date != "" AND complete_date = "" AND type != "Todo" AND (str_to_date(NOW(),"%Y-%m-%d %H:%i:%s") > str_to_date(concat(target_date," ",target_time),"%d-%M-%Y %H:%i:%s"))')
		->first();
		}
		else
		{
		$count = DB::table('taskstatuses')
		->select(DB::raw('(SELECT COUNT(distinct taskstatuses.Id) FROM `taskstatuses`
					LEFT JOIN tasks ON taskstatuses.TaskId = tasks.Id
					WHERE Status = "Assigned" AND tasks.type != "Todo" AND tasks.UserId = "'.$userid.'" and taskstatuses.Id IN (select max(taskstatuses.Id) from taskstatuses left join tasks on tasks.Id=taskstatuses.TaskId group by tasks.Id)) as assigned'),
			DB::raw('(SELECT COUNT(distinct taskstatuses.Id) FROM `taskstatuses`
					LEFT JOIN tasks ON taskstatuses.TaskId = tasks.Id
					WHERE Status = "In Progress" AND tasks.type != "Todo" AND tasks.UserId = "'.$userid.'" and taskstatuses.Id IN (select max(taskstatuses.Id) from taskstatuses left join tasks on tasks.Id=taskstatuses.TaskId group by concat(tasks.Project_Code,Current_Task))) as inprogress'),
			DB::raw('(SELECT COUNT(taskstatuses.Id) FROM `taskstatuses`
					LEFT JOIN tasks ON taskstatuses.TaskId = tasks.Id
					WHERE Status = "Rejected" AND tasks.type != "Todo" AND tasks.UserId = "'.$userid.'") as rejected'),
			DB::raw('(SELECT COUNT(taskstatuses.Id) FROM `taskstatuses`
					LEFT JOIN tasks ON taskstatuses.TaskId = tasks.Id
					WHERE Status = "Completed" AND tasks.type != "Todo" AND tasks.UserId = "'.$userid.'" and taskstatuses.Id IN (select max(taskstatuses.Id) from taskstatuses left join tasks on tasks.Id=taskstatuses.TaskId group by concat(tasks.Project_Code,Current_task))) as completed'))
		->first();

		$overduecompleted = DB::table('tasks')
		->select(DB::raw('COUNT(Id) as overduecompleted'))
		->whereRaw('complete_date !="" AND type != "Todo" AND tasks.UserId = "'.$userid.'" AND (str_to_date(concat(complete_date," ",complete_time),"%d-%M-%Y% H:%i:%s") > str_to_date(concat(target_date," ",target_time),"%d-%M-%Y %H:%i:%s"))')
		->first();

		$overdue = DB::table('tasks')
		->select(DB::raw('COUNT(Id) as overdue'))
		->whereRaw('target_date != "" AND complete_date = "" AND type != "Todo" AND tasks.UserId = "'.$userid.'" AND (str_to_date(NOW(),"%Y-%m-%d %H:%i:%s") > str_to_date(concat(target_date," ",target_time),"%d-%M-%Y %H:%i:%s"))')
		->first();
		}

		$showtasks = DB::table('tasks')
			->leftJoin(DB::raw('(SELECT MAX(Id) as maxid,TaskId FROM taskstatuses GROUP BY TaskId) as max'),'tasks.Id','=','max.TaskId')
			->leftJoin('taskstatuses','taskstatuses.Id','=',DB::raw('max.`maxid`'))
			->leftJoin('users','users.Id','=','tasks.UserId')
			->leftJoin('users as assignby','assignby.Id','=','tasks.assign_by')
			->select('tasks.Id','users.Name','assignby.Name as assignby','tasks.Current_Task','tasks.Threshold','tasks.assign_date','tasks.target_date','tasks.complete_date','taskstatuses.Status')
			->where('tasks.type','<>','Todo')
			->get();

			$user = DB::table('users')
			->select('Id','Name')
			->get();

		return view('cmedashboard',['me'=> $me , 'count' => $count, 'overdue' => $overdue , 'showtasks' => $showtasks, 'overduecompleted'=>$overduecompleted,'user'=>$user , 'userid'=> $userid]);
	}

	public function tasklog($id)
	{
		$me = (new CommonController)->get_current_user();
		$log = DB::table('taskstatuses')
		->leftJoin('users','users.Id','=','taskstatuses.UserId')
		->select('users.Name','taskstatuses.Status','taskstatuses.Comment','taskstatuses.created_at')
		->where('TaskId','=',$id)
		->get();

		return view('tasklog',['log'=>$log,'me'=>$me]);
	}

	public function rejectedtaskrevoke($id, Request $request)
	{
		$me = (new CommonController)->get_current_user();

		DB::table('taskstatuses')
		->insert([
			'UserId' => $me->UserId,
			'TaskId' => $id,
			'Status' => "Revoke",
			'Comment' => $request->remarks,
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now()
		]);

		DB::table('taskstatuses')
		->insert([
			'UserId' => $me->UserId,
			'TaskId' => $id,
			'Status' => "Assigned",
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now()
		]);

		$pic = DB::table('tasks')
		->select('UserId')
		->where('Id','=',$id)
		->first();

		$getplayer = DB::table('users')
		->select('Player_Id')
		->where('Id','=',$pic->UserId)
		->first();

		$playerid = array();

		array_push($playerid,$getplayer->Player_Id);

		$this->newtask($playerid);

		return 1;
	}

	public function todolist($type = NULL , $start = NULL , $end = NULL , $userid = NULL )
	{
		$me = (new CommonController)->get_current_user();
		if ($start==null)
        {
            $start=date('d-M-Y', strtotime('first day of last month'));
        }
        if ($end==null)
        {
            $end=date('d-M-Y', strtotime('last day of this month'));
        }

		if ($me->UserId == 855)
		{
			$user = DB::table('users')
			->select('Name','Id')
			->where('Active','=',1)
			->get();
		}
        elseif($me->Project_Manager)
        {
        	$projects = DB::Table('projects')
        	->where('projects.Project_Manager','=',$me->UserId)
        	->select('Id','projects.Project_Name')
        	->get();

        	$projectlist = array();

        	foreach ($projects as $key => $value) {
        		array_push($projectlist,$value->Project_Name);
        	}

			$user = DB::table('users')
			->select('Name','Id')
			->where('Active','=',1)
			->whereIn('Department',$projectlist)
			->groupby('Id')
			->get();
		}
		else
		{
			$user = DB::table('users')
			->select('Name','Id')
			->where('Id','=',$me->UserId)
			->get();
		}

		if(!$user)
		{
			$user = DB::table('users')
			->select('Name','Id')
			->where('Id','=',$me->UserId)
			->get();
		}

		$userlist = array();
		foreach ($user as $key => $value) {
			array_push($userlist, $value->Id);
		}

		$list = DB::table('tasks')
		->leftJoin(DB::raw('(SELECT MAX(Id) as maxid,TaskId FROM taskstatuses GROUP BY TaskId) as max'),'tasks.Id','=','max.TaskId')
		->leftJoin('taskstatuses','taskstatuses.Id','=',DB::raw('max.`maxid`'))
		->leftJoin('users','users.Id','=','tasks.UserId')
		->leftJoin('users as assignby','assignby.Id','=','tasks.assign_by')
		->select('tasks.Id','users.Name','assignby.Name as assignby','tasks.Current_Task','tasks.assign_date',DB::raw('Concat(tasks.target_date," ",tasks.target_time) as target_date'),DB::raw('Concat(tasks.complete_date," ",tasks.complete_time) as complete_date'),'tasks.reminder','taskstatuses.Status','tasks.taskrepeat','tasks.repeattype')
		->whereRaw('str_to_date(tasks.assign_date,"%d-%M-%Y") Between str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y")')
		->whereRaw('taskstatuses.Id In (select max(taskstatuses.Id) from taskstatuses left join tasks on tasks.Id=taskstatuses.TaskId group by tasks.Id)')
		->where('tasks.type','=','Todo')
		->whereIn('tasks.UserId',$userlist);

		if(!$me->View_Todolist)
		{
			$list->whereRaw(' (tasks.UserId = '.$me->UserId.' OR tasks.assign_by = '.$me->UserId.')');
		}

		if( $userid == NULL || $userid == "All" )
		{
			if( $type == NULL || $type == "All")
			{

			}
			elseif ($type == "Overdue-Completed")
			{
				$list
				// ->whereRaw('tasks.complete_date !="" AND (str_to_date(tasks.complete_date,"%d-%M-%Y") > str_to_date(tasks.target_date,"%d-%M-%Y") AND str_to_date(complete_time,"%H:%i:%s") >= str_to_date(target_time,"%H:%i:%s"))');
				->whereRaw('tasks.complete_date !="" AND (str_to_date(concat(complete_date," ",complete_time),"%d-%M-%Y %H:%i:%s") > str_to_date(concat(target_date," ",target_time),"%d-%M-%Y %H:%i:%s"))');
			}
			elseif ($type == "Overdue")
			{
				$list
				// ->whereRaw('tasks.target_date != "" AND tasks.complete_date = "" AND (str_to_date(NOW(),"%Y-%m-%d") > str_to_date(tasks.target_date,"%d-%M-%Y") AND str_to_date(CURTIME(),"%H:%i:%s") > str_to_date(target_time,"%H:%i:%s")) ');
				->whereRaw('tasks.target_date != "" AND tasks.complete_date = "" AND (str_to_date(NOW(),"%Y-%m-%d %H:%i:%s") > str_to_date(concat(target_date," ",target_time),"%d-%M-%Y %H:%i:%s"))');
			}
			else
			{
				$list->where('taskstatuses.Status','=',$type);
			}

		}
		else
		{
			if( $type == NULL || $type == "All")
			{
					$list->where('tasks.UserId','=',$userid);
			}
			elseif ($type == "Overdue-Completed")
			{
				$list
				->whereRaw('tasks.complete_date !="" AND (str_to_date(concat(complete_date," ",complete_time),"%d-%M-%Y% H:%i:%s") > str_to_date(concat(target_date," ",target_time),"%d-%M-%Y %H:%i:%s"))');
				$list->where('tasks.UserId','=',$userid);
			}
			elseif ($type == "Overdue")
			{
				$list
				->whereRaw('tasks.target_date != "" AND tasks.complete_date = "" AND (str_to_date(NOW(),"%Y-%m-%d %H:%i:%s") > str_to_date(concat(target_date," ",target_time"),"%d-%M-%Y %H:%i:%s))');
				$list->where('tasks.UserId','=',$userid);
			}
			else
			{
				$list->where('taskstatuses.Status','=',$type);
				$list->where('tasks.UserId','=',$userid);
			}
		}
		$list = $list->get();
		return view('todolist',['me'=> $me , 'list' => $list, 'user' => $user, 'start' => $start, 'end' => $end,'type'=>$type]);
	}

	public function todolistgetdetails(Request $request)
	{
		$input = $request->all();
		$details = DB::table('tasks')
		->where('Id','=',$input['Id'])
		->select('*')
		->first();

		return response()->json(['details' => $details]);
	}

	public function todolistupdate(Request $request)
	{
		$input = $request->all();
		if($input['repeattype'] == "")
		{
			$taskrepeat = 0;
		}
		else
		{
			$taskrepeat = 1;
		}
		DB::table('tasks')
		->where('parentId','=',$input['taskid'])
		->update([
			'reminder' => $input['reminder'],
			'taskrepeat' => $taskrepeat,
			'repeattype' => $input['repeattype']
		]);

		return 1;
	}

	public function todolistCreate(Request $request)
	{
		$input = $request->all();
		if(!$input['assign_date'])
		{
			return 0;
		}

		if($input['repeattype'] == "")
		{
			$taskrepeat = 0;
		}
		else
		{
			$taskrepeat = 1;
		}

		$me = (new CommonController)->get_current_user();
		$id = DB::table('tasks')->insertGetId([
			'Current_Task' => $input['task'],
			'UserId' => $input['pic'],
			'assign_date' => $input['assign_date'],
			'created_at' => Carbon::now(),
			'assign_by' => $me->UserId,
			'type' => "Todo",
			'reminder' => $input['reminder'],
			'taskrepeat' => $taskrepeat,
			'repeattype' => $input['repeattype']
		]);

		DB::table('tasks')
		->where('Id','=',$id)
		->update([
			'parentId' => $id
		]);

		DB::table('taskstatuses')
		->insert([
			'TaskId' => $id,
			'UserId' => $me->UserId,
			'Status' => "Assigned",
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now()
		]);

		$playerid = DB::table('users')
		->select('Player_Id')
		->where('Id','=',$input['pic'])
		->first();

		$notifyplayerid=array();

        array_push($notifyplayerid,$playerid->Player_Id);

            if($notifyplayerid)
            {
                $this->newtask($notifyplayerid);
            }

		return 1;
	}

	public function todolistDelete(Request $request)
	{
		DB::table('tasks')
		->where('Id','=',$request->Id)
		->delete();

		DB::table('taskstatuses')
		->where('TaskId','=',$request->Id)
		->delete();

		return 1;
	}

	public function rejectedtask($start = NULL , $end = NULL)
	{
		$me = (new CommonController)->get_current_user();

		if ($start==null)
		{
			$start=date('d-M-Y', strtotime('first day of January'));
		}
		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('last day of this month'));
		}

		$user = DB::table('users')
		->select('Id','Name')
		->where('Active','=',1)
		->get();

		$list = DB::table('tasks')
			->leftJoin('users','users.Id','=','tasks.assign_by')
			->leftJoin('users as pic','pic.Id','=','tasks.UserId')
			->leftJoin(DB::raw('(SELECT Max(Id) as maxid, TaskId From taskstatuses GROUP BY TaskId) as max'),'max.TaskId','=','tasks.Id')
			->leftJoin('taskstatuses','taskstatuses.Id','=',DB::raw('max.maxid'))
			->select('tasks.Id','tasks.Current_Task','tasks.Previous_Task','tasks.Previous_Task_Date','tasks.Project_Code','tasks.Site_Name','tasks.Threshold','pic.Name as pic','tasks.assign_date','tasks.target_date','tasks.complete_date','users.Name','taskstatuses.Status','taskstatuses.Comment')
			->whereRaw('str_to_date(tasks.created_at,"%Y-%m-%d") Between str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y")')
			->where('type','<>','Todo')
			->where('taskstatuses.Status','=','Rejected')
			->get();

			return view('rejectedtask',['me'=> $me , 'list' => $list, 'user' => $user, 'start' => $start, 'end' => $end]);


	}

	public function taskslist($status = NULL,$start = NULL , $end = NULL , $userid = NULL )
	{
		$me = (new CommonController)->get_current_user();
		if($status == "null")
		{
			$status = null;
		}

		if($start == "null")
		{
			$start = null;
		}

		if($end == "null")
		{
			$end = null;
		}

		if ($start==null)
		{
				$start=date('d-M-Y', strtotime('first day of January'));
		}
		if ($end==null)
		{
				$end=date('d-M-Y', strtotime('last day of this month'));
		}

		$user = DB::Table('users')
		->select('Id','Name')
		->where('Active','=',1)
		->get();

		if($status=="Assigned" || $status=="Rejected")
		{

			$list = DB::table('tasks')
			->leftJoin('users','users.Id','=','tasks.assign_by')
			->leftJoin('users as pic','pic.Id','=','tasks.UserId')
			->leftJoin(DB::raw('(SELECT Max(Id) as maxid, TaskId From taskstatuses GROUP BY TaskId) as max'),'max.TaskId','=','tasks.Id')
			->leftJoin('taskstatuses','taskstatuses.Id','=',DB::raw('max.maxid'))
			->select('tasks.Id','tasks.Current_Task','tasks.Previous_Task','tasks.Previous_Task_Date','tasks.Project_Code','tasks.Site_Name','tasks.Threshold','pic.Name as pic','tasks.assign_date',DB::raw('concat(tasks.target_date," ",tasks.target_time) as target_date'),DB::raw('concat(tasks.complete_date," ",tasks.complete_time) as complete_date'),'users.Name','taskstatuses.Status')
			->whereRaw('str_to_date(tasks.created_at,"%Y-%m-%d") Between str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y")')
			->whereRaw('taskstatuses.Id In (select max(taskstatuses.Id) from taskstatuses left join tasks on tasks.Id=taskstatuses.TaskId group by tasks.Id)')
			->where('type','<>','Todo');
		}
		else if($status=="Overdue-Completed")
		{
			$list = DB::table('tasks')
			->leftJoin('users','users.Id','=','tasks.assign_by')
			->leftJoin('users as pic','pic.Id','=','tasks.UserId')
			->leftJoin(DB::raw('(SELECT Max(Id) as maxid, TaskId From taskstatuses GROUP BY TaskId) as max'),'max.TaskId','=','tasks.Id')
			->leftJoin('taskstatuses','taskstatuses.Id','=',DB::raw('max.maxid'))
			->select('tasks.Id','tasks.Current_Task','tasks.Previous_Task','tasks.Previous_Task_Date','tasks.Project_Code','tasks.Site_Name','tasks.Threshold','pic.Name as pic','tasks.assign_date',DB::raw('concat(tasks.target_date," ",tasks.target_time) as target_date'),DB::raw('concat(tasks.complete_date," ",tasks.complete_time) as complete_date'),'users.Name','taskstatuses.Status')
			->whereRaw('str_to_date(tasks.created_at,"%Y-%m-%d") Between str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y")')
			// ->whereRaw('taskstatuses.Id In (select max(taskstatuses.Id) from taskstatuses left join tasks on tasks.Id=taskstatuses.TaskId group by tasks.Id)')
			->where('type','<>','Todo');
		}
		else if($status == "Overdue")
		{
			$list = DB::table('tasks')
			->leftJoin('users','users.Id','=','tasks.assign_by')
			->leftJoin('users as pic','pic.Id','=','tasks.UserId')
			->leftJoin(DB::raw('(SELECT Max(Id) as maxid, TaskId From taskstatuses GROUP BY TaskId) as max'),'max.TaskId','=','tasks.Id')
			->leftJoin('taskstatuses','taskstatuses.Id','=',DB::raw('max.maxid'))
			->select('tasks.Id','tasks.Current_Task','tasks.Previous_Task','tasks.Previous_Task_Date','tasks.Project_Code','tasks.Site_Name','tasks.Threshold','pic.Name as pic','tasks.assign_date',DB::raw('concat(tasks.target_date," ",tasks.target_time) as target_date'),DB::raw('concat(tasks.complete_date," ",tasks.complete_time) as complete_date'),'users.Name','taskstatuses.Status')
			->whereRaw('str_to_date(tasks.created_at,"%Y-%m-%d") Between str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y")')
			// ->whereRaw('taskstatuses.Id In (select max(taskstatuses.Id) from taskstatuses left join tasks on tasks.Id=taskstatuses.TaskId group by tasks.Id)')
			->where('type','<>','Todo');
		}
		else {
			// code...
			$list = DB::table('tasks')
			->leftJoin('users','users.Id','=','tasks.assign_by')
			->leftJoin('users as pic','pic.Id','=','tasks.UserId')
			->leftJoin(DB::raw('(SELECT Max(Id) as maxid, TaskId From taskstatuses GROUP BY TaskId) as max'),'max.TaskId','=','tasks.Id')
			->leftJoin('taskstatuses','taskstatuses.Id','=',DB::raw('max.maxid'))
			->select('tasks.Id','tasks.Current_Task','tasks.Previous_Task','tasks.Previous_Task_Date','tasks.Project_Code','tasks.Site_Name','tasks.Threshold','pic.Name as pic','tasks.assign_date',DB::raw('concat(tasks.target_date," ",tasks.target_time) as target_date'),DB::raw('concat(tasks.complete_date," ",tasks.complete_time) as complete_date'),'users.Name','taskstatuses.Status')
			->whereRaw('taskstatuses.Id In (select max(taskstatuses.Id) from taskstatuses left join tasks on tasks.Id=taskstatuses.TaskId group by concat(Project_Code,Current_Task))')
			->where('type','<>','Todo');
		}
		if($userid != NULL && $userid != "All")
		{
			$list->where('pic.Id','=',$userid);
		}

		if($status != NULL && $status != "Overdue" && $status != "Overdue-Completed")
		{
			$list->where('taskstatuses.Status','=',$status);
		}
		else if($status == "Overdue-Completed")
		{
			$list->whereRaw('complete_date != "" AND (str_to_date(concat(complete_date," ",complete_time),"%d-%M-%Y% H:%i:%s") > str_to_date(concat(target_date," ",target_time),"%d-%M-%Y %H:%i:%s"))');
		}
		else if($status == "Overdue")
		{
			$list->whereRaw('target_date != "" AND complete_date = "" AND (str_to_date(NOW(),"%Y-%m-%d %H:%i:%s") > str_to_date(concat(target_date," ",target_time),"%d-%M-%Y %H:%i:%s"))');
		}

		$list = $list->get();
		return view('taskslist',['me'=> $me , 'list' => $list, 'user' => $user, 'start' => $start, 'end' => $end,'status'=>$status]);
	}

	public function completetodo(Request $request)
	{
		$me = (new CommonController)->get_current_user();

		DB::table('tasks')
		->where('Id',$request->id)
		->update([
			'complete_date' => Carbon::now()->format('d-M-Y'),
			'complete_time' => Carbon::now()->format('H:i:s')
		]);

		DB::Table('taskstatuses')
		->insert([
			'TaskId' => $request->id,
			'UserId' => $me->UserId,
			'Status' => "Completed",
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now()
		]);
		return 1;
	}

	public function accepttodo(Request $request)
	{
		$me = (new CommonController)->get_current_user();
		if(!$request->date || !$request->time)
		{
			return "Date and Time is required";
		}

		DB::table('tasks')
		->where('Id',$request->id)
		->update([
			'target_date' => $request->date,
			'target_time' => $request->time
		]);

		DB::Table('taskstatuses')
		->insert([
			'TaskId' => $request->id,
			'UserId' => $me->UserId,
			'Status' => "In Progress",
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now()
		]);
		return 1;
	}

	public function otwgetpoints($id)
	{
		$item = DB::table('timesheetotw')
		->where('TimesheetId','=',$id)
		->select('Latitude','Longitude','Time')
		->get();

		return response()->json(['item'=>$item]);
	}

	 function newtask($playerids)
    {

            // $me = JWTAuth::parseToken()->authenticate();
        $me = (new CommonController)->get_current_user();


            $content = array(
                "en" => 'New Task Assigned'
            );

            $fields = array(
                'app_id' => "b22a7a60-2cfa-4641-a309-4720c564fddf",
                // 'included_segments' => array("All"),
                'include_player_ids' =>$playerids,
                // 'data' => array("type" => "NewTrip"),
                'contents' => $content,
            );

            $fields = json_encode($fields);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic NWU1YjI2ZmYtOTM3NS00NWRkLTk2YTYtOTM5N2Y3NGJhNDY4'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $response = curl_exec($ch);
            curl_close($ch);
    }

    public function staffnotimein($start = null, $end = null, $userid = null, $includeResigned = 'false')
	{
		$me = (new CommonController)->get_current_user();
		$today = date('d-M-Y', strtotime('today'));
		if ($start==null)
		{

			$start=date('d-M-Y', strtotime('today'));
		}

		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('today'));

		}
		
		if($userid == null){

			$hod = DB::table('projects')
			->select('Project_Name')
			->where('projects.Project_Manager', '=', $me->UserId)
			->get();
			$arrdepartment=array();

			if($hod && !$me->Admin)
			{


				foreach ($hod as $department) {
					# code...
					array_push($arrdepartment,$department->Project_Name);
				}

				$timesheetdetail = DB::table('timesheets')
				->select('timesheets.Id','timesheets.Id as TimesheetId','users.StaffId','users.Name','users.Resignation_Date','users.Company','users.Department','users.Category','users.Position','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheets.Date',DB::raw('"" as Day'),'timesheets.Site_Name','timesheets.Code','users.Available', DB::raw('(TimeDIFF(STR_TO_DATE(Time_Out,"%h:%i %p"),STR_TO_DATE(Time_In,"%h:%i %p"))) as timediff'),'timesheets.total_distance','timesheets.Check_In_Type','leaves.Leave_Type','leavestatuses.Leave_Status',
				 'timesheets.Time_In','timesheets.Time_Out','timesheets.Remarks','timesheets.Deduction','files.Web_Path')
				->leftJoin('users', 'timesheets.UserId', '=', DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")'))
				->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
				->leftJoin('leaves','leaves.UserId','=',DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y") Between str_to_date(leaves.Start_Date,"%d-%M-%Y") and str_to_date(leaves.End_Date,"%d-%M-%Y")'))
				->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as maxleave'), 'maxleave.LeaveId', '=', 'leaves.Id')
				->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('maxleave.`maxid`'))
				->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as maxuser'), 'maxuser.TargetId', '=', 'users.Id')
				->leftJoin('files', 'files.Id', '=', DB::raw('maxuser.`maxid` and files.`Type`="User"'))
				->where('users.Name','<>','')
				// ->whereRaw('str_to_date(users.Resignation_Date,"%d-%M-%Y") > str_to_date("'.$end.'","%d-%M-%Y")')
				->whereIn('users.Department',$arrdepartment)
				->whereNotIn('users.Id',array(855, 883,902))
				->orderBy('users.Name','asc');

				if (! ($includeResigned == 'true')) {
					$timesheetdetail->whereRaw('(users.Resignation_Date = "" OR str_to_date(users.Resignation_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y"))');
				}

				$timesheetdetail = $timesheetdetail->get();

			}
			else {
				# code...
				$timesheetdetail = DB::table('timesheets')
				->select('timesheets.Id','timesheets.Id as TimesheetId','users.StaffId','users.Name','users.Resignation_Date','users.Company','users.Department','users.Category','users.Position','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheets.Date',DB::raw('"" as Day'),'timesheets.Site_Name','timesheets.Code','users.Available',DB::raw('(TimeDIFF(STR_TO_DATE(Time_Out,"%h:%i %p"),STR_TO_DATE(Time_In,"%h:%i %p"))) as timediff'),'timesheets.total_distance','timesheets.Check_In_Type','leaves.Leave_Type','leavestatuses.Leave_Status',
				 'timesheets.Time_In','timesheets.Time_Out','timesheets.Remarks','timesheets.Deduction','files.Web_Path')
				->leftJoin('users', 'timesheets.UserId', '=', DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")'))
				->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
				->leftJoin('leaves','leaves.UserId','=',DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y") Between str_to_date(leaves.Start_Date,"%d-%M-%Y") and str_to_date(leaves.End_Date,"%d-%M-%Y")'))
				->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as maxleave'), 'maxleave.LeaveId', '=', 'leaves.Id')
				->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('maxleave.`maxid`'))
				->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as maxuser'), 'maxuser.TargetId', '=', 'users.Id')
				->leftJoin('files', 'files.Id', '=', DB::raw('maxuser.`maxid` and files.`Type`="User"'))
				->where('users.Name','<>','')
				// ->whereRaw('str_to_date(users.Resignation_Date,"%d-%M-%Y") > str_to_date("'.$end.'","%d-%M-%Y")')
				->whereNotIn('users.Id',array(855, 883,902))
				->orderBy('users.Name','asc');

				if (! ($includeResigned == 'true')) {
					$timesheetdetail->whereRaw('(users.Resignation_Date = "" OR str_to_date(users.Resignation_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y"))');
				}

				$timesheetdetail = $timesheetdetail->get();
			}


			$codes = DB::table('scopeofwork')
			->orderBy('scopeofwork.Code')
			->get();
		}
		else{
			$hod = DB::table('projects')
			->select('Project_Name')
			->where('projects.Project_Manager', '=', $me->UserId)
			->get();
			$arrdepartment=array();

			if($hod && !$me->Admin)
			{


				foreach ($hod as $department) {
					# code...
					array_push($arrdepartment,$department->Project_Name);
				}

				$timesheetdetail = DB::table('timesheets')
				->select('timesheets.Id','timesheets.Id as TimesheetId','users.StaffId','users.Name','users.Resignation_Date','users.Company','users.Department','users.Category','users.Position','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheets.Date',DB::raw('"" as Day'),'timesheets.Site_Name','timesheets.Code','users.Available', DB::raw('(TimeDIFF(STR_TO_DATE(Time_Out,"%h:%i %p"),STR_TO_DATE(Time_In,"%h:%i %p"))) as timediff'),'timesheets.total_distance','timesheets.Check_In_Type','leaves.Leave_Type','leavestatuses.Leave_Status',
				 'timesheets.Time_In','timesheets.Time_Out','timesheets.Remarks','timesheets.Deduction','files.Web_Path')
				->leftJoin('users', 'timesheets.UserId', '=', DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")'))
				->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
				->leftJoin('leaves','leaves.UserId','=',DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y") Between str_to_date(leaves.Start_Date,"%d-%M-%Y") and str_to_date(leaves.End_Date,"%d-%M-%Y")'))
				->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as maxleave'), 'maxleave.LeaveId', '=', 'leaves.Id')
				->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('maxleave.`maxid`'))
				->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as maxuser'), 'maxuser.TargetId', '=', 'users.Id')
				->leftJoin('files', 'files.Id', '=', DB::raw('maxuser.`maxid` and files.`Type`="User"'))
				->where('users.Name','<>','')
				->whereRaw('timesheets.UserId = "'.$userid.'"')
				// ->whereRaw('str_to_date(users.Resignation_Date,"%d-%M-%Y") > str_to_date("'.$end.'","%d-%M-%Y")')
				->whereIn('users.Department',$arrdepartment)
				->whereNotIn('users.Id',array(855, 883,902))
				->orderBy('users.Name','asc');

				if (! ($includeResigned == 'true')) {
					$timesheetdetail->whereRaw('(users.Resignation_Date = "" OR str_to_date(users.Resignation_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y"))');
				}

				$timesheetdetail = $timesheetdetail->get();

			}
			else {
				# code...
				$timesheetdetail = DB::table('timesheets')
				->select('timesheets.Id','timesheets.Id as TimesheetId','users.StaffId','users.Name','users.Resignation_Date','users.Company','users.Department','users.Category','users.Position','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheets.Date',DB::raw('"" as Day'),'timesheets.Site_Name','timesheets.Code','users.Available',DB::raw('(TimeDIFF(STR_TO_DATE(Time_Out,"%h:%i %p"),STR_TO_DATE(Time_In,"%h:%i %p"))) as timediff'),'timesheets.total_distance','timesheets.Check_In_Type','leaves.Leave_Type','leavestatuses.Leave_Status',
				 'timesheets.Time_In','timesheets.Time_Out','timesheets.Remarks','timesheets.Deduction','files.Web_Path')
				->leftJoin('users', 'timesheets.UserId', '=', DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")'))
				->leftJoin('projects', 'timesheets.ProjectId', '=', 'projects.Id')
				->leftJoin('leaves','leaves.UserId','=',DB::raw('users.Id AND str_to_date(timesheets.Date,"%d-%M-%Y") Between str_to_date(leaves.Start_Date,"%d-%M-%Y") and str_to_date(leaves.End_Date,"%d-%M-%Y")'))
				->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as maxleave'), 'maxleave.LeaveId', '=', 'leaves.Id')
				->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('maxleave.`maxid`'))
				->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as maxuser'), 'maxuser.TargetId', '=', 'users.Id')
				->leftJoin('files', 'files.Id', '=', DB::raw('maxuser.`maxid` and files.`Type`="User"'))
				->where('users.Name','<>','')
				->whereRaw('timesheets.UserId = "'.$userid.'"')
				// ->whereRaw('str_to_date(users.Resignation_Date,"%d-%M-%Y") > str_to_date("'.$end.'","%d-%M-%Y")')
				->whereNotIn('users.Id',array(855, 883,902))
				->orderBy('users.Name','asc');

				if (! ($includeResigned == 'true')) {
					$timesheetdetail->whereRaw('(users.Resignation_Date = "" OR str_to_date(users.Resignation_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y"))');
				}

				$timesheetdetail = $timesheetdetail->get();
			}

			$codes = DB::table('scopeofwork')
			->orderBy('scopeofwork.Code')
			->get();
		}

		$user = DB::table('users')
		->select('Id','Name')
		->get();

		return view('staffnotimein', ['me' => $me, 'start'=>$start,'end'=>$end, 'userid'=>$userid , 'user'=>$user ,'timesheetdetail' => $timesheetdetail,'codes'=>$codes, 'includeResigned' => $includeResigned, 'arrdepartment' => $arrdepartment]);
	}
}
