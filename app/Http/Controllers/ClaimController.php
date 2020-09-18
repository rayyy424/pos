<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;

class ClaimController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function myclaim()
	{
		$me = (new CommonController)->get_current_user();


			$paymentmonth = DB::table('cutoff')
			->orderBy(DB::raw('str_to_date(cutoff.Payment_Month,"%M %Y")'))
			->get();


			$current=date('M Y');

			$myclaim = DB::table('claimsheets')
			->select('claimsheets.Id','claimsheets.UserId','claimsheets.Claim_Sheet_Name','claimsheets.Remarks','claimstatuses.Status','claimsheets.created_at as Created_Date')
			->leftJoin('claims', 'claimsheets.Id', '=', 'claims.ClaimSheetId')
			->leftJoin('users as submitter', 'claimsheets.UserId', '=', 'submitter.Id')
			->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
			->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
			->where('claimsheets.UserId', '=', $me->UserId)
			->orderBy('claimsheets.Id','desc')
			->groupBy('claimsheets.Id','claimstatuses.UserId','claimstatuses.Status')
			->get();

			$options= DB::table('options')
			->whereIn('Table', ["users","claims"])
			->orderBy('Table','asc')
			->orderBy('Option','asc')
			->get();

			return view('myclaim', ['me' => $me, 'myclaim' => $myclaim,'options' =>$options,'current'=>$current,'paymentmonth'=>$paymentmonth]);

	}

	public function deletereceipt(Request $request)
	{
		$input = $request->all();

		return DB::table('files')
		->where('Id', '=', $input["Id"])
		->delete();

	}

	public function uploadreceipt(Request $request)
	{
		$filenames="";
		$input = $request->all();
		$insertid=$input["ClaimSheetId"];
		$type="Claim";
		$uploadcount=count($request->file('receipt'));

			if ($request->hasFile('receipt')) {

				for ($i=0; $i <$uploadcount ; $i++) {
					# code...
					$file = $request->file('receipt')[$i];
					$destinationPath=public_path()."/private/upload/Claim";
					$extension = $file->getClientOriginalExtension();
					$originalName=$file->getClientOriginalName();
					$fileSize=$file->getSize();
					$fileName=time()."_".$i.".".$extension;
					$upload_success = $file->move($destinationPath, $fileName);
					$insert=DB::table('files')->insertGetId(
						['Type' => $type,
						 'TargetId' => $insertid,
						 'File_Name' => $originalName,
						 'File_Size' => $fileSize,
						 'Web_Path' => '/private/upload/Claim/'.$fileName
						]
					);
					$filenames.= $insert."|".url('/private/upload/Claim/'.$fileName)."|" .$originalName.",";
				}

				$filenames=substr($filenames, 0, strlen($filenames)-1);

				return $filenames;

				//return '/private/upload/'.$fileName;
			}
			else {
				return 0;
			}
	}

	public function claimmanagement($start = null, $end = null)
	{
		$me = (new CommonController)->get_current_user();

		$showleave = DB::table('leaves')
		->leftJoin('leavestatuses', 'leaves.Id', '=', 'leavestatuses.LeaveId')
		->leftJoin('users as applicant', 'leaves.UserId', '=', 'applicant.Id')
		// ->leftJoin('accesscontrols', 'accesscontrols.UserId', '=', 'applicant.Id')
		->leftJoin('users as approver', 'leavestatuses.UserId', '=', 'approver.Id')
		->select('leaves.Id','applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.Reason','leaves.created_at as Application_Date','approver.Name as Approver','leavestatuses.Leave_Status as Status','leavestatuses.updated_at as Review_Date','leavestatuses.Comment')
		// ->where('accesscontrols.Show_Leave_To_Public', '=', 1)
		->orderBy('leaves.Id','desc')
		->get();

		$d=date('d');

		if ($start==null)
		{

			if($d>=16)
			{

				$start=date('d-M-Y', strtotime('first day of this month'));
				// $start=date('d-M-Y', strtotime($start,' +16 days'));
				$start = date('d-M-Y', strtotime($start . " +15 days"));

			}
			else {

				$start=date('d-M-Y', strtotime('first day of last month'));
				// $start=date('d-M-Y', strtotime($start,' +16 days'));
				$start = date('d-M-Y', strtotime($start . " +15 days"));

			}
		}

		if ($end==null)
		{
			if($d>=16)
			{

				$end=date('d-M-Y', strtotime('first day of next month'));
				$end = date('d-M-Y', strtotime($end . " +14 days"));
				// $end=date('d-M-Y', strtotime($end,' +15 days'));
			}
			else {

				$end=date('d-M-Y', strtotime('first day of this month'));
				$end = date('d-M-Y', strtotime($end . " +14 days"));
				// $end=date('d-M-Y', strtotime($end,' +15 days'));

			}

		}

		// $claims = DB::table('claimsheets')
		// ->select('claimsheets.Id','claimsheets.UserId','submitter.Name','claimsheets.Claim_Sheet_Name','claimsheets.Remarks','claimsheets.Status','claimsheets.created_at as Created_Date')
		// ->leftJoin('users as submitter', 'claimsheets.UserId', '=', 'submitter.Id')
		// ->leftJoin('claims', 'claims.ClaimSheetId', '=', 'claimsheets.Id')
		// ->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		// ->leftJoin('claimstatuses', 'claimstatuses.ClaimId', '=', DB::raw('max.`maxid`'))
		// ->where('claimstatuses.UserId', '=', $me->UserId)
		// ->orderBy('claimsheets.Id','desc')
		// ->groupBy('claimsheets.Id')
		// ->groupBy('claimstatuses.UserId')
		// ->get();

		$claims = DB::table('claimsheets')
		->select('claimsheets.Id','claimsheets.UserId','submitter.StaffId as Staff_ID','submitter.Name','claimsheets.Claim_Sheet_Name','claimsheets.Remarks','claimstatuses.Status','claimsheets.created_at as Created_Date')
		->leftJoin('claims', 'claimsheets.Id', '=', 'claims.ClaimSheetId')
		->leftJoin('users as submitter', 'claimsheets.UserId', '=', 'submitter.Id')
		->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		->where('claimstatuses.UserId', '=', $me->UserId)
		->orderBy('claimsheets.Id','desc')
		->groupBy('claimsheets.Id','claimstatuses.UserId','claimstatuses.Status')
		->get();

		$allclaims = DB::table('claims')
		->select('claimsheets.Id','claimsheets.UserId','submitter.StaffId as Staff_ID','submitter.Name','claimsheets.Claim_Sheet_Name','claimsheets.Remarks','approver.Name as Approver','claimstatuses.Status','claimsheets.created_at as Created_Date')
		->leftJoin('claimsheets', 'claimsheets.Id', '=', 'claims.ClaimSheetId')
		->leftJoin('users as submitter', 'claimsheets.UserId', '=', 'submitter.Id')
		->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
		->where(DB::raw('str_to_date(claims.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(claims.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y") AND claimsheets.UserId is not null'))
		->orderBy('claimsheets.Id','desc')
		->groupBy('claimsheets.Id')
		->get();

		$allfinalclaims = DB::table('claimsheets')
		->select('claimsheets.Id','claimsheets.UserId','submitter.StaffId as Staff_ID','submitter.Name','claimsheets.Claim_Sheet_Name','claimsheets.Remarks','approver.Name as Approver','claimstatuses.Status','claimsheetstatuses.Claim_Status','update.Name as Updated_By','claimsheetstatuses.Updated_At','claimsheets.created_at as Created_Date')
		->leftJoin('claims', 'claimsheets.Id', '=', 'claims.ClaimSheetId')

		->leftJoin( DB::raw('(select Max(Id) as maxid2,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max2'), 'max2.ClaimSheetId', '=', 'claimsheets.Id')
		->leftJoin('claimsheetstatuses', 'claimsheetstatuses.Id', '=', DB::raw('max2.`maxid2`'))

		->leftJoin('users as submitter', 'claimsheets.UserId', '=', 'submitter.Id')
		->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
		->leftJoin('users as update', 'claimsheetstatuses.UserId', '=', 'update.Id')
		->where('claimstatuses.Status', 'like','%Final Approved%')
		->orderBy('claimsheets.Id','desc')
		->groupBy('claimsheets.Id')
		->get();

			return view('claimmanagement', ['me' => $me,'showleave' => $showleave ,'allclaims' =>$allclaims,'allfinalclaims' =>$allfinalclaims,'claims' => $claims ,'start'=>$start,'end'=>$end ]);

	}

	public function myclaimdetail($id)
	{
		$me = (new CommonController)->get_current_user();

		// $hierarchy = DB::table('users')
		// ->select('L2.Id as L2Id','L2.Name as L2Name','L2.Claim_1st_Approval as L21st','L2.Claim_2nd_Approval as L22nd',
		// 'L3.Id as L3Id','L3.Name as L3Name','L3.Claim_1st_Approval as L31st','L3.Claim_2nd_Approval as L32nd')
		// ->leftJoin(DB::raw("(select users.Id,users.Name,users.SuperiorId,accesscontrols.Claim_1st_Approval,accesscontrols.Claim_2nd_Approval,accesscontrols.Claim_Final_Approval from users left join accesscontrols on users.Id=accesscontrols.UserId) as L2"),'L2.Id','=','users.SuperiorId')
		// ->leftJoin(DB::raw("(select users.Id,users.Name,users.SuperiorId,accesscontrols.Claim_1st_Approval,accesscontrols.Claim_2nd_Approval,accesscontrols.Claim_Final_Approval from users left join accesscontrols on users.Id=accesscontrols.UserId) as L3"),'L3.Id','=','L2.SuperiorId')
		// ->where('users.Id', '=', $me->UserId)
		// ->get();

		// $final = DB::table('users')
		// ->select('users.Id','users.Name')
		// ->leftJoin('accesscontrols', 'accesscontrols.UserId', '=', 'users.Id')
		// ->where('Claim_Final_Approval', '=', 1)
		// ->get();

		$myclaim = DB::table('claimsheets')
		->leftJoin('users as submitter', 'claimsheets.UserId', '=', 'submitter.Id')
		->select('claimsheets.Id','submitter.Name','claimsheets.Claim_Sheet_Name','claimsheets.Remarks','claimsheets.Status','claimsheets.created_at as Created_Date')
		->where('claimsheets.Id', '=', $id)
		->get();

		$myreceipt = DB::table('files')
		->where('TargetId', '=', $id)
		->where('Type', '=', 'Claim')
		->get();


		$options= DB::table('options')
		->whereIn('Table', ["users","claims"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		$myclaimdetail = DB::table('claims')
    ->select('claims.Id','claims.ClaimSheetId','claims.Date',DB::raw('"" as Day'),'claims.Depart_From','claims.Destination','claims.Site_Name','claims.State','claims.Work_Description',
		'claims.Next_Person','claims.Transport_Type','claims.Car_No','claims.Mileage','claims.Currency','claims.Rate','claims.Expenses_Type','claims.Total_Expenses','claims.Petrol_SmartPay',DB::raw('"" as Claims_Amount_Exclude_SmartPay'),'claims.Advance','claims.Total_Amount','claims.GST_Amount','claims.Total_Without_GST','claims.Receipt_No','claims.Company_Name','claims.GST_No','claims.Remarks','approver.Name as Approver','claimstatuses.Status','claimstatuses.Comment','claimstatuses.updated_at as Updated_At')
		->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
    ->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
    ->where('claims.ClaimSheetId', '=', $id)
		->orderBy('claims.Id','desc')
    ->get();


			return view('myclaimdetail', ['me' => $me, 'myclaim' => $myclaim, 'myclaimdetail' => $myclaimdetail,'myreceipt' =>$myreceipt, 'options' =>$options]);
			//return view('mytimesheet', ['me' => $me,'showleave' =>$showleave, 'mytimesheet' => $mytimesheet, 'hierarchy' => $hierarchy, 'final' => $final]);

	}

	public function claimdetail($id,$userid,$viewall=null,$start = null, $end = null)
	{
		$me = (new CommonController)->get_current_user();

		$d=date('d');

		if ($start==null)
		{

			if($d>=30)
			{

				$start=date('d-M-Y', strtotime('first day of this month'));
				// $start=date('d-M-Y', strtotime($start,' +16 days'));
				$start = date('d-M-Y', strtotime($start . " +15 days"));

			}
			else {

				$start=date('d-M-Y', strtotime('first day of last month'));
				// $start=date('d-M-Y', strtotime($start,' +16 days'));
				$start = date('d-M-Y', strtotime($start . " +15 days"));

			}
		}

		if ($end==null)
		{
			if($d>=30)
			{

				$end=date('d-M-Y', strtotime('first day of next month'));
				$end = date('d-M-Y', strtotime($end . " +14 days"));
				// $end=date('d-M-Y', strtotime($end,' +15 days'));
			}
			else {

				$end=date('d-M-Y', strtotime('first day of this month'));
				$end = date('d-M-Y', strtotime($end . " +14 days"));
				// $end=date('d-M-Y', strtotime($end,' +15 days'));

			}

		}

		$user = DB::table('users')
		->select('users.Id','StaffId','Name','Password','User_Type','Company_Email','Personal_Email','Contact_No_1','Contact_No_2','Nationality','Permanent_Address','Current_Address','Home_Base','DOB','NRIC','Passport_No','Gender','Marital_Status','Position','Emergency_Contact_Person',
		'Emergency_Contact_No','Emergency_Contact_Relationship','Emergency_Contact_Address','files.Web_Path')
		// ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
    ->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->where('users.Id', '=', $userid)
		->first();

		$claim = DB::table('claimsheets')
		->leftJoin('users as submitter', 'claimsheets.UserId', '=', 'submitter.Id')
		->select('claimsheets.Id','submitter.Name','claimsheets.Claim_Sheet_Name','claimsheets.Remarks','claimsheets.Status','claimsheetstatuses.Claim_Status','users.Name as Updated_By','claimsheetstatuses.Updated_At','claimsheets.created_at as Created_Date')
		->leftJoin( DB::raw('(select Max(Id) as maxid2,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max2'), 'max2.ClaimSheetId', '=', 'claimsheets.Id')
		->leftJoin('claimsheetstatuses', 'claimsheetstatuses.Id', '=', DB::raw('max2.`maxid2`'))
		->leftJoin('users', 'claimsheetstatuses.UserId', '=','users.Id')
		->where('claimsheets.Id', '=', $id)
		->get();

		$receipts = DB::table('files')
		->where('TargetId', '=', $id)
		->where('Type', '=', 'Claim')
		->get();

		$options= DB::table('options')
		->whereIn('Table', ["users","claims"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();



		$claimdetail = DB::table('claims')
		->select('claimstatuses.Id','claims.Id as ClaimId','claimstatuses.Status','claims.Date',DB::raw('"" as Day'),'claims.Depart_From','claims.Destination','claims.Site_Name','claims.State','claims.Work_Description',
		'claims.Next_Person','claims.Transport_Type','claims.Car_No','claims.Mileage','claims.Currency','claims.Rate','claims.Expenses_Type','claims.Total_Expenses','claims.Petrol_SmartPay',DB::raw('"" as Claims_Amount_Exclude_SmartPay'),'claims.Advance','claims.Summon','claims.Total_Amount','allowance.Allowance','allowance.Monetary_Comp','claims.GST_Amount','claims.Total_Without_GST','claims.Receipt_No','claims.Company_Name','claims.GST_No','claims.Remarks','approver.Name as Approver','claimstatuses.Comment','claimstatuses.updated_at as Review_Date')
		->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
		->leftJoin( DB::raw('(Select Date,sum(Allowance) as Allowance,sum(Monetary_Comp) as Monetary_Comp from timesheets where UserId='.$userid.' AND Date in (Select claims.Date from claims where ClaimsheetId='.$id.') GROUP BY Date) as allowance'),'allowance.Date','=',DB::raw('claims.Date AND claims.Id in (Select min(claims.Id) from claims left join claimsheets on claims.ClaimsheetId=claimsheets.Id Where ClaimsheetId='.$id.' Group By Date)'))
		->where('claims.ClaimSheetId', '=', $id)
		->orderBy('claims.Date','asc')
		->get();

		$timesheetdetail = DB::table('timesheets')
		->select('timesheetstatuses.Id','timesheets.Id as TimesheetId','timesheets.Latitude_In','timesheets.Longitude_In','timesheets.Latitude_Out','timesheets.Longitude_Out','timesheetstatuses.Status','timesheets.Date',DB::raw('"" as Day'),'timesheets.Check_In_Type',
		 'timesheets.Time_In','timesheets.Time_Out','timesheets.State','timesheets.Allowance','timesheets.Monetary_Comp','timesheets.OT1','timesheets.OT2','timesheets.OT3','timesheets.Leader_Member','timesheets.Next_Person','timesheets.Site_Name','timesheets.Work_Description','timesheets.Remarks','approver.Name as Approver','timesheetstatuses.Comment','timesheetstatuses.updated_at as Review_Date','timesheetchecked.Timesheet_Status','checker.Name as Checked_By','timesheetchecked.Updated_At')
		 ->leftJoin( DB::raw('(select Max(Id) as maxid2,TimesheetId from timesheetchecked Group By TimesheetId) as max2'), 'max2.TimesheetId', '=', 'timesheets.Id')
		 ->leftJoin('timesheetchecked', 'timesheetchecked.Id', '=', DB::raw('max2.`maxid2`'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
		->leftJoin('users as checker', 'timesheetchecked.UserId', '=', 'checker.Id')
		->where('timesheets.UserId', '=', $userid)
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->orderBy(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),'asc')
		->get();

		// $allowance = DB::table('timesheets')
		// ->select(DB::raw('sum(timesheets.Allowance) As TotalAllowance,sum(timesheets.Monetary_Comp) As TotalMonetary'))
		// ->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
 	 //  ->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		// ->where('timesheets.UserId', '=', $userid)
		// ->whereRaw('timesheets.Date in (select Date from claims where ClaimsheetId='.$id.')')
		// ->first();

		$approver = DB::table('users')
		->leftJoin('accesscontroltemplates', 'users.AccessControlTemplateId', '=', 'accesscontroltemplates.Id')
		->select('users.Id','users.Name')
		->where('accesscontroltemplates.Approve_Claim', '=', 1)
		->get();

		$mylevel = DB::table('approvalsettings')
		->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
		->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country')
		->where('approvalsettings.Type', '=', 'Claim')
		->where('approvalsettings.UserId', '=', $me->UserId)
		->orderBy('approvalsettings.Country','asc')

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


		$syncdata = DB::select("
			select Date
			from  claims where Expenses_Type='Mileage (RM)' and ClaimsheetId = ".$id." and
			Date NOT IN (select Date FROM claims where Expenses_Type IN ('Toll','Car Park','Car Park (Inc GST)') and ClaimsheetId = ".$id.") UNION all
			select Date
			from  claims where Expenses_Type IN ('Car Park','Car Park (Inc GST)') and ClaimsheetId = ".$id." and
			Date NOT IN (select Date FROM claims where Expenses_Type IN ('Toll','Mileage (RM)') and ClaimsheetId = ".$id.") UNION all
			select Date
			from  claims where Expenses_Type='Toll' and ClaimsheetId = ".$id." and
			Date NOT IN (select Date FROM claims where Expenses_Type IN ('Mileage (RM)','Car Park','Car Park (Inc GST)') and ClaimsheetId = ".$id.")

		");

		$mileageconflict ="";

		foreach($syncdata as $sync)
		{
				$mileageconflict = $mileageconflict."|".$sync->Date;
		}

			// dd($mileageconflict);

		return view('claimdetail', ['me' => $me,'ClaimsheetId'=>$id,'viewall'=>$viewall, 'claim' =>$claim,'receipts' =>$receipts, 'Id' =>$id, 'user' =>$user,'claimdetail' => $claimdetail, 'options' =>$options,'mylevel' => $mylevel,
			'approver' =>$approver,'months'=>$months,'timesheetdetail'=>$timesheetdetail,'start' => $start,'end' => $end,'mileageconflict'=>$mileageconflict]);

	}

	public function newclaimitemstatus(Request $request)
	{

		$input = $request->all();

		DB::table('claimitemstatuses')->insert(
			['ClaimItemId' => $input["ClaimItemId"],
			 'UserId' => $input["UserId"],
			 'Status' => $input["Status"]
			]
		);

		return 1;

	}

	public function submitforapproval(Request $request)
	{

		$me = (new CommonController)->get_current_user();
		$emaillist=array();
		array_push($emaillist,$me->UserId);

		$input = $request->all();

		$Id = explode(",", $input["Id"]);

		$claims = DB::table('claimsheets')
		->select('claims.Id','claimsheets.Id as ClaimsheetId','claimsheets.UserId as SubmitterId','claims.Date','claims.Depart_From','claims.Destination','claims.Site_Name','claims.State','claims.Work_Description',
		'claims.Next_Person','claims.Transport_Type','claims.Car_No','claims.Mileage','claims.Currency','claims.Rate','claims.Expenses_Type','claims.Total_Amount','claims.GST_Amount','claims.Total_Without_GST','claims.Receipt_No','claims.Company_Name','claims.GST_No','claims.Remarks','approver.Name','claimstatuses.UserId','claimstatuses.Status','claimstatuses.Comment','claimstatuses.updated_at as Updated_At')
		->leftJoin('claims', 'claims.ClaimSheetId', '=', 'claimsheets.Id')
		->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
		->where('claims.ClaimSheetId','=', $Id)
		->orderBy('claims.Id','desc')
		->get();

		$mylevel = DB::table('approvalsettings')
		->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
		->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country')
		->where('approvalsettings.Type', '=', 'Claim')
		->where('approvalsettings.UserId', '=', $me->UserId)
		->orderBy('approvalsettings.Country','asc')
		// ->orderBy('approvalsettings.Level','Final Approval","5th Approval","4th Approval","3rd Approval","2nd Approval","1st Approval")
		->orderByRaw("FIELD(approvalsettings.Level , 'Final Approval', '5th Approval', '4th Approval','3rd Approval','2nd Approval','1st Approval') ASC")
		->get();

		$approver = DB::table('approvalsettings')
		->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
		->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country')
		->where('approvalsettings.Type', '=', 'Claim')
		->orderBy('approvalsettings.Country','asc')
		->orderByRaw("FIELD(approvalsettings.Level , '1st Approval', '2nd Approval', '3rd Approval','4th Approval','5th Approval','Final Approval') ASC")
		->get();

		foreach ($claims as $claim) {
			# code...
			foreach ($mylevel as $level) {

					if($level->Level=="Final Approval")
					{
						$level->Level="6 Final Approval";
					}

					break;
			}

			if(!$mylevel)
			{
				$level = (object) ['Id'=>0,'Name'=>"",'Level'=>0,'Country'=>''];
			}
			else

			$submitted=false;
			foreach ($approver as $user) {

				if($user->Level=="Final Approval")
				{
					$user->Level="6 Final Approval";
				}

					if (!empty($user->Id) && $claim->UserId != $user->Id && filter_var($level->Level, FILTER_SANITIZE_NUMBER_INT)<filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT))
					{

						DB::table('claimstatuses')->insert(
							['ClaimId' => $claim->Id,
							 'UserId' => $user->Id,
							 'Status' => "Pending Approval"
							]
						);
						$submitted=true;
						array_push($emaillist,$user->Id);

						break;
					}
					elseif (!empty($user->Id) && $claim->SubmitterId == $user->Id && $level->Id == $user->Id && $level->Level=="6 Final Approval")
					{

						DB::table('claimstatuses')->insert(
							['ClaimId' => $claim->Id,
							 'UserId' => $user->Id,
							 'Status' => "Pending Approval"
							]
						);
						$submitted=true;
						array_push($emaillist,$user->Id);

						break;
					}
					elseif (!empty($user->Id) && $claim->UserId == $user->Id && $claim->Status=="Recalled")
					{

						DB::table('claimstatuses')->insert(
							['ClaimId' => $claim->Id,
							 'UserId' => $user->Id,
							 'Status' => "Pending Approval"
							]
						);
						$submitted=true;
						array_push($emaillist,$user->Id);

						break;
					}
					elseif (!empty($user->Id) && $claim->UserId == $user->Id) {
						# code...
						if(str_contains($claim->Status, 'Rejected') || str_contains($claim->Status, 'Recalled'))
						{
							DB::table('claimstatuses')->insert(
								['ClaimId' => $claim->Id,
								 'UserId' => $user->Id,
								 'Status' => "Pending Approval"
								]
							);
						}

						$submitted=true;
						array_push($emaillist,$user->Id);
					}
				}

			// if ($submitted==false)
			// {
			//
			// 	// DB::table('claimstatuses')->insert(
			// 	// 	['ClaimId' => $claim->Id,
			// 	// 	 'UserId' => $me->SuperiorId,
			// 	// 	 'Status' => "Pending Approval"
			// 	// 	]
			// 	// );
			// 	// array_push($emaillist,$me->SuperiorId);
			// 	foreach ($countryapprover as $user) {
			//
			// 		if ($claim->UserId != $user->Id)
			// 		{
			// 			DB::table('claimstatuses')->insert(
			// 				['ClaimId' => $claim->Id,
			// 				 'UserId' => $user->Id,
			// 				 'Status' => "Pending Approval"
			// 				]
			// 			);
			// 			array_push($emaillist,$user->Id);
			// 			break;
			// 		}
			// 		elseif ($claim->UserId == $user->Id && $claim->Status=="Recalled")
			// 		{
			// 			DB::table('claimstatuses')->insert(
			// 				['ClaimId' => $claim->Id,
			// 				 'UserId' => $user->Id,
			// 				 'Status' => "Pending Approval"
			// 				]
			// 			);
			// 			array_push($emaillist,$user->Id);
			// 			break;
			// 		}
			// 		elseif ($claim->UserId == $user->Id)
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

			DB::table('claimsheets')
						->where('Id', '=',$claim->ClaimsheetId)
						->update(array(
						'submitted_at' =>  DB::raw('now()'),
					));


			DB::table('claimsheets')
						->where('Id', $Id)
						->update(array(
						'Status' =>  'Submitted for Approval',
					));

			$notify = DB::table('users')
			->whereIn('Id', $emaillist)
			->get();

			$subscribers = DB::table('notificationtype')
			->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
			->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
			->where('notificationtype.Id','=',21)
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

			$claims = DB::table('claims')
			->select('claims.Id','claims.Date','claims.Depart_From','claims.Destination','claims.Site_Name','claims.State','claims.Work_Description',
			'claims.Next_Person','claims.Transport_Type','claims.Car_No','claims.Mileage','claims.Currency','claims.Rate','claims.Expenses_Type','claims.Total_Expenses','claims.Petrol_SmartPay',DB::raw('"" as Claims_Amount_Exclude_SmartPay'),'claims.Advance','claims.Total_Amount','claims.Remarks','approver.Name as Approver','claimstatuses.UserId','claimstatuses.Status','claimstatuses.Comment','claimstatuses.updated_at as Updated_At')
			->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
			->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
			->where('claims.ClaimSheetId','=', $Id)
			->orderBy('claims.Date','asc')
			->get();

			// Mail::send('emails.claimapproval', ['me' => $me,'claims' => $claims], function($message) use ($emails,$me,$NotificationSubject)
			// {
			// 		$emails = array_filter($emails);
			// 		array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
			// 		$message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');

			// });

			return 1;
		}
		else {
			return 0;
		}

	}

	public function recall(Request $request)
	{

		$me = (new CommonController)->get_current_user();
		$emaillist=array();
		array_push($emaillist,$me->UserId);

		$input = $request->all();

		$Id = explode(",", $input["Id"]);

		$claims = DB::table('claims')
		->select('claims.Id','claimstatuses.Id as ClaimStatusId','claims.Date','claims.Depart_From','claims.Destination','claims.Site_Name','claims.State','claims.Work_Description',
		'claims.Next_Person','claims.Transport_Type','claims.Car_No','claims.Mileage','claims.Currency','claims.Rate','claims.Expenses_Type','claims.Total_Expenses','claims.Petrol_SmartPay',DB::raw('"" as Claims_Amount_Exclude_SmartPay'),'claims.Advance','claims.Total_Amount','claims.GST_Amount','claims.Total_Without_GST','claims.Receipt_No','claims.Company_Name','claims.GST_No','claims.Remarks','approver.Name','claimstatuses.UserId','claimstatuses.Status','claimstatuses.Comment','claimstatuses.updated_at as Updated_At')
		->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
		->where('claims.ClaimSheetId','=', $Id)
		->orderBy('claims.Id','desc')
		->get();

		foreach ($claims as $claim)
		{
			# code...

			array_push($emaillist,$claim->UserId);

			// DB::table('claimstatuses')
			// 			->where('Id', $claim->ClaimStatusId)
			// 			->update(array(
			// 			'Status' =>  'Recalled',
			// 		));

		}

		DB::table('claimsheets')
					->where('Id', $Id)
					->update(array(
					'Status' =>  'Recalled',
				));

		if (count($emaillist)>1)
		{

			$notify = DB::table('users')
			->whereIn('Id', $emaillist)
			->get();

			$subscribers = DB::table('notificationtype')
			->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
			->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
			->where('notificationtype.Id','=',22)
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

			$claims = DB::table('claims')
			->select('claims.Id','claims.Date','claims.Depart_From','claims.Destination','claims.Site_Name','claims.State','claims.Work_Description',
			'claims.Next_Person','claims.Transport_Type','claims.Car_No','claims.Mileage','claims.Currency','claims.Rate','claims.Expenses_Type','claims.Total_Expenses','claims.Petrol_SmartPay',DB::raw('"" as Claims_Amount_Exclude_SmartPay'),'claims.Advance','claims.Total_Amount','claims.GST_Amount','claims.Total_Without_GST','claims.Receipt_No','claims.Company_Name','claims.GST_No','claims.Remarks','approver.Name as Approver','claimstatuses.UserId','claimstatuses.Status','claimstatuses.Comment','claimstatuses.updated_at as Updated_At')
			->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
			->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
			->where('claims.ClaimSheetId','=', $Id)
			->orderBy('claims.Date','asc')
			->get();

			// Mail::send('emails.claimrecall', ['me' => $me,'claims' => $claims], function($message) use ($emails,$me,$NotificationSubject)
			// {
			// 		$emails = array_filter($emails);
			// 		array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
			// 		$message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');

			// });
		}

		return 1;

	}

	public function updateclaimsheet(Request $request)
	{

		$input = $request->all();

		$me = (new CommonController)->get_current_user();

		if($input["Status"]=="Reset")
		{
			return DB::table('claimsheetstatuses')
			->where('ClaimSheetId', '=', $input["Id"])
			->delete();

		}
		else {
			# code...
			$id=DB::table('claimsheetstatuses')
						->insertGetId([
						'ClaimSheetId' => $input["Id"],
						'Claim_Status' =>  $input["Status"],
						'UserId' =>$me->UserId
					]);
		}

		return $id;

	}

	public function updateremark(Request $request)
	{

		$input = $request->all();

		$me = (new CommonController)->get_current_user();

		if($input["ClaimstatusId"]=="")
		{
			$id=DB::table('claimsheetstatuses')
						->insertGetId([
						'ClaimSheetId' => $input["ClaimsheetId"],
						'Remarks' =>  $input["Remark"],
						'UserId' =>$me->UserId
					]);

		}
		else {
			# code...
			DB::table('claimsheetstatuses')
						->where('Id', $input["ClaimstatusId"])
						->update(array(
						'Remarks' =>  $input["Remark"],
						'UserId' =>$me->UserId
					));

			$id=1;

		}

		return $id;

	}

	public function newclaim(Request $request)
	{
		$auth = Auth::user();

		$me = DB::table('users')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By TargetId) as max'), 'max.TargetId', '=', 'users.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		// ->leftJoin('accesscontrols', 'accesscontrols.UserId', '=', 'users.Id')
		->where('users.Id', '=',$auth -> Id)
		->first();

		if ($me -> Web_Path=="")
		{
				$me -> Web_Path = URL::to('/') ."/img/default-user.png" ;
		}

			$input = $request->all();

			$rules = array(
				'Claim_Name' => 'Required',
				'Date'     => 'Required',
				'Expenses_Type'       => 'Required',
				'Total_Amount'  =>'Required'
				);

				$messages = array(
					'Claim_Name.required' => 'The Claim Name field is required',
					'Date.required'     => 'The Date field is required',
					'Expenses_Type.required'       => 'The Expenses Type field is required',
					'Total_Amount.required'  =>'The Total Amount field is required'
					);

			$validator = Validator::make($input, $rules,$messages);

			if ($validator->passes())
			{
					DB::table('claims')->insert(
						['UserId' => $me->UserId,
						 'Claim_Name' => $input["Claim_Name"],
						 'Date' => $input["Date"],
						 'Site_Name' => $input["Site_Name"],
						 'State' => $input["State"],
						 'Work_Description' => $input["Work_Description"],
						 'Expenses_Type' => $input["Expenses_Type"],
						 'GST_No' => $input["GST_No"],
						 'GST_Amount' => $input["GST_Amount"],
						 'Total_Amount' => $input["Total_Amount"],
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
		array_push($emaillist,$me->UserId);

		$input = $request->all();

		$Id = explode(",", $input["Id"]);

		$claims = DB::table('claims')
		->select('claims.Id','claimsheets.UserId','claims.Date','claims.Depart_From','claims.Destination','claims.Site_Name','claims.State','claims.Work_Description',
		'claims.Next_Person','claims.Transport_Type','claims.Car_No','claims.Mileage','claims.Currency','claims.Rate','claims.Expenses_Type','claims.Total_Expenses','claims.Petrol_SmartPay',DB::raw('"" as Claims_Amount_Exclude_SmartPay'),'claims.Advance','claims.Total_Amount','claims.GST_Amount','claims.Total_Without_GST','claims.Receipt_No','claims.Company_Name','claims.GST_No','claims.Remarks','approver.Name','claimstatuses.UserId','claimstatuses.Status','claimstatuses.Comment','claimstatuses.updated_at as Updated_At')
		->leftJoin('claimsheets', 'claimsheets.Id', '=', 'claims.ClaimSheetId')
		->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
		->whereIn('claims.ClaimSheetId', $Id)
		->orderBy('claims.Id','desc')
		->get();

		$approver = DB::table('approvalsettings')
		->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
		->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country')
		->where('approvalsettings.Type', '=', 'Claim')
		->orderBy('approvalsettings.Country','asc')
		->orderByRaw("FIELD(approvalsettings.Level , '1st Approval','2nd Approval','3rd Approval','4th Approval','5th Approval','Final Approval') ASC")
		->get();

		$submittedfornextapproval=false;
		$final=false;

		foreach ($claims as $claim) {
			# code...
			$submitted=false;

			if ((strpos($claim->Status, 'Rejected') === false) && $claim->Status!="Final Approved")
			{

				foreach ($approver as $user) {

						if (!empty($user->Id) && $claim->UserId != $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($claim->Status, FILTER_SANITIZE_NUMBER_INT))
						{

							DB::table('claimstatuses')->insert(
								['ClaimId' => $claim->Id,
								 'UserId' => $user->Id,
								 'Status' => "Pending Approval"
								]
							);
							$submitted=true;
							$submittedfornextapproval=true;
							array_push($emaillist,$user->Id);
							array_push($emaillist,$claim->UserId);

							break;
						}
						elseif (!empty($user->Id) && $claim->UserId == $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($claim->Status, FILTER_SANITIZE_NUMBER_INT))
						{
							# code...
								$submitted=true;
								array_push($emaillist,$user->Id);
						}
						elseif (!empty($user->Id) && $claim->UserId == $user->Id && $claim->Status=="Recalled")
						{

							DB::table('claimstatuses')->insert(
								['ClaimId' => $claim->Id,
								 'UserId' => $user->Id,
								 'Status' => "Pending Approval"
								]
							);
							$submitted=true;
							$submittedfornextapproval=true;
							array_push($emaillist,$user->Id);

							break;
						}
						elseif (!empty($user->Id) && $claim->UserId == $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($claim->Status, FILTER_SANITIZE_NUMBER_INT))
						{
							# code...
								$submitted=true;
								$submittedfornextapproval=true;
								array_push($emaillist,$user->Id);
								array_push($emaillist,$claim->UserId);
						}
						elseif (!empty($user->Id) && $claim->UserId != $user->Id && $user->Level=="Final Approval")
						{

							DB::table('claimstatuses')->insert(
								['ClaimId' => $claim->Id,
								 'UserId' => $user->Id,
								 'Status' => "Pending Approval"
								]
							);
							$submitted=true;
							$submittedfornextapproval=true;
							array_push($emaillist,$user->Id);
							array_push($emaillist,$claim->UserId);

							break;
						}
						else {

						}

					}

				// if ($submitted==false)
				// {
				// 	// 	DB::table('claimstatuses')->insert(
				// 	// 	['ClaimId' => $claim->Id,
				// 	// 	 'UserId' => $me->SuperiorId,
				// 	// 	 'Status' => "Pending Approval"
				// 	// 	]
				// 	// );
				// 	foreach ($countryapprover as $user)
				// 	{
				//
				// 		if (!empty($user->Id) && $claim->UserId != $user->Id  && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($claim->Status, FILTER_SANITIZE_NUMBER_INT))
				// 		{
				// 			DB::table('claimstatuses')->insert(
				// 				['ClaimId' => $claim->Id,
				// 				 'UserId' => $user->Id,
				// 				 'Status' => "Pending Approval"
				// 				]
				// 			);
				// 			array_push($emaillist,$user->Id);
				// 			array_push($emaillist,$claim->UserId);
				// 			break;
				// 		}
				// 		elseif (!empty($user->Id) && $claim->UserId == $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($claim->Status, FILTER_SANITIZE_NUMBER_INT))
				// 		{
				// 			array_push($emaillist,$user->Id);
				// 			array_push($emaillist,$claim->UserId);
				// 			break;
				// 		}
				// 		elseif (!empty($user->Id) && $claim->UserId != $user->Id && $user->Level=="Final Approval")
				// 		{
				//
				// 			DB::table('claimstatuses')->insert(
				// 				['ClaimId' => $claim->Id,
				// 				 'UserId' => $user->Id,
				// 				 'Status' => "Pending Approval"
				// 				]
				// 			);
				// 			$submitted=true;
				// 			array_push($emaillist,$user->Id);
				// 			array_push($emaillist,$claim->UserId);
				//
				// 			break;
				// 		}
				//
				// 	}
				// }

			}
			elseif ((strpos($claim->Status, 'Rejected') !== false))
			{
				// DB::table('claimsheets')
				// 			->where('Id', $Id)
				// 			->update(array(
				// 			'Status' =>  $claim->Status
				// 		));

				array_push($emaillist,$claim->UserId);
			}
			elseif ($claim->Status=="Final Approved" ||$claim->Status=="Final Approved with Special Attention" || $claim->Status=="Final Rejected")
			{
				$final=true;
				array_push($emaillist,$claim->UserId);
			}

		}

		if($final)
		{

			DB::table('claimsheets')
						->where('Id', $Id)
						->update(array(
						'Status' =>  $claim->Status
					));

					array_push($emaillist,$claim->UserId);

		}
		// elseif($submittedfornextapproval)
		// {
		//
		// 	DB::table('claimsheets')
		// 				->where('Id', $Id)
		// 				->update(array(
		// 				'Status' =>  'Submitted for Next Approval'
		// 			));
		//
		// }

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
				->where('notificationtype.Id','=',40)
				->get();

			}
			else {
				# code...

				$subscribers = DB::table('notificationtype')
				->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
				->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
				->where('notificationtype.Id','=',23)
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

			$claims = DB::table('claims')
			->select('claims.Id','claimsheets.UserId','claims.Date','submitter.Name as Submitter','claims.Depart_From','claims.Destination','claims.Site_Name','claims.State','claims.Work_Description',
			'claims.Next_Person','claims.Transport_Type','claims.Car_No','claims.Mileage','claims.Currency','claims.Rate','claims.Expenses_Type','claims.Total_Expenses','claims.Petrol_SmartPay',DB::raw('"" as Claims_Amount_Exclude_SmartPay'),'claims.Advance','claims.Total_Amount','claims.GST_Amount','claims.Total_Without_GST','claims.Receipt_No','claims.Company_Name','claims.GST_No','claims.Remarks','approver.Name as Approver','claimstatuses.UserId','claimstatuses.Status','claimstatuses.Comment','claimstatuses.updated_at as Updated_At')
			->leftJoin('claimsheets', 'claimsheets.Id', '=', 'claims.ClaimSheetId')
			->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
			->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
			->leftJoin('users as submitter', 'claimsheets.UserId', '=', 'submitter.Id')
			->whereIn('claims.ClaimSheetId', $Id)
			->orderBy('claims.Date','asc')
			->get();

			// Mail::send('emails.claimapproval2', ['me' => $me,'claims' => $claims], function($message) use ($emails,$claims,$NotificationSubject)
			// {
			// 		$emails = array_filter($emails);
			// 		array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
			// 		$message->to($emails)->subject($NotificationSubject.' ['.$claims[0]->Submitter.']');

			// });

			return 1;
		}
		else {
			return 0;
		}

	}

	public function export3($id,$userid,$start=null, $end=null)
	{

		$me = (new CommonController)->get_current_user();

		$user = DB::table('users')
		->select('users.Id','StaffId','Name','Password','User_Type','Company_Email','Personal_Email','Contact_No_1','Contact_No_2','Nationality','Permanent_Address','Current_Address','Home_Base','DOB','NRIC','Passport_No','Gender','Marital_Status','Position','Emergency_Contact_Person',
		'Emergency_Contact_No','Emergency_Contact_Relationship','Emergency_Contact_Address','files.Web_Path')
		// ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
    	->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->where('users.Id', '=', $userid)
		->first();

		$claim = DB::table('claimsheets')
		->leftJoin('users as submitter', 'claimsheets.UserId', '=', 'submitter.Id')
		->select('claimsheets.Id','submitter.Name','claimsheets.Claim_Sheet_Name','claimsheets.Remarks','claimsheets.Status','claimsheets.created_at as Created_Date')
		->where('claimsheets.Id', '=', $id)
		->get();

		$receipts = DB::table('files')
		->where('TargetId', '=', $id)
		->where('Type', '=', 'Claim')
		->get();

		$options= DB::table('options')
		->whereIn('Table', ["users","claims"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		$claimdetail = DB::table('claims')
		->select('claims.Date',DB::raw('"" as Day'),'claims.Depart_From As From','claims.Destination As To','claims.Site_Name As Site','claims.State',
		'claims.Next_Person as Next_P','claims.Transport_Type as Type','claims.Car_No AS Number','claims.Mileage','claims.Currency as Cur','claims.Rate','claims.Expenses_Type AS Exp_Type','claims.Total_Expenses as Tot_Exp','claims.Petrol_SmartPay as SmartP',DB::raw('"-" as Exc_SmartP'),'claims.Advance as Adv','claims.Summon','claims.Total_Amount as Tot','allowance.Allowance','claims.GST_Amount as GST','claims.Total_Without_GST As Without_GST','claims.Receipt_No as Receipt','claims.Company_Name AS Company','claims.GST_No','claims.Remarks','approver.Name as Approver','claimstatuses.Status','claimstatuses.Comment','claimstatuses.updated_at as Review_Date')
		->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
		->leftJoin( DB::raw('(Select Date,sum(Allowance) as Allowance,sum(Monetary_Comp) as Monetary_Comp from timesheets where UserId='.$userid.' AND Date in (Select claims.Date from claims where ClaimsheetId='.$id.') GROUP BY Date) as allowance'),'allowance.Date','=',DB::raw('claims.Date AND claims.Id in (Select min(claims.Id) from claims left join claimsheets on claims.ClaimsheetId=claimsheets.Id Where ClaimsheetId='.$id.' Group By Date)'))
		->where('claims.ClaimSheetId', '=', $id)
		->orderBy(DB::raw('str_to_date(claims.Date,"%d-%M-%Y")'),'asc')
		->get();

		if($start)
		{

			$timesheetdetail = DB::table('timesheets')
			->select('timesheets.Date',DB::raw('"" as Day'),'timesheets.Check_In_Type',
			 'timesheets.Time_In','timesheets.Time_Out','timesheets.State','timesheets.Allowance','timesheets.Monetary_Comp','timesheets.OT1','timesheets.OT2','timesheets.OT3','timesheets.Leader_Member','timesheets.Next_Person','timesheets.Site_Name','timesheets.Work_Description','timesheets.Remarks','approver.Name as Approver','timesheetstatuses.Status','timesheetstatuses.Comment','timesheetstatuses.updated_at as Review_Date')
			->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
			->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
			->where('timesheets.UserId', '=', $userid)
			->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
			->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
			->orderBy(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),'asc')
			->get();

		}
		else {
			$timesheetdetail=false;
		}

		$allowance = DB::table('timesheets')
		->select(DB::raw('sum(timesheets.Allowance) As TotalAllowance,sum(timesheets.Monetary_Comp) As TotalMoney'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->where('timesheets.UserId', '=', $userid)
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->first();

		$mylevel = DB::table('approvalsettings')
		->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
		->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country')
		->where('approvalsettings.Type', '=', 'Claim')
		->where('approvalsettings.UserId', '=', $me->UserId)
		->orderBy('approvalsettings.Country','asc')
		// ->orderBy('approvalsettings.Level','Final Approval","5th Approval","4th Approval","3rd Approval","2nd Approval","1st Approval")
		->orderByRaw("FIELD(approvalsettings.Level , 'Final Approval', '5th Approval', '4th Approval','3rd Approval','2nd Approval','1st Approval') ASC")
		->first();

		$total = DB::table('claims')
		->leftJoin('claimsheets', 'claimsheets.Id', '=','claims.ClaimsheetId')
		->select(DB::Raw('sum(claims.Total_Expenses) As TotalExpenses,sum(claims.Petrol_SmartPay) As TotalSmartpay,sum(claims.Summon) as TotalSummon,sum(claims.Advance) As TotalAdvance, sum(claims.GST_Amount) As TotalGSTAmount,sum(claims.Total_Without_GST) As TotalnoGST,sum(claims.Total_Amount) As TotalPayable'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		->where('claims.ClaimSheetId', '=', $id)
		->where('claimstatuses.Status', 'like','%Approved%')
		->get();

			$html = view('exportclaim', ['me' => $me, 'claim' =>$claim,'receipts' =>$receipts, 'Id' =>$id, 'user' =>$user,'claimdetail' => $claimdetail,'timesheetdetail' => $timesheetdetail, 'options' =>$options,'mylevel' => $mylevel,'total' => $total,'TotalAllowance'=>($allowance->TotalAllowance + $allowance->TotalMoney)]);
			(new ExportPDFController)->ExportLandscape($html);

	}

	public function export($id,$userid,$start=null, $end=null)
	{

		$me = (new CommonController)->get_current_user();

		$user = DB::table('users')
		->select('users.Id','StaffId','Name','Password','User_Type','Company_Email','Personal_Email','Contact_No_1','Contact_No_2','Nationality','Permanent_Address','Current_Address','Home_Base','DOB','NRIC','Passport_No','Gender','Marital_Status','Position','Emergency_Contact_Person',
		'Emergency_Contact_No','Emergency_Contact_Relationship','Emergency_Contact_Address','files.Web_Path')
		// ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
    	->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->where('users.Id', '=', $userid)
		->first();

		$claim = DB::table('claimsheets')
		->leftJoin('users as submitter', 'claimsheets.UserId', '=', 'submitter.Id')
		->select('claimsheets.Id','submitter.Name','claimsheets.Claim_Sheet_Name','claimsheets.Remarks','claimsheets.Status','claimsheets.created_at as Created_Date')
		->where('claimsheets.Id', '=', $id)
		->get();

		$receipts = DB::table('files')
		->where('TargetId', '=', $id)
		->where('Type', '=', 'Claim')
		->get();

		$options= DB::table('options')
		->whereIn('Table', ["users","claims"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		$claimdetail = DB::table('claims')
		->select('claims.Date',DB::raw('"" as Day'),'claims.Depart_From As From','claims.Destination As To','claims.Site_Name As Site','claims.State',
		'claims.Next_Person as Next_P','claims.Transport_Type as Type','claims.Car_No AS Number','claims.Mileage','claims.Currency as Cur','claims.Rate','claims.Expenses_Type AS Exp_Type','claims.Total_Expenses as Tot_Exp','claims.Petrol_SmartPay as SmartP',DB::raw('"-" as Exc_SmartP'),'claims.Advance as Adv','claims.Summon','claims.Total_Amount as Tot','allowance.Allowance','claims.GST_Amount as GST','claims.Total_Without_GST As Without_GST','claims.Receipt_No as Receipt','claims.Company_Name AS Company','claims.GST_No','claims.Remarks','approver.Name as Approver','claimstatuses.Status','claimstatuses.Comment','claimstatuses.updated_at as Review_Date')
		->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
		->leftJoin( DB::raw('(Select Date,sum(Allowance) as Allowance,sum(Monetary_Comp) as Monetary_Comp from timesheets where UserId='.$userid.' AND Date in (Select claims.Date from claims where ClaimsheetId='.$id.') GROUP BY Date) as allowance'),'allowance.Date','=',DB::raw('claims.Date AND claims.Id in (Select min(claims.Id) from claims left join claimsheets on claims.ClaimsheetId=claimsheets.Id Where ClaimsheetId='.$id.' Group By Date)'))
		->where('claims.ClaimSheetId', '=', $id)
		->orderBy(DB::raw('str_to_date(claims.Date,"%d-%M-%Y")'),'asc')
		->get();

		if($start)
		{

			$timesheetdetail = DB::table('timesheets')
			->select('timesheets.Date',DB::raw('"" as Day'),'timesheets.Check_In_Type',
			 'timesheets.Time_In','timesheets.Time_Out','timesheets.State','timesheets.Allowance','timesheets.Monetary_Comp','timesheets.OT1','timesheets.OT2','timesheets.OT3','timesheets.Leader_Member','timesheets.Next_Person','timesheets.Site_Name','timesheets.Work_Description','timesheets.Remarks','approver.Name as Approver','timesheetstatuses.Status','timesheetstatuses.Comment','timesheetstatuses.updated_at as Review_Date')
			->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
			->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
			->where('timesheets.UserId', '=', $userid)
			->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
			->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
			->orderBy(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'),'asc')
			->get();

		}
		else {
			$timesheetdetail=false;
		}

		$allowance = DB::table('timesheets')
		->select(DB::raw('sum(timesheets.Allowance) As TotalAllowance,sum(timesheets.Monetary_Comp) As TotalMoney'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->where('timesheets.UserId', '=', $userid)
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->first();

		$mylevel = DB::table('approvalsettings')
		->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
		->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country')
		->where('approvalsettings.Type', '=', 'Claim')
		->where('approvalsettings.UserId', '=', $me->UserId)
		->orderBy('approvalsettings.Country','asc')
		// ->orderBy('approvalsettings.Level','Final Approval","5th Approval","4th Approval","3rd Approval","2nd Approval","1st Approval")
		->orderByRaw("FIELD(approvalsettings.Level , 'Final Approval', '5th Approval', '4th Approval','3rd Approval','2nd Approval','1st Approval') ASC")
		->first();

		$total = DB::table('claims')
		->leftJoin('claimsheets', 'claimsheets.Id', '=','claims.ClaimsheetId')
		->select(DB::Raw('sum(claims.Total_Expenses) As TotalExpenses,sum(claims.Petrol_SmartPay) As TotalSmartpay,sum(claims.Summon) as TotalSummon,sum(claims.Advance) As TotalAdvance, sum(claims.GST_Amount) As TotalGSTAmount,sum(claims.Total_Without_GST) As TotalnoGST,sum(claims.Total_Amount) As TotalPayable'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		->where('claims.ClaimSheetId', '=', $id)
		->where('claimstatuses.Status', 'like','%Approved%')
		->get();

			$html = view('exportclaim', ['me' => $me, 'claim' =>$claim,'receipts' =>$receipts, 'Id' =>$id, 'user' =>$user,'claimdetail' => $claimdetail,'timesheetdetail' => $timesheetdetail, 'options' =>$options,'mylevel' => $mylevel,'total' => $total,'TotalAllowance'=>($allowance->TotalAllowance + $allowance->TotalMoney)]);
			(new ExportPDFController)->ExportLandscape($html);

	}

	public function export2($id,$userid,$start=null, $end=null)
	{

		$me = (new CommonController)->get_current_user();

		$user = DB::table('users')
		->select('users.Id','StaffId','Name','Password','User_Type','Company_Email','Personal_Email','Contact_No_1','Contact_No_2','Nationality','Permanent_Address','Current_Address','Home_Base','DOB','NRIC','Passport_No','Gender','Marital_Status','Position','Emergency_Contact_Person',
		'Emergency_Contact_No','Emergency_Contact_Relationship','Emergency_Contact_Address','files.Web_Path')
		// ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
    	->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->where('users.Id', '=', $userid)
		->first();

		$claim = DB::table('claimsheets')
		->leftJoin('users as submitter', 'claimsheets.UserId', '=', 'submitter.Id')
		->select('claimsheets.Id','submitter.Name','claimsheets.Claim_Sheet_Name','claimsheets.Remarks','claimsheets.Status','claimsheets.created_at as Created_Date')
		->where('claimsheets.Id', '=', $id)
		->get();

		$receipts = DB::table('files')
		->where('TargetId', '=', $id)
		->where('Type', '=', 'Claim')
		->get();



		$options= DB::table('options')
		->whereIn('Table', ["users","claims"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();


		$claimdetail = DB::table('claims')
		->select('claims.Date',DB::raw('"" as Day'),'claims.Depart_From As From','claims.Destination As To','claims.Site_Name','claims.State',
		'claims.Next_Person','claims.Transport_Type as Type','claims.Car_No AS Vehicle_No','claims.Mileage','claims.Currency','claims.Rate','claims.Expenses_Type AS Exp_Type','claims.Total_Expenses as Tot_Exp','claims.Petrol_SmartPay as SmartPay',DB::raw('"-" as Exclude_SmartPay'),'claims.Advance as Adv','claims.Summon','claims.Total_Amount as Tot','allowance.Allowance','claims.GST_Amount','claims.Total_Without_GST As Tot_Without_GST','claims.Receipt_No','claims.Company_Name AS Company','claims.GST_No','claims.Remarks','approver.Name as Approver','claimstatuses.Status','claimstatuses.Comment','claimstatuses.updated_at as Review_Date')
		->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
		->leftJoin( DB::raw('(Select Date,sum(Allowance) as Allowance,sum(Monetary_Comp) as Monetary_Comp from timesheets where UserId='.$userid.' AND Date in (Select claims.Date from claims where ClaimsheetId='.$id.') GROUP BY Date) as allowance'),'allowance.Date','=',DB::raw('claims.Date AND claims.Id in (Select min(claims.Id) from claims left join claimsheets on claims.ClaimsheetId=claimsheets.Id Where ClaimsheetId='.$id.' Group By Date)'))
		->where('claims.ClaimSheetId', '=', $id)
		->orderBy(DB::raw('str_to_date(claims.Date,"%d-%M-%Y")'),'asc')
		->get();


			$timesheetdetail=false;

		$allowance = DB::table('timesheets')
		->select(DB::raw('sum(timesheets.Allowance) As TotalAllowance,sum(timesheets.Monetary_Comp) As TotalMoney'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->where('timesheets.UserId', '=', $userid)
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(timesheets.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->first();

		$mylevel = DB::table('approvalsettings')
		->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
		->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country')
		->where('approvalsettings.Type', '=', 'Claim')
		->where('approvalsettings.UserId', '=', $me->UserId)
		->orderBy('approvalsettings.Country','asc')
		// ->orderBy('approvalsettings.Level','Final Approval","5th Approval","4th Approval","3rd Approval","2nd Approval","1st Approval")
		->orderByRaw("FIELD(approvalsettings.Level , 'Final Approval', '5th Approval', '4th Approval','3rd Approval','2nd Approval','1st Approval') ASC")
		->first();

		$total = DB::table('claims')
		->leftJoin('claimsheets', 'claimsheets.Id', '=','claims.ClaimsheetId')
		->select(DB::Raw('sum(claims.Total_Expenses) As TotalExpenses,sum(claims.Petrol_SmartPay) As TotalSmartpay,sum(claims.Summon) as TotalSummon,sum(claims.Advance) As TotalAdvance, sum(claims.GST_Amount) As TotalGSTAmount,sum(claims.Total_Without_GST) As TotalnoGST,sum(claims.Total_Amount) As TotalPayable'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		->where('claims.ClaimSheetId', '=', $id)
		->where('claimstatuses.Status', 'like','%Approved%')
		->get();

		$html = view('exportclaim', ['me' => $me, 'claim' =>$claim,'receipts' =>$receipts, 'Id' =>$id, 'user' =>$user,'claimdetail' => $claimdetail,'timesheetdetail' => $timesheetdetail, 'options' =>$options,'mylevel' => $mylevel,'total' => $total,'TotalAllowance'=>($allowance->TotalAllowance + $allowance->TotalMoney)]);
		(new ExportPDFController)->ExportLandscape($html);

	}

	public function summary2($month=null, $year=null)
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

			if($d>=16)
			{

				$start=date('d-M-Y', strtotime('first day of last month'));
				// $start=date('d-M-Y', strtotime($start,' +16 days'));
				$start = date('d-M-Y', strtotime($start . " +15 days"));

			}
			else {

				$start=date('d-M-Y', strtotime('first day of this month'));
				// $start=date('d-M-Y', strtotime($start,' +16 days'));
				$start = date('d-M-Y', strtotime($start . " +15 days"));

			}

			if($d>=16)
			{

				$end=date('d-M-Y', strtotime('first day of next month'));
				$end = date('d-M-Y', strtotime($end . " +14 days"));
				// $end=date('d-M-Y', strtotime($end,' +15 days'));
			}
			else {

				$end=date('d-M-Y', strtotime('first day of this month'));
				$end = date('d-M-Y', strtotime($end . " +14 days"));
				// $end=date('d-M-Y', strtotime($end,' +15 days'));

			}

		}
		else {
			$start = strtotime('01 '.$month.' '.$year);
			$start = date('d F Y', $start);
			$start = date('d F Y', strtotime('-1 month',strtotime($start)));
			$start = date('d-M-Y', strtotime($start . " +15 days"));

			$end = strtotime('01 '.$month.' '.$year);
			$end = date('d F Y', $end);
			$end = date('d-M-Y', strtotime($end . " +14 days"));

		}

		//convert long month name to short month name
		$orimonth=$month;
		$month=substr($month,0,3);

		$startTime = strtotime($start);
		$endTime = strtotime($end);
		$query="";
		$startTime=strtotime("+1 days",$startTime);
		 while ($startTime <= $endTime){
			 //$expensetype=
		 	$query.="SELECT '" . date('d-M-Y', $startTime) . "' UNION ALL ";
			$startTime=strtotime("+1 days",$startTime);
		 }
		$query=substr($query,0,strlen($query)-10);

		$summary = DB::select("
				SELECT claims.Expenses_Type, SUM(claims.Total_Expenses-claims.Petrol_SmartPay) As 'Total_Expenses_Without_SmartPay',SUM(claims.GST_Amount) As 'Total_GST'
				FROM claims
				LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
				LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
				LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
				LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
				WHERE Expenses_Type NOT IN ('Advance','Petrol SmartPay') AND claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
				GROUP BY claims.Expenses_Type UNION all

				SELECT 'Staff_Allowance',SUM(Allowance),'0.00'
				FROM timesheets
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
				LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
				WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."' UNION ALL

				SELECT 'Monetary_Comp',Sum(Monetary_Comp),'0.00'
				FROM timesheets
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
				LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
				WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."'
				ORDER BY Total_Expenses_Without_SmartPay DESC
		");

		$total = DB::select("
				SELECT 'Total Without SmartPay',SUM(tot.Total_Expenses_Without_SmartPay),SUM(tot.Total_GST) FROM
					(SELECT 'Total', SUM(claims.Total_Expenses-claims.Petrol_SmartPay) As 'Total_Expenses_Without_SmartPay',SUM(claims.GST_Amount) As 'Total_GST'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'

					UNION all
					SELECT 'Staff_Allowance',SUM(Allowance+Monetary_Comp),'0.00'
					FROM timesheets
					LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
					LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
					LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
					WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."') AS tot UNION ALL

					SELECT 'Advance',SUM(Advance),'0.00'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					GROUP BY 1 UNION all

					SELECT 'Summon',SUM(Summon),'0.00'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					GROUP BY 1 UNION all

					SELECT 'Petrol_SmartPay',SUM(Petrol_SmartPay),'0.00'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					GROUP BY 1

		");

		$summary2 = DB::select("
				SELECT claims.Expenses_Type, SUM(claims.Total_Expenses-claims.Petrol_SmartPay) As 'Total_Expenses_Without_SmartPay',SUM(claims.GST_Amount) As 'Total_GST'
				FROM claims
				LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
				LEFT JOIN users on users.Id=claimsheets.UserId
				LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
				LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
				LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
				WHERE Expenses_Type NOT IN ('Advance','Petrol SmartPay') AND claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
				GROUP BY claims.Expenses_Type UNION all

				SELECT 'Staff_Allowance',SUM(Allowance),'0.00'
				FROM timesheets
				LEFT JOIN users on users.Id=timesheets.UserId
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
				LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
				WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."'

				SELECT 'Monetary_Comp',Sum(Monetary_Comp),'0.00'
				FROM timesheets
				LEFT JOIN users on users.Id=timesheets.UserId
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
				LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
				WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."'
				ORDER BY Total_Expenses_Without_SmartPay DESC
		");

		$total2 = DB::select("
				SELECT 'Total Without SmartPay',SUM(tot.Total_Expenses_Without_SmartPay),SUM(tot.Total_GST) FROM
					(SELECT 'Total', SUM(claims.Total_Expenses-claims.Petrol_SmartPay) As 'Total_Expenses_Without_SmartPay',SUM(claims.GST_Amount) As 'Total_GST'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN users on users.Id=claimsheets.UserId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'

					UNION all
					SELECT 'Staff_Allowance',SUM(Allowance+Monetary_Comp),'0.00'
					FROM timesheets
					LEFT JOIN users on users.Id=timesheets.UserId
					LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
					LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
					LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
					WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."' AS tot UNION ALL

					SELECT 'Advance',SUM(Advance),'0.00'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN users on users.Id=claimsheets.UserId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					GROUP BY 1 UNION all

					SELECT 'Summon',SUM(Summon),'0.00'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN users on users.Id=claimsheets.UserId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					GROUP BY 1 UNION all

					SELECT 'Petrol_SmartPay',SUM(Petrol_SmartPay),'0.00'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN users on users.Id=claimsheets.UserId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					GROUP BY 1

		");

		$summary3 = DB::select("
				SELECT claims.Expenses_Type, SUM(claims.Total_Expenses-claims.Petrol_SmartPay) As 'Total_Expenses_Without_SmartPay',SUM(claims.GST_Amount) As 'Total_GST'
				FROM claims
				LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
				LEFT JOIN users on users.Id=claimsheets.UserId
				LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
				LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
				LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
				WHERE Expenses_Type NOT IN ('Advance','Petrol SmartPay') AND claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
				GROUP BY claims.Expenses_Type UNION all

				SELECT 'Staff_Allowance',SUM(Allowance),'0.00'
				FROM timesheets
				LEFT JOIN users on users.Id=timesheets.UserId
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
				LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
				WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."' UNION ALL

				SELECT 'Monetary_Comp',Sum(Monetary_Comp),'0.00'
				FROM timesheets
				LEFT JOIN users on users.Id=timesheets.UserId
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
				LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
				WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."'
				ORDER BY Total_Expenses_Without_SmartPay DESC
		");


		$total3 = DB::select("
				SELECT 'Total Without SmartPay',SUM(tot.Total_Expenses_Without_SmartPay),SUM(tot.Total_GST) FROM
					(SELECT 'Total', SUM(claims.Total_Expenses-claims.Petrol_SmartPay) As 'Total_Expenses_Without_SmartPay',SUM(claims.GST_Amount) As 'Total_GST'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN users on users.Id=claimsheets.UserId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'

					UNION all
					SELECT 'Staff_Allowance',SUM(Allowance+Monetary_Comp),'0.00'
					FROM timesheets
					LEFT JOIN users on users.Id=timesheets.UserId
					LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
					LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
					LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
					WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."' AS tot UNION ALL

					SELECT 'Advance',SUM(Advance),'0.00'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN users on users.Id=claimsheets.UserId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					GROUP BY 1 UNION all

					SELECT 'Summon',SUM(Summon),'0.00'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN users on users.Id=claimsheets.UserId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					GROUP BY 1 UNION all

					SELECT 'Petrol_SmartPay',SUM(Petrol_SmartPay),'0.00'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN users on users.Id=claimsheets.UserId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					GROUP BY 1

		");

		if ($summary==null){
			$data = "";
			$title = "";
		}
		else {
			$data = "";
			$title = "";
			foreach($summary as $key => $quote){
				$ret[]=$quote->Total_Expenses_Without_SmartPay;
				$data .= $quote->Total_Expenses_Without_SmartPay.",";
			}
		}
		foreach($summary as $key => $quote){
			$title .= $quote->Expenses_Type.",";
		}
		$data=substr($data,0,strlen($data)-1);
		$title=substr($title,0,strlen($title)-1);
		$options= DB::table('options')
		->select('Option')
		->where('Table', 'claims')
		->where('Field','=','Expenses_Type')
		->whereNotIn('Option',['Advance','Petrol SmartPay'])
		->orderBy('Option','asc')
		->get();

		$data1="";
		foreach($options as $key => $quote){
			$ret[]=$quote->Option;
			// $data1 .= "FORMAT(SUM(case when claims.Expenses_Type = '".$quote->Option."' then (claims.Total_Expenses-claims.Petrol_SmartPay) else 0 end),2) As '".$quote->Option."',";

			$data1 .="(SELECT SUM(claims.Total_Expenses-claims.Petrol_SmartPay)
			FROM claims
			LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
			LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
			WHERE claims.Expenses_Type = '".$quote->Option."' AND claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."' and claimsheets.UserId=users.Id
			) As '".$quote->Option."',";

			// FROM claimsheets
			// LEFT JOIN claims on claims.ClaimsheetId=claimsheets.Id
			// LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			// LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			// WHERE claims.Expenses_Type = '".$quote->Option."' AND claimstatuses.Status like '%Final Approved%' and str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') and claimsheets.UserId=users.Id
			// ) As '".$quote->Option."',";
		}

		$data1=substr($data1,0,strlen($data1)-1);

		$byperson = DB::select("
			SELECT users.Id,users.StaffId,users.Name,".$data1.",

			(SELECT SUM(Petrol_SmartPay)
			FROM claims
			LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
			LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
			WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."' and claimsheets.UserId=users.Id) as 'Total_Petrol_SmartPay',

			(SELECT SUM(Total_Expenses)
			FROM claims
			LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
			LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
			WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."' and claimsheets.UserId=users.Id) as 'Total_Claim_With_SmartPay',

			(SELECT SUM(Total_Expenses-Petrol_SmartPay)
			FROM claims
			LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
			LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
			WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."' and claimsheets.UserId=users.Id) as 'Total_Claim_Without_SmartPay',

			(SELECT SUM(Allowance)
			FROM timesheets
			LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
			LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
			WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."' and timesheets.UserId=users.Id) as 'Staff_Allowance',

			(SELECT SUM(Monetary_Comp)
			FROM timesheets
			LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
			LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
			WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."' and timesheets.UserId=users.Id) as 'Staff_Monetary_Comp',

			'' As Total_Claim_With_Allowance_Monetary,

			(SELECT SUM(Advance)
			FROM claims
			LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
			LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
			WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."' and claimsheets.UserId=users.Id) as 'Total_Advance',

			(SELECT SUM(Summon)
			FROM claims
			LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
			LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
			WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."' and claimsheets.UserId=users.Id) as 'Total_Summon',

			'' as Total_Payable
			FROM users
			WHERE users.Active=1
			GROUP BY users.Id
			ORDER BY users.Name ASC
		");

		$byperson2 = DB::select("
		SELECT users.Id,users.StaffId,users.Name,

		(SELECT SUM(Total_Expenses)
		FROM claims
		LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
		LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
		LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
		LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
		LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
		WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."' and claimsheets.UserId=users.Id) as 'Total_Claim_With_SmartPay',

		(SELECT SUM(Total_Expenses-Petrol_SmartPay)
		FROM claims
		LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
		LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
		LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
		LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
		LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
		WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."' and claimsheets.UserId=users.Id) as 'Total_Claim_Without_SmartPay',

		(SELECT SUM(Allowance)
		FROM timesheets
		LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
		LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
		WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."' and timesheets.UserId=users.Id) as 'Staff_Allowance',

		(SELECT SUM(Monetary_Comp)
		FROM timesheets
		LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
		LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
		WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."' and timesheets.UserId=users.Id) as 'Staff_Monetary_Comp',

		'' As Total_Claim_With_Allowance_Monetary,

		(SELECT SUM(Advance)
		FROM claims
		LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
		LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
		LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
		LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
		LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
		WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."' and claimsheets.UserId=users.Id) as 'Total_Advance',

		(SELECT SUM(Summon)
		FROM claims
		LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
		LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
		LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
		LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
		LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
		WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."' and claimsheets.UserId=users.Id) as 'Total_Summon',

		'' as Total_Payable
		FROM users
		WHERE users.Active=1
		GROUP BY users.Id
		ORDER BY users.Name ASC
		");


		$bytype = DB::select("
		SELECT claims.Id,claims.Expenses_Type,

		(SELECT SUM(Petrol_SmartPay)
		FROM claims
		LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
		LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
		LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
		LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
		LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
		WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."') as 'Total_Petrol_SmartPay',

		(SELECT SUM(Total_Expenses)
		FROM claims
		LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
		LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
		LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
		LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
		LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
		WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."') as 'Total_Claim_With_SmartPay',

		(SELECT SUM(Total_Expenses-Petrol_SmartPay)
		FROM claims
		LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
		LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
		LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
		LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
		LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
		WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."') as 'Total_Claim_Without_SmartPay',

		(SELECT SUM(Allowance)
		FROM timesheets
		LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
		LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
		WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."') as 'Staff_Allowance',

		(SELECT SUM(Monetary_Comp)
		FROM timesheets
		LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
		LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
		WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."') as 'Staff_Monetary_Comp',

		'' As Total_Claim_With_Allowance_Monetary,

		(SELECT SUM(Advance)
		FROM claims
		LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
		LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
		LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
		LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
		LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
		WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."') as 'Total_Advance',

		(SELECT SUM(Summon)
		FROM claims
		LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
		LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
		LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
		LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
		LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
		WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."') as 'Total_Summon',

		'' as Total_Payable
		FROM claims
		GROUP BY claims.Expenses_Type
		ORDER BY claims.Id ASC
		");

		//revert month back to long month name
		$month=$orimonth;

		return view("claimsummary2", ['me' => $me, 'month' => $month , 'year' =>$year,'start' => $start,'end' =>$end, 'summary' => $summary,'total' => $total,'summary2' => $summary2,'total2' => $total2,'summary3' => $summary3,'total3' => $total3, 'data' => $data, 'title' => $title, 'byperson' => $byperson, 'byperson2' => $byperson2, 'bytype' => $bytype, 'options'=>$options]);
	}

	public function summary3($month=null, $year=null)
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

			if($d>=16)
			{

				$start=date('d-M-Y', strtotime('first day of last month'));
				// $start=date('d-M-Y', strtotime($start,' +16 days'));
				$start = date('d-M-Y', strtotime($start . " +15 days"));

			}
			else {

				$start=date('d-M-Y', strtotime('first day of this month'));
				// $start=date('d-M-Y', strtotime($start,' +16 days'));
				$start = date('d-M-Y', strtotime($start . " +15 days"));

			}

			if($d>=16)
			{

				$end=date('d-M-Y', strtotime('first day of next month'));
				$end = date('d-M-Y', strtotime($end . " +14 days"));
				// $end=date('d-M-Y', strtotime($end,' +15 days'));
			}
			else {

				$end=date('d-M-Y', strtotime('first day of this month'));
				$end = date('d-M-Y', strtotime($end . " +14 days"));
				// $end=date('d-M-Y', strtotime($end,' +15 days'));

			}

		}
		else {
			$start = strtotime('01 '.$month.' '.$year);
			$start = date('d F Y', $start);
			$start = date('d F Y', strtotime('-1 month',strtotime($start)));
			$start = date('d-M-Y', strtotime($start . " +15 days"));

			$end = strtotime('01 '.$month.' '.$year);
			$end = date('d F Y', $end);
			$end = date('d-M-Y', strtotime($end . " +14 days"));

		}

		//convert long month name to short month name
		$orimonth=$month;
		$month=substr($month,0,3);

		$startTime = strtotime($start);
		$endTime = strtotime($end);
		$query="";
		$startTime=strtotime("+1 days",$startTime);
		 while ($startTime <= $endTime){
			 //$expensetype=
		 	$query.="SELECT '" . date('d-M-Y', $startTime) . "' UNION ALL ";
			$startTime=strtotime("+1 days",$startTime);
		 }
		$query=substr($query,0,strlen($query)-10);

		$summary = DB::select("
				SELECT claims.Expenses_Type, SUM(IF(claims.Rate>0,(claims.Total_Expenses * claims.Rate)-claims.Petrol_SmartPay,claims.Total_Expenses-claims.Petrol_SmartPay)) As 'Total_Expenses_Without_SmartPay',SUM(claims.GST_Amount) As 'Total_GST'
				FROM claims
				LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
				LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
				LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
				LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
				WHERE Expenses_Type NOT IN ('Advance','Petrol SmartPay') AND claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
				GROUP BY claims.Expenses_Type UNION all

				SELECT 'Staff_Allowance',SUM(Allowance),'0.00'
				FROM timesheets
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
				LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
				WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."' UNION ALL

				SELECT 'Monetary_Comp',Sum(Monetary_Comp),'0.00'
				FROM timesheets
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
				LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
				WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."'
				ORDER BY Total_Expenses_Without_SmartPay DESC
		");

		$total = DB::select("
				SELECT 'Total Without SmartPay',SUM(tot.Total_Expenses_Without_SmartPay),SUM(tot.Total_GST) FROM
					(SELECT 'Total', SUM(IF(claims.Rate>0,(claims.Total_Expenses-claims.Petrol_SmartPay)*claims.rate,claims.Total_Expenses-claims.Petrol_SmartPay)) As 'Total_Expenses_Without_SmartPay',SUM(IF(claims.Rate>0,claims.GST_Amount*claims.Rate,claims.GST_Amount)) As 'Total_GST'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'

					UNION all
					SELECT 'Staff_Allowance',SUM(Allowance+Monetary_Comp),'0.00'
					FROM timesheets
					LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
					LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
					LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
					WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."') AS tot UNION ALL

					SELECT 'Advance',SUM(Advance),'0.00'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					GROUP BY 1 UNION all

					SELECT 'Summon',SUM(Summon),'0.00'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					GROUP BY 1 UNION all

					SELECT 'Petrol_SmartPay',SUM(Petrol_SmartPay),'0.00'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					GROUP BY 1

		");

		$summary2 = DB::select("
				SELECT claims.Expenses_Type, SUM(IF(claims.Rate>0,(claims.Total_Expenses * claims.Rate)-claims.Petrol_SmartPay,claims.Total_Expenses-claims.Petrol_SmartPay)) As 'Total_Expenses_Without_SmartPay',SUM(claims.GST_Amount) As 'Total_GST'
				FROM claims
				LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
				LEFT JOIN users on users.Id=claimsheets.UserId
				LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
				LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
				LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
				WHERE Expenses_Type NOT IN ('Advance','Petrol SmartPay') AND claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
				GROUP BY claims.Expenses_Type UNION all

				SELECT 'Staff_Allowance',SUM(Allowance),'0.00'
				FROM timesheets
				LEFT JOIN users on users.Id=timesheets.UserId
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
				LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
				WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."' UNION ALL

				SELECT 'Monetary_Comp',Sum(Monetary_Comp),'0.00'
				FROM timesheets
				LEFT JOIN users on users.Id=timesheets.UserId
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
				LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
				WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."'
				ORDER BY Total_Expenses_Without_SmartPay DESC
		");

		$total2 = DB::select("
				SELECT 'Total Without SmartPay',SUM(tot.Total_Expenses_Without_SmartPay),SUM(tot.Total_GST) FROM
					(SELECT 'Total', SUM(IF(claims.Rate>0,(claims.Total_Expenses-claims.Petrol_SmartPay)*claims.rate,claims.Total_Expenses-claims.Petrol_SmartPay)) As 'Total_Expenses_Without_SmartPay',SUM(IF(claims.Rate>0,claims.GST_Amount*claims.Rate,claims.GST_Amount)) As 'Total_GST'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN users on users.Id=claimsheets.UserId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'

					UNION all
					SELECT 'Staff_Allowance',SUM(Allowance+Monetary_Comp),'0.00'
					FROM timesheets
					LEFT JOIN users on users.Id=timesheets.UserId
					LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
					LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
					LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
					WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."' AS tot UNION ALL

					SELECT 'Advance',SUM(Advance),'0.00'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN users on users.Id=claimsheets.UserId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					GROUP BY 1 UNION all

					SELECT 'Summon',SUM(Summon),'0.00'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN users on users.Id=claimsheets.UserId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					GROUP BY 1 UNION all

					SELECT 'Petrol_SmartPay',SUM(Petrol_SmartPay),'0.00'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN users on users.Id=claimsheets.UserId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					GROUP BY 1

		");

		$summary3 = DB::select("
				SELECT claims.Expenses_Type, SUM(IF(claims.Rate>0,(claims.Total_Expenses * claims.Rate)-claims.Petrol_SmartPay,claims.Total_Expenses-claims.Petrol_SmartPay)) As 'Total_Expenses_Without_SmartPay',SUM(claims.GST_Amount) As 'Total_GST'
				FROM claims
				LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
				LEFT JOIN users on users.Id=claimsheets.UserId
				LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
				LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
				LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
				WHERE Expenses_Type NOT IN ('Advance','Petrol SmartPay') AND claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
				GROUP BY claims.Expenses_Type UNION all

				SELECT 'Staff_Allowance',SUM(Allowance),'0.00'
				FROM timesheets
				LEFT JOIN users on users.Id=timesheets.UserId
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
				LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
				WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."' UNION ALL

				SELECT 'Monetary_Comp',Sum(Monetary_Comp),'0.00'
				FROM timesheets
				LEFT JOIN users on users.Id=timesheets.UserId
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
				LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
				WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."'
				ORDER BY Total_Expenses_Without_SmartPay DESC
		");


		$total3 = DB::select("
		SELECT 'Total Without SmartPay',SUM(tot.Total_Expenses_Without_SmartPay),SUM(tot.Total_GST) FROM
			(SELECT 'Total', SUM(IF(claims.Rate>0,(claims.Total_Expenses-claims.Petrol_SmartPay)*claims.rate,claims.Total_Expenses-claims.Petrol_SmartPay)) As 'Total_Expenses_Without_SmartPay',SUM(IF(claims.Rate>0,claims.GST_Amount*claims.Rate,claims.GST_Amount)) As 'Total_GST'
			FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN users on users.Id=claimsheets.UserId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'

					UNION all
					SELECT 'Staff_Allowance',SUM(Allowance+Monetary_Comp),'0.00'
					FROM timesheets
					LEFT JOIN users on users.Id=timesheets.UserId
					LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
					LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
					LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
					WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."' AS tot UNION ALL

					SELECT 'Advance',SUM(Advance),'0.00'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN users on users.Id=claimsheets.UserId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					GROUP BY 1 UNION all

					SELECT 'Summon',SUM(Summon),'0.00'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN users on users.Id=claimsheets.UserId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					GROUP BY 1 UNION all

					SELECT 'Petrol_SmartPay',SUM(Petrol_SmartPay),'0.00'
					FROM claims
					LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
					LEFT JOIN users on users.Id=claimsheets.UserId
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					GROUP BY 1

		");

		if ($summary==null){
			$data = "";
			$title = "";
		}
		else {
			$data = "";
			$title = "";
			foreach($summary as $key => $quote){
				$ret[]=$quote->Total_Expenses_Without_SmartPay;
				$data .= $quote->Total_Expenses_Without_SmartPay.",";
			}
		}
		foreach($summary as $key => $quote){
			$title .= $quote->Expenses_Type.",";
		}
		$data=substr($data,0,strlen($data)-1);
		$title=substr($title,0,strlen($title)-1);
		$options= DB::table('options')
		->select('Option')
		->where('Table', 'claims')
		->where('Field','=','Expenses_Type')
		->whereNotIn('Option',['Advance','Petrol SmartPay'])
		->orderBy('Option','asc')
		->get();

		$data1="";
		foreach($options as $key => $quote){
			$ret[]=$quote->Option;
			// $data1 .= "FORMAT(SUM(case when claims.Expenses_Type = '".$quote->Option."' then (claims.Total_Expenses-claims.Petrol_SmartPay) else 0 end),2) As '".$quote->Option."',";

			$data1 .="(SELECT SUM(IF(claims.Rate>0,(claims.Total_Expenses-claims.Petrol_SmartPay)*claims.Rate,claims.Total_Expenses-claims.Petrol_SmartPay))
			FROM claims
			LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
			LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
			WHERE claims.Expenses_Type = '".$quote->Option."' AND claimstatuses.Status like '%Final Approved%' and claimsheetstatuses.Claim_Status='". $month.' '.$year ."' and claimsheets.UserId=users.Id
			) As '".$quote->Option."',";

		}

		$data1=substr($data1,0,strlen($data1)-1);

		$byperson = DB::select("
			SELECT users.Id,users.StaffId,users.Name,".$data1.",

			(SELECT SUM(Petrol_SmartPay)
			FROM claims
			LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
			LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
			WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."' and claimsheets.UserId=users.Id) as 'Total_Petrol_SmartPay',

			(SELECT SUM(IF(claims.Rate>0,Total_Expenses*Rate,Total_Expenses))
			FROM claims
			LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
			LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
			WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."' and claimsheets.UserId=users.Id) as 'Total_Claim_With_SmartPay',

			(SELECT SUM(IF(claims.Rate>0,(Total_Expenses-Petrol_SmartPay)*claims.Rate,Total_Expenses-Petrol_SmartPay))
			FROM claims
			LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
			LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
			WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."' and claimsheets.UserId=users.Id) as 'Total_Claim_Without_SmartPay',

			(SELECT SUM(Allowance)
			FROM timesheets
			LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
			LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
			WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."' and timesheets.UserId=users.Id) as 'Staff_Allowance',

			(SELECT SUM(Monetary_Comp)
			FROM timesheets
			LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
			LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
			WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."' and timesheets.UserId=users.Id) as 'Staff_Monetary_Comp',

			'' As Total_Claim_With_Allowance_Monetary,

			(SELECT SUM(Advance)
			FROM claims
			LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
			LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
			WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."' and claimsheets.UserId=users.Id) as 'Total_Advance',

			(SELECT SUM(Summon)
			FROM claims
			LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
			LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
			WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."' and claimsheets.UserId=users.Id) as 'Total_Summon',

			'' as Total_Payable
			FROM users
			WHERE users.Active=1
			GROUP BY users.Id
			ORDER BY users.Name ASC
		");

		$byperson2 = DB::select("
		SELECT users.Id,users.StaffId,users.Name,

		(SELECT SUM(IF(claims.Rate>0,Total_Expenses*claims.Rate,Total_Expenses))
		FROM claims
		LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
		LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
		LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
		LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
		LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
		WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."' and claimsheets.UserId=users.Id) as 'Total_Claim_With_SmartPay',

		(SELECT SUM(IF(claims.Rate>0,(Total_Expenses-Petrol_SmartPay)*claims.Rate,Total_Expenses-Petrol_SmartPay))
		FROM claims
		LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
		LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
		LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
		LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
		LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
		WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."' and claimsheets.UserId=users.Id) as 'Total_Claim_Without_SmartPay',

		(SELECT SUM(Allowance)
		FROM timesheets
		LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
		LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
		WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."' and timesheets.UserId=users.Id) as 'Staff_Allowance',

		(SELECT SUM(Monetary_Comp)
		FROM timesheets
		LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
		LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
		WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."' and timesheets.UserId=users.Id) as 'Staff_Monetary_Comp',

		'' As Total_Claim_With_Allowance_Monetary,

		(SELECT SUM(Advance)
		FROM claims
		LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
		LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
		LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
		LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
		LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
		WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."' and claimsheets.UserId=users.Id) as 'Total_Advance',

		(SELECT SUM(Summon)
		FROM claims
		LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
		LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
		LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
		LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
		LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
		WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."' and claimsheets.UserId=users.Id) as 'Total_Summon',

		'' as Total_Payable
		FROM users
		WHERE users.Active=1
		GROUP BY users.Id
		ORDER BY users.Name ASC
		");


		$bytype = DB::select("
		SELECT claims.Id,claims.Expenses_Type,

		(SELECT SUM(Petrol_SmartPay)
		FROM claims
		LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
		LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
		LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
		LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
		LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
		WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."') as 'Total_Petrol_SmartPay',

		(SELECT SUM(IF(claims.Rate>0,Total_Expenses*claims.Rate,Total_Expenses))
		FROM claims
		LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
		LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
		LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
		LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
		LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
		WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."') as 'Total_Claim_With_SmartPay',

		(SELECT SUM(IF(claims.Rate>0,(Total_Expenses-Petrol_SmartPay)*claims.Rate,Total_Expenses-Petrol_SmartPay))
		FROM claims
		LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
		LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
		LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
		LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
		LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
		WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."') as 'Total_Claim_Without_SmartPay',

		(SELECT SUM(Allowance)
		FROM timesheets
		LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
		LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
		WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."') as 'Staff_Allowance',

		(SELECT SUM(Monetary_Comp)
		FROM timesheets
		LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
		LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
		WHERE timesheetchecked.Payment_Status='". $month.' '.$year ."') as 'Staff_Monetary_Comp',

		'' As Total_Claim_With_Allowance_Monetary,

		(SELECT SUM(Advance)
		FROM claims
		LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
		LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
		LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
		LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
		LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
		WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."') as 'Total_Advance',

		(SELECT SUM(Summon)
		FROM claims
		LEFT JOIN claimsheets on claimsheets.Id=claims.ClaimsheetId
		LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
		LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
		LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
		LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
		WHERE claimstatuses.Status like '%Final Approved%' AND claimsheetstatuses.Claim_Status='". $month.' '.$year ."') as 'Total_Summon',

		'' as Total_Payable
		FROM claims
		GROUP BY claims.Expenses_Type
		ORDER BY claims.Id ASC
		");

		//revert month back to long month name
		$month=$orimonth;

		return view("claimsummary2", ['me' => $me, 'month' => $month , 'year' =>$year,'start' => $start,'end' =>$end, 'summary' => $summary,'total' => $total,'summary2' => $summary2,'total2' => $total2,'summary3' => $summary3,'total3' => $total3, 'data' => $data, 'title' => $title, 'byperson' => $byperson, 'byperson2' => $byperson2, 'bytype' => $bytype, 'options'=>$options]);
	}

	public function summary($start=null, $end=null)
	{
		$me = (new CommonController)->get_current_user();
		if ($start==null)
		{
			$start=date('d-M-Y', strtotime('first day of last month'));
			// $start=date('d-M-Y', strtotime($start,' +16 days'));
			$start = date('d-M-Y', strtotime($start . " +15 days"));
		}
		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('first day of this month'));
			$end = date('d-M-Y', strtotime($end . " +14 days"));
			// $end=date('d-M-Y', strtotime($end,' +15 days'));
		}
		$startTime = strtotime($start);
		$endTime = strtotime($end);
		$query="";
		$startTime=strtotime("+1 days",$startTime);
		 while ($startTime <= $endTime){
			 //$expensetype=
		 	$query.="SELECT '" . date('d-M-Y', $startTime) . "' UNION ALL ";
			$startTime=strtotime("+1 days",$startTime);
		 }
		$query=substr($query,0,strlen($query)-10);
		$summary = DB::select("
				SELECT claims.Expenses_Type, SUM(claims.Total_Expenses-claims.Petrol_SmartPay) As 'Total_Expenses_Without_SmartPay',SUM(claims.GST_Amount) As 'Total_GST'
				FROM claims
				LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
				LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
				WHERE Expenses_Type NOT IN ('Advance','Petrol SmartPay') AND claimstatuses.Status like '%Final Approved%' and str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y')
				GROUP BY claims.Expenses_Type UNION all

				SELECT 'Staff_Allowance',SUM(Allowance),'0.00'
				FROM timesheets
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				WHERE timesheetstatuses.Status like '%Final Approved%' and str_to_date(timesheets.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') UNION ALL

				SELECT 'Monetary_Comp',Sum(Monetary_Comp),'0.00'
				FROM timesheets
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				WHERE timesheetstatuses.Status like '%Final Approved%' and str_to_date(timesheets.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y')
				ORDER BY Total_Expenses_Without_SmartPay DESC
		");
		$total = DB::select("
				SELECT 'Total Without SmartPay',SUM(tot.Total_Expenses_Without_SmartPay),SUM(tot.Total_GST) FROM
					(SELECT 'Total', SUM(claims.Total_Expenses-claims.Petrol_SmartPay) As 'Total_Expenses_Without_SmartPay',SUM(claims.GST_Amount) As 'Total_GST'
					FROM claims
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					WHERE claimstatuses.Status like '%Final Approved%' and str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y')
				 	UNION all
					SELECT 'Staff_Allowance',SUM(Allowance+Monetary_Comp),'0.00'
					FROM timesheets
					LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
					LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
					WHERE timesheetstatuses.Status like '%Final Approved%' and str_to_date(timesheets.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y')) AS tot UNION ALL

					SELECT 'Advance',SUM(Advance),'0.00'
					FROM claims
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					WHERE claimstatuses.Status like '%Final Approved%' and str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y')
					GROUP BY 1 UNION all

					SELECT 'Summon',SUM(Summon),'0.00'
					FROM claims
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					WHERE claimstatuses.Status like '%Final Approved%' and str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y')
					GROUP BY 1 UNION all

					SELECT 'Petrol_SmartPay',SUM(Petrol_SmartPay),'0.00'
					FROM claims
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					WHERE claimstatuses.Status like '%Final Approved%' and str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y')
					GROUP BY 1

		");
		if ($summary==null){
			$data = "";
			$title = "";
		}
		else {
			$data = "";
			$title = "";
			foreach($summary as $key => $quote){
				$ret[]=$quote->Total_Expenses_Without_SmartPay;
				$data .= $quote->Total_Expenses_Without_SmartPay.",";
			}
		}
		foreach($summary as $key => $quote){
			$title .= $quote->Expenses_Type.",";
		}
		$data=substr($data,0,strlen($data)-1);
		$title=substr($title,0,strlen($title)-1);
		$options= DB::table('options')
		->select('Option')
		->where('Table', 'claims')
		->where('Field','=','Expenses_Type')
		->whereNotIn('Option',['Advance','Petrol SmartPay'])
		->orderBy('Option','asc')
		->get();

		$data1="";
		foreach($options as $key => $quote){
			$ret[]=$quote->Option;
			// $data1 .= "FORMAT(SUM(case when claims.Expenses_Type = '".$quote->Option."' then (claims.Total_Expenses-claims.Petrol_SmartPay) else 0 end),2) As '".$quote->Option."',";

			$data1 .="(SELECT SUM(claims.Total_Expenses-claims.Petrol_SmartPay)
			FROM claimsheets
			LEFT JOIN claims on claims.ClaimsheetId=claimsheets.Id
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			WHERE claims.Expenses_Type = '".$quote->Option."' AND claimstatuses.Status like '%Final Approved%' and str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') and claimsheets.UserId=users.Id
			) As '".$quote->Option."',";
		}

		$data1=substr($data1,0,strlen($data1)-1);

		$byperson = DB::select("
			SELECT users.Id,users.StaffId,users.Name,".$data1.",

			(SELECT SUM(Petrol_SmartPay)
			FROM claimsheets
			LEFT JOIN claims on claims.ClaimsheetId=claimsheets.Id
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			WHERE claimstatuses.Status like '%Final Approved%' and str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') and claimsheets.UserId=users.Id
			) As 'Total_Petrol_SmartPay',

			(SELECT SUM(Total_Expenses)
			FROM claimsheets
			LEFT JOIN claims on claims.ClaimsheetId=claimsheets.Id
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			WHERE claimstatuses.Status like '%Final Approved%' and str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') and claimsheets.UserId=users.Id
			) As 'Total_Claim_With_SmartPay',

			(SELECT SUM(claims.Total_Expenses-claims.Petrol_SmartPay)
			FROM claimsheets
			LEFT JOIN claims on claims.ClaimsheetId=claimsheets.Id
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			WHERE claimstatuses.Status like '%Final Approved%' and str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') and claimsheets.UserId=users.Id
			) As 'Total_Claim_Without_SmartPay',

			(SELECT SUM(Allowance)
			FROM timesheets
			LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
			LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
			WHERE timesheetstatuses.Status like '%Final Approved%' and str_to_date(timesheets.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') and timesheets.UserId=users.Id
			) As 'Staff_Allowance',

			(SELECT Sum(Monetary_Comp)
			FROM timesheets
			LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
			LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
			WHERE timesheetstatuses.Status like '%Final Approved%' and str_to_date(timesheets.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') and timesheets.UserId=users.Id
			) As 'Staff_Monetary_Comp',

			'' As Total_Claim_With_Allowance_Monetary,

			(SELECT SUM(claims.Advance)
			FROM claimsheets
			LEFT JOIN claims on claims.ClaimsheetId=claimsheets.Id
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			WHERE claimstatuses.Status like '%Final Approved%' and str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') and claimsheets.UserId=users.Id
			) As 'Total_Advance',

			(SELECT SUM(claims.Summon)
			FROM claimsheets
			LEFT JOIN claims on claims.ClaimsheetId=claimsheets.Id
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			WHERE claimstatuses.Status like '%Final Approved%' and str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') and claimsheets.UserId=users.Id
			) As 'Total_Summon',

			'' as Total_Payable
			FROM users
			GROUP BY users.Id
			ORDER BY users.Name ASC
		");

		$byperson2 = DB::select("
			SELECT users.Id,users.StaffId,users.Name,

			(SELECT SUM(Total_Expenses)
			FROM claimsheets
			LEFT JOIN claims on claims.ClaimsheetId=claimsheets.Id
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			WHERE claimstatuses.Status like '%Final Approved%' and str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') and claimsheets.UserId=users.Id
			) As 'Total_Claim_With_SmartPay',

			(SELECT SUM(claims.Total_Expenses-claims.Petrol_SmartPay)
			FROM claimsheets
			LEFT JOIN claims on claims.ClaimsheetId=claimsheets.Id
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			WHERE claimstatuses.Status like '%Final Approved%' and str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') and claimsheets.UserId=users.Id
			) As 'Total_Claim_Without_SmartPay',

			(SELECT SUM(Allowance)
			FROM timesheets
			LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
			LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
			WHERE timesheetstatuses.Status like '%Final Approved%' and str_to_date(timesheets.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') and timesheets.UserId=users.Id
			) As 'Staff_Allowance',

			(SELECT Sum(Monetary_Comp)
			FROM timesheets
			LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
			LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
			WHERE timesheetstatuses.Status like '%Final Approved%' and str_to_date(timesheets.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') and timesheets.UserId=users.Id
			) As 'Staff_Monetary_Comp',

			'' As Total_Claim_With_Allowance_Monetary,

			(SELECT SUM(claims.Advance)
			FROM claimsheets
			LEFT JOIN claims on claims.ClaimsheetId=claimsheets.Id
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			WHERE claimstatuses.Status like '%Final Approved%' and str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') and claimsheets.UserId=users.Id
			) As 'Total_Advance',

			(SELECT SUM(claims.Summon)
			FROM claimsheets
			LEFT JOIN claims on claims.ClaimsheetId=claimsheets.Id
			LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
			LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
			WHERE claimstatuses.Status like '%Final Approved%' and str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y') and claimsheets.UserId=users.Id
			) As 'Total_Summon',

			'' as Total_Payable
			FROM users
			GROUP BY users.Id
			ORDER BY users.Name ASC
		");
		//dd($byperson2);

		$bytype = DB::select("select claims.Id,claims.Expenses_Type,
		'' as Total_Petrol_SmartPay,
		'' as Total_Claim_With_SmartPay,
		'' as Total_Claim_Without_SmartPay,
		'' as Staff_Allowance,
		'' as Staff_Monetary_Comp,
		'' as Total_Advance,
		'' as Total_Summon,
		'' as Total_Payable
		from claims group by claims.Expenses_Type

		");


		return view("claimsummary", ['me' => $me, 'start' => $start,'end' =>$end, 'summary' => $summary,'total' => $total, 'data' => $data, 'title' => $title, 'byperson' => $byperson, 'byperson2' => $byperson2, 'bytype' => $bytype, 'options'=>$options]);
	}

	public function userclaimbreakdown($UserId,$start=null, $end=null)
	{
		$me = (new CommonController)->get_current_user();

		$user = DB::table('users')
		->select('users.Id','StaffId','Name','Password','User_Type','Company_Email','Personal_Email','Contact_No_1','Contact_No_2','Nationality','Permanent_Address','Current_Address','Home_Base','DOB','NRIC','Passport_No','Gender','Marital_Status','Position','Emergency_Contact_Person',
		'Emergency_Contact_No','Emergency_Contact_Relationship','Emergency_Contact_Address','files.Web_Path')
		// ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->where('users.Id', '=', $UserId)
		->first();

		if ($start==null)
		{

			$start=date('d-M-Y', strtotime('first day of last month'));
			// $start=date('d-M-Y', strtotime($start,' +16 days'));
			$start = date('d-M-Y', strtotime($start . " +15 days"));
		}

		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('first day of this month'));
			$end = date('d-M-Y', strtotime($end . " +14 days"));
			// $end=date('d-M-Y', strtotime($end,' +15 days'));

		}

		$startTime = strtotime($start);
		$endTime = strtotime($end);

		$chartdata = DB::select("
				SELECT claims.Expenses_Type, SUM(claims.Total_Expenses-claims.Petrol_SmartPay) As 'Total_Expenses_Without_SmartPay',SUM(claims.GST_Amount) As 'Total_GST'
				FROM claims
				LEFT JOIN claimsheets ON claims.ClaimsheetId=claimsheets.Id
				LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
				LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
				WHERE Expenses_Type NOT IN ('Advance','Petrol SmartPay') AND claimstatuses.Status like '%Final Approved%' and str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y')
				AND claimsheets.UserId=".$UserId."
				GROUP BY claims.Expenses_Type UNION all

				SELECT 'Staff_Allowance',SUM(Allowance),'0.00'
				FROM timesheets
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				WHERE timesheetstatuses.Status like '%Final Approved%' and str_to_date(timesheets.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y')
				AND timesheets.UserId=".$UserId." UNION ALL

				SELECT 'Monetary_Comp',Sum(Monetary_Comp),'0.00'
				FROM timesheets
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				WHERE timesheetstatuses.Status like '%Final Approved%' and str_to_date(timesheets.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y')
				AND timesheets.UserId=".$UserId."
		");
		$total = DB::select("
				SELECT 'Total',SUM(tot.Total_Expenses_Without_SmartPay),SUM(tot.Total_GST) FROM
					(SELECT 'Total', SUM(claims.Total_Expenses-claims.Petrol_SmartPay) As 'Total_Expenses_Without_SmartPay',SUM(claims.GST_Amount) As 'Total_GST'
					FROM claims
					LEFT JOIN claimsheets ON claims.ClaimsheetId=claimsheets.Id
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					WHERE claimstatuses.Status like '%Final Approved%' and str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y')
					AND claimsheets.UserId=".$UserId."
				 	UNION all
					SELECT 'Staff_Allowance',SUM(Allowance+Monetary_Comp),'0.00'
					FROM timesheets
					LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
					LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
					WHERE timesheetstatuses.Status like '%Final Approved%' and str_to_date(timesheets.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y')
					AND timesheets.UserId=".$UserId.") AS tot UNION ALL

					SELECT 'Petrol_SmartPay',SUM(Petrol_SmartPay),'0.00'
					FROM claims
					LEFT JOIN claimsheets ON claims.ClaimsheetId=claimsheets.Id
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					WHERE claimstatuses.Status like '%Final Approved%' and str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y')
					AND claimsheets.UserId=".$UserId."
					GROUP BY 1 UNION all

					SELECT 'Advance',SUM(Advance),'0.00'
					FROM claims
					LEFT JOIN claimsheets ON claims.ClaimsheetId=claimsheets.Id
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					WHERE claimstatuses.Status like '%Final Approved%' and str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y')
					AND claimsheets.UserId=".$UserId."
					GROUP BY 1 UNION all

					SELECT 'Summon',SUM(Summon),'0.00'
					FROM claims
					LEFT JOIN claimsheets ON claims.ClaimsheetId=claimsheets.Id
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					WHERE claimstatuses.Status like '%Final Approved%' and str_to_date(claims.Date,'%d-%M-%Y') Between str_to_date('".$start."','%d-%M-%Y') and str_to_date('".$end."','%d-%M-%Y')
					AND claimsheets.UserId=".$UserId."
					GROUP BY 1

		");

		if ($chartdata==null){
			$data = "";
			$title = "";
		}
		else {

			$data = "";
			$title = "";

			foreach($chartdata as $key => $quote){
				$ret[]=$quote->Total_Expenses_Without_SmartPay;

				$data .= $quote->Total_Expenses_Without_SmartPay.",";
			}

		}

		foreach($chartdata as $key => $quote){
			$title .= $quote->Expenses_Type.",";
		}

		$data=substr($data,0,strlen($data)-1);
		$title=substr($title,0,strlen($title)-1);

		// $total = DB::table('claims')
		// ->leftJoin('claimsheets', 'claimsheets.Id', '=', 'claims.ClaimSheetId')
		// ->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		// ->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		// ->leftJoin('users', 'claimsheets.UserId', '=', 'users.Id')
		// ->select(DB::raw('"Total_Claim"'), DB::raw('SUM(claims.Total_Amount) As Total_Expenses'),DB::raw('SUM(claims.GST_Amount) As Total_GST'))
		// ->where('claimstatuses.Status', 'like','%Final Approved%')
		// ->where( 'claimsheets.UserId', '=', $UserId)
		// ->where(DB::raw('str_to_date(claims.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		// ->where(DB::raw('str_to_date(claims.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		// ->groupBy('claimsheets.UserId')
		// ->get();

		return view("userclaimsummary", ['me' => $me,'user' =>$user, 'start' => $start,'end' =>$end, 'chartdata' => $chartdata,'total' => $total, 'data' => $data, 'title' => $title]);

	}

	public function userclaimbreakdown2($UserId,$month=null, $year=null)
	{
		$me = (new CommonController)->get_current_user();

		$user = DB::table('users')
		->select('users.Id','StaffId','Name','Password','User_Type','Company_Email','Personal_Email','Contact_No_1','Contact_No_2','Nationality','Permanent_Address','Current_Address','Home_Base','DOB','NRIC','Passport_No','Gender','Marital_Status','Position','Emergency_Contact_Person',
		'Emergency_Contact_No','Emergency_Contact_Relationship','Emergency_Contact_Address','files.Web_Path')
		// ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->where('users.Id', '=', $UserId)
		->first();

		$d=date('d');

		if ($year==null)
		{
			$year=date('Y');
		}

		if ($month==null)
		{

			$month=date('F');

			if($d>=16)
			{

				$start=date('d-M-Y', strtotime('first day of last month'));
				// $start=date('d-M-Y', strtotime($start,' +16 days'));
				$start = date('d-M-Y', strtotime($start . " +15 days"));

			}
			else {

				$start=date('d-M-Y', strtotime('first day of this month'));
				// $start=date('d-M-Y', strtotime($start,' +16 days'));
				$start = date('d-M-Y', strtotime($start . " +15 days"));

			}

			if($d>=16)
			{

				$end=date('d-M-Y', strtotime('first day of next month'));
				$end = date('d-M-Y', strtotime($end . " +14 days"));
				// $end=date('d-M-Y', strtotime($end,' +15 days'));
			}
			else {

				$end=date('d-M-Y', strtotime('first day of this month'));
				$end = date('d-M-Y', strtotime($end . " +14 days"));
				// $end=date('d-M-Y', strtotime($end,' +15 days'));

			}

		}
		else {
			$start = strtotime('01 '.$month.' '.$year);
			$start = date('d F Y', $start);
			$start = date('d F Y', strtotime('-1 month',strtotime($start)));
			$start = date('d-M-Y', strtotime($start . " +15 days"));

			$end = strtotime('01 '.$month.' '.$year);
			$end = date('d F Y', $end);
			$end = date('d-M-Y', strtotime($end . " +14 days"));

		}

		$orimonth=$month;
		$month=substr($month,0,3);

		$claimsheets= DB::select("
		SELECT claimsheets.Id,claimsheets.UserId,claimsheets.Claim_Sheet_Name
		FROM claimsheets
		LEFT JOIN claims ON claims.ClaimsheetId=claimsheets.Id
		LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
		LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
		WHERE claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
		AND claimsheets.UserId=".$UserId."
		GROUP BY claimsheets.Id
		ORDER BY claimsheets.Id ASC");

		$chartdata = DB::select("
				SELECT claims.Expenses_Type, SUM(claims.Total_Expenses-claims.Petrol_SmartPay) As 'Total_Expenses_Without_SmartPay',SUM(claims.GST_Amount) As 'Total_GST'
				FROM claims
				LEFT JOIN claimsheets ON claims.ClaimsheetId=claimsheets.Id
				LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
				LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
				LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
				WHERE Expenses_Type NOT IN ('Advance','Petrol SmartPay') AND claimstatuses.Status like '%Final Approved%' and  claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
				AND claimsheets.UserId=".$UserId."
				GROUP BY claims.Expenses_Type UNION all

				SELECT 'Staff_Allowance',SUM(Allowance),'0.00'
				FROM timesheets
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
			  LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
				WHERE timesheetstatuses.Status like '%Final Approved%' and timesheetchecked.Payment_Status='". $month.' '.$year ."'
				AND timesheets.UserId=".$UserId." UNION ALL

				SELECT 'Monetary_Comp',Sum(Monetary_Comp),'0.00'
				FROM timesheets
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
			  LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
				WHERE timesheetstatuses.Status like '%Final Approved%' and timesheetchecked.Payment_Status='". $month.' '.$year ."'
				AND timesheets.UserId=".$UserId."
		");
		$total = DB::select("
				SELECT 'Total',SUM(tot.Total_Expenses_Without_SmartPay),SUM(tot.Total_GST) FROM
					(SELECT 'Total', SUM(claims.Total_Expenses-claims.Petrol_SmartPay) As 'Total_Expenses_Without_SmartPay',SUM(claims.GST_Amount) As 'Total_GST'
					FROM claims
					LEFT JOIN claimsheets ON claims.ClaimsheetId=claimsheets.Id
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and  claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					AND claimsheets.UserId=".$UserId."
				 	UNION all

					SELECT 'Staff_Allowance',SUM(Allowance+Monetary_Comp),'0.00'
					FROM timesheets
					LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
					LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
			    LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
					WHERE timesheetstatuses.Status like '%Final Approved%' and timesheetchecked.Payment_Status='". $month.' '.$year ."'
					AND timesheets.UserId=".$UserId.") AS tot UNION ALL

					SELECT 'Petrol_SmartPay',SUM(Petrol_SmartPay),'0.00'
					FROM claims
					LEFT JOIN claimsheets ON claims.ClaimsheetId=claimsheets.Id
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and  claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					AND claimsheets.UserId=".$UserId."
					GROUP BY 1 UNION all

					SELECT 'Advance',SUM(Advance),'0.00'
					FROM claims
					LEFT JOIN claimsheets ON claims.ClaimsheetId=claimsheets.Id
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and  claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					AND claimsheets.UserId=".$UserId."
					GROUP BY 1 UNION all

					SELECT 'Summon',SUM(Summon),'0.00'
					FROM claims
					LEFT JOIN claimsheets ON claims.ClaimsheetId=claimsheets.Id
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and  claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					AND claimsheets.UserId=".$UserId."
					GROUP BY 1

		");

		if ($chartdata==null){
			$data = "";
			$title = "";
		}
		else {

			$data = "";
			$title = "";

			foreach($chartdata as $key => $quote){
				$ret[]=$quote->Total_Expenses_Without_SmartPay;

				$data .= $quote->Total_Expenses_Without_SmartPay.",";
			}

		}

		foreach($chartdata as $key => $quote){
			$title .= $quote->Expenses_Type.",";
		}

		$data=substr($data,0,strlen($data)-1);
		$title=substr($title,0,strlen($title)-1);

		// $total = DB::table('claims')
		// ->leftJoin('claimsheets', 'claimsheets.Id', '=', 'claims.ClaimSheetId')
		// ->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		// ->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		// ->leftJoin('users', 'claimsheets.UserId', '=', 'users.Id')
		// ->select(DB::raw('"Total_Claim"'), DB::raw('SUM(claims.Total_Amount) As Total_Expenses'),DB::raw('SUM(claims.GST_Amount) As Total_GST'))
		// ->where('claimstatuses.Status', 'like','%Final Approved%')
		// ->where( 'claimsheets.UserId', '=', $UserId)
		// ->where(DB::raw('str_to_date(claims.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		// ->where(DB::raw('str_to_date(claims.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		// ->groupBy('claimsheets.UserId')
		// ->get();

		//convert long month name to short month name

		$month=$orimonth;

		return view("userclaimsummary2", ['me' => $me,'user' =>$user, 'claimsheets' =>$claimsheets,'month' => $month , 'year' =>$year, 'start' => $start,'end' =>$end, 'chartdata' => $chartdata,'total' => $total, 'data' => $data, 'title' => $title]);

	}

	public function userclaimbreakdown3($UserId,$month=null, $year=null)
	{
		$me = (new CommonController)->get_current_user();

		$user = DB::table('users')
		->select('users.Id','StaffId','Name','Password','User_Type','Company_Email','Personal_Email','Contact_No_1','Contact_No_2','Nationality','Permanent_Address','Current_Address','Home_Base','DOB','NRIC','Passport_No','Gender','Marital_Status','Position','Emergency_Contact_Person',
		'Emergency_Contact_No','Emergency_Contact_Relationship','Emergency_Contact_Address','files.Web_Path')
		// ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->where('users.Id', '=', $UserId)
		->first();

		$d=date('d');

		if ($year==null)
		{
			$year=date('Y');
		}

		if ($month==null)
		{

			$month=date('F');

			if($d>=16)
			{

				$start=date('d-M-Y', strtotime('first day of last month'));
				// $start=date('d-M-Y', strtotime($start,' +16 days'));
				$start = date('d-M-Y', strtotime($start . " +15 days"));

			}
			else {

				$start=date('d-M-Y', strtotime('first day of this month'));
				// $start=date('d-M-Y', strtotime($start,' +16 days'));
				$start = date('d-M-Y', strtotime($start . " +15 days"));

			}

			if($d>=16)
			{

				$end=date('d-M-Y', strtotime('first day of next month'));
				$end = date('d-M-Y', strtotime($end . " +14 days"));
				// $end=date('d-M-Y', strtotime($end,' +15 days'));
			}
			else {

				$end=date('d-M-Y', strtotime('first day of this month'));
				$end = date('d-M-Y', strtotime($end . " +14 days"));
				// $end=date('d-M-Y', strtotime($end,' +15 days'));

			}

		}
		else {
			$start = strtotime('01 '.$month.' '.$year);
			$start = date('d F Y', $start);
			$start = date('d F Y', strtotime('-1 month',strtotime($start)));
			$start = date('d-M-Y', strtotime($start . " +15 days"));

			$end = strtotime('01 '.$month.' '.$year);
			$end = date('d F Y', $end);
			$end = date('d-M-Y', strtotime($end . " +14 days"));

		}

		$month=substr($month,0,3);

		$claimsheets= DB::select("
		SELECT claimsheets.Id,claimsheets.UserId,claimsheets.Claim_Sheet_Name
		FROM claimsheets
		LEFT JOIN claims ON claims.ClaimsheetId=claimsheets.Id
		LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
		LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
		WHERE claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
		AND claimsheets.UserId=".$UserId."
		GROUP BY claimsheets.Id
		ORDER BY claimsheets.Id ASC");

		$chartdata = DB::select("
				SELECT claims.Expenses_Type,
				SUM(IF(claims.Rate>0,(claims.Total_Expenses-claims.Petrol_SmartPay)*claims.Rate,claims.Total_Expenses-claims.Petrol_SmartPay)) As 'Total_Expenses_Without_SmartPay',
				SUM(IF(claims.Rate>0,claims.GST_Amount*claims.Rate,claims.GST_Amount)) As 'Total_GST'
				FROM claims
				LEFT JOIN claimsheets ON claims.ClaimsheetId=claimsheets.Id
				LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
				LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
				LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
				WHERE Expenses_Type NOT IN ('Advance','Petrol SmartPay') AND claimstatuses.Status like '%Final Approved%' and  claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
				AND claimsheets.UserId=".$UserId."
				GROUP BY claims.Expenses_Type UNION all

				SELECT 'Staff_Allowance',SUM(Allowance),'0.00'
				FROM timesheets
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
			  LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
				WHERE timesheetstatuses.Status like '%Final Approved%' and timesheetchecked.Payment_Status='". $month.' '.$year ."'
				AND timesheets.UserId=".$UserId." UNION ALL

				SELECT 'Monetary_Comp',Sum(Monetary_Comp),'0.00'
				FROM timesheets
				LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
				LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
				LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
			  LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
				WHERE timesheetstatuses.Status like '%Final Approved%' and timesheetchecked.Payment_Status='". $month.' '.$year ."'
				AND timesheets.UserId=".$UserId."
		");
		$total = DB::select("
				SELECT 'Total',SUM(tot.Total_Expenses_Without_SmartPay),SUM(tot.Total_GST) FROM
					(SELECT 'Total', SUM(IF(claims.Rate>0,(claims.Total_Expenses-claims.Petrol_SmartPay)*claims.Rate,claims.Total_Expenses-claims.Petrol_SmartPay)) As 'Total_Expenses_Without_SmartPay',
					SUM(IF(claims.Rate>0,claims.GST_Amount*claims.Rate,claims.GST_Amount)) As 'Total_GST'
					FROM claims
					LEFT JOIN claimsheets ON claims.ClaimsheetId=claimsheets.Id
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and  claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					AND claimsheets.UserId=".$UserId."
				 	UNION all

					SELECT 'Staff_Allowance',SUM(Allowance+Monetary_Comp),'0.00'
					FROM timesheets
					LEFT JOIN (select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max on max.TimesheetId=timesheets.Id
					LEFT JOIN timesheetstatuses on timesheetstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid4,TimesheetId from timesheetchecked Group By TimesheetId) as max4 ON max4.TimesheetId=timesheets.Id
			    LEFT JOIN timesheetchecked on timesheetchecked.Id=max4.`maxid4`
					WHERE timesheetstatuses.Status like '%Final Approved%' and timesheetchecked.Payment_Status='". $month.' '.$year ."'
					AND timesheets.UserId=".$UserId.") AS tot UNION ALL

					SELECT 'Petrol_SmartPay',SUM(Petrol_SmartPay),'0.00'
					FROM claims
					LEFT JOIN claimsheets ON claims.ClaimsheetId=claimsheets.Id
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and  claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					AND claimsheets.UserId=".$UserId."
					GROUP BY 1 UNION all

					SELECT 'Advance',SUM(Advance),'0.00'
					FROM claims
					LEFT JOIN claimsheets ON claims.ClaimsheetId=claimsheets.Id
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and  claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					AND claimsheets.UserId=".$UserId."
					GROUP BY 1 UNION all

					SELECT 'Summon',SUM(Summon),'0.00'
					FROM claims
					LEFT JOIN claimsheets ON claims.ClaimsheetId=claimsheets.Id
					LEFT JOIN (select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max on max.ClaimId=claims.Id
					LEFT JOIN claimstatuses on claimstatuses.Id=max.`maxid`
					LEFT JOIN (select Max(Id) as maxid3,ClaimSheetId from claimsheetstatuses Group By ClaimSheetId) as max3 ON max3.ClaimSheetId=claimsheets.Id
					LEFT JOIN claimsheetstatuses on claimsheetstatuses.Id=max3.`maxid3`
					WHERE claimstatuses.Status like '%Final Approved%' and  claimsheetstatuses.Claim_Status='". $month.' '.$year ."'
					AND claimsheets.UserId=".$UserId."
					GROUP BY 1

		");

		if ($chartdata==null){
			$data = "";
			$title = "";
		}
		else {

			$data = "";
			$title = "";

			foreach($chartdata as $key => $quote){
				$ret[]=$quote->Total_Expenses_Without_SmartPay;

				$data .= $quote->Total_Expenses_Without_SmartPay.",";
			}

		}

		foreach($chartdata as $key => $quote){
			$title .= $quote->Expenses_Type.",";
		}

		$data=substr($data,0,strlen($data)-1);
		$title=substr($title,0,strlen($title)-1);

		// $total = DB::table('claims')
		// ->leftJoin('claimsheets', 'claimsheets.Id', '=', 'claims.ClaimSheetId')
		// ->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		// ->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		// ->leftJoin('users', 'claimsheets.UserId', '=', 'users.Id')
		// ->select(DB::raw('"Total_Claim"'), DB::raw('SUM(claims.Total_Amount) As Total_Expenses'),DB::raw('SUM(claims.GST_Amount) As Total_GST'))
		// ->where('claimstatuses.Status', 'like','%Final Approved%')
		// ->where( 'claimsheets.UserId', '=', $UserId)
		// ->where(DB::raw('str_to_date(claims.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		// ->where(DB::raw('str_to_date(claims.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		// ->groupBy('claimsheets.UserId')
		// ->get();

		return view("userclaimsummary2", ['me' => $me,'user' =>$user, 'claimsheets' =>$claimsheets,'month' => $month , 'year' =>$year, 'start' => $start,'end' =>$end, 'chartdata' => $chartdata,'total' => $total, 'data' => $data, 'title' => $title]);

	}

	public function viewtimesheet(Request $request)
	{

		$input = $request->all();

		$viewtimesheet = DB::table('timesheets')
		->select('timesheets.Check_In_Type', 'timesheets.Time_In','timesheets.Time_Out','timesheets.Allowance','timesheets.Leader_Member','timesheets.Next_Person','timesheets.Site_Name','timesheets.State','timesheets.Work_Description','timesheets.Reason','timesheets.Remarks','approver.Name as Approver','timesheetstatuses.Status','timesheetstatuses.Comment','timesheetstatuses.updated_at as Review_Date','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Timesheet" Group By TargetId) as maxfile'), 'maxfile.TargetId', '=', 'timesheets.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('maxfile.`maxid` and files.`Type`="Timesheet"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TimesheetId from timesheetstatuses Group By TimesheetId) as max'), 'max.TimesheetId', '=', 'timesheets.Id')
		->leftJoin('timesheetstatuses', 'timesheetstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'timesheetstatuses.UserId', '=', 'approver.Id')
		->where('timesheets.UserId', '=',  $input["UserId"])
		->where('timesheets.Date', '=',  $input["Date"])
		->orderBy('timesheets.Id','desc')
		->get();

		return json_encode($viewtimesheet);

	}

	public function approve(Request $request)
	{

		$me = (new CommonController)->get_current_user();

		$input = $request->all();

		$Ids = explode(",", $input["StatusIds"]);

		$claims = DB::table('claims')
		->select('claimstatuses.Id as StatusId','claims.Id as ClaimId','claims.Date',DB::raw('"" as Day'),'claims.Depart_From','claims.Destination','claims.Site_Name','claims.State','claims.Work_Description',
		'claims.Next_Person','claims.Transport_Type','claims.Car_No','claims.Mileage','claims.Expenses_Type','claims.Total_Expenses','claims.Petrol_SmartPay',DB::raw('"" as Claims_Amount_Exclude_SmartPay'),'claims.Advance','claims.Total_Amount','claims.GST_Amount','claims.Total_Without_GST','claims.Receipt_No','claims.Company_Name','claims.GST_No','claims.Remarks','approver.Name as Approver','claimstatuses.UserId','claimstatuses.Status','claimstatuses.Comment','claimstatuses.updated_at as Review_Date')
		->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
		->whereIn('claimstatuses.Id', $Ids)
		->orderBy('claims.Id','desc')
		->get();

		foreach ($claims as $claim) {
			# code...
			if ($claim->UserId!=$me->UserId)
			{
				$id=DB::table('claimstatuses')->insertGetId(
					['ClaimId' => $claim->ClaimId,
					 'UserId' => $me->UserId,
					 'Status' => $input["Status"],
					 'updated_at' => DB::raw('now()')
					]
				);


			}
			else {

				$result= DB::table('claimstatuses')
							->where('Id', '=',$claim->StatusId)
							->update(array(
							'Status' =>  $input["Status"],
						));

			}
		}

		return 1;

	}

	public function redirect(Request $request)
	{

		$arrClaimId = array();

		$me = (new CommonController)->get_current_user();

		$input = $request->all();

		$Ids = explode(",", $input["StatusIds"]);

		$claims = DB::table('claims')
		->select('claimstatuses.Id as StatusId','claims.Id as ClaimId','claims.Date',DB::raw('"" as Day'),'claims.Depart_From','claims.Destination','claims.Site_Name','claims.State','claims.Work_Description',
		'claims.Next_Person','claims.Transport_Type','claims.Car_No','claims.Mileage','claims.Expenses_Type','claims.Total_Expenses','claims.Petrol_SmartPay',DB::raw('"" as Claims_Amount_Exclude_SmartPay'),'claims.Advance','claims.Total_Amount','claims.GST_Amount','claims.Total_Without_GST','claims.Receipt_No','claims.Company_Name','claims.GST_No','claims.Remarks','approver.Name as Approver','claimstatuses.UserId','claimstatuses.Status','claimstatuses.Comment','claimstatuses.updated_at as Review_Date')
		->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
		->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
		->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
		->whereIn('claimstatuses.Id', $Ids)
		->orderBy('claims.Id','desc')
		->get();

		foreach ($claims as $item) {

			# code...
			$id=DB::table('claimstatuses')->insertGetId(
				['ClaimId' => $item->ClaimId,
				 'UserId' => $input["Approver"],
				 'Status' => "Pending Approval"
				]
			);

			array_push($arrClaimId,$item->ClaimId);
		}

		if ($id>0)
		{

			$claims = DB::table('claims')
			->select('claims.Id','claimsheets.UserId','claims.Date','submitter.Name as Submitter','claims.Depart_From','claims.Destination','claims.Site_Name','claims.State','claims.Work_Description',
			'claims.Next_Person','claims.Transport_Type','claims.Car_No','claims.Mileage','claims.Expenses_Type','claims.Total_Expenses','claims.Petrol_SmartPay',DB::raw('"" as Claims_Amount_Exclude_SmartPay'),'claims.Advance','claims.Total_Amount','claims.GST_Amount','claims.Total_Without_GST','claims.Receipt_No','claims.Company_Name','claims.GST_No','claims.Remarks','approver.Name as Approver','claimstatuses.UserId','claimstatuses.Status','claimstatuses.Comment','claimstatuses.updated_at as Updated_At')
			->leftJoin('claimsheets', 'claimsheets.Id', '=', 'claims.ClaimSheetId')
			->leftJoin( DB::raw('(select Max(Id) as maxid,ClaimId from claimstatuses Group By ClaimId) as max'), 'max.ClaimId', '=', 'claims.Id')
			->leftJoin('claimstatuses', 'claimstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver', 'claimstatuses.UserId', '=', 'approver.Id')
			->leftJoin('users as submitter', 'claimsheets.UserId', '=', 'submitter.Id')
			->whereIn('claims.Id', $arrClaimId)
			->orderBy('claims.Date','asc')
			->get();

			$notify = DB::table('users')
			->whereIn('Id', [$me->UserId, $input["Approver"]])
			->get();

			$subscribers = DB::table('notificationtype')
			->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
			->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
			->where('notificationtype.Id','=',24)
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

			// Mail::send('emails.claimredirected', ['me'=>$me,'claims' => $claims], function($message) use ($emails,$me,$NotificationSubject)
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

	public function cutoffmanagement()
	{

		$me = (new CommonController)->get_current_user();

		$cutoff = DB::table('cutoff')
		->select('Id','Payment_Month','Start_Date','End_Date')
		->get();

		return view('cutoffmanagement',['me'=>$me,'cutoff'=>$cutoff]);


	}

	public function checkcutoff(Request $request)
	{

		$input = $request->all();

		$month=$input["m"];
		$year=$input["y"];

		$cutoff = DB::table('cutoff')
		->select(DB::raw('COUNT(*) as count'))
		->where('Month', '=',$month)
		->where('Year', '=',$year)
		->first();

		return $cutoff->count;


	}

	public function medicalclaimsummary($start=null, $end=null)
	{
		$me = (new CommonController)->get_current_user();

		if ($start==null)
		{
			$start = date('d-M-Y',strtotime('first day of this year'));
		}

		if ($end==null)
		{
			$end= date ('d-M-Y', strtotime('last day of december'));
		}

		$byperson = DB::table('leaves')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
            ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
            ->leftJoin('users','users.Id', '=', 'leaves.UserId')
            // ->select('users.Id','users.StaffId','users.name',DB::raw('SUM(leaves.Medical_Claim) as Claim'))
            ->select('users.Id',
						'users.StaffId',
						'users.name',
						// 'users.Bank_Name',
						// 'users.Bank_Account_No',
						'users.Grade',
						DB::raw('SUM(leaves.Medical_Claim) as Claim'),
						DB::raw('SUM(leaves.Panel_Claim) as Panel_Claim')
						)
            ->where('leavestatuses.Leave_Status', 'like','%Final Approved%')
            ->whereRaw('str_to_date(leaves.Start_Date,"%d-%M-%Y") BETWEEN str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y") ')
            ->where('leaves.Medical_Claim', '>','0')
            // ->where(DB::raw('MONTH(str_to_date(leaves.Start_Date,"%d-%M-%Y"))'), $monthnum)
            // ->havingRaw('SUM(leaves.Medical_Claim) > ?', [0])
            ->groupBy('users.Id')
            ->orderBy('users.Name')
            ->get();
         if(!$byperson)
         {
         	$byperson = DB::table('leaves')
         	->select(DB::raw('"" as Id'),DB::raw('"" as StaffId'),DB::raw('"" as name'),DB::raw('"" as Grade'),DB::raw('"" as Claim'),DB::raw('"" as Panel_Claim'))
         	->limit(1)
         	->get();
         }

		$total = DB::table('leaves')
            ->leftJoin( DB::raw('(select Max(Id) as maxid,LeaveId from leavestatuses Group By LeaveId) as max'), 'max.LeaveId', '=', 'leaves.Id')
            ->leftJoin('leavestatuses', 'leavestatuses.Id', '=', DB::raw('max.`maxid`'))
            ->leftJoin('users','users.Id', '=', 'leaves.UserId')
            ->select(DB::raw('SUM(leaves.Medical_Claim) as Total_Claim'),DB::raw('SUM(leaves.Panel_Claim) as Total_Panel_Claim'), DB::raw("SUM(CASE WHEN leaves.Medical_Paid_Month <> '' AND leaves.Medical_Paid_Month IS NOT NULL THEN leaves.Medical_Claim ELSE 0 END) as Total_Paid"))
            ->where('leavestatuses.Leave_Status', 'like','%Final Approved%')
            // ->where(DB::raw('YEAR(str_to_date(leaves.Start_Date,"%d-%M-%Y"))'), $year)
            // ->where(DB::raw('MONTH(str_to_date(leaves.Start_Date,"%d-%M-%Y"))'), $monthnum)
            ->whereRaw('str_to_date(leaves.Start_Date,"%d-%M-%Y") BETWEEN str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y") ')
            ->where('leaves.Medical_Claim', '>','0')
            // ->where(DB::raw('MONTH(str_to_date(leaves.Start_Date,"%d-%M-%Y"))'), $monthnum)
            ->havingRaw('SUM(leaves.Medical_Claim) > ?', [0])
            // ->groupBy('users.Id')
            ->orderBy('users.Name')
            ->first();

		return view("medicalclaimsummary", ['me' => $me, 'start' => $start , 'end' =>$end, 'byperson' => $byperson, 'total' => $total]);
	}

	public function createmedicalclaim(Request $request)
	{	
		$input = $request->all();

		$getGrade = DB::Table('users')
		->select('Grade')
		->where('Id',$input['confirmid'])
		->first();

		if($getGrade->Grade == "A")
		{
			$medicallimit = 1200;
		}
		elseif($getGrade->Grade == "B")
		{
			$medicallimit = 800;
		}
		elseif($getGrade->Grade == "C")
		{
			$medicallimit = 500;
		}
		else
		{
			$medicallimit = 300;
		}

		$panellimit = 300;

		$claimed = DB::table('leaves')
		->select(DB::raw('SUM(Medical_Claim) as medical'),DB::raw('SUM(Panel_Claim) as panel'))
		->where('UserId',$input['confirmid'])
		->first();

		$panelbalance = 0;
		$medicalbalance = 0;
		if($input['panel'])
		{
			$paneltoclaim = $claimed->panel + $input['panel'];
			$panelbalance = $panellimit - $paneltoclaim;
		}

		if($input['claim'])
		{
			$medicaltoclaim = $claimed->medical + $input['claim'];
			$medicalbalance = $medicallimit - $medicaltoclaim;
		}
		
		if($panelbalance < 0)
		{
			return "Panel claim not enough balance";
		}

		if($medicalbalance < 0)
		{
			return "Medical claim not enough balance";
		}

		$id = DB::table('leaves')
		->insertGetId([
			'UserId' => $input['confirmid'],
			'Leave_Type' => "Medical Leave",
			'No_of_Days' => 0,
			'Reason' => "Medical Claim for Non Medical Leave",
			'Medical_Claim' => $input['claim'],
			'Panel_Claim' => $input['panel'],
			'created_at' => DB::raw('NOW()')
		]);

		$filenames="";
        $attachmentUrl = null;
        $type="Leave";
        $uploadcount=count($request->file('attachment'));

		if ($request->hasFile('attachment')) {

            for ($i=0; $i <$uploadcount ; $i++) {
                # code...
                $file = $request->file('attachment')[$i];
                $destinationPath=public_path()."/private/upload/Leave";
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize=$file->getSize();
                $fileName=time()."_".$i.".".$extension;
                $upload_success = $file->move($destinationPath, $fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => $type,
                     'TargetId' => $id,
                     'File_Name' => $originalName,
                     'File_Size' => $fileSize,
                     'Web_Path' => '/private/upload/Leave/'.$fileName
                    ]
                );
                $attachmentUrl = url('/private/upload/Leave/'.$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
            }

            $filenames=substr($filenames, 0, strlen($filenames)-1);

        }

		DB::table('leavestatuses')
		->insert([
			'LeaveId' => $id,
			'UserId' => 562,
			'Leave_Status' => "Final Approved",
			'Comment' => "Medical Claim for Non Medical Leave",
			'created_at' => DB::raw('NOW()')
		]);

		return 1;
	}

	public function getmedicalclaim($userid, Request $request)
	{
		$list = DB::table('leaves')
		->select('leaves.Id','leaves.Start_Date','leaves.End_Date','leaves.Medical_Claim','leaves.Panel_Claim')
		->where('leaves.UserId',$userid)
		->where('leaves.Leave_Type',"Medical Leave")
		->whereRaw('(STR_TO_DATE(Start_Date,"%d-%M-%Y") >= STR_TO_DATE("'.$request->start.'","%d-%M-%Y")) AND (STR_TO_DATE(End_Date,"%d-%M-%Y") <= STR_TO_DATE("'.$request->end.'","%d-%M-%Y"))')
		->get();

		// $fileid = array();
		// foreach ($list as $key => $value) {
		// 	array_push($fileid,$value->Id);
		// }

		// $photos = DB::table('files')
		// ->whereIn('TargetId',$fileid)
		// ->where('Type','Leave')
		// ->select('TargetId','Web_Path')
		// ->get();

		return response()->json(['list'=>$list]);
	}

}
