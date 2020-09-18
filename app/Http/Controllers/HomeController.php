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
use File;
use Storage;
use Dompdf\Dompdf;
use PDF;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

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

	public function index()
	{
		$me = (new CommonController)->get_current_user();
		$Date=date("d-M-Y", strtotime('+2 weeks'));
		$Today=date("d-M-Y");
		$staffconfirmationcount = null;
		$staffconfirmationlist = [];

		$notifydate = date('d-M-Y', strtotime('today -15 days'));
		$notifydate1 = date('d-M-Y', strtotime('today +15 days'));
		$oneweek = date('d-M-Y', strtotime('today +7 days'));
		$onemonth = date('d-M-Y', strtotime('today 1 month'));
		$today = date('d-M-Y', strtotime('today'));

		$users = DB::table('users')
		->where('users.Id','<>',562)
		->get();

		$d=date('d');

		if($d>=16)
		{

			$start=date('d-M-Y', strtotime('first day of this month'));
			$start = date('d-M-Y', strtotime($start . " +15 days"));

			$end=date('d-M-Y', strtotime('first day of next month'));
			$end = date('d-M-Y', strtotime($end . " +14 days"));

		}
		else {

			$start=date('d-M-Y', strtotime('first day of last month'));
			$start = date('d-M-Y', strtotime($start . " +15 days"));

			$end=date('d-M-Y', strtotime('first day of this month'));
			$end = date('d-M-Y', strtotime($end . " +14 days"));

		}

    $notice = DB::table('noticeboards')
    ->select('noticeboards.Id','noticeboards.Title','noticeboards.Content','noticeboards.Start_Date','noticeboards.End_Date','users.Name as Created_By','noticeboards.created_at','f.FileName','f.Attachment')
    ->where(DB::raw('str_to_date(noticeboards.Start_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$Date.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(noticeboards.End_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$Today.'","%d-%M-%Y")'))
    ->leftJoin('users', 'users.Id', '=', 'noticeboards.UserId')
		->leftJoin(DB::raw('(SELECT TargetId, GROUP_CONCAT( Web_Path SEPARATOR "|") as Attachment,GROUP_CONCAT( File_Name SEPARATOR "|") as FileName FROM files WHERE Type="Notice" GROUP BY TargetId) as f'),'f.TargetId','=','noticeboards.Id')
    ->orderBy('noticeboards.Start_Date','asc')
    ->get();

		// $lastnotice = DB::table('noticeboards')
    // ->select('noticeboards.Id','noticeboards.Title','noticeboards.Content','noticeboards.Start_Date','noticeboards.End_Date','users.Name as Created_By','noticeboards.created_at','f.FileName','f.Attachment')
    // ->leftJoin('users', 'users.Id', '=', 'noticeboards.UserId')
		// ->leftJoin(DB::raw('(SELECT TargetId, GROUP_CONCAT( Web_Path SEPARATOR "|") as Attachment,GROUP_CONCAT( File_Name SEPARATOR "|") as FileName FROM files WHERE Type="Notice" GROUP BY TargetId) as f'),'f.TargetId','=','noticeboards.Id')
    // ->orderBy('noticeboards.Start_Date','DESC')
		// ->limit(5)
    // ->get();

		$holidays = DB::table('holidays')
		->select('holidays.Id','holidays.Holiday','holidays.Start_Date','holidays.End_Date','holidays.State','holidays.Country')
		->orderBy('holidays.Start_Date','asc')
		->get();

		$myclaim = DB::table('claims')
		->select('claims.Id','claims.Date','claims.Site_Name','claims.State','claims.Work_Description',
		'claims.Next_Person','claims.Car_No','claims.Mileage','claims.Expenses_Type','claims.GST_No','claims.GST_Amount','claims.Total_Amount','claims.Remarks','approver.Name as Approver','claimstatuses.Status','claimstatuses.Comment','claimstatuses.updated_at as Updated_At','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Claim" Group By TargetId) as maxfile'), 'maxfile.TargetId', '=', 'claims.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('maxfile.`maxid` and files.`Type`="Claim"'))
		->leftJoin('claimsheets', 'claimsheets.Id', '=', 'claims.ClaimSheetId')
		->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
		->where('claimsheets.UserId', '=', $me->UserId)
		->orderBy('claims.Id','desc')
		->get();

		$myleave = DB::table('leaves')
		->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
		->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
		// ->leftJoin('accesscontrols', 'accesscontrols.UserId', '=', 'applicant.Id')
		->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
		->select('leaves.Id','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.Comment','leavestatuses.updated_at as Review_Date')
		// ->where('accesscontrols.Show_Leave_To_Public', '=', 1)
		->where(DB::raw('str_to_date(leaves.Start_Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->orWhere(DB::raw('str_to_date(leaves.End_Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->where('leavestatuses.Leave_Status','!=','Cancelled')
		->orderBy('leaves.Id','desc')
		->get();

		$mytimesheet = DB::table('timesheets')
		->select('timesheets.Id','timesheets.UserId','timesheets.Date','timesheets.Leader_Member','timesheets.Next_Person','timesheets.Site_Name','timesheets.State','timesheets.Check_In_Type',
		'timesheets.Time_In','timesheets.Time_Out','timesheets.Reason','timesheets.Remarks','approver.Name as Approver','timesheetstatuses.Status','timesheetstatuses.Comment','timesheetstatuses.updated_at as Updated_At','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Timesheet" Group By TargetId) as maxfile'), 'maxfile.TargetId', '=', 'timesheets.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('maxfile.`maxid` and files.`Type`="Timesheet"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
		->where('timesheets.UserId', '=', $me->UserId)
		->where('timesheetstatuses.Status', '<>','null')
		->orderBy('timesheets.Id','desc')
		->get();

		$assettrackings = DB::table('assets')
		->select('assets.Id','assettrackings.Id as TrackingId','assettrackings.UserId','assets.Label','assets.Type','assets.Serial_No','assets.IMEI','assets.Model_No','assets.Car_No',
		'assets.Color','assettrackings.Date as Taken_Date','transfer.Name as Transfer_To','assettrackings.Transfer_Date_Time','assets.Remarks')
		->leftJoin( DB::raw('(select Max(Id) as maxid,AssetId from assettrackings Group By AssetId) as max'), 'max.AssetId', '=', 'assets.Id')
		->leftJoin('assettrackings', 'assettrackings.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin(DB::raw('users as transfer'), 'assettrackings.Transfer_To', '=','transfer.Id' )
		->where('assettrackings.UserId', '=',$me->UserId)
		->orWhere('assettrackings.Transfer_To', '=',$me->UserId)
		->where('assettrackings.Status', '<>','Returned')
		->orderBy(DB::raw('str_to_date(assettrackings.Date,"%d-%M-%Y")'),'desc')
		->get();

		$todaydate = date('d-M', strtotime('today'));

		$birthday = DB::table('users')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->select('users.Id','users.Name','files.Web_Path')
		//->where('users.Id','=',579)
		->where(DB::raw('str_to_date(users.DOB,"%d-%M")'),'=',DB::raw('str_to_date("'.$todaydate.'","%d-%M")'))
		->where('users.Active','=','1')
		->get();

		//dd($birthday);

		$birthdaycount = count($birthday);

		if ($me->Detail_Approved_On=="0000-00-00 00:00:00")
		{
			$interval="-1";

		}
		else {
			$datetime1 = new DateTime($me->Detail_Approved_On);
			$datetime2 = new DateTime(date("Y-m-d H:i:s"));

			$interval = date_diff($datetime1, $datetime2);
			$interval=$interval->format("%m");

		}

		$leavesummary="";

		if($me->View_All_Leave)
		{
			$leavesummary = DB::table('leaves')
			->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
			->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
			->select(DB::raw('COUNT(*) as On_Leave'))
			->where(DB::raw('str_to_date(leaves.Start_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$Today.'","%d-%M-%Y")'))
			->where(DB::raw('str_to_date(leaves.End_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$Today.'","%d-%M-%Y")'))
			->where('leavestatuses.Leave_Status','like','%Approved%')
			->first();
		}

		$interns="";

		if($me->View_User_Profile)
		{
			$interns = DB::table('users')
			->select(DB::raw('COUNT(*) as Interns'))
			->where('User_Type','=','Assistant Engineer')
			->where('Approved','=','1')
			->where(DB::raw('str_to_date(users.Internship_Start_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$Today.'","%d-%M-%Y")'))
			->where(DB::raw('str_to_date(users.Internship_End_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$Today.'","%d-%M-%Y")'))
			->where('users.Internship_Status','=','Accepted')
			->first();
		}

		$assetsummary="";

		$trackercolumns = DB::table('trackercolumn')
		->select()
		->where('trackercolumn.Type','=',"Name List")
		->get();

		$taskquery="";

		foreach ($trackercolumns as $key => $value) {
			$taskquery.='`'.$value->Column_Name.'`="'.$me->Name.'" OR';
		}

		$taskquery=substr($taskquery,0,strlen($taskquery)-3);

		$mytask="";
		$pendingpo="";
		$pendinginvoice="";
		$pendingpayment="";

		if($taskquery)
		{

					$mytask = DB::select('
						SELECT COUNT(*) As task FROM tracker WHERE '.$taskquery);

		}

		if($me->View_PO_Summary || $me->View_Invoice_Summary)
		{

			$trackercolumns = DB::table('trackercolumn')
			->select()
			->where('trackercolumn.Type','=',"PO List")
			->get();

			$pendingpoquery="";

			foreach ($trackercolumns as $key => $value) {
				$pendingpoquery.="`".$value->Column_Name."`='No PO' OR ";
			}

			$pendingpoquery=substr($pendingpoquery,0,strlen($pendingpoquery)-3);

			// if($pendingpoquery)
			// {
			// 	$pendingpo = DB::select("
			// 		SELECT COUNT(*) As pending FROM tracker WHERE ".$pendingpoquery);
			//
			// }

			$pendingpo="";

			$pendinginvoice= DB::select("
				SELECT COUNT(*) As pending FROM po WHERE
				(First_Milestone_Completed_Date<>'' AND First_Milestone_Invoice_No='') OR
				(Second_Milestone_Completed_Date<>'' AND Second_Milestone_Invoice_No='') OR
				(Third_Milestone_Completed_Date<>'' AND Third_Milestone_Invoice_No='') OR
				(Fourth_Milestone_Completed_Date<>'' AND Fourth_Milestone_Invoice_No='') OR
				(Fifth_Milestone_Completed_Date<>'' AND Fifth_Milestone_Invoice_No='')
				");

			$pendingpayment = DB::select("
				SELECT COUNT(*) As pending FROM invoices WHERE Invoice_Status='Open'");

		}

		if($me->Asset_Tracking)
		{
			$assetsummary = DB::select("
				SELECT  (
					SELECT SUM(case when assets.Availability = 'Yes' then 1 else 0 end) as available
					FROM assets
					WHERE assets.Rental_End_Date = 'Laptop' AND assets.Availability IN ('Yes','No')
					GROUP BY assets.Rental_End_Date
				) AS Laptop,
				(
					SELECT Count(*)
					FROM assets
					WHERE assets.Rental_End_Date = 'Laptop' AND assets.Availability IN ('Yes','No')
					GROUP BY assets.Rental_End_Date
				) AS LaptopTotal,
				(
					SELECT SUM(case when assets.Availability = 'Yes' then 1 else 0 end) as available
					FROM assets
					WHERE assets.Rental_End_Date = 'Desktop' AND assets.Availability IN ('Yes','No')
					GROUP BY assets.Rental_End_Date
				) AS Desktop,
				(
					SELECT Count(*)
					FROM assets
					WHERE assets.Rental_End_Date = 'Desktop' AND assets.Availability IN ('Yes','No')
					GROUP BY assets.Rental_End_Date
				) AS DesktopTotal
		");
		}

		$accountsummary="";

		if($me->Admin)
		{
			$accountsummary = DB::select("
				SELECT  (
				SELECT COUNT(*)
				FROM users
				WHERE users.Status = 'New Registered Account'
				) AS Pending_Account_Approval,
				(
					SELECT COUNT(*)
					FROM users
					WHERE users.Status = 'Account Approved'
					) AS Pending_Account_Detail_Update,
				(
					SELECT COUNT(*)
					FROM users
					WHERE users.Status = 'Pending Account Detail Approval'
					) AS Pending_Account_Detail_Approval
			");

			$licenses = DB::table('licenses')->select('licenses.Id','License_Type','Identity_No','Issue_Date','Expiry_Date','License_Status')
			->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="License" Group By TargetId) as max'), 'max.TargetId', '=', 'licenses.Id')
			->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="License"'))
			->where('licenses.Expiry_Date', '>=', $notifydate)
			->orderBy('licenses.Id','desc')
			->get();

			$internend = DB::table('users')
			->select('Id','StaffId','Name', 'Internship_Start_Date','Internship_End_Date','Internship_Status')
			->where('User_Type','=','Assistant Engineer')
			->where('Approved','=','1')
			->where(DB::raw('str_to_date(users.Internship_End_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$notifydate.'","%d-%M-%Y")'))
			->where(DB::raw('str_to_date(users.Internship_End_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$notifydate1.'","%d-%M-%Y")'))
			->get();


			$confirmStartDate = date("d-M-Y", strtotime("+20 day", strtotime("first day of last month")));
			$confirmEndDate = date("d-M-Y", strtotime("+19 day", strtotime("first day of this month")));
			$todayDate = date('d-M-Y');
			$staffconfirmationcount = DB::table('users')
			->whereRaw("str_to_date(users.Confirmation_Date,'%d-%M-%Y') BETWEEN str_to_date('$confirmStartDate','%d-%M-%Y') AND str_to_date('$confirmEndDate','%d-%M-%Y')")
			->where(function ($query) use ($todayDate) {
				$query->whereRaw('str_to_date(users.Resignation_Date,"%d-%M-%Y") > str_to_date("'.$todayDate.'","%d-%M-%Y")');
				$query->orWhere('users.Resignation_Date', '');
			})
			->count();

			$staffconfirmationlist = DB::table('users')
			->select('users.Id','users.StaffId','users.Name','users.Grade','users.Company','users.Position','users.Joining_Date','users.Confirmation_Date')
			->whereRaw("str_to_date(users.Confirmation_Date,'%d-%M-%Y') BETWEEN str_to_date('$confirmStartDate','%d-%M-%Y') AND str_to_date('$confirmEndDate','%d-%M-%Y')")
			->where(function ($query) use ($todayDate) {
				$query->whereRaw('str_to_date(users.Resignation_Date,"%d-%M-%Y") > str_to_date("'.$todayDate.'","%d-%M-%Y")');
				$query->orWhere('users.Resignation_Date', '');
			})
			->orderBy(DB::raw("str_to_date(users.Confirmation_Date,'%d-%M-%Y')"))
			->get();


		}

		$pendingtimesheets = DB::table('timesheets')
		->select(DB::raw('count(Distinct timesheets.UserId) as pending'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->where('timesheetstatuses.UserId', '=', $me->UserId)
		->where('timesheetstatuses.Status', 'like', DB::raw('"%Pending%"'))
		->get();

		$pendingclaims = DB::table('claims')
		->select(DB::raw('count(Distinct claimsheets.Id) as pending'))
		->leftJoin('claimsheets', 'claimsheets.Id', '=', 'claims.ClaimSheetId')
		->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		->where('claimstatuses.UserId', '=', $me->UserId)
		->where('claimstatuses.Status', 'like', DB::raw('"%Pending%"'))
		->get();

		$pendingadvance = DB::table('advances')
		->select(DB::raw('count(Distinct advances.Id) as pending'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,AdvanceId from advancestatuses Group By AdvanceId) as max'), 'max.AdvanceId', '=', 'advances.Id')
		->leftJoin('advancestatuses', 'advancestatuses.Id', '=', DB::raw('max.`maxid`'))
		->where('advancestatuses.UserId', '=', $me->UserId)
		->where('advancestatuses.Status', 'like', DB::raw('"%Pending%"'))
		->get();

		$pendingleaves = DB::table('leaves')
		->select(DB::raw('count(Distinct leaves.Id) as pending'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
		->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
		->orderBy('leaves.Id','desc')
		->where('leavestatuses.UserId', '=',$me->UserId)
		->where('leavestatuses.Leave_Status', 'like', DB::raw('"%Pending%"'))
		->get();

		$rejectedtimesheets = DB::table('timesheets')
		->select(DB::raw('count(Distinct timesheets.UserId) as rejected'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->where('timesheets.UserId', '=', $me->UserId)
		->where('timesheetstatuses.Status', 'like', DB::raw('"%Reject%"'))
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->get();

		$rejectedclaims = DB::table('claims')
		->select(DB::raw('count(Distinct claimsheets.UserId) as rejected'))
		->leftJoin('claimsheets', 'claimsheets.Id', '=', 'claims.ClaimSheetId')
		->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		->where('claimsheets.UserId', '=', $me->UserId)
		->where('claimstatuses.Status', 'like', DB::raw('"%Reject%"'))
		->get();

		$rejectedleaves = DB::table('leaves')
		->select(DB::raw('count(Distinct leaves.Id) as rejected'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
		->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
		->orderBy('leaves.Id','desc')
		->where('leaves.UserId', '=',$me->UserId)
		->where('leavestatuses.Leave_Status', 'like', DB::raw('"%Reject%"'))
		->get();

		if($me->Timesheet_Required)
		{
			$mypendingtimesheet = DB::table('timesheets')
			->select(DB::raw('count(Distinct timesheets.Id) as pending'))
			->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
			->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
			->where('timesheets.UserId', '=', $me->UserId)
			->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
			->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
			->whereNotNull('timesheetstatuses.Status')
			->orderBy('timesheets.Id','desc')
			->get();

			$date1 = new DateTime($start);
			$date2 = new DateTime($end);

			$diff = $date2->diff($date1)->format("%a");

			$mypendingtimesheet=$diff-$mypendingtimesheet[0]->pending+1;
		}
		else {
			$mypendingtimesheet=0;
		}



		$endNotify = DB::table('assets')
		->select('assets.Label','assets.Label','assets.Type','assets.Serial_No','assets.IMEI','assets.Brand','assets.Model_No','assets.Car_No','assets.Color','assets.Rental_Start_Date','assets.Rental_End_Date')
		->leftJoin( DB::raw('(select Max(Id) as maxid,AssetId from assettrackings Group By AssetId) as max'), 'max.AssetId', '=', 'assets.Id')
		->leftJoin('assettrackings', 'assettrackings.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users', 'assettrackings.UserId', '=', 'users.Id')
		->leftJoin(DB::raw('users as transfer'), 'assettrackings.Transfer_To', '=', 'transfer.Id')
		->orderBy('assets.Label','asc')
		->where('assets.Rental_End_Date', '=', $notifydate)
		->where('users.Id','=',$me->UserId)
		->get();

		$licenses = DB::table('licenses')->select('licenses.Id','License_Type','Identity_No','Issue_Date','Expiry_Date','License_Status')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="License" Group By TargetId) as max'), 'max.TargetId', '=', 'licenses.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="License"'))
		->where('licenses.UserId', '=', $me->UserId)
		->where('licenses.Expiry_Date', '>=', $notifydate)
		->orderBy('licenses.Id','desc')
		->get();

		$internend = DB::table('users')
		->select('Id','StaffId','Name', 'Internship_Start_Date','Internship_End_Date','Internship_Status')
		->where('User_Type','=','Assistant Engineer')
		->where(DB::raw('str_to_date(users.Internship_End_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$notifydate.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(users.Internship_End_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$notifydate1.'","%d-%M-%Y")'))
		->get();

		$summons = DB::table('summons')
		->select('Summon_No','Vehicle_No','Date','Time','Offense','Amount','Remarks')
		// ->where('Name', '=', $me->Name)
		// ->where('Status', '=', 'Unpaid')
		->orderBy(DB::raw('str_to_date(`Date`,"%d-%M-%Y")'),'ASC')
		->get();

		$deductamount = DB::table('deductions')
		->select(DB::raw('SUM(Amount) As Amount'))
		->leftJoin('deductionitems', 'deductions.Id', '=', 'deductionitems.DeductionId')
		->whereIn('deductions.Type', array("shellcards", "accident", "touchngo"))
		->where('deductions.UserId', '=', $me->UserId)
		->orderBy('deductions.Id','desc')
		->groupBy('deductions.UserId')
		->first();

		$deductions = DB::table('deductions')
		->select('deductionitems.Date','deductions.Type','deductionitems.Amount','deductions.Remarks')
		->leftJoin('deductionitems', 'deductions.Id', '=', 'deductionitems.DeductionId')
		->whereIn('deductions.Type', array("shellcards", "accident", "touchngo"))
		->where('deductions.UserId', '=', $me->UserId)
		->orderBy('deductions.Id','desc')
		->groupBy('deductions.UserId')
		->get();

		$roadtax = DB::table('roadtax')
		->select(DB::raw('Count(*) as Total'))
		->whereRaw('roadtax.Id in (select max(Id) from roadtax group by Vehicle_No)')
		->where(DB::raw('str_to_date(roadtax.RoadTax_Expire_Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$onemonth.'","%d-%M-%Y")'))
		// ->where(DB::raw('str_to_date(roadtax.RoadTax_Expire_Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$Today.'","%d-%M-%Y")'))
		->get();

		$thisyear=date('Y');
		$lastyear=$thisyear-1;
		$currentmonth=date('n');

		// $leavebalance = DB::select("
		// SELECT users.Id,
		// users.StaffId,
		// users.Name,
		// users.Grade,
		// users.Joining_Date,
		// DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'), str_to_date(users.Joining_Date,'%d-%M-%Y')) as Days_of_Service,
		// CEILING((DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),str_to_date(users.Joining_Date,'%d-%M-%Y')) / 365 )) as Years_of_Service,
		// '' as Yearly_Entitlement,
		// '' as Current_Entitlement,
		// leavecarryforwards.Days as Carried_Forward,
		// IF (leavecarryforwards.Days>0,leavecarryforwards.Days-SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND YEAR(str_to_date(leaves.End_Date,'%d-%M-%Y'))=".$thisyear.",No_of_Days,0)),0) AS Burnt,
		// '' as Total_Leave_Days,
		// SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND YEAR(str_to_date(leaves.End_Date,'%d-%M-%Y'))=".$thisyear.",No_of_Days,0)) as Total_Leave_Taken,
		// '' as Total_Leave_Balance
		// from users
		// LEFT JOIN leaves ON users.Id = leaves.UserId AND (Leave_Type='Annual Leave' OR Leave_Type='Emergency Leave')
		// LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id
		// LEFT JOIN leavestatuses ON leavestatuses.Id=max.`maxid`
		// LEFT JOIN leavecarryforwards ON leavecarryforwards.UserId=users.Id AND leavecarryforwards.Year=".$lastyear."
		// WHERE users.Id=".$me->UserId."
		// GROUP BY users.Id
		// ");
		 $leavebalanceresult = DB::select("SELECT
            CASE WHEN service.Days_of_Service <= 90
                    THEN 0
                -- WHEN service.confirmed AND service.Days_of_Service > 365
                --     THEN leaveentitlements.Days
                ELSE
                    5*ROUND(leaveentitlements.Days/12*service.Months_of_Service/5 ,1)
                END as Current_Entitlement,
            Leave_Taken_In_Between_March,
            SUM(IF((leavestatuses.Leave_Status like '%Final Approved%') AND leaves.Leave_Type IN ('Annual Leave','1 Hour Time Off','2 Hours Time Off','Emergency Leave', 'Replacement Leave') AND MONTH(str_to_date(leaves.End_Date,'%d-%M-%Y')) <=3,No_of_Days,0)) as Leave_Taken_Before_April,
            SUM(IF((leavestatuses.Leave_Status like '%Final Approved%') AND leaves.Leave_Type IN ('Annual Leave','1 Hour Time Off','2 Hours Time Off','Emergency Leave', 'Replacement Leave'),No_of_Days,0)) as Total_Leave_Taken,

            leaveadjustments.Adjustment_Value as Adjusted,
            leavecarryforwards.Days as Carried_Forward,
            '' as Burnt,
            '' as Total_Leave_Balance
            FROM users
          	LEFT JOIN leaves ON users.Id = leaves.UserId and YEAR(str_to_date(leaves.Start_Date,'%d-%M-%Y')) = $thisyear
            LEFT JOIN (SELECT * FROM (select Max(Id) as maxid from leavestatuses Group By leavestatuses.LeaveId) as max JOIN leavestatuses ON  leavestatuses.Id=max.`maxid`) as leavestatuses ON leavestatuses.LeaveId=leaves.Id
            LEFT JOIN (
                SELECT leaves.UserId, sum(Case WHEN lt.Leave_Period = 'AM' OR lt.Leave_Period = 'PM' THEN 0.5 WHEN lt.Leave_Period = '1 Hour' THEN 0.125 WHEN lt.Leave_Period = '2 Hours' THEN 0.25 WHEN  lt.Leave_Period = 'Full' THEN 1 ELSE 0 END) Leave_Taken_In_Between_March  FROM leaves
                LEFT JOIN (SELECT leave_terms.Leave_Period, leave_terms.Leave_Id from leave_terms WHERE YEAR(Str_to_date(leave_terms.Leave_Date, '%d-%M-%Y')) = $thisyear AND MONTH(Str_to_date(leave_terms.Leave_Date, '%d-%M-%Y')) <= 3) as lt
                ON leaves.Id = lt.Leave_Id
                LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id LEFT JOIN leavestatuses ON leavestatuses.Id=max.`maxid`
                WHERE (leaves.Leave_Type = 'Annual Leave' OR leaves.Leave_Type = 'Emergency Leave' OR leaves.Leave_Type = 'Replacement Leave' OR leaves.Leave_Type = '1 Hour Time Off' OR leaves.Leave_Type = '2 Hours Time Off') AND YEAR(Str_to_date(leaves.Start_Date, '%d-%M-%Y')) = $thisyear AND MONTH(Str_to_date(leaves.Start_Date, '%d-%M-%Y')) <= 3 AND MONTH(Str_to_date(leaves.End_Date, '%d-%M-%Y')) > 3 AND leavestatuses.Leave_Status like '%Final Approved%'
                GROUP BY leaves.UserId
            ) as leaveInBetweenMarch ON leaveInBetweenMarch.UserId = users.Id
            LEFT JOIN leaveadjustments ON users.Id = leaveadjustments.UserId AND (leaveadjustments.Adjustment_Leave_Type = 'Annual Leave' OR leaveadjustments.Adjustment_Leave_Type = 'Replacement Leave') AND leaveadjustments.Adjustment_Year = $thisyear
            LEFT JOIN (
                SELECT users.id AS UserId, users.Marital_Status, users.Gender,
                    CASE WHEN
                    DATEDIFF(Date_format(Now(), '%Y-%m-%d'), Str_to_date(users.confirmation_date, '%d-%M-%Y')) >= 0 THEN 1 ELSE 0 end AS confirmed,
                    DATEDIFF(Date_format(Now(), '%Y-%m-%d'),Str_to_date(users.joining_date, '%d-%M-%Y')) AS Days_of_Service,
                    Ceiling((SELECT days_of_service) / 365) AS Years_of_Service,
                    CASE WHEN YEAR(str_to_date(users.Joining_date,'%d-%M-%Y')) = $thisyear THEN 1 ELSE 0 END as Joined_This_Year,
                    Joining_date,
                    (SELECT days_of_service) / 30 AS Current_Completed_Month,
                    CASE WHEN (SELECT joined_this_year) THEN (SELECT current_completed_month) ELSE Month(Now()) end AS Months_of_Service,
                    CASE WHEN (SELECT Joined_This_Year) THEN (SELECT Days_of_Service) ELSE DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE_FORMAT(NOW() ,'%Y-01-01')) END as Days_of_Service_Current_Year
                FROM users
            ) as service ON users.Id = service.UserId
            LEFT JOIN (SELECT leaveentitlements.*,tblEnt.MaxYear FROM leaveentitlements LEFT JOIN
                    (SELECT leaveentitlements.Grade, Leave_Type, MAX(Year) as MaxYear
                        FROM leaveentitlements
                        GROUP BY Grade, Leave_Type ) as tblEnt
                    ON leaveentitlements.Grade = tblEnt.Grade and leaveentitlements.Leave_Type = tblEnt.Leave_Type) as leaveentitlements
                ON leaveentitlements.Grade = users.Grade
                AND (leaveentitlements.Year = LEAST(leaveentitlements.MaxYear,service.Years_of_Service))
                AND leaveentitlements.Leave_Type = 'Annual Leave'
            LEFT JOIN (SELECT * FROM leavecarryforwards GROUP BY Year,UserId) as leavecarryforwards ON leavecarryforwards.UserId=users.Id AND leavecarryforwards.Year = $lastyear
            WHERE users.Id=$me->UserId
            GROUP BY users.Id
        ");



	$balance = $leavebalanceresult[0];
	if ($balance->Carried_Forward > ($balance->Leave_Taken_Before_April +  $balance->Leave_Taken_In_Between_March) && date('n') > 3) {
		$burnt = 0;
	} else {
		$burnt = 0;
	}

	$annualbalance = (float) $balance->Current_Entitlement+$balance->Adjusted+($balance->Carried_Forward-$burnt)-$balance->Total_Leave_Taken;



		$sickleavebalance = DB::select("
		SELECT users.Id, users.StaffId, users.Name, users.Grade, users.Joining_Date,
		DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'), str_to_date(users.Joining_Date,'%d-%M-%Y')) as Days_of_Service,
		CEILING((DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),str_to_date(users.Joining_Date,'%d-%M-%Y')) / 365 )) as Years_of_Service,
		'' as Yearly_Entitlement,
		SUM(IF(leavestatuses.Leave_Status like '%Final Approved%' AND YEAR(str_to_date(leaves.End_Date,'%d-%M-%Y'))=".$thisyear.",No_of_Days,0)) as Total_Leave_Taken,
		'' as Total_Leave_Balance
		from users
		LEFT JOIN leaves ON users.Id = leaves.UserId AND Leave_Type='Medical Leave'
		LEFT JOIN (select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max ON  max.LeaveId=leaves.Id
		LEFT JOIN leavestatuses ON leavestatuses.Id=max.`maxid`
		LEFT JOIN leavecarryforwards ON leavecarryforwards.UserId=users.Id AND leavecarryforwards.Year=".$thisyear."
		WHERE users.Id=".$me->UserId."
		GROUP BY users.Id
		");

		// $annualentitlement = DB::table('leaveentitlements')
		// ->select('Grade','Year','Days')
		// ->orderBy('Grade','ASC')
		// ->orderBy('Year','DESC')
		// ->where('Leave_Type', '=','Annual Leave')
		// ->where('Grade', '=',$me->Grade)
		// ->get();

		// $annualbalance=0;
		$sickbalance=0;

		// if($leavebalance)
		// {

		// 	foreach ($annualentitlement as $entitlement) {
		// 		# code...
		// 		if($leavebalance[0]->Years_of_Service>=$entitlement->Year)
		// 		{

		// 			if ($leavebalance[0]->Burnt<0)
		// 			{
		// 				$leavebalance[0]->Burnt=0;
		// 			}

		// 			$annualbalance=floor((round($entitlement->Days/12*$currentmonth,1))+$leavebalance[0]->Carried_Forward-($leavebalance[0]->Total_Leave_Taken+$leavebalance[0]->Burnt));
		// 			break;

		// 		}
		// 	}
		// }

		$adjustment = DB::table('leaveadjustments')
		->select(
			DB::raw("SUM(CASE WHEN leaveadjustments.Adjustment_Leave_Type = 'Annual Leave' THEN leaveadjustments.Adjustment_Value ELSE 0 END) as Annual_Leave_Adjustment"),
			DB::raw("SUM(CASE WHEN leaveadjustments.Adjustment_Leave_Type = 'Medical Leave' THEN leaveadjustments.Adjustment_Value ELSE 0 END) as Sick_Leave_Adjustment")
		)
		->groupBy('leaveadjustments.UserId','leaveadjustments.Adjustment_Year')
		->where('leaveadjustments.Adjustment_Year', $thisyear)
		->where('leaveadjustments.UserId', $me->UserId)
		->first();

		$sickentitlement = DB::table('leaveentitlements')
		->select('Grade','Year','Days')
		->orderBy('Grade','ASC')
		->orderBy('Year','DESC')
		->where('Leave_Type', '=','Medical Leave')
		->where('Grade', '=',$me->Grade)
		->get();

		if($sickleavebalance)
		{
			foreach ($sickentitlement as $entitlement) {
				# code...
				if($sickleavebalance[0]->Years_of_Service>=$entitlement->Year)
				{
					$sickbalance=$entitlement->Days-$sickleavebalance[0]->Total_Leave_Taken;
					break;
				}
			}
		}

		if ($adjustment) {
			$sickbalance = $sickbalance + $adjustment->Sick_Leave_Adjustment;
		}


		$schedules = DB::table('schedules')
		->select(DB::raw('COUNT(*) as schedule'))
		->leftJoin('schedulecandidates', 'schedulecandidates.ScheduleId', '=', 'schedules.Id')
		->leftJoin('users', 'users.Id', '=', 'schedulecandidates.UserId')
		->leftJoin('users as Assign', 'Assign.Id', '=', 'schedules.Assigned_By')
		->whereRaw('(str_to_date(schedules.Start_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y") OR str_to_date(schedules.End_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y"))')
		->first();

		$myschedules = DB::table('schedules')
		->select(DB::raw('COUNT(*) as schedule'))
		->leftJoin('schedulecandidates', 'schedulecandidates.ScheduleId', '=', 'schedules.Id')
		->leftJoin('users', 'users.Id', '=', 'schedulecandidates.UserId')
		->leftJoin('users as Assign', 'Assign.Id', '=', 'schedules.Assigned_By')
		->whereRaw('(str_to_date(schedules.Start_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y") OR str_to_date(schedules.End_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y"))')
		->where('schedulecandidates.UserId','=',$me->UserId)
		->first();

		$showleave = DB::table('leaves')
		->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
		->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Leave" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'leaves.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Leave"'))
		->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
		->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
		->select('leaves.Id','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.Comment','leavestatuses.updated_at as Review_Date')
		->where('leavestatuses.Leave_Status', '<>','Cancelled')
		->orderBy('leaves.Id','desc')
		->get();



		return view('home', ['me' => $me,'start' => $start, 'end' => $end, 'users' =>$users,'notice' => $notice,'holidays' => $holidays,'mytask' =>$mytask,'myclaim' => $myclaim,'mytimesheet' => $mytimesheet,
		'myleave' => $myleave,'interval' =>$interval,'assets' =>$assettrackings, 'assetsummary' => $assetsummary,'leavesummary'=>$leavesummary,'interns'=>$interns,'accountsummary'=>$accountsummary,
		'pendingtimesheets' => $pendingtimesheets, 'pendingclaims' => $pendingclaims,'pendingadvance'=>$pendingadvance,'pendingleaves' => $pendingleaves,'mypendingtimesheet' => $mypendingtimesheet,
	 	'rejectedtimesheets' => $rejectedtimesheets, 'rejectedclaims' => $rejectedclaims,'rejectedleaves' => $rejectedleaves, 'licenses'=>$licenses,
		'pendingpo' =>$pendingpo,'pendinginvoice'=>$pendinginvoice,'pendingpayment'=>$pendingpayment, 'endNotify'=>$endNotify, 'internend'=>$internend,
		'birthday'=>$birthday,'birthdaycount'=>$birthdaycount,'summons'=>$summons, 'roadtax'=>$roadtax,'annualbalance'=>$annualbalance,'sickbalance'=>$sickbalance,'schedules'=>$schedules,'myschedules'=>$myschedules,
		'deductamount'=>$deductamount,'deductions'=>$deductions,'showleave'=>$showleave,'staffconfirmationcount' => $staffconfirmationcount, 'staffconfirmationlist' => $staffconfirmationlist
		]);

	}

	public function birthdayalert(){

		$me = (new CommonController)->get_current_user();

		$todaydate = date('d-M', strtotime('today'));


		$birthday = DB::table('users')
		->select('users.Id','users.Name')
		->where(DB::raw('str_to_date(users.DOB,"%d-%M")'),'=',DB::raw('str_to_date("'.$todaydate.'","%d-%M")'))
		->get();

		$birthdaycount = count($birthday);

		$subscribers = DB::table('notificationtype')
		->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
		->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
		->where('notificationtype.Id','=',44)
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

		if ($birthday)
		{




		// Mail::send('emails.birthdayalert', ['me' => $me, 'birthday'=>$birthday, 'birthdaycount'=>$birthdaycount], function($message) use ($emails,$NotificationSubject)
		// {
		// 		$emails = array_filter($emails);
		// 		array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
		// 		$message->to($emails)->subject($NotificationSubject);

		// });

			return 1;
		}

		else {
			return 0;
		}

	}

	public function labelview(Request $request)
	{
		$input = $request->all();

		$view = DB::table('assets')
		->select('Label','Brand','Car_No','Model_No','IMEI','Rental_Company')
		->orderBy('Label','Desc')
		->where('assets.Availability', '=', 'Yes')
		->where('assets.Type', '=', $input["Type"])

		->get();

		return json_encode($view);
	}

	public function test()
	{

			// instantiate and use the dompdf class
			$dompdf = new Dompdf();
			$dompdf->loadHtml('hello world');

			// (Optional) Setup the paper size and orientation
			$dompdf->setPaper('A4', 'landscape');

			// Render the HTML as PDF
			$dompdf->render();

			// Output the generated PDF to Browser
			$dompdf->stream();
	}
	 public function chart(){

		 $me = (new CommonController)->get_current_user();

		 return view('chart',['me'=>$me]);
	 }

}
