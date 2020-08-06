<?php namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use DateTime;
use Carbon\Carbon;

use Dompdf\Dompdf;

class HumanResourceController extends Controller {


/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index($start = null, $end = null)
	{
		$me = (new CommonController)->get_current_user();
		$today = date('d-M-Y',strtotime('today'));

		if($start == null)
		{
			$start = date('d-M-Y',strtotime('today'));

		}
		if($end == null)
		{
			$end = date('d-M-Y',strtotime('today'));
		}

		$finalapprovedleave = DB::table('leaves')
            ->leftJoin(DB::raw('(SELECT Max(Id) as maxid,LeaveId from leavestatuses  GROUP BY LeaveId) as max'),'max.LeaveId', '=', 'leaves.Id')
            ->leftJoin('leavestatuses','leavestatuses.Id','=','max.maxid')
            ->select(DB::raw('(SELECT COUNT(leavestatuses.Id)) as count'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "1 Hour Time Off") as 1_Hour_Time_Off'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "2 Hours Time Off") as 2_Hours_Time_Off'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "3 Hours Time Off") as 3_Hours_Time_Off'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Annual Leave") as Annual_Leave'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Compassionate Leave") as Compassionate_Leave'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Emergency Leave") as Emergency_Leave'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Hospitalization Leave") as Hospitalization_Leave'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Marriage Leave") as Marriage_Leave'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Maternity Leave") as Maternity_Leave'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Medical Leave") as Medical_Leave'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Paternity Leave") as Paternity_Leave'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Replacement Leave") as Replacement_Leave'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Sick Leave") as Sick_Leave'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Unpaid Leave") as Unpaid_Leave'))
			->where('leavestatuses.Leave_Status', 'like','%Approved%')
            ->whereRaw('(str_to_date(leaves.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

        $staffconfirm = DB::table('users')
        	->select(DB::raw('COUNT(users.Id) as count'))
        	->where(DB::raw('str_to_date(users.Confirmation_Date,"%d-%M-%Y")'),'>=',DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
        	->where(DB::raw('str_to_date(users.Confirmation_Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
        	->where('Active',1)
            ->first();

        $newstaffjoin = DB::table('users')
        	->select(DB::raw('COUNT(users.Id) as count'))
			->where(DB::raw('str_to_date(users.Joining_Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
			->where(DB::raw('str_to_date(users.Joining_Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
			->where('Active',1)
            ->first();

        $staffresigned = DB::table('users')
        	->select(DB::raw('COUNT(users.Id) as count'))
			->where('users.Resignation_Date','!=','')
			->where(DB::raw('str_to_date(users.Resignation_Date,"%d-%M-%Y")'),'>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
			->where(DB::raw('str_to_date(users.Resignation_Date,"%d-%M-%Y")'),'<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
			->first();

		$staffloanpending = DB::table('staffloanstatuses')
        	->select(DB::raw('COUNT(staffloanstatuses.Id) as count'))
			->where('staffloanstatuses.Status','like','%Pending Approval%')
			->first();

		$approvedloan = DB::table('staffloanstatuses')
        	->select(DB::raw('COUNT(staffloanstatuses.Id) as count'))
			->where('staffloanstatuses.Status','like',"%Approve%")
			->first();
	
		$totalstaffloan = DB::table('staffloan')
        	->select(DB::raw('SUM(staffloan.Amount) as sum'))
			->where('staffloan.Date_Approved','<>',"")
			->first();

		$totalrepayment = DB::table('staffdeductions')
        	->select(DB::raw('SUM(staffdeductions.Amount) as sum'))
			->first();

		return view('humanresourcedashboard', ['me'=>$me,'finalapprovedleave'=> $finalapprovedleave, 'staffconfirm' => $staffconfirm, 'newstaffjoin' => $newstaffjoin, 'staffresigned' => $staffresigned, 'staffloanpending' => $staffloanpending,'approvedloan' => $approvedloan, 'totalstaffloan' => $totalstaffloan, 'totalrepayment' => $totalrepayment, 'start'=>$start , 'end'=>$end ,'today' => $today]);
	}

	public function onleavetodaydashboard($start = null, $end = null){
		$me = (new CommonController)->get_current_user();
		$today = date('d-M-Y',strtotime('today'));

		if($start == null)
		{
			$start = date('d-M-Y',strtotime('today'));

		}
		if($end == null)
		{
			$end = date('d-M-Y',strtotime('today'));
		}

		$finalapprovedleave = DB::Table('leaves')
		->select(
			DB::raw('(SELECT COUNT(leaves.Id) FROM leaves left join (SELECT Max(Id) as maxid,LeaveId from leavestatuses GROUP BY LeaveId) as max on max.LeaveId = leaves.Id left join `leavestatuses` on `leavestatuses`.`Id` = max.maxid WHERE leaves.Leave_Type = "1 Hour Time Off" AND leavestatuses.Leave_Status LIKE "%Approve%" AND str_to_date(leaves.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")) "1 Hour Time Off"'),
			DB::raw('(SELECT COUNT(leaves.Id) FROM leaves left join (SELECT Max(Id) as maxid,LeaveId from leavestatuses GROUP BY LeaveId) as max on max.LeaveId = leaves.Id left join `leavestatuses` on `leavestatuses`.`Id` = max.maxid WHERE leaves.Leave_Type = "2 Hours Time Off" AND leavestatuses.Leave_Status LIKE "%Approve%" AND str_to_date(leaves.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")) "2 Hours Time Off"'),
			DB::raw('(SELECT COUNT(leaves.Id) FROM leaves left join (SELECT Max(Id) as maxid,LeaveId from leavestatuses GROUP BY LeaveId) as max on max.LeaveId = leaves.Id left join `leavestatuses` on `leavestatuses`.`Id` = max.maxid WHERE leaves.Leave_Type = "3 Hours Time Off" AND leavestatuses.Leave_Status LIKE "%Approve%" AND str_to_date(leaves.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")) "3 Hours Time Off"'),
			DB::raw('(SELECT COUNT(leaves.Id) FROM leaves left join (SELECT Max(Id) as maxid,LeaveId from leavestatuses GROUP BY LeaveId) as max on max.LeaveId = leaves.Id left join `leavestatuses` on `leavestatuses`.`Id` = max.maxid WHERE leaves.Leave_Type = "Annual Leave" AND leavestatuses.Leave_Status LIKE "%Approve%" AND str_to_date(leaves.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")) "Annual Leave"'),
			DB::raw('(SELECT COUNT(leaves.Id) FROM leaves left join (SELECT Max(Id) as maxid,LeaveId from leavestatuses GROUP BY LeaveId) as max on max.LeaveId = leaves.Id left join `leavestatuses` on `leavestatuses`.`Id` = max.maxid WHERE leaves.Leave_Type = "Compassionate Leave" AND leavestatuses.Leave_Status LIKE "%Approve%" AND str_to_date(leaves.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")) "Compassionate Leave"'),
			DB::raw('(SELECT COUNT(leaves.Id) FROM leaves left join (SELECT Max(Id) as maxid,LeaveId from leavestatuses GROUP BY LeaveId) as max on max.LeaveId = leaves.Id left join `leavestatuses` on `leavestatuses`.`Id` = max.maxid WHERE leaves.Leave_Type = "Emergency Leave" AND leavestatuses.Leave_Status LIKE "%Approve%" AND str_to_date(leaves.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")) "Emergency Leave"'),
			DB::raw('(SELECT COUNT(leaves.Id) FROM leaves left join (SELECT Max(Id) as maxid,LeaveId from leavestatuses GROUP BY LeaveId) as max on max.LeaveId = leaves.Id left join `leavestatuses` on `leavestatuses`.`Id` = max.maxid WHERE leaves.Leave_Type = "Hospitalization Leave" AND leavestatuses.Leave_Status LIKE "%Approve%" AND str_to_date(leaves.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")) "Hospitalization Leave"'),
			DB::raw('(SELECT COUNT(leaves.Id) FROM leaves left join (SELECT Max(Id) as maxid,LeaveId from leavestatuses GROUP BY LeaveId) as max on max.LeaveId = leaves.Id left join `leavestatuses` on `leavestatuses`.`Id` = max.maxid WHERE leaves.Leave_Type = "Marriage Leave" AND leavestatuses.Leave_Status LIKE "%Approve%" AND str_to_date(leaves.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")) "Marriage Leave"'),
			DB::raw('(SELECT COUNT(leaves.Id) FROM leaves left join (SELECT Max(Id) as maxid,LeaveId from leavestatuses GROUP BY LeaveId) as max on max.LeaveId = leaves.Id left join `leavestatuses` on `leavestatuses`.`Id` = max.maxid WHERE leaves.Leave_Type = "Maternity Leave" AND leavestatuses.Leave_Status LIKE "%Approve%" AND str_to_date(leaves.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")) "Maternity Leave"'),
			DB::raw('(SELECT COUNT(leaves.Id) FROM leaves left join (SELECT Max(Id) as maxid,LeaveId from leavestatuses GROUP BY LeaveId) as max on max.LeaveId = leaves.Id left join `leavestatuses` on `leavestatuses`.`Id` = max.maxid WHERE leaves.Leave_Type = "Medical Leave" AND leavestatuses.Leave_Status LIKE "%Approve%" AND str_to_date(leaves.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")) "Medical Leave"'),
			DB::raw('(SELECT COUNT(leaves.Id) FROM leaves left join (SELECT Max(Id) as maxid,LeaveId from leavestatuses GROUP BY LeaveId) as max on max.LeaveId = leaves.Id left join `leavestatuses` on `leavestatuses`.`Id` = max.maxid WHERE leaves.Leave_Type = "Paternity Leave" AND leavestatuses.Leave_Status LIKE "%Approve%" AND str_to_date(leaves.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")) "Paternity Leave"'),
			DB::raw('(SELECT COUNT(leaves.Id) FROM leaves left join (SELECT Max(Id) as maxid,LeaveId from leavestatuses GROUP BY LeaveId) as max on max.LeaveId = leaves.Id left join `leavestatuses` on `leavestatuses`.`Id` = max.maxid WHERE leaves.Leave_Type = "Replacement Leave" AND leavestatuses.Leave_Status LIKE "%Approve%" AND str_to_date(leaves.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")) "Replacement Leave"'),
			DB::raw('(SELECT COUNT(leaves.Id) FROM leaves left join (SELECT Max(Id) as maxid,LeaveId from leavestatuses GROUP BY LeaveId) as max on max.LeaveId = leaves.Id left join `leavestatuses` on `leavestatuses`.`Id` = max.maxid WHERE leaves.Leave_Type = "Sick Leave" AND leavestatuses.Leave_Status LIKE "%Approve%" AND str_to_date(leaves.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")) "Sick Leave"'),
			DB::raw('(SELECT COUNT(leaves.Id) FROM leaves left join (SELECT Max(Id) as maxid,LeaveId from leavestatuses GROUP BY LeaveId) as max on max.LeaveId = leaves.Id left join `leavestatuses` on `leavestatuses`.`Id` = max.maxid WHERE leaves.Leave_Type = "Unpaid Leave" AND leavestatuses.Leave_Status LIKE "%Approve%" AND str_to_date(leaves.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")) "Unpaid Leave"')
		)
        ->first();
            return view('onleavetodaydashboard', ['me'=>$me,'finalapprovedleave'=> $finalapprovedleave, 'start'=>$start , 'end'=>$end ,'today' => $today]);
	}
	public function leavetoday($start = null, $end = null, $k = null){

		$me=(new CommonController)->get_current_user();

        $today=date("d-M-Y");

        if($start == null)
		{
			$start = date('d-M-Y',strtotime('today'));

		}
		if($end == null)
		{
			$end = date('d-M-Y',strtotime('today'));
		}

        if($me->View_All_Leave)
        {
            $showleave = DB::table('leaves')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
            ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
            ->leftJoin(DB::raw('(SELECT leave_terms.Leave_Id, GROUP_CONCAT(DISTINCT CONCAT(\'[\',SUBSTR(leave_terms.Leave_Date,1,6),\' \',leave_terms.Leave_Period,\']\') ORDER BY leave_terms.Id SEPARATOR \',\') as Terms FROM leave_terms WHERE str_to_date(leave_terms.Leave_Date,"%d-%M-%Y") BETWEEN str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y") Group By leave_terms.Leave_Id) as leave_terms'), 'leave_terms.Leave_Id', '=', 'leaves.Id')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
            ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))
            ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
            ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
            ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
            ->select('leaves.Id','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leave_terms.Terms','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.Comment','leavestatuses.updated_at as Review_Date')
            ->where('leaves.Leave_Type',$k)
            ->where('leavestatuses.Leave_Status', '<>','Cancelled')
            ->whereRaw('(str_to_date(leaves.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") OR str_to_date(leaves.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            // ->where(DB::raw('str_to_date(leaves.Start_Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
            ->orderBy('leaves.Id','desc')
            ->get();

        }
        else {
            # code...
            $showleave = DB::table('leaves')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
            ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
            ->leftJoin(DB::raw('(SELECT leave_terms.Leave_Id, GROUP_CONCAT(DISTINCT CONCAT(\'[\',SUBSTR(leave_terms.Leave_Date,1,6),\' \',leave_terms.Leave_Period,\']\') ORDER BY leave_terms.Id SEPARATOR \',\') as Terms FROM leave_terms WHERE str_to_date(leave_terms.Leave_Date,"%d-%M-%Y") BETWEEN str_to_date("'.$end.'","%d-%M-%Y") AND str_to_date("'.$start.'","%d-%M-%Y") Group By leave_terms.Leave_Id) as leave_terms'), 'leave_terms.Leave_Id', '=', 'leaves.Id')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
            ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))
            ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
            ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
            ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
            ->select('leaves.Id','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leave_terms.Terms','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.Comment','leavestatuses.updated_at as Review_Date')
            ->where('leaves.Leave_Type',$k)
            ->where('leavestatuses.Leave_Status', '<>','Cancelled')
            ->where('leavestatuses.UserId', '=',$me->UserId)
            ->whereRaw('(str_to_date(leaves.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") OR str_to_date(leaves.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            // ->where(DB::raw('str_to_date(leaves.Start_Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
            // ->orWhere(DB::raw('str_to_date(leaves.End_Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
            ->orderBy('leaves.Id','desc')
            ->get();
        }

        $finalapprovedleave =  DB::table('leaves')
        ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
            ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
            ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
            ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))
            ->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
            ->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
            ->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
            ->select('leavestatuses.Id','leaves.Id as LeaveId','leavestatuses.Leave_Status as Status','applicant.StaffId as Staff_ID','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.Medical_Claim','leaves.Panel_Claim','leaves.Verified_By_HR','leaves.Medical_Paid_Month','leaves.created_at as Application_Date','projects.Project_Name','approver.Name as Approver','leavestatuses.Comment','leavestatuses.updated_at as Review_Date','files.Web_Path')
            ->where('leavestatuses.Leave_Status', 'like','%Final Approved%')
            ->where('leaves.Leave_Type',$k)
            ->orderBy('leaves.Id','desc')
            ->get();

        $holidays = DB::table('holidays')
            ->select('holidays.Id','holidays.Holiday','holidays.Start_Date','holidays.End_Date','holidays.State','holidays.Country')
            ->whereRaw('right(Start_Date,4)='.date('Y'))
            ->orderBy('holidays.Start_Date','asc')
            ->get();

        return view('onleavetoday', ['me' => $me,'showleave' => $showleave,'finalapprovedleave'=>$finalapprovedleave,'start'=>$start,'end'=>$end,'holidays' => $holidays, 'k'=>$k]);
	}

	public function staffconfirmed(){
		$me=(new CommonController)->get_current_user();
		$month = date('m');
		
		$users = DB::table('users')->select('users.Id','files.Web_Path','users.Status','users.StaffId as Staff_ID','users.Name','users.NRIC','users.Joining_Date','users.Confirmation_Date','users.Resignation_Date','users.Position','users.Contact_No_1','users.Nationality','users.Grade','users.Company','users.Department','users.Category','users.Entitled_for_OT','users.Working_Days','holidayterritories.Name as HolidayTerritory','users.Ext_No','users.Company_Email','users.Nick_Name','users.User_Type','users.Personal_Email', 'users.Contact_No_2','users.Permanent_Address','users.Current_Address','users.Country_Base','users.Home_Base','users.DOB','users.Place_Of_Birth','users.Race','users.Religion','users.Passport_No','users.Gender','users.Marital_Status','superior.Name as Superior','team.Name as TeamMember','users.Internship_Start_Date','users.Internship_End_Date','users.Bank_Name','users.Bank_Account_No','users.EPF_No','users.SOCSO_No','users.Income_Tax_No','users.Emergency_Contact_Person','users.Emergency_Contact_No','users.Emergency_Contact_Relationship','users.Emergency_Contact_Address','users.Driving_License','users.Car_Owner','users.Criminal_Activity')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->leftJoin('users as superior','superior.Id','=','users.SuperiorId')
		->leftJoin('holidayterritories','holidayterritories.Id','=','users.HolidayTerritoryId')
		->leftJoin('users as team','team.Id','=','users.Team')
		->orderBy('users.Id')
		->get();


		$options= DB::table('options')
		->whereIn('Table', ["users"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		$projects = DB::table('projects')
		->where('projects.Project_Name','like','%department%')
		->get();

		$holidayterritories = DB::table('holidayterritories')->select('holidayterritories.Id','holidayterritories.Name')->get();

    return view('staffconfirmed', ['me' => $me,'users' => $users,'options' =>$options,'resigned'=>false,'projects'=>$projects, 'holidayterritories' => $holidayterritories,'month' => $month]);
	}

	public function newstaff(){

		$me=(new CommonController)->get_current_user();
		$year = Carbon::now()->year;

		$users = DB::table('users')->select('users.Id','files.Web_Path','users.Status','users.StaffId as Staff_ID','users.Name','users.NRIC','users.Joining_Date','users.Confirmation_Date','users.Resignation_Date','users.Position','users.Contact_No_1','users.Nationality','users.Grade','users.Company','users.Department','users.Category','users.Entitled_for_OT','users.Working_Days','holidayterritories.Name as HolidayTerritory','users.Ext_No','users.Company_Email','users.Nick_Name','users.User_Type','users.Personal_Email', 'users.Contact_No_2','users.Permanent_Address','users.Current_Address','users.Country_Base','users.Home_Base','users.DOB','users.Place_Of_Birth','users.Race','users.Religion','users.Passport_No','users.Gender','users.Marital_Status','superior.Name as Superior','team.Name as TeamMember','users.Internship_Start_Date','users.Internship_End_Date','users.Bank_Name','users.Bank_Account_No','users.EPF_No','users.SOCSO_No','users.Income_Tax_No','users.Emergency_Contact_Person','users.Emergency_Contact_No','users.Emergency_Contact_Relationship','users.Emergency_Contact_Address','users.Driving_License','users.Car_Owner','users.Criminal_Activity')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->leftJoin('users as superior','superior.Id','=','users.SuperiorId')
		->leftJoin('holidayterritories','holidayterritories.Id','=','users.HolidayTerritoryId')
		->leftJoin('users as team','team.Id','=','users.Team')
		->orderBy('users.Id')
		->where(DB::raw('str_to_date(users.Joining_Date,"%Y")'),"=",DB::raw('str_to_date("'.$year.'","%Y")'))
		->get();

		$options= DB::table('options')
		->whereIn('Table', ["users"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		$projects = DB::table('projects')
		->where('projects.Project_Name','like','%department%')
		->get();

		$holidayterritories = DB::table('holidayterritories')->select('holidayterritories.Id','holidayterritories.Name')->get();

    return view('newstaffjoin', ['me' => $me,'users' => $users,'options' =>$options,'resigned'=>false,'projects'=>$projects, 'holidayterritories' => $holidayterritories,'year' => $year]);

	}

	public function resignedstaff(){

		$me=(new CommonController)->get_current_user();
		$year = Carbon::now()->year;

		$users = DB::table('users')->select('users.Id','files.Web_Path','users.Status','users.StaffId as Staff_ID','users.Name','users.NRIC','users.Joining_Date','users.Confirmation_Date','users.Resignation_Date','users.Position','users.Contact_No_1','users.Nationality','users.Grade','users.Company','users.Department','users.Category','users.Entitled_for_OT','users.Working_Days','holidayterritories.Name as HolidayTerritory','users.Ext_No','users.Company_Email','users.Nick_Name','users.User_Type','users.Personal_Email', 'users.Contact_No_2','users.Permanent_Address','users.Current_Address','users.Country_Base','users.Home_Base','users.DOB','users.Place_Of_Birth','users.Race','users.Religion','users.Passport_No','users.Gender','users.Marital_Status','superior.Name as Superior','team.Name as TeamMember','users.Internship_Start_Date','users.Internship_End_Date','users.Bank_Name','users.Bank_Account_No','users.EPF_No','users.SOCSO_No','users.Income_Tax_No','users.Emergency_Contact_Person','users.Emergency_Contact_No','users.Emergency_Contact_Relationship','users.Emergency_Contact_Address','users.Driving_License','users.Car_Owner','users.Criminal_Activity')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->leftJoin('users as superior','superior.Id','=','users.SuperiorId')
		->leftJoin('holidayterritories','holidayterritories.Id','=','users.HolidayTerritoryId')
		->leftJoin('users as team','team.Id','=','users.Team')
		->orderBy('users.Id')
		->where('users.Resignation_Date','!=','')
		->where(DB::raw('str_to_date(users.Resignation_Date,"%Y")'),"=",DB::raw('str_to_date("'.$year.'","%Y")'))
		->get();

		$options= DB::table('options')
		->whereIn('Table', ["users"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		$projects = DB::table('projects')
		->where('projects.Project_Name','like','%department%')
		->get();

		$holidayterritories = DB::table('holidayterritories')->select('holidayterritories.Id','holidayterritories.Name')->get();

		return view('staffresigned', ['me' => $me,'users' => $users,'options' =>$options,'resigned'=>true,'projects'=>$projects, 'holidayterritories' => $holidayterritories]);
	}

	public function staffloanpending($start=null,$end=null){

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

			$all = DB::table('staffloans')
			->select('staffloans.Id','staffloanstatuses.Status','users.Name','staffloans.Date',DB::raw('Format((staffloans.Total_Requested),2) as Total_Requested'),DB::raw('Format((staffloans.Total_Approved),2) as Total_Approved'),'approver.Name as Approver')
			->leftJoin('users','users.Id','=','staffloans.UserId')
			->leftJoin( DB::raw('(select Max(Id) as maxid,StaffLoanId from staffloanstatuses Group By StaffLoanId) as max'), 'max.StaffLoanId', '=', 'staffloans.Id')
			->leftJoin('staffloanstatuses', 'staffloanstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver','approver.Id','=','staffloanstatuses.UserId')
			->get();

			$staffloans = DB::table('staffloans')
			->select('staffloans.Id','staffloanstatuses.Status','users.Name','staffloans.Date',DB::raw('Format((staffloans.Total_Requested),2) as Total_Requested'),DB::raw('Format((staffloans.Total_Approved),2) as Total_Approved'),'approver.Name as Approver')
			->leftJoin('users','users.Id','=','staffloans.UserId')
			->leftJoin( DB::raw('(select Max(Id) as maxid,StaffLoanId from staffloanstatuses Group By StaffLoanId) as max'), 'max.StaffLoanId', '=', 'staffloans.Id')
			->leftJoin('staffloanstatuses', 'staffloanstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver','approver.Id','=','staffloanstatuses.UserId')
			->where('staffloanstatuses.UserId', '=', $me->UserId)
			->get();

			$allfinal = DB::table('staffloans')
			->select('staffloans.Id','staffloanstatuses.Status','users.Name','staffloans.Date',DB::raw('Format((staffloans.Total_Requested),2) as Total_Requested'),DB::raw('Format((staffloans.Total_Approved),2) as Total_Approved'),'approver.Name as Approver')
			->leftJoin('users','users.Id','=','staffloans.UserId')
			->leftJoin( DB::raw('(select Max(Id) as maxid,StaffLoanId from staffloanstatuses Group By StaffLoanId) as max'), 'max.StaffLoanId', '=', 'staffloans.Id')
			->leftJoin('staffloanstatuses', 'staffloanstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver','approver.Id','=','staffloanstatuses.UserId')
			->where('staffloanstatuses.Status','like','%Pending Approval%')
			->get();

			$users = DB::table('users')->where('active', 1)->get();
			$users_depts = collect($users)->groupBy('Department')->all();
			return view('staffloanpending',['me'=>$me, 'start'=>$start, 'end'=>$end,'staffloans'=>$staffloans,'all'=>$all, 'allfinal'=>$allfinal]);
	}

	public function approvedloan($start=null,$end=null){

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

			$all = DB::table('staffloans')
			->select('staffloans.Id','staffloanstatuses.Status','users.Name','staffloans.Date',DB::raw('Format((staffloans.Total_Requested),2) as Total_Requested'),DB::raw('Format((staffloans.Total_Approved),2) as Total_Approved'),'approver.Name as Approver')
			->leftJoin('users','users.Id','=','staffloans.UserId')
			->leftJoin( DB::raw('(select Max(Id) as maxid,StaffLoanId from staffloanstatuses Group By StaffLoanId) as max'), 'max.StaffLoanId', '=', 'staffloans.Id')
			->leftJoin('staffloanstatuses', 'staffloanstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver','approver.Id','=','staffloanstatuses.UserId')
			->get();

			$staffloans = DB::table('staffloans')
			->select('staffloans.Id','staffloanstatuses.Status','users.Name','staffloans.Date',DB::raw('Format((staffloans.Total_Requested),2) as Total_Requested'),DB::raw('Format((staffloans.Total_Approved),2) as Total_Approved'),'approver.Name as Approver')
			->leftJoin('users','users.Id','=','staffloans.UserId')
			->leftJoin( DB::raw('(select Max(Id) as maxid,StaffLoanId from staffloanstatuses Group By StaffLoanId) as max'), 'max.StaffLoanId', '=', 'staffloans.Id')
			->leftJoin('staffloanstatuses', 'staffloanstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver','approver.Id','=','staffloanstatuses.UserId')
			->where('staffloanstatuses.UserId', '=', $me->UserId)
			->get();

			$allfinal = DB::table('staffloans')
			->select('staffloans.Id','staffloanstatuses.Status','users.Name','staffloans.Date',DB::raw('Format((staffloans.Total_Requested),2) as Total_Requested'),DB::raw('Format((staffloans.Total_Approved),2) as Total_Approved'),'approver.Name as Approver')
			->leftJoin('users','users.Id','=','staffloans.UserId')
			->leftJoin( DB::raw('(select Max(Id) as maxid,StaffLoanId from staffloanstatuses Group By StaffLoanId) as max'), 'max.StaffLoanId', '=', 'staffloans.Id')
			->leftJoin('staffloanstatuses', 'staffloanstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver','approver.Id','=','staffloanstatuses.UserId')
			->where('staffloanstatuses.Status','=','Pending Approval')
			->get();

			$users = DB::table('users')->where('active', 1)->get();
			$users_depts = collect($users)->groupBy('Department')->all();
			return view('approvedloan',['me'=>$me, 'start'=>$start, 'end'=>$end,'staffloans'=>$staffloans,'all'=>$all, 'allfinal'=>$allfinal]);
	}
}