<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Storage;
use DateTime;
//use Input;
// use Illuminate\Support\Facades\Mail;
class UserController extends Controller {
	public function __construct()
	{
		$this->middleware('auth');
	}
	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index($start = null, $end = null, $type = null)
	{
		$me = (new CommonController)->get_current_user();
		$users = DB::table('users')->select('users.Id','files.Web_Path','users.Status','users.StaffId as Staff_ID','users.Name','users.NRIC','users.Joining_Date','users.Confirmation_Date','users.Resignation_Date','users.Position','users.Contact_No_1','users.Nationality','users.Grade','users.Company','users.Department','users.Category','users.Entitled_for_OT','users.Working_Days','holidayterritories.Name as HolidayTerritory','users.Ext_No','users.Company_Email','users.Nick_Name','users.User_Type','users.Personal_Email', 'users.Contact_No_2','users.Permanent_Address','users.Current_Address','users.Country_Base','users.Home_Base','users.DOB','users.Place_Of_Birth','users.Race','users.Religion','users.Passport_No','users.Gender','users.Marital_Status','superior.Name as Superior','team.Name as TeamMember','users.Internship_Start_Date','users.Internship_End_Date','users.Bank_Name','users.Bank_Account_No','users.EPF_No','users.SOCSO_No','users.Income_Tax_No','users.Emergency_Contact_Person','users.Emergency_Contact_No','users.Emergency_Contact_Relationship','users.Emergency_Contact_Address','users.Driving_License','users.Car_Owner','users.Criminal_Activity')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->leftJoin('users as superior','superior.Id','=','users.SuperiorId')
		->leftJoin('holidayterritories','holidayterritories.Id','=','users.HolidayTerritoryId')
		->leftJoin('users as team','team.Id','=','users.Team')
		->orderBy('users.Id')
		->get();

		// if($start && $end)
		// {
		// 	$users = $users->where(DB::raw('str_to_date(users.Confirmation_Date,"%d-%M-%Y")'),'>=',DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
  //       	->where(DB::raw('str_to_date(users.Confirmation_Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))->get();
		// }
		// else
		// {
		// 	$users = $users->get();
		// }

		$options= DB::table('options')
		->whereIn('Table', ["users"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		$projects = DB::table('projects')
		->where('projects.Project_Name','like','%department%')
		->get();

		$holidayterritories = DB::table('holidayterritories')->select('holidayterritories.Id','holidayterritories.Name')->get();

		// Mail::send('emails.welcome', ['key' => 'value'], function($message)
		// {
		//     $message->to('shhau@softoya.com', 'Hau')->subject('Welcome!');
		// });
    return view('user', ['me' => $me,'users' => $users,'options' =>$options,'resigned'=>false,'projects'=>$projects, 'holidayterritories' => $holidayterritories, 'start' => $start, 'end' => $end, 'type' => $type]);
//		return view('Staff');
	}

	public function resigned($start = null, $end = null,$type = null)
	{
		$me = (new CommonController)->get_current_user();
		$users = DB::table('users')->select('users.Id','files.Web_Path','users.Status','users.StaffId as Staff_ID','users.Name','users.NRIC','users.Joining_Date','users.Confirmation_Date','users.Resignation_Date','users.Position','users.Contact_No_1','users.Nationality','users.Grade','users.Company','users.Department','users.Category','users.Working_Days','holidayterritories.Name as HolidayTerritory','users.Ext_No','users.Company_Email','users.Nick_Name','users.User_Type','users.Personal_Email', 'users.Contact_No_2','users.Permanent_Address','users.Current_Address','users.Country_Base','users.Home_Base','users.DOB','users.Place_Of_Birth','users.Race','users.Religion','users.Passport_No','users.Gender','users.Marital_Status','superior.Name as Superior','team.Name as TeamMember','users.Internship_Start_Date','users.Internship_End_Date','users.Bank_Name','users.Bank_Account_No','users.EPF_No','users.SOCSO_No','users.Income_Tax_No','users.Emergency_Contact_Person','users.Emergency_Contact_No','users.Emergency_Contact_Relationship','users.Emergency_Contact_Address','users.Driving_License','users.Car_Owner','users.Criminal_Activity')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->leftJoin('users as superior','superior.Id','=','users.SuperiorId')
		->leftJoin('holidayterritories','holidayterritories.Id','=','users.HolidayTerritoryId')
		->leftJoin('users as team','team.Id','=','users.Team')
		->where('users.Resignation_Date', '!=','')
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
		// Mail::send('emails.welcome', ['key' => 'value'], function($message)
		// {
		//     $message->to('shhau@softoya.com', 'Hau')->subject('Welcome!');
		// });

    return view('user', ['me' => $me,'users' => $users,'options' =>$options,'resigned'=>true,'projects'=>$projects, 'holidayterritories' => $holidayterritories, 'start' => $start, 'end' => $end, 'type'=>$type]);
//		return view('Staff');
	}

	public function staffexpenses($start = null,$end = null)
	{
		$me = (new CommonController)->get_current_user();

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


		$year=date('Y');


		$users = DB::table('users')
		->select('users.Id','users.StaffId as Staff_ID','users.Name','users.Department','users.Position','users.Joining_Date','users.Nationality',DB::raw('(SELECT SUM(Amount) FROM staffexpenses a WHERE a.UserId=users.Id AND a.Year='.$year.') AS Total_Expenses'))
		->orderBy('users.Name')
		->get();

		// $total= DB::select("SELECT SUM(Amount) as Total FROM staffexpenses WHERE Year=$year");
		$total = DB::table('staffexpenses')
					->select(DB::raw('SUM(staffexpenses.Amount) as Total'))
					->where(DB::raw('str_to_date(staffexpenses.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
					->where(DB::raw('str_to_date(staffexpenses.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
					->orderBy('staffexpenses.Date')
					->get();


    	return view('staffexpenses', ['me' => $me,'users' => $users,'year'=>$year,'total'=>$total[0]->Total, 'start' => $start, 'end' => $end]);

	}

	public function staffdeductions($start = null,$end = null)
	{
		$me = (new CommonController)->get_current_user();

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

		$year=date('Y');

		$resignedlastmonthsameday=date('d-M-Y');

		$datestring=$resignedlastmonthsameday. 'first day of last month';
		$dt=date_create($datestring);
		$resignedlastmonthsameday=date('d')."-".$dt->format('M-Y'); //2011-02

		$users = DB::table('users')
		->select(
			'users.Id','users.StaffId as Staff_ID','users.Name','users.Department','users.Position','users.Joining_Date','users.Nationality',
			DB::raw('(
				SELECT SUM(FinalAmount) FROM staffdeductions a
				WHERE a.UserId=users.Id
				AND str_to_date(Date,"%d-%M-%Y") >= str_to_date("'.$start.'","%d-%M-%Y")
				AND str_to_date(Date,"%d-%M-%Y") <= str_to_date("'.$end.'","%d-%M-%Y"))
			AS Total_Deductions')
		)
		->whereRaw('(users.Resignation_Date="" or str_to_date(users.Resignation_Date,"%d-%M-%Y")>=str_to_date("'.$resignedlastmonthsameday.'","%d-%M-%Y"))')
		->orderBy('users.Name')
		->get();

		// PETTY CASH SABAH (FKA PETTY CASH CME)
		// Late
		// ADVANCE SALARY DEDUCTION
		// MAX CARD
		// NIOSH
		// CIDB CARD
		// LOSS OF EQUIPMENT

		// PETTY CASH FION
		// PAY BACK TO ERIC
		// DRIVING LICENSE DEDUCTION

		$total = DB::table('staffdeductions')
		->leftJoin('users','staffdeductions.UserId','=','users.Id')
		->select(
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "STAFF LOAN" THEN staffdeductions.FinalAmount ELSE 0 END) as Staff_Loan_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "PRE-SAVING SCHEME" THEN staffdeductions.FinalAmount ELSE 0 END) as Presaving_Scheme_Total'),

			DB::raw('SUM(CASE WHEN staffdeductions.Type = "SUMMONS" THEN staffdeductions.FinalAmount ELSE 0 END) as Summons_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "ACCIDENT" THEN staffdeductions.FinalAmount ELSE 0 END) as Accident_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "SHELL CARD" THEN staffdeductions.FinalAmount ELSE 0 END) as Shell_Card_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "TOUCH N GO" THEN staffdeductions.FinalAmount ELSE 0 END) as TNG_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "SAFETY SHOES" THEN staffdeductions.FinalAmount ELSE 0 END) as Safety_Shoes_Total'),

			DB::raw('SUM(CASE WHEN staffdeductions.Type = "PETTY CASH SABAH (FKA PETTY CASH CME)" THEN staffdeductions.FinalAmount ELSE 0 END) as Petty_Cash_CME_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "Late" THEN staffdeductions.FinalAmount ELSE 0 END) as Late_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "ADVANCE SALARY DEDUCTION" THEN staffdeductions.FinalAmount ELSE 0 END) as Advance_Salary_Total'),

			DB::raw('SUM(CASE WHEN staffdeductions.Type = "MAX CARD" THEN staffdeductions.FinalAmount ELSE 0 END) as MAX_CARD_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "NIOSH" THEN staffdeductions.FinalAmount ELSE 0 END) as NIOSH_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "CIDB CARD" THEN staffdeductions.FinalAmount ELSE 0 END) as CIDB_Total'),

			DB::raw('SUM(CASE WHEN staffdeductions.Type = "LOSS OF EQUIPMENT" THEN staffdeductions.FinalAmount ELSE 0 END) as LOSS_OF_EQ_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "PETTY CASH FION" THEN staffdeductions.FinalAmount ELSE 0 END) as Fion_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "PAY BACK TO ERIC" THEN staffdeductions.FinalAmount ELSE 0 END) as Eric_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "DRIVING LICENSE DEDUCTION" THEN staffdeductions.FinalAmount ELSE 0 END) as License_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "Not In Radius" THEN staffdeductions.FinalAmount ELSE 0 END) as Not_In_Radius'),

			DB::raw('SUM(staffdeductions.FinalAmount) as Total')
		)
		->where(DB::raw('str_to_date(staffdeductions.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(staffdeductions.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->whereRaw('(users.Resignation_Date="" or str_to_date(users.Resignation_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y"))')
		->orderBy('staffdeductions.Date')
		->first();

    	return view('staffdeductions', ['me' => $me,'users' => $users,'year'=>$year,'total'=>$total, 'start' => $start, 'end' => $end]);

	}

	public function staffdeductionslist($type, $start = null,$end = null)
	{
		$me = (new CommonController)->get_current_user();

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


		$record = DB::table('staffdeductions')
		->select('staffdeductions.Id','staffdeductions.UserId','staffdeductions.Date','staffdeductions.Type','staffdeductions.Description','staffdeductions.Amount','staffdeductions.Amount','staffdeductions.FinalAmount','users.StaffId as Staff_ID','users.Name','users.Department','users.Position','users.Joining_Date','users.Nationality','users.Name as Created_By','staffdeductions.created_at')
		->leftJoin('users','staffdeductions.UserId','=','users.Id')
		->leftJoin('users as creator','staffdeductions.created_by','=','creator.Id')
		->where(DB::raw('str_to_date(staffdeductions.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(staffdeductions.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->whereRaw('(users.Resignation_Date="" or str_to_date(users.Resignation_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y"))')
		->orderBy('staffdeductions.Date')
		->get();

		$total = DB::table('staffdeductions')
		->select(DB::raw('SUM(staffdeductions.FinalAmount) as Total'))
		->leftJoin('users','staffdeductions.UserId','=','users.Id')
		->where(DB::raw('str_to_date(staffdeductions.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(staffdeductions.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->whereRaw('(users.Resignation_Date="" or str_to_date(users.Resignation_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y"))')
		->groupBy('staffdeductions.Type')
		->where('staffdeductions.Type', $type)
		->first();


    	return view('staffdeductionslist', ['me' => $me,'total'=>$total, 'start' => $start, 'end' => $end, 'type' => $type, 'record' => $record]);
	}

	public function staffdeductionsrecord($id,$start = null,$end = null)
	{
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

		$user = DB::table('users')
		->where('Id', '=',$id)
		->first();

		$record = DB::table('staffdeductions')
		->select('staffdeductions.Id','staffdeductions.UserId','staffdeductions.Month','staffdeductions.Date','staffdeductions.Type','staffdeductions.Description','staffdeductions.Amount','staffdeductions.FinalAmount','staffdeductions.Amount','users.Name as Created_By','staffdeductions.created_at')
		->leftJoin('users','staffdeductions.UserId','=','users.Id')
		->leftJoin('users as creator','staffdeductions.created_by','=','creator.Id')
		->where(DB::raw('str_to_date(staffdeductions.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(staffdeductions.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->where('users.Id', $id)
		->orderBy('staffdeductions.Date')
		->get();

		$total = DB::table('staffdeductions')
		->leftJoin('users','staffdeductions.UserId','=','users.Id')
		->select(
			'users.Name','users.Company','users.Department',
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "STAFF LOAN" THEN staffdeductions.FinalAmount ELSE 0 END) as Staff_Loan_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "PRE-SAVING SCHEME" THEN staffdeductions.FinalAmount ELSE 0 END) as Presaving_Scheme_Total'),

			DB::raw('SUM(CASE WHEN staffdeductions.Type = "SUMMONS" THEN staffdeductions.FinalAmount ELSE 0 END) as Summons_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "ACCIDENT" THEN staffdeductions.FinalAmount ELSE 0 END) as Accident_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "SHELL CARD" THEN staffdeductions.FinalAmount ELSE 0 END) as Shell_Card_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "TOUCH N GO" THEN staffdeductions.FinalAmount ELSE 0 END) as TNG_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "SAFETY SHOES" THEN staffdeductions.FinalAmount ELSE 0 END) as Safety_Shoes_Total'),

			DB::raw('SUM(CASE WHEN staffdeductions.Type = "PETTY CASH SABAH (FKA PETTY CASH CME)" THEN staffdeductions.FinalAmount ELSE 0 END) as Petty_Cash_CME_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "Late" THEN staffdeductions.FinalAmount ELSE 0 END) as Late_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "ADVANCE SALARY DEDUCTION" THEN staffdeductions.FinalAmount ELSE 0 END) as Advance_Salary_Total'),

			DB::raw('SUM(CASE WHEN staffdeductions.Type = "MAX CARD" THEN staffdeductions.FinalAmount ELSE 0 END) as MAX_CARD_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "NIOSH" THEN staffdeductions.FinalAmount ELSE 0 END) as NIOSH_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "CIDB CARD" THEN staffdeductions.FinalAmount ELSE 0 END) as CIDB_Total'),

			DB::raw('SUM(CASE WHEN staffdeductions.Type = "LOSS OF EQUIPMENT" THEN staffdeductions.FinalAmount ELSE 0 END) as LOSS_OF_EQ_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "PETTY CASH FION" THEN staffdeductions.FinalAmount ELSE 0 END) as Fion_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "PAY BACK TO ERIC" THEN staffdeductions.FinalAmount ELSE 0 END) as Eric_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "DRIVING LICENSE DEDUCTION" THEN staffdeductions.FinalAmount ELSE 0 END) as License_Total'),
			DB::raw('SUM(CASE WHEN staffdeductions.Type = "Not In Radius" THEN staffdeductions.FinalAmount ELSE 0 END) as Not_In_Radius'),

			DB::raw('SUM(staffdeductions.FinalAmount) as Total')
		)
		->where(DB::raw('str_to_date(staffdeductions.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(staffdeductions.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->where('users.Id', $id)
		->orderBy('staffdeductions.Date')
		->first();

		$availableMonth = collect($record)->unique('Month')->pluck('Month')->toArray();

		$options = DB::table('options')->where('Field', 'Expenses_Type')->get();

		return view('staffdeductionsrecord', ['me' => $me,'record' => $record,'user'=>$user, 'options' => $options, 'year' => $year, 'start' => $start, 'end' => $end, 'availableMonth' => $availableMonth, 'total' => $total]);

	}



		public function staffexpensesrecord($id,$start = null,$end = null)
		{
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

			$user = DB::table('users')
			->where('Id', '=',$id)
			->first();

			$record = DB::table('staffexpenses')
			->select('staffexpenses.Id','staffexpenses.UserId','staffexpenses.Type','staffexpenses.Year','staffexpenses.Date','staffexpenses.Amount','users.Name as Created_By','staffexpenses.created_at','staffexpenses.updated_at')
			->leftJoin('users','staffexpenses.UserId','=','users.Id')
			->leftJoin('users as creator','staffexpenses.created_by','=','creator.Id')
			->where(DB::raw('str_to_date(staffexpenses.Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
			->where(DB::raw('str_to_date(staffexpenses.Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
			->orderBy('staffexpenses.Date')
			->get();

			// dd($record);
			$options = DB::table('options')->where('Field', 'Expenses_Type')->get();

			return view('staffexpensesrecord', ['me' => $me,'record' => $record,'user'=>$user, 'options' => $options, 'year' => $year, 'start' => $start, 'end' => $end]);

		}

		public function presaving($year=null)
		{
			$me = (new CommonController)->get_current_user();

			if($year==null)
			{
				$year=date("Y");
			}

			$presaving = DB::table('presaving')
			->select('presaving.Id','users.StaffId as Staff_ID','users.Name','users.Department','users.Position','users.Joining_Date','users.Nationality','users.Passport_No','presaving.Presaving_Scheme','presaving.Presaving_Start_On','presaving.Presaving_End_Date','presaving.Presaving_Monthly_Amount',DB::raw('"" AS Total_Saving'),DB::raw('"" AS Total_Withdraw'),
			DB::raw('"" As CarryForward'),
			DB::raw('"" AS Total_Balance'),'presaving.created_at','presaving.updated_at','creator.Name as Creator')
			->leftJoin('users','presaving.UserId','=','users.Id')
			->leftJoin('users as creator','presaving.created_by','=','creator.Id')
			->orderBy('users.Name')
			->get();

			$users = DB::table('users')
			->get();

			$users = collect($users)->groupBy('Department');

			return view('presaving', ['me' => $me,'presaving' => $presaving,'users'=>$users,'year'=>$year]);

		}

		public function presavingrecord($id,$year)
		{
			$me = (new CommonController)->get_current_user();

			$presaving = DB::table('presaving')
			->select('presaving.Id','users.StaffId as Staff_ID','users.Name','users.Department','users.Position','users.Joining_Date','users.Nationality','users.Passport_No','presaving.Presaving_Scheme','presaving.Presaving_Start_On',DB::raw('"" AS Total_Saving'),'presaving.created_at','presaving.updated_at')
			->leftJoin('users','presaving.UserId','=','users.Id')
			->where('presaving.Id', '=',$id)
			->orderBy('users.Name')
			->first();

			$total = DB::table('presaving')
			->select('a.Total1','b.Total2','c.Total3','d.Total4')
			->leftJoin(DB::RAW('(SELECT SUM(Amount)as Total1,PresavingId FROM presavingrecords WHERE Type="Saving" and presavingrecords.Payment_Date like "%'.$year.'%" GROUP BY PresavingId) a'), 'presaving.Id', '=', 'a.PresavingId')
      ->leftJoin(DB::RAW('(SELECT SUM(Amount)as Total2,PresavingId FROM presavingrecords WHERE Type="Withdraw" and presavingrecords.Payment_Date like "%'.$year.'%" GROUP BY PresavingId) b'), 'presaving.Id', '=', 'b.PresavingId')
			->leftJoin(DB::RAW('(SELECT SUM(Amount)as Total3,PresavingId FROM presavingrecords WHERE Type="Saving" and year(str_to_date(payment_date,"%d-%M-%Y")) < '.$year.' GROUP BY PresavingId) c'), 'presaving.Id', '=', 'c.PresavingId')
      ->leftJoin(DB::RAW('(SELECT SUM(Amount)as Total4,PresavingId FROM presavingrecords WHERE Type="Withdraw" and year(str_to_date(payment_date,"%d-%M-%Y")) < '.$year.' GROUP BY PresavingId) d'), 'presaving.Id', '=', 'd.PresavingId')
			->where('presaving.Id', '=',$id)
			->leftJoin('users','presaving.UserId','=','users.Id')
			->orderBy('users.Name')
			->first();

			if($total)
			{
				$carryforward=$total->Total3-$total->Total4;
			}
			else {
				// code...
				$carryforward=0;
			}


			$savings = DB::table('presavingrecords')
			->select('presavingrecords.Id','presavingrecords.PresavingId','presavingrecords.Type','presavingrecords.Payment_Date','presavingrecords.Amount','presavingrecords.Reason','presavingrecords.created_at','presavingrecords.updated_at','created_by')
			->leftJoin('users','presavingrecords.created_by','=','users.Id')
			->where('presavingrecords.PresavingId', '=',$id)
			->orderBy('presavingrecords.Payment_Date')
			->get();

			return view('presavingrecord', ['me' => $me,'presaving'=>$presaving,'savings' => $savings,'id'=>$id,'year'=>$year,'carryforward'=>$carryforward]);

		}

		public function staffloanold()
	{
		$me = (new CommonController)->get_current_user();

		$loans = DB::table('staffloan')
		->select('staffloan.Id','users.StaffId as Staff_ID','users.Name','users.Department','staffloan.Reason','staffloan.Date_Approved','staffloan.Repayment_Start_On','staffloan.Amount',DB::raw('"" AS Total_Paid'),DB::raw('"" AS Total_Paid_Month'),DB::raw('"" AS Outstanding_Balance'),'staffloan.created_at','staffloan.updated_at')
		->leftJoin('users','staffloan.UserId','=','users.Id')
		->orderBy('users.Name')
		->get();

		$users = DB::table('users')
		->get();

    return view('staffloanold', ['me' => $me,'loans' => $loans,'users'=>$users]);

	}

	public function staffloan($end=null)
	{
		$me = (new CommonController)->get_current_user();
		if ($end==null)
		{
			$end=date('d-M-Y', strtotime('last day of this month'));
			// $end = date('d-M-Y', strtotime($end . " +19 days"));
		}
		$loans = DB::table('staffloans')
		->select('staffloans.Id','users.StaffId as Staff_ID','users.Name','users.Department','staffloans.Purpose','staffloans.Date','staffloanstatuses.update_at','staffloans.Total_Requested','staffloans.Total_Approved',DB::raw('"" as Total_Paid'),DB::raw('"" as Outstanding'),'staffloans.Approver','staffloans.created_at','staffloans.updated_at','us.StaffId as USStaff_ID','us.Name')
		->leftJoin('users','staffloans.UserId','=','users.Id')
		->leftJoin( DB::raw('(select Max(Id) as maxid,StaffLoanId from staffloanstatuses Group By StaffLoanId) as max'), 'max.StaffLoanId', '=', 'staffloans.Id')
		->leftJoin('staffloanstatuses','max.maxid','=','staffloanstatuses.Id')
		->where(DB::raw("str_to_date(staffloans.Date,'%d-%M-%Y')"),"str_to_date('".$end."','%d-%M-%Y')")
		->leftJoin('users as us','us.Id','=','staffloanstatuses.UserId')

		->orderBy('users.Name')
		->get();

		$users = DB::table('users')
		->get();

    	return view('staffloan', ['me' => $me,'loans' => $loans,'users'=>$users,'end'=>$end]);

	}

	public function staffloan2($end=null)
		{
			$me=(new CommonController)->get_current_user();

			// if ($start==null)
			// {
			// 	$start=date('d-M-Y', strtotime('first day of last month'));
			// 	// $start=date('d-M-Y', strtotime($start,' +16 days'));
			// 	$start = date('d-M-Y', strtotime($start . " +20 days"));
			// }
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
			->whereRaw("str_to_date('".$end."','%d-%M-%Y')")
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
			->where('staffloanstatuses.Status','=','Final Approved')
			->get();

			$users = DB::table('users')->where('active', 1)->get();
			$users_depts = collect($users)->groupBy('Department')->all();
			return view('staffloan2',['me'=>$me, 'end'=>$end,'staffloans'=>$staffloans,'all'=>$all, 'allfinal'=>$allfinal]);
		}

		public function loanrecord($id)
		{
			$me = (new CommonController)->get_current_user();

			$staffloan = DB::table('staffloan')
			->select('staffloan.Id','users.StaffId as Staff_ID','users.Name','users.Department','staffloan.Reason','staffloan.Date_Approved','staffloan.Repayment_Start_On','staffloan.Amount',DB::raw('"" AS Total_Paid'),DB::raw('"" AS Outstanding_Balance'),'staffloan.created_at','staffloan.updated_at')
			->leftJoin('users','staffloan.UserId','=','users.Id')
			->where('staffloan.Id', '=',$id)
			->orderBy('users.Name')
			->first();

			$loans = DB::table('repaymentrecords')
			->select('repaymentrecords.Id','repaymentrecords.StaffLoanId','repaymentrecords.Payment_Date','repaymentrecords.Amount','repaymentrecords.created_at','repaymentrecords.updated_at')
			->where('repaymentrecords.StaffLoanId', '=',$id)
			->orderBy('repaymentrecords.Payment_Date')
			->get();

			return view('loanrecord', ['me' => $me,'staffloan'=>$staffloan,'loans' => $loans,'id'=>$id]);

		}

	function randomPassword() {
		$alphabet = 'abcdefghijklmnopqrstuvwxyz`~!@#$%^&*()_+-={}|[]:";<>?,.ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) {
				$n = rand(0, $alphaLength);
				$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
}

	public function approved(Request $request)
	{
		$me = (new CommonController)->get_current_user();

		$input = $request->all();
		$emaillist=array();
		array_push($emaillist,$me->UserId);
		array_push($emaillist,$input["UserId"]);

		$subscribers = DB::table('notificationtype')
		->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
		->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
		->where('notificationtype.Id','=',35)
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

		$notify = DB::table('users')
		->whereIn('Id', $emaillist)
		->get();

		$password=$this->randomPassword();

		DB::table('users')
					->where('Id', $input["UserId"])
					->update(array(
					'Status' =>  'Account Approved',
					'Password' => bcrypt($password)
				));

		DB::table('userability')
					->insert(array(
					'UserId' => $input["UserId"]
				));

		DB::table('userprojects')
					->insert(array(
					'UserId' => $input["UserId"]
				));

		$approveduser = DB::table('users')
		->where('Id', "=",$input["UserId"])
		->first();

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

		Mail::send('emails.accountapproved', ['me'=>$me,'user'=>$approveduser,'password'=>$password], function($message) use ($emails,$approveduser,$NotificationSubject)
		{
				$emails = array_filter($emails);
				array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
				$message->to($emails)->subject($NotificationSubject.' ['.$approveduser->Name.']');
		});

		return 1;
	}

	public function contractor()
	{
		$me = (new CommonController)->get_current_user();
		$contractors = DB::table('users')->select('users.Id','users.StaffId as Staff_ID','users.Name','users.Company','users.Company_Email')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->leftJoin('users as superior','superior.Id','=','users.SuperiorId')
		->where('users.User_Type','=','Client')
		->orderBy('users.Id')
		->get();

		$options= DB::table('options')
		->whereIn('Table', ["users"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		$company = DB::table('companies')
		->select('Company_Name')
		->where('Client','Yes')
		->get();

		return view('contractor', ['me' => $me,'contractors' =>$contractors,'options' =>$options,'company'=>$company]);
//		return view('Staff');
	}

	public function contractorcreate(Request $request)
	{
		$input = $request->all();

		$rules = [
            'name' => 'required|min:4',
            'staffid' => 'required|unique:users',
            'password' => 'required',
            'company' => 'required',
            'email' => 'required',
        ];

        $messages = [
        	'name.required' => "The name field is required",
        	'staffid.required' => "The staffid field is required",
        	'password.required' => "The password field is required",
        	'company.required' => "The company field is required",
        	'email.required' => "The email field is required"
        ];

		$validator = Validator::make($input, $rules,$messages);
		if ($validator->passes()) {
			DB::table('users')
			->insert([
				'StaffId'	 	=> $input['staffid'],
				'Name' 		 	=> $input['name'],
				'Password' 	 	=> bcrypt($input["password"]),
				'Company'    	=> $input['company'],
				'Company_Email' => $input['email'],
				'User_Type' 	=> "Client",
				'Active' 		=> 1
			]);

			return 1;
		}
		// return 0;
		return json_encode($validator->errors()->all());

	}

	public function changepassword(Request $request)
	{
		$input = $request->all();
		return DB::table('users')
					->where('Id', $input["UserId"])
					->update(array(
					'Password' =>  bcrypt($input["Password"]),
					'First_Change' => 1
				));
//		return view('Staff');
	}
	public function logout(Request $request)
	{
		$input = $request->all();

		$user = DB::table('users')
		->where('StaffId', '=',$input["StaffID"])
		->where('Admin', '=',1)
		->first();

		if($user)
		{
			if(Hash::check($input["Password"], $user->Password))
			{
				return 1;
			}
			else {
				# code...
				return 0;
			}
		}
		else {
			# code...
			return 0;
		}

	}
	public function changepassword2(Request $request)
	{
		$input = $request->all();
		$user = DB::table('users')
		->select('Password')
		->where('Id', '=',$input["UserId"])
		->first();
		if (Hash::check($input["CurrentPassword"], $user->Password))
		{
			return DB::table('users')
						->where('Id', $input["UserId"])
						->update(array(
						'Password' =>  bcrypt($input["Password"])
					));
		}
		else {
			return 0;
		}
	}
	public function changepassword3(Request $request)
	{
		$input = $request->all();
		$user = DB::table('users')
		->select('Password')
		->where('Id', '=',$input["UserId"])
		->first();
			return DB::table('users')
						->where('Id', $input["UserId"])
						->update(array(
						'Password' =>  bcrypt($input["Password"])
					));
	}

	public function updateprofile(Request $request)
	{
		$me = (new CommonController)->get_current_user();

		// $user = DB::table('users')->select('users.Id','users.StaffId as Staff_ID','Name','Nick_Name','Password','User_Type','Company_Email','Personal_Email','Contact_No_1','Contact_No_2','Nationality','Permanent_Address','Current_Address','Country_Base','Home_Base','DOB','NRIC','Passport_No','Gender','Marital_Status','Department','Position','superior.Id as SuperiorId','superior.Name as Superior','Joining_Date','Resignation_Date','Emergency_Contact_Person',
		// 'Emergency_Contact_No','Emergency_Contact_Relationship','Emergency_Contact_Address','files.Web_Path','users.Detail_Approved_On','users.Status','users.Comment')
		// // ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
		// ->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
    // ->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		// ->leftJoin('users as superior','superior.Id','=','users.SuperiorId')
		// ->where('users.Id', '=', $me->UserId)
		// ->first();

		$user = DB::table('users')->select('users.Id','users.StaffId as Staff_ID','users.Name','users.Nick_Name','users.Password','users.User_Type','users.Company_Email','users.Personal_Email','users.Contact_No_1','users.Contact_No_2','users.Nationality','users.Permanent_Address','users.Current_Address','users.Country_Base','users.Home_Base','users.DOB','users.Place_Of_Birth','users.Race','users.Religion','users.Bank_Name','users.Bank_Account_No','users.Acc_Holder_Name','users.EPF_No','users.SOCSO_No','users.Income_Tax_No','users.Driving_License','users.Car_Owner','users.Criminal_Activity',
		'users.Team','users.NRIC','users.Passport_No','users.Union_No','users.Gender','users.Marital_Status','users.Department','users.Category','users.Entitled_for_OT','users.Working_Days','users.Position','users.Grade','superior.Id as SuperiorId','superior.Name as Superior','users.Joining_Date','users.Confirmation_Date','users.Resignation_Date','users.Emergency_Contact_Person',
		'users.Emergency_Contact_No','users.Emergency_Contact_Relationship','users.Emergency_Contact_Person_2','users.Emergency_Contact_No_2','users.Emergency_Contact_Relationship_2','users.Emergency_Contact_Address','files.Web_Path','users.Detail_Approved_On','users.Status','users.Comment','users.House_Phone_No','users.ticket_team','users.team_leader')
		// ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
	    ->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->leftJoin('users as superior','superior.Id','=','users.SuperiorId')
		->where('users.Id', '=', $me->UserId)
		->first();

		$subscribers = DB::table('notificationtype')
		->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
		->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
		->where('notificationtype.Id','=',36)
		->get();

		$input = $request->all();
		$changedcolumn="";

		$ticket_team = "";

		if(isset($input['ticket_team']))
		{
			if($input['ticket_team'] != "")
			{
				foreach($input['ticket_team'] as $tk => $tv)
				{
					$ticket_team .= $tv.",";
				}

			}
		}

		if(isset($input['ticket_team']))
		{
			$team_leader = "";
			if($input['team_leader'] != "")
			{
				foreach($input['team_leader'] as $tk => $tv)
				{
					$team_leader .= $tv.",";
				}

			}
		}

		foreach($input as $name => $value)
		{
			if (property_exists($user,$name))
			{

				if ($user->$name!=$input[$name])
				{
						$changedcolumn.=$name.",";
				}

			}

		}

		$emails = array();

		array_push($emails,$me->Company_Email);
		array_push($emails,$me->Personal_Email);

		$NotificationSubject="";


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

		$input = $request->all();


		if ($me->UserId==$input["UserId"])
		{

			$result= DB::table('users')
						->where('Id', $input["UserId"])
						->update(array(
							"Name" => $input["Name"],
							"StaffId" => $input["StaffId"],
							// "User_Type" => $input["User_Type"],
							// "Nick_Name" => $input["Nick_Name"],
							"Company_Email" => $input["Company_Email"],
							"Personal_Email" => $input["Personal_Email"],
							"Contact_No_1" => $input["Contact_No_1"],
							"Contact_No_2" => $input["Contact_No_2"],
							"Nationality" => $input["Nationality"],
							"House_Phone_No" => $input["House_Phone_No"],
							// "Home_Base" => $input["Home_Base"],
							// "Country_Base" => $input["Country_Base"],
							"Gender" => $input["Gender"],
							"Marital_Status" => $input["Marital_Status"],
							"Permanent_Address" => $input["Permanent_Address"],
							// "Current_Address" => $input["Current_Address"],
							"DOB" => $input["DOB"],
							"Place_Of_Birth" => $input["Place_Of_Birth"],
							"Race" => $input["Race"],
							"Religion" => $input["Religion"],
							"Bank_Name" => $input["Bank_Name"],
							"Bank_Account_No" => $input["Bank_Account_No"],
							"Acc_Holder_Name" => $input["Acc_Holder_Name"],
							"EPF_No" => $input["EPF_No"],
							"SOCSO_No" => $input["SOCSO_No"],
							"Income_Tax_No" => $input["Income_Tax_No"],
							// "Driving_License" => $input["Driving_License"],
							// "Car_Owner" => $input["Car_Owner"],
							// "Criminal_Activity" => $input["Criminal_Activity"],
							// "Team" => $input["Team"],
							"NRIC" => $input["NRIC"],
							"Passport_No" => $input["Passport_No"],
							"Union_No" => $input["Union_No"],
							"Company" => $input["Company"],
							"Department" => $input["Department"],
							"Category" => $input["Category"],
							// "Entitled_for_OT" => $input["Entitled_for_OT"],
							"Position" => $input["Position"],
							// "Grade" => $input["Grade"],
							// "SuperiorId" => $input["Superior"],
							// "Joining_Date" => $input["Joining_Date"],
							// "Resignation_Date" => $input["Resignation_Date"],
							"Emergency_Contact_Person" => $input["Emergency_Contact_Person"],
							"Emergency_Contact_Relationship" => $input["Emergency_Contact_Relationship"],
							"Emergency_Contact_No" => $input["Emergency_Contact_No"],
							"Emergency_Contact_Person_2" => $input["Emergency_Contact_Person_2"],
							"Emergency_Contact_Relationship_2" => $input["Emergency_Contact_Relationship_2"],
							"Emergency_Contact_No_2" => $input["Emergency_Contact_No_2"],
							// "ticket_team" => $ticket_team,
							// "team_leader" => $team_leader,
							// "Emergency_Contact_Address" => $input["Emergency_Contact_Address"],
							"Status" => "Pending Account Detail Approval"
					));

					$insert=DB::table('profileupdates')->insertGetId(
						['UserId' => $input["UserId"],
						 'Changes' => $changedcolumn
						]
					);

					if ($result)
					{

						if ($input["Company_Email"]!="")
						{
							array_push($emails,$input["Company_Email"]);
						}

						if ($input["Personal_Email"]!="")
						{
							array_push($emails,$input["Personal_Email"]);
						}

							$notify = DB::table('users')
							->where('Admin', "=",1)
							->get();

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

						if ($me->UserId==$input["UserId"])
						{
							Mail::send('emails.accountdetailpendingapproval', ['detail'=>$input], function($message) use ($emails,$input,$NotificationSubject)
							{

									$emails = array_filter($emails);
									array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
									$message->to($emails)->subject($NotificationSubject.' ['.$input["Name"].']');

							});
						}


					}

		}
		else {

			$result= DB::table('users')
						->where('Id', $input["UserId"])
						->update(array(
							"Name" => $input["Name"],
							"StaffId" => $input["StaffId"],
							// "User_Type" => $input["User_Type"],
							// "Nick_Name" => $input["Nick_Name"],
							"Company_Email" => $input["Company_Email"],
							"Personal_Email" => $input["Personal_Email"],
							"Contact_No_1" => $input["Contact_No_1"],
							"Contact_No_2" => $input["Contact_No_2"],
							"Nationality" => $input["Nationality"],
							// "Home_Base" => $input["Home_Base"],
							// "Country_Base" => $input["Country_Base"],
							"Gender" => $input["Gender"],
							"Marital_Status" => $input["Marital_Status"],
							"Permanent_Address" => $input["Permanent_Address"],
							// "Current_Address" => $input["Current_Address"],
							"DOB" => $input["DOB"],
							"Place_Of_Birth" => $input["Place_Of_Birth"],
							"Race" => $input["Race"],
							"Religion" => $input["Religion"],
							"Bank_Name" => $input["Bank_Name"],
							"Bank_Account_No" => $input["Bank_Account_No"],
							"Acc_Holder_Name" => $input["Acc_Holder_Name"],
							"EPF_No" => $input["EPF_No"],
							"SOCSO_No" => $input["SOCSO_No"],
							"Income_Tax_No" => $input["Income_Tax_No"],
							// "Driving_License" => $input["Driving_License"],
							// "Car_Owner" => $input["Car_Owner"],
							// "Criminal_Activity" => $input["Criminal_Activity"],
							// "Team" => $input["Team"],
							"NRIC" => $input["NRIC"],
							"Passport_No" => $input["Passport_No"],
							"Company" => $input["Company"],
							"Department" => $input["Department"],
							"Category" => $input["Category"],
							"Entitled_for_OT" => $input["Entitled_for_OT"],
							"Working_Days" => $input["Working_Days"],
							"Position" => $input["Position"],
							"Grade" => $input["Grade"],
							// "SuperiorId" => $input["Superior"],
							"Joining_Date" => $input["Joining_Date"],
							"Confirmation_Date" => $input["Confirmation_Date"],
							"Resignation_Date" => $input["Resignation_Date"],
							"Emergency_Contact_Person" => $input["Emergency_Contact_Person"],
							"Emergency_Contact_Relationship" => $input["Emergency_Contact_Relationship"],
							"Emergency_Contact_No" => $input["Emergency_Contact_No"],
							"Emergency_Contact_Person_2" => $input["Emergency_Contact_Person_2"],
							"Emergency_Contact_Relationship_2" => $input["Emergency_Contact_Relationship_2"],
							"Emergency_Contact_No_2" => $input["Emergency_Contact_No_2"],
							"HolidayTerritoryId" => $input["HolidayTerritoryId"],
							'ticket_team' => $ticket_team,
							'team_leader' => $team_leader

					));

				if(isset($input["Resignation_Date"]) && $input["Resignation_Date"] != '') {
					$resignationDate = strtotime($input["Resignation_Date"]);
					$today = strtotime(date("d-M-Y"));
					if ($resignationDate <= $today) {
						$result= DB::table('users')
									->where('Id', $input["UserId"])
									->update(array('Active' => 0));
					}
				}

		}

				return $result;

//		return view('Staff');
	}

	public function approveprofile(Request $request)
	{
		$me = (new CommonController)->get_current_user();
		$emails = array();

		array_push($emails,$me->Company_Email);
		array_push($emails,$me->Personal_Email);

		$input = $request->all();

		$result= DB::table('users')
					->where('Id', $input["UserId"])
					->update(array(
						"Detail_Approved_On" => DB::raw("now()"),
						"Comment" => "",
						"Status" => "Account Detail Approved"
				));

		$targetuser = DB::table('users')->select('users.Id','users.StaffId as Staff_ID','Name','Nick_Name','Password','User_Type','Company_Email','Personal_Email','Contact_No_1','Contact_No_2','Nationality','Permanent_Address','Current_Address','Country_Base','Home_Base','DOB','Place_Of_Birth','Race','Religion','Bank_Name','Bank_Account_No','Acc_Holder_Name','Driving_License','Car_Owner','Criminal_Activity','Team','Income_Tax_No','EPF_No','SOCSO_No','NRIC','Passport_No','Gender','Marital_Status','Department','Working_Days','Position','Grade','Joining_Date','Resignation_Date','Emergency_Contact_Person',
		'Emergency_Contact_No','Emergency_Contact_Relationship','Emergency_Contact_Address','files.Web_Path','Detail_Approved_On','users.Status')
		// ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
    ->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->where('users.Id', '=', $input["UserId"])
		->first();

		$subscribers = DB::table('notificationtype')
		->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
		->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
		->where('notificationtype.Id','=',37)
		->get();


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

				if ($result)
				{

					$result2= DB::table('profileupdates')
								->where('UserId', $input["UserId"])
								->where('Status', "")
								->update(array(
									"Status" => "Approved"
							));

					if ($input["Company_Email"]!="")
					{
						array_push($emails,$input["Company_Email"]);
					}

					if ($input["Personal_Email"]!="")
					{
						array_push($emails,$input["Personal_Email"]);
					}

						$notify = DB::table('users')
						->where('Admin', "=",1)
						->get();

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

					Mail::send('emails.accountdetailapproved', ['user'=>$targetuser], function($message) use ($emails,$targetuser,$NotificationSubject)
					{
							$emails = array_filter($emails);
							array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
							$message->to($emails)->subject($NotificationSubject.' ['.$targetuser->Name.']');
					});

				}

				return $result;
//		return view('Staff');
	}

	public function rejectprofile(Request $request)
	{
		$me = (new CommonController)->get_current_user();
		$emails = array();

		array_push($emails,$me->Company_Email);
		array_push($emails,$me->Personal_Email);

		$input = $request->all();

		$result= DB::table('users')
					->where('Id', $input["UserId"])
					->update(array(
						"Detail_Approved_On" => "",
						"Status" => "Account Detail Rejected",
						"Comment" => $input["Comment"]
				));

		$targetuser = DB::table('users')->select('users.Id','users.StaffId as Staff_ID','Name','Nick_Name','Password','User_Type','Company_Email','Personal_Email','Contact_No_1','Contact_No_2','Nationality','Permanent_Address','Current_Address','Country_Base','Home_Base','DOB','Place_Of_Birth','Race','Religion','Bank_Name','Bank_Account_No','Acc_Holder_Name','EPF_No','SOCSO_No','Income_Tax_No','Driving_License','Car_Owner','Criminal_Activity','users.Team','NRIC','Passport_No','Gender','Marital_Status','Department','Working_Days','Position','Grade','Joining_Date','Resignation_Date','Emergency_Contact_Person',
		'Emergency_Contact_No','Emergency_Contact_Relationship','Emergency_Contact_Address','files.Web_Path','Detail_Approved_On','users.Status')
		// ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
    	->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->where('users.Id', '=', $input["UserId"])
		->first();

		$subscribers = DB::table('notificationtype')
		->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
		->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
		->where('notificationtype.Id','=',38)
		->get();



				if ($result)
				{

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


					if ($input["Company_Email"]!="")
					{
						array_push($emails,$input["Company_Email"]);
					}

					if ($input["Personal_Email"]!="")
					{
						array_push($emails,$input["Personal_Email"]);
					}

						$notify = DB::table('users')
						->where('Admin', "=",1)
						->get();

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

					Mail::send('emails.accountdetailrejected', ['user'=>$targetuser,'comment'=>$input["Comment"]], function($message) use ($emails,$targetuser,$NotificationSubject)
					{
							$emails = array_filter($emails);
							array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
							$message->to($emails)->subject($NotificationSubject.'['.$targetuser->Name.']');
					});

				}

				return $result;
//		return view('Staff');
	}

	public function updatecontractorprofile(Request $request)
	{
		$input = $request->all();
		return DB::table('users')
					->where('Id', $input["UserId"])
					->update(array(
						"Name" => $input["Name"],
						"Company" => $input["Company"],
						"Company_Email" => $input["Company_Email"],
						"Personal_Email" => $input["Personal_Email"],
						"Contact_No_1" => $input["Contact_No_1"],
						"Contact_No_2" => $input["Contact_No_2"],
						"Nationality" => $input["Nationality"],
						"Gender" => $input["Gender"],
						"Permanent_Address" => $input["Permanent_Address"],
						"Current_Address" => $input["Current_Address"],
						"NRIC" => $input["NRIC"],
						"Passport_No" => $input["Passport_No"]
				));
//		return view('Staff');
	}
	public function updateprofilepicture(Request $request)
	{
		$input = $request->all();
		$insertid=$input["UserId"];
		$type="User";
		$uploadcount=1;
			//$file = Input::file('profilepicture');
			if ($request->hasFile('profilepicture')) {
				$file = $request->file('profilepicture');
				$destinationPath=public_path()."/private/upload/User";
				$extension = $file->getClientOriginalExtension();
				$originalName=$file->getClientOriginalName();
				$fileSize=$file->getSize();
				$fileName=time()."_".$uploadcount.".".$extension;
				$upload_success = $file->move($destinationPath, $fileName);
				DB::table('files')->insert(
					['Type' => $type,
					 'TargetId' => $insertid,
					 'File_Name' => $originalName,
					 'File_Size' => $fileSize,
					 'Web_Path' => '/private/upload/User/'.$fileName
					]
				);
				return url('/private/upload/User/'.$fileName);
				//return '/private/upload/'.$fileName;
			}
			else {
				return 0;
			}
	}
	public function userdetail($Id)
	{
		$me = (new CommonController)->get_current_user();


		$user = DB::table('users')->select('users.Id','users.StaffId as Staff_ID','users.Name','users.Nick_Name','users.Password','users.User_Type','users.Company_Email','users.Personal_Email','users.Contact_No_1','users.Contact_No_2','users.Nationality','users.Permanent_Address','users.Current_Address','users.Country_Base','users.Home_Base','users.DOB','users.Place_Of_Birth','users.Race','users.Religion','users.Bank_Name','users.Bank_Account_No','users.EPF_No','users.SOCSO_No','users.Income_Tax_No','users.Driving_License','users.Car_Owner','users.Criminal_Activity','users.Team','users.NRIC','users.Passport_No','users.Union_No','users.Gender','users.Marital_Status','users.Company','users.Department','users.Category','users.Entitled_for_OT','users.Working_Days','users.Position','users.Grade','superior.Id as SuperiorId','superior.Name as Superior','users.Joining_Date','users.Confirmation_Date','users.Resignation_Date','users.Emergency_Contact_Person',
		'users.Emergency_Contact_No','users.Emergency_Contact_Relationship','users.Emergency_Contact_Person_2','users.Emergency_Contact_No_2','users.Emergency_Contact_Relationship_2','users.Emergency_Contact_Address','files.Web_Path','users.Detail_Approved_On','users.Status','users.Comment','users.House_Phone_No','users.Company', 'users.HolidayTerritoryId','users.ticket_team','users.team_leader','users.Acc_Holder_Name')
		// ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
    	->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->leftJoin('users as superior','superior.Id','=','users.SuperiorId')
		->where('users.Id', '=', $Id)
		->first();

		$superior= DB::table('users')
		->where('Active', '=','1')
		->where('Approved', '=','1')
		->orderBy('users.Name','ASC')
		->get();

		$changes= DB::table('profileupdates')
		->where('UserId', '=', $Id)
		->where('Status', '=', '')
		->get();

		$changed="";

		foreach ($changes as $change) {
			# code...
			$changed.=$change->Changes.",";

		}

		$resumes = DB::table('files')
		->where('TargetId', '=', $Id)
		->where('Type', '=', 'Resume')
		->get();

		$employmenthistories = DB::table('employmenthistories')->select('employmenthistories.Id','employmenthistories.Company','employmenthistories.Company_Address','employmenthistories.Company_Contact_No','employmenthistories.Start_Date','employmenthistories.End_Date','employmenthistories.Position','employmenthistories.Supervisor','employmenthistories.Remarks','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Employment History" Group By TargetId) as max'), 'max.TargetId', '=', 'employmenthistories.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Employment History"'))
		->where('employmenthistories.UserId', '=', $user->Id)
		->orderBy('employmenthistories.Id','desc')
		->get();
		$experiences = DB::table('experiences')->select('experiences.Id','Project','Role','Responsibility','Achievement','Start_Date','End_Date','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Experience" Group By TargetId) as max'), 'max.TargetId', '=', 'experiences.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Experience"'))
		->where('experiences.UserId', '=', $user->Id)
		->orderBy('experiences.Id','desc')
		->get();
		$licenses = DB::table('licenses')->select('licenses.Id','License_Type','Identity_No','Issue_Date','Expiry_Date','License_Status','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="License" Group By TargetId) as max'), 'max.TargetId', '=', 'licenses.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="License"'))
		->where('licenses.UserId', '=', $user->Id)
		->orderBy('licenses.Id','desc')
		->get();
		$qualifications = DB::table('qualifications')->select('qualifications.Id','Institution','Major','Qualification_Level','Start_Date','End_Date','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Qualification" Group By TargetId) as max'), 'max.TargetId', '=', 'qualifications.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Qualification"'))
		->where('qualifications.UserId', '=', $user->Id)
		->orderBy('qualifications.Id','desc')
		->get();
		$references = DB::table('references')->select('references.Id','Reference','Contact_No','Company','Position','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Reference" Group By TargetId) as max'), 'max.TargetId', '=', 'references.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Reference"'))
		->where('references.UserId', '=', $user->Id)
		->orderBy('references.Id','desc')
		->get();
		$skills = DB::table('skills')->select('skills.Id','Skill','Level','Description','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Skill" Group By TargetId) as max'), 'max.TargetId', '=', 'skills.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Skill"'))
		->where('skills.UserId', '=', $user->Id)
		->orderBy('skills.Id','desc')
		->get();
		$trainings = DB::table('trainings')->select('trainings.Id','Training','Description','Organizer','Training_Date','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Training" Group By TargetId) as max'), 'max.TargetId', '=', 'trainings.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Training"'))
		->where('trainings.UserId', '=', $user->Id)
		->orderBy('trainings.Id','desc')
		->get();
		$certificates = DB::table('certificates')->select('certificates.Id','certificates.Certificate','certificates.Certificate_Date','certificates.Valid_Until','certificates.Description','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Certificate" Group By TargetId) as max'), 'max.TargetId', '=', 'certificates.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Certificate"'))
		->where('certificates.UserId', '=', $user->Id)
		->orderBy('certificates.Id','desc')
		->get();
		$salary = DB::table('salary')->select('salary.Id','salary.Salary','salary.Remarks','users.Name','salary.created_at as Adjustment_Date')
		->leftJoin('users','users.Id','=','salary.Created_By')
		->where('salary.UserId', '=', $user->Id)
		->orderBy('salary.Id','desc')
		->get();
		$review = DB::table('reviews')->select('reviews.Id','reviews.Status','reviews.Remarks','reviews.created_at as Reviewed_Date','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Review" Group By TargetId) as max'), 'max.TargetId', '=', 'reviews.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Review"'))
		->where('reviews.UserId', '=', $user->Id)
		->orderBy('reviews.Id','desc')
		->get();
		$family = DB::table('family')->select('family.Id','family.Name','family.NRIC','family.Gender','family.Age','family.Relationship','family.Occupation','family.Company_School_Name','family.Contact_No')
		->where('family.UserId', '=', $user->Id)
		->orderBy('family.Id','desc')
		->get();
		$language = DB::table('languages')->select('languages.Id','languages.Language','languages.Speak','languages.Written')
		->where('languages.UserId', '=', $user->Id)
		->orderBy('languages.Id','desc')
		->get();
		$options= DB::table('options')
		->whereIn('Table', ["users","employmenthistories","experiences","qualifications","skills","licenses","trainings","references","languages"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		$teamuser = DB::table("users")
		->get();

		if ($user->Detail_Approved_On=="0000-00-00 00:00:00")
		{
			$interval="-1";

		}
		else {
			$datetime1 = new DateTime($user->Detail_Approved_On);
			$datetime2 = new DateTime(date("Y-m-d H:i:s"));

			$interval = date_diff($datetime1, $datetime2);
			$interval=$interval->format("%m");

		}

		$projects = DB::table('projects')
		->where('projects.Project_Name','like','%department%')
		->get();

		$holidayterritories = DB::table('holidayterritories')->select('holidayterritories.Id','holidayterritories.Name')->get();

		//kp add user team
		$ticket_team = DB::table('options')
		->where('Table','Genset')
		->where('Field','Ticket Type')
		->select('Option')
		->get();

		$team_leader = DB::table('options')
		->where('Table','Genset')
		->where('Field','Ticket Type')
		->select('Option')
		->get();

    return view('userdetail', ['UserId' => $Id , 'me' => $me, 'user' =>$user,'changes' =>$changed, 'resumes'=>$resumes,'employmenthistories' => $employmenthistories, 'experiences' => $experiences, 'licenses' => $licenses, 'qualifications' => $qualifications, 'references' => $references, 'skills' => $skills, 'trainings' => $trainings,'certificates' => $certificates,
		 'options' => $options,'interval' =>$interval,'superior' =>$superior,'salary'=>$salary,'review'=>$review,'family'=>$family,'language'=>$language, 'teamuser'=>$teamuser,'projects'=>$projects, 'holidayterritories' => $holidayterritories, 'ticket_team'=>$ticket_team, 'team_leader'=>$team_leader]);
//		return view('Staff');
	}
	public function contractordetail($Id)
	{
		$me = (new CommonController)->get_current_user();
		$user = DB::table('users')->select('users.Id','users.StaffId as Staff_ID','Name','Password','Company','User_Type','Company_Email','Personal_Email','Contact_No_1','Contact_No_2','Nationality','Permanent_Address','Current_Address','Country_Base','Home_Base','DOB','Place_Of_Birth','Race','Religion','Bank_Name','Bank_Account_No','EPF_No','SOCSO_No','Income_Tax_No','Driving_License','Car_Owner','Criminal_Activity','Team','NRIC','Passport_No','Gender','Marital_Status','Department','Position','Grade','Joining_Date','Resignation_Date','Emergency_Contact_Person',
		'Emergency_Contact_No','Emergency_Contact_Relationship','Emergency_Contact_Address','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
    ->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->where('users.Id', '=', $Id)
		->first();

		$documents = DB::table('files')
		->where('TargetId', '=', $Id)
		->where('Type', '=', 'Document')
		->get();

		$experiences = DB::table('experiences')->select('experiences.Id','Project','Role','Responsibility','Achievement','Start_Date','End_Date','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Experience" Group By TargetId) as max'), 'max.TargetId', '=', 'experiences.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Experience"'))
		->where('experiences.UserId', '=', $Id)
		->orderBy('experiences.Id','desc')
		->get();
		$licenses = DB::table('licenses')->select('licenses.Id','License_Type','Identity_No','Issue_Date','Expiry_Date','License_Status','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="License" Group By TargetId) as max'), 'max.TargetId', '=', 'licenses.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="License"'))
		->where('licenses.UserId', '=', $Id)
		->orderBy('licenses.Id','desc')
		->get();
		$references = DB::table('references')->select('references.Id','Reference','Contact_No','Company','Position','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Reference" Group By TargetId) as max'), 'max.TargetId', '=', 'references.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Reference"'))
		->where('references.UserId', '=', $Id)
		->orderBy('references.Id','desc')
		->get();
		$skills = DB::table('skills')->select('skills.Id','Skill','Level','Description','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Skill" Group By TargetId) as max'), 'max.TargetId', '=', 'skills.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Skill"'))
		->where('skills.UserId', '=', $Id)
		->orderBy('skills.Id','desc')
		->get();
		$options= DB::table('options')
		->whereIn('Table', ["users","employmenthistories","experiences","qualifications","skills","licenses","trainings","references"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();
    return view('contractordetail', ['UserId' => $Id , 'me' => $me, 'user' =>$user, 'documents'=>$documents,'experiences' => $experiences, 'licenses' => $licenses, 'references' => $references, 'skills' => $skills,
		 'options' => $options]);
//		return view('Staff');
	}
	public function myprofile()
	{
		$me = (new CommonController)->get_current_user();

		$user = DB::table('users')->select('users.Id','users.StaffId as Staff_ID','users.Name','users.Nick_Name','users.Password','users.User_Type','users.Company_Email','users.Personal_Email','users.Contact_No_1','users.Contact_No_2','users.Nationality','users.Permanent_Address','users.Current_Address','users.Country_Base','users.Home_Base','users.DOB','users.Place_Of_Birth','users.Race','users.Religion','users.Bank_Name','users.Bank_Account_No','users.Acc_Holder_Name','users.EPF_No','users.SOCSO_No','users.Income_Tax_No','users.Driving_License','users.Car_Owner','users.Criminal_Activity','users.Team','users.NRIC','users.Passport_No','users.Union_No','users.Gender','users.Marital_Status','users.Company','users.Department','users.Category','users.Entitled_for_OT','users.Working_Days','users.Position','users.Grade','superior.Id as SuperiorId','superior.Name as Superior','users.Joining_Date','users.Confirmation_Date','users.Resignation_Date','users.Emergency_Contact_Person',
		'users.Emergency_Contact_No','users.Emergency_Contact_Relationship','users.Emergency_Contact_Person_2','users.Emergency_Contact_No_2','users.Emergency_Contact_Relationship_2','users.Emergency_Contact_Address','files.Web_Path','users.Detail_Approved_On','users.Status','users.Comment','users.House_Phone_No','users.Company')
		// ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
    	->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->leftJoin('users as superior','superior.Id','=','users.SuperiorId')
		->where('users.Id', '=', $me->UserId)
		->first();

		$changes= DB::table('profileupdates')
		->where('UserId', '=', $me->UserId)
		->where('Status', '=', '')
		->get();

		$superior= DB::table('users')
		->where('Active', '=','1')
		->where('Approved', '=','1')
		->orderBy('users.Name','ASC')
		->get();

		$changed="";

		foreach ($changes as $change) {
			# code...
			$changed.=$change->Changes.",";

		}

		$resumes = DB::table('files')
		->where('TargetId', '=', $me->UserId)
		->where('Type', '=', 'Resume')
		->get();

		$employmenthistories = DB::table('employmenthistories')
		->select('employmenthistories.Id','employmenthistories.Company','employmenthistories.Company_Address','employmenthistories.Company_Contact_No','employmenthistories.Start_Date','employmenthistories.End_Date','employmenthistories.Position','employmenthistories.Supervisor','employmenthistories.Remarks','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Employment History" Group By TargetId) as max'), 'max.TargetId', '=', 'employmenthistories.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Employment History"'))
		->where('employmenthistories.UserId', '=', $me->UserId)
		->orderBy('employmenthistories.Id','desc')
		->get();
		$experiences = DB::table('experiences')->select('experiences.Id','Project','Role','Responsibility','Achievement','Start_Date','End_Date','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Experience" Group By TargetId) as max'), 'max.TargetId', '=', 'experiences.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Experience"'))
		->where('experiences.UserId', '=', $me->UserId)
		->orderBy('experiences.Id','desc')
		->get();
		$licenses = DB::table('licenses')->select('licenses.Id','License_Type','Identity_No','Issue_Date','Expiry_Date','License_Status','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="License" Group By TargetId) as max'), 'max.TargetId', '=', 'licenses.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="License"'))
		->where('licenses.UserId', '=', $me->UserId)
		->orderBy('licenses.Id','desc')
		->get();
		$qualifications = DB::table('qualifications')->select('qualifications.Id','Institution','Major','Qualification_Level','Start_Date','End_Date','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Qualification" Group By TargetId) as max'), 'max.TargetId', '=', 'qualifications.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Qualification"'))
		->where('qualifications.UserId', '=', $me->UserId)
		->orderBy('qualifications.Id','desc')
		->get();
		$references = DB::table('references')->select('references.Id','Reference','Contact_No','Company','Position','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Reference" Group By TargetId) as max'), 'max.TargetId', '=', 'references.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Reference"'))
		->where('references.UserId', '=', $me->UserId)
		->orderBy('references.Id','desc')
		->get();
		$skills = DB::table('skills')->select('skills.Id','Skill','Level','Description','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Skill" Group By TargetId) as max'), 'max.TargetId', '=', 'skills.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Skill"'))
		->where('skills.UserId', '=', $me->UserId)
		->orderBy('skills.Id','desc')
		->get();
		$trainings = DB::table('trainings')->select('trainings.Id','Training','Description','Organizer','Training_Date','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Training" Group By TargetId) as max'), 'max.TargetId', '=', 'trainings.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Training"'))
		->where('trainings.UserId', '=', $me->UserId)
		->orderBy('trainings.Id','desc')
		->get();
		$certificates = DB::table('certificates')
		->select('certificates.Id','certificates.Certificate','certificates.Description','certificates.Certificate_Date','certificates.Valid_Until','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Certificate" Group By TargetId) as max'), 'max.TargetId', '=', 'certificates.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Certificate"'))
		->where('certificates.UserId', '=', $me->UserId)
		->orderBy('certificates.Id','desc')
		->get();
		$salary = DB::table('salary')->select('salary.Id','salary.Salary','salary.Remarks','users.Name','salary.created_at as Adjustment_Date')
		->leftJoin('users','users.Id','=','salary.Created_By')
		->where('salary.UserId', '=', $me->UserId)
		->orderBy('salary.Id','desc')
		->get();
		$review = DB::table('reviews')->select('reviews.Id','reviews.Status','reviews.Remarks','reviews.created_at as Reviewed_Date','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Review" Group By TargetId) as max'), 'max.TargetId', '=', 'reviews.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Review"'))
		->where('reviews.UserId', '=', $me->UserId)
		->orderBy('reviews.Id','desc')
		->get();
		$family = DB::table('family')->select('family.Id','family.Name','family.NRIC','family.Gender','family.Age','family.Relationship','family.Occupation','family.Company_School_Name','family.Contact_No')
		->where('family.UserId', '=', $me->UserId)
		->orderBy('family.Id','desc')
		->get();
		$language = DB::table('languages')->select('languages.Id','languages.Language','languages.Speak','languages.Written')
		->where('languages.UserId', '=', $me->UserId)
		->orderBy('languages.Id','desc')
		->get();
		$options= DB::table('options')
		->whereIn('Table', ["users","employmenthistories","experiences","qualifications","skills","licenses","trainings","references","languages"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		$teamuser = DB::table("users")
		->get();


		if ($user->Detail_Approved_On=="0000-00-00 00:00:00")
		{
			$interval="-1";

		}
		else {
			$datetime1 = new DateTime($user->Detail_Approved_On);
			$datetime2 = new DateTime(date("Y-m-d H:i:s"));

			$interval = date_diff($datetime1, $datetime2);
			$interval=$interval->format("%m");

		}

		$projects = DB::table('projects')
		->where('projects.Project_Name','like','%department%')
		->get();

     return view('myprofile', ['UserId' => $me->UserId , 'me' => $me, 'user' =>$user,'changes' =>$changed, 'resumes' =>$resumes,'employmenthistories' => $employmenthistories, 'experiences' => $experiences, 'licenses' => $licenses, 'qualifications' => $qualifications,
		 'references' => $references, 'skills' => $skills, 'trainings' => $trainings,'certificates' => $certificates, 'review'=>$review, 'salary'=>$salary,
	 	 'options' => $options,'interval' =>$interval,'superior' =>$superior,'family'=>$family,'language'=>$language, 'teamuser'=>$teamuser,'projects'=>$projects]);
//		return view('Staff');
	}
	public function resumeview1($userid)
	{
		$me = (new CommonController)->get_current_user();
		$user = DB::table('users')->select('users.Id','StaffId', 'Name','Password','User_Type','Company_Email','Personal_Email','Contact_No_1','Contact_No_2','Nationality','Permanent_Address','Current_Address','Country_Base','Home_Base','DOB','Place_Of_Birth','Race','Religion','Bank_Name','Bank_Account_No','EPF_No','SOCSO_No','Income_Tax_No','Driving_License','Car_Owner','Criminal_Activity','Team','NRIC','Passport_No','Gender','Marital_Status','Department','Position','Grade','Joining_Date','Resignation_Date','Emergency_Contact_Person',
		'Emergency_Contact_No','Emergency_Contact_Relationship','Emergency_Contact_Address','files.Web_Path')
		// ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
    ->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->where('users.Id', $userid)
		->first();
		$employmenthistories = DB::table('employmenthistories')->select('employmenthistories.Company','employmenthistories.Company_Address','employmenthistories.Company_Contact_No','employmenthistories.Start_Date','employmenthistories.End_Date','employmenthistories.Position','employmenthistories.Supervisor','employmenthistories.Remarks')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Employment History" Group By TargetId) as max'), 'max.TargetId', '=', 'employmenthistories.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Employment History"'))
		->where('employmenthistories.UserId', $userid)
		->orderBy('employmenthistories.Id','desc')
		->get();
		$experiences = DB::table('experiences')->select('Project','Role','Responsibility','Achievement','Start_Date','End_Date')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Experience" Group By TargetId) as max'), 'max.TargetId', '=', 'experiences.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Experience"'))
		->where('experiences.UserId', $userid)
		->orderBy('experiences.Id','desc')
		->get();
		$licenses = DB::table('licenses')->select('License_Type','Identity_No','Issue_Date','Expiry_Date','License_Status')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="License" Group By TargetId) as max'), 'max.TargetId', '=', 'licenses.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="License"'))
		->where('licenses.UserId', $userid)
		->orderBy('licenses.Id','desc')
		->get();
		$qualifications = DB::table('qualifications')->select('Institution','Major','Qualification_Level','Start_Date','End_Date')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Qualification" Group By TargetId) as max'), 'max.TargetId', '=', 'qualifications.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Qualification"'))
		->where('qualifications.UserId', $userid)
		->orderBy('qualifications.Id','desc')
		->get();
		$references = DB::table('references')->select('Reference','Contact_No','Company','Position')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Reference" Group By TargetId) as max'), 'max.TargetId', '=', 'references.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Reference"'))
		->where('references.UserId',  $userid)
		->orderBy('references.Id','desc')
		->get();
		$skills = DB::table('skills')->select('Skill','Level','Description')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Skill" Group By TargetId) as max'), 'max.TargetId', '=', 'skills.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Skill"'))
		->where('skills.UserId', $userid)
		->orderBy('skills.Id','desc')
		->get();
		$trainings = DB::table('trainings')->select('Training','Description','Organizer','Training_Date')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Training" Group By TargetId) as max'), 'max.TargetId', '=', 'trainings.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Training"'))
		->where('trainings.UserId',$userid)
		->orderBy('trainings.Id','desc')
		->get();
		$certificates = DB::table('certificates')->select('certificates.Id','certificates.Certificate','certificates.Certificate_Date','certificates.Valid_Until','certificates.Description','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Certificate" Group By TargetId) as max'), 'max.TargetId', '=', 'certificates.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Certificate"'))
		->where('certificates.UserId', '=', $userid)
		->orderBy('certificates.Id','desc')
		->get();
		$options= DB::table('options')
		->whereIn('Table', ["users","employmenthistories","experiences","qualifications","skills","licenses","trainings","references"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();
		$html=view('pdf1', ['UserId' =>$userid , 'me' => $me, 'user' =>$user, 'employmenthistories' => $employmenthistories, 'experiences' => $experiences, 'licenses' => $licenses, 'qualifications' => $qualifications, 'references' => $references, 'skills' => $skills, 'trainings' => $trainings,'certificates' => $certificates,
		 'options' => $options]);

		(new ExportPDFController)->Export($html);
	}
	public function resumeview2($userid)
	{
		$me = (new CommonController)->get_current_user();
		$user = DB::table('users')->select('users.Id','StaffId', 'Name','Password','User_Type','Company_Email','Personal_Email','Contact_No_1','Contact_No_2','Nationality','Permanent_Address','Current_Address','Country_Base','Home_Base','DOB','Place_Of_Birth','Race','Religion','Bank_Name','Bank_Account_No','EPF_No','SOCSO_No','Income_Tax_No','Driving_License','Car_Owner','Criminal_Activity','Team','NRIC','Passport_No','Gender','Marital_Status','Department','Position','Grade','Joining_Date','Resignation_Date','Emergency_Contact_Person',
		'Emergency_Contact_No','Emergency_Contact_Relationship','Emergency_Contact_Address','files.Web_Path')
		// ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
    ->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->where('users.Id', $userid)
		->first();
		$employmenthistories = DB::table('employmenthistories')->select('employmenthistories.Company','employmenthistories.Company_Address','employmenthistories.Company_Contact_No','employmenthistories.Start_Date','employmenthistories.End_Date','employmenthistories.Position','employmenthistories.Supervisor','employmenthistories.Remarks')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Employment History" Group By TargetId) as max'), 'max.TargetId', '=', 'employmenthistories.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Employment History"'))
		->where('employmenthistories.UserId', $userid)
		->orderBy('employmenthistories.Id','desc')
		->get();
		$experiences = DB::table('experiences')->select('Project','Role','Responsibility','Achievement','Start_Date','End_Date')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Experience" Group By TargetId) as max'), 'max.TargetId', '=', 'experiences.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Experience"'))
		->where('experiences.UserId', $userid)
		->orderBy('experiences.Id','desc')
		->get();
		$licenses = DB::table('licenses')->select('License_Type','Identity_No','Issue_Date','Expiry_Date','License_Status')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="License" Group By TargetId) as max'), 'max.TargetId', '=', 'licenses.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="License"'))
		->where('licenses.UserId', $userid)
		->orderBy('licenses.Id','desc')
		->get();
		$qualifications = DB::table('qualifications')->select('Institution','Major','Qualification_Level','Start_Date','End_Date')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Qualification" Group By TargetId) as max'), 'max.TargetId', '=', 'qualifications.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Qualification"'))
		->where('qualifications.UserId', $userid)
		->orderBy('qualifications.Id','desc')
		->get();
		$references = DB::table('references')->select('Reference','Contact_No','Company','Position')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Reference" Group By TargetId) as max'), 'max.TargetId', '=', 'references.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Reference"'))
		->where('references.UserId',  $userid)
		->orderBy('references.Id','desc')
		->get();
		$skills = DB::table('skills')->select('Skill','Level','Description')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Skill" Group By TargetId) as max'), 'max.TargetId', '=', 'skills.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Skill"'))
		->where('skills.UserId', $userid)
		->orderBy('skills.Id','desc')
		->get();
		$trainings = DB::table('trainings')->select('Training','Description','Organizer','Training_Date')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Training" Group By TargetId) as max'), 'max.TargetId', '=', 'trainings.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Training"'))
		->where('trainings.UserId',$userid)
		->orderBy('trainings.Id','desc')
		->get();
		$certificates = DB::table('certificates')->select('certificates.Id','certificates.Certificate','certificates.Certificate_Date','certificates.Valid_Until','certificates.Description','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Certificate" Group By TargetId) as max'), 'max.TargetId', '=', 'certificates.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Certificate"'))
		->where('certificates.UserId', '=', $userid)
		->orderBy('certificates.Id','desc')
		->get();
		$options= DB::table('options')
		->whereIn('Table', ["users","employmenthistories","experiences","qualifications","skills","licenses","trainings","references"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();
		$html=view('pdf2', ['UserId' =>$userid , 'me' => $me, 'user' =>$user, 'employmenthistories' => $employmenthistories, 'experiences' => $experiences, 'licenses' => $licenses, 'qualifications' => $qualifications, 'references' => $references, 'skills' => $skills, 'trainings' => $trainings,'certificates' => $certificates,
		 'options' => $options]);

		(new ExportPDFController)->Export($html);
	}
	public function resumeview3($userid)
	{
		$me = (new CommonController)->get_current_user();
		$user = DB::table('users')->select('users.Id','StaffId', 'Nick_Name','Password','User_Type','Company_Email','Personal_Email','Contact_No_1','Contact_No_2','Nationality','Permanent_Address','Current_Address','Country_Base','Home_Base','DOB','Place_Of_Birth','Race','Religion','Bank_Name','Bank_Account_No','EPF_No','SOCSO_No','Income_Tax_No','Driving_License','Car_Owner','Criminal_Activity','Team','NRIC','Passport_No','Gender','Marital_Status','Department','Position','Grade','Joining_Date','Resignation_Date','Emergency_Contact_Person',
		'Emergency_Contact_No','Emergency_Contact_Relationship','Emergency_Contact_Address','files.Web_Path')
		// ->leftJoin('files', 'files.TargetId', '=', DB::raw('users.`Id` and files.`Type`="User"'))
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="User" Group By Type,TargetId) as max'), 'max.TargetId', '=', 'users.Id')
    	->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="User"'))
		->where('users.Id', $userid)
		->first();
		$employmenthistories = DB::table('employmenthistories')->select('employmenthistories.Company','employmenthistories.Company_Address','employmenthistories.Company_Contact_No','employmenthistories.Start_Date','employmenthistories.End_Date','employmenthistories.Position','employmenthistories.Supervisor','employmenthistories.Remarks')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Employment History" Group By TargetId) as max'), 'max.TargetId', '=', 'employmenthistories.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Employment History"'))
		->where('employmenthistories.UserId', $userid)
		->orderBy('employmenthistories.Id','desc')
		->get();
		$experiences = DB::table('experiences')->select('Project','Role','Responsibility','Achievement','Start_Date','End_Date')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Experience" Group By TargetId) as max'), 'max.TargetId', '=', 'experiences.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Experience"'))
		->where('experiences.UserId', $userid)
		->orderBy('experiences.Id','desc')
		->get();
		$licenses = DB::table('licenses')->select('License_Type','Identity_No','Issue_Date','Expiry_Date','License_Status')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="License" Group By TargetId) as max'), 'max.TargetId', '=', 'licenses.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="License"'))
		->where('licenses.UserId', $userid)
		->orderBy('licenses.Id','desc')
		->get();
		$qualifications = DB::table('qualifications')->select('Institution','Major','Qualification_Level','Start_Date','End_Date')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Qualification" Group By TargetId) as max'), 'max.TargetId', '=', 'qualifications.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Qualification"'))
		->where('qualifications.UserId', $userid)
		->orderBy('qualifications.Id','desc')
		->get();
		$references = DB::table('references')->select('Reference','Contact_No','Company','Position')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Reference" Group By TargetId) as max'), 'max.TargetId', '=', 'references.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Reference"'))
		->where('references.UserId',  $userid)
		->orderBy('references.Id','desc')
		->get();
		$skills = DB::table('skills')->select('Skill','Level','Description')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Skill" Group By TargetId) as max'), 'max.TargetId', '=', 'skills.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Skill"'))
		->where('skills.UserId', $userid)
		->orderBy('skills.Id','desc')
		->get();
		$trainings = DB::table('trainings')->select('Training','Description','Organizer','Training_Date')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Training" Group By TargetId) as max'), 'max.TargetId', '=', 'trainings.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Training"'))
		->where('trainings.UserId',$userid)
		->orderBy('trainings.Id','desc')
		->get();
		$certificates = DB::table('certificates')->select('certificates.Id','certificates.Certificate','certificates.Certificate_Date','certificates.Valid_Until','certificates.Description','files.Web_Path')
		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Certificate" Group By TargetId) as max'), 'max.TargetId', '=', 'certificates.Id')
		->leftJoin('files', 'files.Id', '=', DB::raw('max.`maxid` and files.`Type`="Certificate"'))
		->where('certificates.UserId', '=', $userid)
		->orderBy('certificates.Id','desc')
		->get();
		$options= DB::table('options')
		->whereIn('Table', ["users","employmenthistories","experiences","qualifications","skills","licenses","trainings","references"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();
		$html=view('pdf3', ['UserId' =>$userid , 'me' => $me, 'user' =>$user, 'employmenthistories' => $employmenthistories, 'experiences' => $experiences, 'licenses' => $licenses, 'qualifications' => $qualifications, 'references' => $references, 'skills' => $skills, 'trainings' => $trainings,'certificates' => $certificates,
		 'options' => $options]);

		(new ExportPDFController)->Export($html);
	}

	public function uploadresume(Request $request)
	{
		$filenames="";
		$input = $request->all();
		$insertid=$input["UserId"];
		$type="Resume";
		$uploadcount=1;

			if ($request->hasFile('resume')) {

					# code...
					$file = $request->file('resume');
					$destinationPath=public_path()."/private/upload/Resume";
					$extension = $file->getClientOriginalExtension();
					$originalName=$file->getClientOriginalName();
					$fileSize=$file->getSize();
					$fileName=time()."_".$uploadcount.".".$extension;
					$upload_success = $file->move($destinationPath, $fileName);
					$insert=DB::table('files')->insertGetId(
						['Type' => $type,
						 'TargetId' => $insertid,
						 'File_Name' => $originalName,
						 'File_Size' => $fileSize,
						 'Web_Path' => '/private/upload/Resume/'.$fileName
						]
					);
					$filenames.= $insert."|".url('/private/upload/Resume/'.$fileName)."|" .$originalName;

				return $filenames;

				//return '/private/upload/'.$fileName;
			}
			else {
				return 0;
			}
	}

	public function uploaddocument(Request $request)
	{
		$filenames="";
		$input = $request->all();
		$insertid=$input["UserId"];
		$type="Document";
		$uploadcount=1;

			if ($request->hasFile('document')) {

					# code...
					$file = $request->file('document');
					$destinationPath=public_path()."/private/upload/Document";
					$extension = $file->getClientOriginalExtension();
					$originalName=$file->getClientOriginalName();
					$fileSize=$file->getSize();
					$fileName=time()."_".$uploadcount.".".$extension;
					$upload_success = $file->move($destinationPath, $fileName);
					$insert=DB::table('files')->insertGetId(
						['Type' => $type,
						 'TargetId' => $insertid,
						 'File_Name' => $originalName,
						 'File_Size' => $fileSize,
						 'Web_Path' => '/private/upload/Document/'.$fileName
						]
					);
					$filenames.= $insert."|".url('/private/upload/Document/'.$fileName)."|" .$originalName;

				return $filenames;

				//return '/private/upload/'.$fileName;
			}
			else {
				return 0;
			}
	}

	public function allocation()
	{
		$me = (new CommonController)->get_current_user();

		$allocations=DB::table('users')
		->select('users.Id','userability.Ability','users.StaffId', 'users.Name','users.Position','users.Grade','users.User_Type','users.Home_Base', 'projects.Project_Name','userprojects.Assigned_As','userprojects.Start_Date','userprojects.End_Date')
		->leftJoin('userprojects', 'userprojects.UserId', '=', 'users.Id')
		->leftJoin('projects', 'userprojects.ProjectId', '=', 'projects.Id')
		->leftJoin('userability','users.Id','=','userability.UserId')
		->get();

		$summary = DB::select("
			SELECT userprojects.ProjectId, projects.Project_Name,SUM(case when users.User_Type = 'Staff' then 1 else 0 end) as Staff,SUM(case when users.User_Type = 'Assistant Engineer' then 1 else 0 end) as Interns, SUM(case when users.Position = 'DTA' then 1 else 0 end) as DTA, SUM(case when users.Position = 'DTE' then 1 else 0 end) as DTE
			FROM userprojects
			LEFT JOIN projects ON projects.Id=userprojects.ProjectId
			LEFT JOIN users ON users.Id=userprojects.UserId
			GROUP BY userprojects.ProjectId
	   ");

		 $projects = DB::table('projects')
     ->get();

		 $assigned_as = DB::table('userability')
		 ->get();

		 $options= DB::table('options')
	 		->whereIn('Table', ["users","userability"])
	 		->orderBy('Table','asc')
	 		->orderBy('Option','asc')
	 		->get();
		 //dd($data);

		 return view('allocation', ['me'=>$me, 'allocations'=>$allocations,'summary'=>$summary, 'projects'=>$projects, 'assigned_as'=>$assigned_as, 'options'=>$options]);


	}

	public function resourcesummary()
	{
		$me = (new CommonController)->get_current_user();

		$allocations=DB::table('users')
		->select('userprojects.Id','users.StaffId', 'users.Name','users.Position','users.Grade','users.User_Type','users.Home_Base', 'projects.Project_Name')
		->leftJoin('userprojects', 'userprojects.UserId', '=', 'users.Id')
		->leftJoin('projects', 'userprojects.ProjectId', '=', 'projects.Id')
		->get();


		// $summary = DB::select("
		// 	SELECT userprojects.ProjectId, projects.Project_Name,SUM(case when users.User_Type = 'Staff' then 1 else 0 end) as Staff,SUM(case when users.User_Type = 'Assistant Engineer' then 1 else 0 end) as Interns, SUM(case when users.Position = 'DTA' then 1 else 0 end) as DTA, SUM(case when users.Position = 'DTE' then 1 else 0 end) as DTE
		// 	FROM userprojects
		// 	LEFT JOIN projects ON projects.Id=userprojects.ProjectId
		// 	LEFT JOIN users ON users.Id=userprojects.UserId
		// 	GROUP BY userprojects.ProjectId
		//
	  //  ");

		$summary = DB::select("
			SELECT userprojects.ProjectId, projects.Project_Name,SUM(case when users.User_Type = 'Staff' then 1 else 0 end) as Staff,SUM(case when users.User_Type = 'Assistant Engineer' then 1 else 0 end) as Interns, SUM(case when users.Position = 'DTA' then 1 else 0 end) as DTA, SUM(case when users.Position = 'DTE' then 1 else 0 end) as DTE
			FROM projects
			LEFT JOIN userprojects ON projects.Id=userprojects.ProjectId
			LEFT JOIN users ON users.Id=userprojects.UserId
			GROUP BY projects.Id

	   ");

		 $projects = DB::table('projects')
     ->get();

		 $projectname = DB::table('projects')
		 ->select('Project_Name')
     ->get();

		 foreach($projectname as $key => $quote){
 			$r[]=$quote->Project_Name;
 			$title = implode(',', $r);
 		}

		$data1="";
		$data2="";
		$data3="";
		$data4="";

		foreach($summary as $key => $quote){
			$a[]=$quote->Staff;
			$data1= implode(',', $a);
		}

		foreach($summary as $key => $quote){
			$b[]=$quote->Interns;
			$data2= implode(',', $b);
		}

		foreach($summary as $key => $quote){
			$c[]=$quote->DTA;
			$data3= implode(',', $c);
		}

		foreach($summary as $key => $quote){
			$d[]=$quote->DTE;
			$data4= implode(',', $d);
		}

		return view('resourcesummary', ['me'=>$me, 'allocations'=>$allocations,'summary'=>$summary, 'projects'=>$projects, 'title'=>$title, 'data1'=>$data1, 'data2'=>$data2, 'data3'=>$data3, 'data4'=>$data4]);
	}

		public function viewlisttype(Request $request)
		{
			$me = (new CommonController)->get_current_user();
			$input = $request->all();

			$namelist=DB::table('users')
			->select('users.Id','users.StaffId', 'users.Name','users.Position','users.Grade','users.User_Type','users.Home_Base', 'userprojects.ProjectId','projects.Project_Name')
			->leftJoin('userprojects', 'userprojects.UserId', '=', 'users.Id')
			->leftJoin('projects', 'userprojects.ProjectId', '=', 'projects.Id')
			->where('projects.Id', $input["ProjectId"])
			->where('users.User_Type', $input["User_Type"])
			->get();

		 return json_encode($namelist);

		}
		public function viewlistposition(Request $request)
		{
			$me = (new CommonController)->get_current_user();
			$input = $request->all();

			$namelist=DB::table('users')
			->select('users.Id','users.StaffId', 'users.Name','users.Position','users.Grade','users.User_Type','users.Home_Base', 'userprojects.ProjectId','projects.Project_Name')
			->leftJoin('userprojects', 'userprojects.UserId', '=', 'users.Id')
			->leftJoin('projects', 'userprojects.ProjectId', '=', 'projects.Id')
			->where('projects.Id', $input["ProjectId"])
			->where('users.Position', $input["Position"])
			->get();

		 return json_encode($namelist);

		}
		public function userability()
		{
			$me = (new CommonController)->get_current_user();

			$users=DB::select('SELECT * FROM users where Id NOT IN (SELECT UserId from userability)');

			foreach ($users as $user) {
				# code...
				DB::table('userability')->insertGetId(
					['UserId' => $user->Id
					]
				);
			}

			$userability = DB::table('userability')
			->select('userability.Id','users.StaffId', 'users.Name','userability.Ability')
			->leftJoin('users', 'users.Id', '=', 'userability.UserId')
			->get();

			$options= DB::table('options')
			->whereIn('Table', ["users","userability"])
			->orderBy('Table','asc')
			->orderBy('Option','asc')
			->get();

			return view("userability",['me'=>$me, 'userability'=>$userability, 'options'=>$options]);


		}

		public function licensechecklist()
		{
			$me = (new CommonController)->get_current_user();

			$license = DB::select("
			Select files.Web_Path,users.Name, users.NRIC,

			(Select Identity_No From licenses where License_Type like '%CIDB%' and licenses.UserId=users.Id Order By licenses.Id DESC Limit 1) as CIDB_License,
			(Select Expiry_Date From licenses where License_Type like '%CIDB%' and licenses.UserId=users.Id Order By licenses.Id DESC Limit 1) as CIDB_Expiry_Date,

			(Select Identity_No From licenses where License_Type like '%NIOSH%' and licenses.UserId=users.Id Order By licenses.Id DESC Limit 1) as NIOSH_License,
			(Select Expiry_Date From licenses where License_Type like '%NIOSH%' and licenses.UserId=users.Id Order By licenses.Id DESC Limit 1) as NIOSH_Expiry_Date,

			(Select Identity_No From licenses where License_Type like '%WAH%' and licenses.UserId=users.Id Order By licenses.Id DESC Limit 1) as WAH_License,
			(Select Expiry_Date From licenses where License_Type like '%WAH%' and licenses.UserId=users.Id Order By licenses.Id DESC Limit 1) as WAH_Expiry_Date,

			(Select Identity_No From licenses where License_Type like '%HUAWEI%' and licenses.UserId=users.Id Order By licenses.Id DESC Limit 1) as HUAWEI_License,
			(Select Expiry_Date From licenses where License_Type like '%HUAWEI%' and licenses.UserId=users.Id Order By licenses.Id DESC Limit 1) as HUAWEI_Expiry_Date

			from users
			LEFT JOIN ((select Max(Id) as maxid,TargetId from files where Type='User' Group By Type,TargetId) as max) ON max.TargetId=users.Id
			LEFT JOIN files ON files.Id=max.maxid

			");

			// (select max(Web_Path) from files left join licenses on files.targetid=licenses.Id and files.type='License' where License_Type like '%Class E License%' and licenses.UserId=users.Id) as Lorry_File,

			// dd($license);

			return view('licensechecklist',['me'=>$me,'license'=>$license]);

		}

		public function licensepdf()
		{
			$license = DB::select("
			Select files.Web_Path as Image,users.Name, users.NRIC,

			(select Web_Path from files left join licenses on files.targetid=licenses.Id and files.type='License' where License_Type like '%CIDB%' and licenses.UserId=users.Id AND files.Id in (Select max(id) from files Group By TargetId,Type)) as CIDB,
			(select Web_Path from files left join licenses on files.targetid=licenses.Id and files.type='License' where License_Type like '%NIOSH%' and licenses.UserId=users.Id AND files.Id in (Select max(id) from files Group By TargetId,Type)) as NIOSH,
			(select Web_Path from files left join licenses on files.targetid=licenses.Id and files.type='License' where License_Type like '%WAH%' and licenses.UserId=users.Id AND files.Id in (Select max(id) from files Group By TargetId,Type)) as WAH,
			(select Web_Path from files left join licenses on files.targetid=licenses.Id and files.type='License' where License_Type like '%HUAWEI%' and licenses.UserId=users.Id AND files.Id in (Select max(files.id) from files LEFT JOIN licenses on files.targetid=licenses.Id Where License_Type like '%HUAWEI%')) as HUAWEI

			from users
			LEFT JOIN ((select Max(Id) as maxid,TargetId from files where Type='User' Group By Type,TargetId) as max) ON max.TargetId=users.Id
			LEFT JOIN files ON files.Id=max.maxid

			");



			// dd($license);
			//  return view('licensepdf',['license'=>$license]);

			$html=view('licensepdf', ['license' =>$license ]);

			(new ExportPDFController)->ExportLandscape($html);

		}

		public function myadvancerequest()
		{
			$me = (new CommonController)->get_current_user();

			$advance = "";

			$users = DB::table('users')
			->select('Id','Name','Bank_Account_No','Position')
			->where('users.Id','=',$me->UserId)
			->get();

			$projects = DB::table('projects')
      ->get();

			$options= DB::table('options')
			->whereIn('Table', ["users"])
			->orderBy('Table','asc')
			->orderBy('Option','asc')
			->get();

			return view('myadvancerequest',['advance'=>$advance,'me'=>$me,'users'=>$users, 'projects'=>$projects,'options'=>$options]);

		}

		public function myrequest()
		{
	        $me = (new CommonController)->get_current_user();

			$myrequest = DB::table('request')
				->leftJoin( DB::raw('(select Max(Id) as maxid,RequestId from requeststatuses Group By RequestId) as max'), 'max.RequestId', '=', 'request.Id')
				->leftJoin('requeststatuses', 'requeststatuses.Id', '=', DB::raw('max.`maxid`'))

				->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Request" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'request.Id')
				->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Request"'))

				->leftJoin('users as applicant', 'request.UserId', '=', 'applicant.Id')
				->leftJoin('users as approver', 'requeststatuses.UserId', '=', 'approver.Id')
				->leftJoin('projects', 'request.ProjectId', '=', 'projects.Id')
				->select('request.Id','requeststatuses.Id as StatusId','request.Request_type','request.Others','request.Start_Date','request.End_Date', 'request.Remarks','request.created_at as Application_Date','projects.Project_Name','approver.Name as Approver','requeststatuses.Request_status as Status','requeststatuses.updated_at as Review_Date','requeststatuses.Comment')
				->where('request.UserId', '=', $me->UserId)
				->orderBy('request.Id','desc')
				->get();

				$options= DB::table('options')
				->whereIn('Table', ["request"])
				->orderBy('Option','asc')
				->get();

				$projects = DB::table('projects')
				->get();

		        $approver = DB::table('approvalsettings')
		            ->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
		            ->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
		            // ->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
								->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
		            ->where('approvalsettings.Type', '=', 'Request')
		            ->orderBy('approvalsettings.Country','asc')
		            // ->orderBy('projects.Project_Name','asc')
		            ->orderBy('approvalsettings.Level','asc')
		            ->groupBy('approvalsettings.Country','projects.Project_Name','users.Id')
		            ->get();

			return view('myrequest', ['me' => $me,'approver'=> $approver, 'myrequest' => $myrequest,'approver' => $approver, 'options' =>$options, 'projects' =>$projects]);

		}

		public function applyrequest(Request $request)
		{
		    $me=(new CommonController)->get_current_user();

		    $input = $request->all();

		    $rules = array(
		        'Request_Type' => 'Required',
		        // 'Others'     => 'Required',
		        'Approver'       => 'Required',
				'Remarks'       => 'Required'
	        );

	        $messages = array(
		        'Request_Type.required' => 'The Request Type field is required',
		        // 'Others.required'     => 'The Others field is required',
		        'Approver.required'       => 'The Approval field is required',
				'Remarks.required'       => 'The Remarks field is required'
		    );

		    $validator = Validator::make($input, $rules,$messages);

		    if ($validator->passes())
		    {
				if(!isset($input["Others"]))
				{
					$input["Others"] = '';
				}

			    $id=DB::table('request')->insertGetId([
			    	'UserId' => $me->UserId,
					'ProjectId' => $input["Project"],
			        'Request_Type' => $input["Request_Type"],
			        'Others' => $input["Others"],
					'Start_Date' => $input["Start_Date"],
					'End_Date' => $input["End_Date"],
					'Remarks' => $input["Remarks"]
			    ]);

		        DB::table('requeststatuses')->insert([
		        	'RequestId' => $id,
		            'UserId' => $input["Approver"],
		            'Request_Status' =>"Pending Approval"
		        ]);

		        $requestdetail = DB::table('request')
		          	->leftJoin('requeststatuses', 'request.Id', '=', 'requeststatuses.RequestId')
		          	->leftJoin('users as applicant', 'request.UserId', '=', 'applicant.Id')
		          	->leftJoin('users as approver', 'requeststatuses.UserId', '=', 'approver.Id')
		          	// ->leftJoin('projects', 'request.ProjectId', '=', 'projects.Id')
		          	->select('applicant.Name','request.Request_Type','request.Others','request.Start_Date','request.End_Date','approver.Name as Approver', 'request.Remarks as Remarks')
		          	->orderBy('requeststatuses.Id','desc')
		          	->where('request.Id', '=',$id)
		          	->first();

		        $notify = DB::table('users')
		          	->whereIn('Id', [$me->UserId, $input["Approver"]])
		          	->get();

		        $subscribers = DB::table('notificationtype')
		          	->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
		          	->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
		          	->where('notificationtype.Id','=',62)
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

		        Mail::send('emails.requestapplication', ['requestdetail' => $requestdetail], function($message) use ($emails,$me,$NotificationSubject)
		        {
		            array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
		            $emails = array_filter($emails);
		            $message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');
		        });

		        return 1;
		    }
		    else
		    {
		        return json_encode($validator->errors()->toArray());
		    }

		}

		public function cancelrequest(Request $request)
		{

			$me=(new CommonController)->get_current_user();

			$input = $request->all();

			$requestdetail = DB::table('request')
			->leftJoin('requeststatuses', 'request.Id', '=', 'requeststatuses.RequestId')
			->leftJoin('users as applicant', 'request.UserId', '=', 'applicant.Id')
			->leftJoin('users as approver', 'requeststatuses.UserId', '=', 'approver.Id')
			// ->leftJoin('projects', 'request.ProjectId', '=', 'projects.Id')
			->select('applicant.Name','requeststatuses.UserId as ApproverId','request.Request_Type','request.Others','request.Start_Date','request.End_Date','approver.Name as Approver', 'request.Remarks as Remarks')
			->orderBy('requeststatuses.Id','desc')
			->where('request.Id', '=',$input["Id"])
			->first();

			$id=DB::table('requeststatuses')->insertGetId(
				['RequestId' => $input["Id"],
				 'UserId' => 0,
				 'Request_Status' => "Cancelled"
				]
			);

			$subscribers = DB::table('notificationtype')
			->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
			->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
			->where('notificationtype.Id','=',65)
			->get();

			if ($id>0)
			{

				$notify = DB::table('users')
				->whereIn('Id', [$me->UserId, $requestdetail->ApproverId])
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

				$requestdetail = DB::table('request')
				->leftJoin('requeststatuses', 'request.Id', '=', 'requeststatuses.RequestId')
				->leftJoin('users as applicant', 'request.UserId', '=', 'applicant.Id')
				->leftJoin('users as approver', 'requeststatuses.UserId', '=', 'approver.Id')
				// ->leftJoin('projects', 'request.ProjectId', '=', 'projects.Id')
				->select('applicant.Name','request.Request_Type','request.Others','request.Start_Date','request.End_Date','approver.Name as Approver', 'request.Remarks as Remarks')
				->orderBy('requeststatuses.Id','desc')
				->where('request.Id', '=',$input["Id"])
				->first();

				Mail::send('emails.requestcancel', ['requestdetail' => $requestdetail], function($message) use ($emails,$me,$NotificationSubject)
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

		public function applyadvance(Request $request)
		{
				$me=(new CommonController)->get_current_user();

				$emaillist=array();
				array_push($emaillist,$me->UserId);

				$input = $request->all();

				$rules = array(
					'ProjectId' => 'Required',
					'Purpose'     => 'Required',
					'Start_Date'       => 'Required',
					'End_Date'  =>'Required',
					'Destination'  =>'Required',
					'Mode_Of_Transport'  =>'Required'
					);

					$messages = array(
						'ProjectId.required' => 'The Project field is required',
						'Purpose.required'     => 'The Purpose field is required',
						'Start_Date.required'       => 'The Start Date field is required',
						'End_Date.required'  =>'The End Date field is required',
						'Destination.required'  =>'The Destination field is required',
						'Mode_Of_Transport.required'  =>'The Mode of Transport field is required'
						);

						$approver = DB::table('approvalsettings')
						->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
						->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
						->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
						->where('approvalsettings.Type', '=', 'Claim')
						->where('approvalsettings.ProjectId', '<>', '0')
						->orderBy('approvalsettings.Country','asc')
						->orderBy('projects.Project_Name','asc')
						->orderByRaw("FIELD(approvalsettings.Level , '1st Approval', '2nd Approval', '3rd Approval','4th Approval','5th Approval','Final Approval') ASC")
						->get();

						$mylevel = DB::table('approvalsettings')
						->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
						->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
						->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
						->where('approvalsettings.Type', '=', 'Claim')
						->where('approvalsettings.UserId', '=', $me->UserId)
						->orderBy('approvalsettings.Country','asc')
						->orderBy('projects.Project_Name','asc')
						->orderByRaw("FIELD(approvalsettings.Level , 'Final Approval', '5th Approval', '4th Approval','3rd Approval','2nd Approval','1st Approval') ASC")
						->get();

						foreach ($approver as $approverid) {

							$validator = Validator::make($input, $rules,$messages);

							if ($validator->passes())
							{

									$id=DB::table('advances')->insertGetId(
										['UserId' => $me->UserId,
										 'ProjectId' => $input["ProjectId"],
										 'Purpose' => $input["Purpose"],
										 'Start_Date' => $input["Start_Date"],
										 'End_Date' => $input["End_Date"],
										 'Destination' => $input["Destination"],
										 'Mode_Of_Transport' => $input["Mode_Of_Transport"],
										 'Car_No' => $input["Car_No"],
										 'Total_Requested' => $input["Total_Requested"],

									 	]
									);

									DB::table('advancestatuses')->insertGetId(
										['UserId' => $approverid->Id,
										 'AdvanceId' => $id,
										 'Status' => "Pending Approval"
									 	]
									);

									DB::table('advancedetails')
												->insert(array(
												array(
													'AdvanceId'=>$id,
													'Type' => 'Meal Allowance',
													'Days' => $input["Meal_Days"],
													'Allowance' => $input["Meal_Per_Day"],
													'Total' => $input["Sum1"],
												 ),
												 array(
 													'AdvanceId'=>$id,
 													'Type' => 'Accomodation/Hotel',
 													'Days' => $input["Accomodation_Days"],
 													'Allowance' => $input["Accomodation_Per_Day"],
													'Total' => $input["Sum2"],
 												 ),
												 array(
 													'AdvanceId'=>$id,
 													'Type' => 'Mileage/Petrol',
 													'Days' => $input["Mileage_Days"],
 													'Allowance' => $input["Mileage_Per_Day"],
													'Total' => $input["Sum3"],
 												 ),
												 array(
 													'AdvanceId'=>$id,
 													'Type' => 'Parking/Tolls',
 													'Days' => $input["Parking_Days"],
 													'Allowance' => $input["Parking_Per_Day"],
													'Total' => $input["Sum4"],
 												 ),
												 array(
 													'AdvanceId'=>$id,
 													'Type' => 'Fare/Ticket',
 													'Days' => $input["Ticket_Days"],
 													'Allowance' => $input["Ticket_Per_Day"],
													'Total' => $input["Sum5"],
 												 ),
												 array(
 													'AdvanceId'=>$id,
 													'Type' => 'Other Purposes',
 													'Days' => $input["Other_Days"],
 													'Allowance' => $input["Other_Per_Day"],
													'Total' => $input["Sum6"],
 												 ),


											));


																				if (count($emaillist)>1)
																				{
																					return 10;
																				}

									array_push($emaillist,$approverid->Id);

									if (count($emaillist)>1)
									{

										$notify = DB::table('users')
										->whereIn('Id', $emaillist)
										->get();

										$subscribers = DB::table('notificationtype')
										->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
										->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
										->where('notificationtype.Id','=',46)
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

										$advance = DB::table('advances')
										->select('advancestatuses.Status','advances.Id','users.Name','users.Bank_Account_No','users.Position','projects.Project_Name','advances.Purpose','advances.Destination','advances.Start_Date','advances.End_Date','advances.Mode_Of_Transport','advances.Car_No','approver.Name as Approver',DB::raw('Format((advances.Total_Requested),2) as Total_Requested'))
										->leftJoin('users','users.Id','=','advances.UserId')
										->leftJoin('projects','projects.Id','=','advances.ProjectId')
										->leftJoin('advancestatuses','advances.Id','=','advancestatuses.AdvanceId')
										->leftJoin('users as approver','approver.Id','=','advancestatuses.UserId')
										->where('advances.Id','=',$id)
										->get();


										Mail::send('emails.advancerequest', ['me' => $me,'advance' => $advance], function($message) use ($emails,$me,$NotificationSubject)
										{
												$emails = array_filter($emails);
												array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
												$message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');

										});

										return 1;
									}


							}

				else {

					return json_encode($validator->errors()->toArray());
				}

				if (count($emaillist)>1)
				{

					$notify = DB::table('users')
					->whereIn('Id', $emaillist)
					->get();

					$subscribers = DB::table('notificationtype')
					->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
					->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
					->where('notificationtype.Id','=',46)
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

					$advance = DB::table('advances')
					->select('advancestatuses.Status','advances.Id','users.Name','users.Bank_Account_No','users.Position','projects.Project_Name','advances.Purpose','advances.Destination','advances.Start_Date','advances.End_Date','advances.Mode_Of_Transport','advances.Car_No','approver.Name as Approver',DB::raw('Format((advances.Total_Requested),2) as Total_Requested'))
					->leftJoin('users','users.Id','=','advances.UserId')
					->leftJoin('projects','projects.Id','=','advances.ProjectId')
					->leftJoin('advancestatuses','advances.Id','=','advancestatuses.AdvanceId')
					->leftJoin('users as approver','approver.Id','=','advancestatuses.UserId')
					->where('advances.Id','=',$advanceid)
					->get();

					Mail::send('emails.advance', ['me' => $me,'advance' => $advance], function($message) use ($emails,$me,$NotificationSubject)
					{
							$emails = array_filter($emails);
							array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
							$message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');

					});
				}


			}

		}

		public function requestmanagement($start = null, $end = null)
		{
		        $me = (new CommonController)->get_current_user();

				if ($start==null)
				{
					$start=date('d-M-Y', strtotime('first day of this month'));
				}

				if ($end==null)
				{
					$end=date('d-M-Y', strtotime('last day of this month'));
				}


				$request = DB::table('request')
					->leftJoin( DB::raw('(select Max(Id) as maxid,RequestId from requeststatuses Group By RequestId) as max'), 'max.RequestId', '=', 'request.Id')
					->leftJoin('requeststatuses', 'requeststatuses.Id', '=', DB::raw('max.`maxid`'))
					->leftJoin('users as applicant', 'request.UserId', '=', 'applicant.Id')
					->leftJoin('users as approver', 'requeststatuses.UserId', '=', 'approver.Id')
					->leftJoin('projects', 'projects.Id', '=', 'request.ProjectId')
					->select('request.Id','requeststatuses.Id as StatusId','applicant.Name as Applicant','applicant.Department','request.Approver as Approver_Id','request.Request_type','request.Others', 'request.Start_Date','request.End_Date','request.Remarks','request.created_at as Application_Date','projects.Project_Name','approver.Name as Approver','requeststatuses.Request_status as Status','requeststatuses.updated_at as Review_Date','requeststatuses.Comment')
					->where('requeststatuses.UserId', '=', $me->UserId)
					->orderBy('request.Id','desc')
					->get();

				$allrequest = DB::table('request')
					->leftJoin( DB::raw('(select Max(Id) as maxid,RequestId from requeststatuses Group By RequestId) as max'), 'max.RequestId', '=', 'request.Id')
					->leftJoin('requeststatuses', 'requeststatuses.Id', '=', DB::raw('max.`maxid`'))
					->leftJoin('users as applicant', 'request.UserId', '=', 'applicant.Id')
					->leftJoin('users as approver', 'requeststatuses.UserId', '=', 'approver.Id')
					->leftJoin('projects', 'projects.Id', '=', 'request.ProjectId')
					->select('request.Id','requeststatuses.Id as StatusId','applicant.Name as Applicant','applicant.Department','request.Approver as Approver_Id','request.Request_type','request.Others', 'request.Start_Date','request.End_Date', 'request.Remarks','request.created_at as Application_Date','projects.Project_Name','approver.Name as Approver','requeststatuses.Request_status as Status','requeststatuses.updated_at as Review_Date','requeststatuses.Comment')
					->orderBy('request.Id','desc')
					->get();

				$finalapprovedrequest = DB::table('request')
				->leftJoin( DB::raw('(select Max(Id) as maxid,RequestId from requeststatuses Group By RequestId) as max'), 'max.RequestId', '=', 'request.Id')
				->leftJoin('requeststatuses', 'requeststatuses.Id', '=', DB::raw('max.`maxid`'))
				->leftJoin('users as applicant', 'request.UserId', '=', 'applicant.Id')
				->leftJoin('users as approver', 'requeststatuses.UserId', '=', 'approver.Id')
				->leftJoin('projects', 'projects.Id', '=', 'request.ProjectId')
				->select('request.Id','requeststatuses.Id as StatusId','applicant.Name as Applicant','applicant.Department','request.Approver as Approver_Id','request.Request_type','request.Others', 'request.Start_Date','request.End_Date', 'request.Remarks','request.created_at as Application_Date','projects.Project_Name','approver.Name as Approver','requeststatuses.Request_status as Status','requeststatuses.updated_at as Review_Date','requeststatuses.Comment')
				->where('requeststatuses.Request_status', 'like','%Final Approved%')
				->orderBy('request.Id','desc')
				->get();

				$approver = DB::table('approvalsettings')
				->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
				->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
				->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
				->where('approvalsettings.Type', '=', 'Request')
				->orderBy('approvalsettings.Country','asc')
				->orderBy('projects.Project_Name','asc')
				->orderBy('approvalsettings.Level','asc')
				->groupBy('approvalsettings.Country','projects.Project_Name','users.Id')
				->get();

				$mylevel = DB::table('approvalsettings')
				->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
				->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
				->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
				->where('approvalsettings.Type', '=', 'Request')
				->where('approvalsettings.UserId', '=', $me->UserId)

				// ->orderBy('approvalsettings.Level','Final Approval","5th Approval","4th Approval","3rd Approval","2nd Approval","1st Approval")
				->orderByRaw("FIELD(approvalsettings.Level , 'Final Approval', '5th Approval', '4th Approval','3rd Approval','2nd Approval','1st Approval') ASC")
				->orderBy('projects.Project_Name','asc')
				->first();

				return view('requestmanagement', ['me' => $me,'allrequest' => $allrequest,'request' => $request,'finalapprovedrequest'=>$finalapprovedrequest,'approver' => $approver,'mylevel' => $mylevel,'start'=>$start,'end'=>$end ]);
	    }

	    public function approverequest(Request $request)
	    {

	    	$me = (new CommonController)->get_current_user();

	    	$input = $request->all();

	    	$Ids = explode(",", $input["Ids"]);
	    	$Status = $input["Status"];
	    	// dd($Ids);

	    	//Retrieve request info
	    	$request = DB::table('request')
	    	// ->leftJoin('requeststatuses', 'request.Id', '=', 'requeststatuses.RequestId')
	    	->leftJoin( DB::raw('(select Max(Id) as maxid,RequestId from requeststatuses Group By RequestId) as max'), 'max.RequestId', '=', 'request.Id')
			->leftJoin('requeststatuses', 'requeststatuses.Id', '=', DB::raw('max.`maxid`'))
	    	->leftJoin('users as applicant', 'request.UserId', '=', 'applicant.Id')
	    	->leftJoin('users as approver', 'requeststatuses.UserId', '=', 'approver.Id')
	    	//->select('leaves.Id as LeaveId','leaves.UserId','applicant.Name','leavestatuses.Id as StatusId','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','approver.Id as ApproverId','approver.Name as Approver')
	    	->select('request.Id as RequestId','requeststatuses.Id as StatusId','request.Approver as ApproverId','request.Request_type','request.Others', 'request.Remarks','request.created_at as Application_Date','approver.Name as Approver','requeststatuses.Request_status as Status','requeststatuses.updated_at as Review_Date','requeststatuses.Comment', 'request.UserId as ApplicantId')
	    	->orderBy('requeststatuses.Id','desc')
	    	->whereIn('request.Id', $Ids)
	    	->get();

			// dd($request);

	    	foreach ($request as $requests) {
	    		# code...

	    		//Check if Approver ID is not same as User Id
	    		if ($requests->ApproverId!=$me->UserId)
	    		{
	    			//Insert new row to requeststatuses, Request_status -> "Approved"
	    			$id=DB::table('requeststatuses')->insertGetId(
	    				['RequestId' => $requests->RequestId,
	    				 'UserId' => $me->UserId,
	    				 'Request_status' => $input["Status"],
	    				 'updated_at' => DB::raw('now()')
	    				]
	    			);


	    		}
	    		else {

	    			$result= DB::table('requeststatuses')
	    						->where('Id', '=',$requests->StatusId)
	    						->update(array(
	    						'Request_status' =>  $input["Status"],
	    					));

	    		}
	    	}

			// dd($request);
	    	$requests = DB::table('request')
	    	->leftJoin( DB::raw('(select Max(Id) as maxid,RequestId from requeststatuses Group By RequestId) as max'), 'max.RequestId', '=', 'request.Id')
	    	->leftJoin('requeststatuses', 'requeststatuses.Id', '=', DB::raw('max.`maxid`'))
	    	->leftJoin('users as applicant', 'request.UserId', '=', 'applicant.Id')
	    	->leftJoin('users as approver', 'requeststatuses.UserId', '=', 'approver.Id')
	    	// ->leftJoin('projects', 'request.ProjectId', '=', 'projects.Id')
	    	// ->select('requeststatuses.Id','request.Id as RequestId','request.UserId as ApplicantId','applicant.Name','request.Leave_Type','request.Leave_Term','request.Start_Date','request.End_Date','request.No_of_Days','request.Reason','request.created_at as Application_Date','projects.Project_Name','approver.Id as ApproverId','approver.Name as Approver','requeststatuses.Request_status as Status','requeststatuses.Comment','requeststatuses.updated_at as Review_Date')
	    	->select('request.Id as RequestId','requeststatuses.Id as StatusId','request.Start_Date','request.End_Date','request.Approver as ApproverId','request.Request_type','request.Others', 'request.Remarks','request.created_at as Application_Date','approver.Name as Approver','requeststatuses.Request_status as Status','requeststatuses.updated_at as Review_Date','requeststatuses.Comment', 'request.UserId as ApplicantId')
	    	->orderBy('request.Id','desc')
	    	->whereIn('request.Id', $Ids)
	    	->get();

	    	$approver = DB::table('approvalsettings')
	    	->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
	    	->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
	    	// ->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
	    	->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country')
	    	->where('approvalsettings.Type', '=', 'Request')
	    	->where('approvalsettings.ProjectId', '<>', '0')
	    	->orderBy('approvalsettings.Country','asc')
	    	->orderBy('projects.Project_Name','asc')
	    	->orderByRaw("FIELD(approvalsettings.Level , '1st Approval','2nd Approval','3rd Approval','4th Approval','5th Approval','Final Approval') ASC")
	    	->get();

	    	$final=false;

	    	$emaillist=array();
	    	array_push($emaillist,$me->UserId);

	    	foreach ($requests as $request) {

	    		# code...
	    		$emaillist=array();
	    		array_push($emaillist,$request->ApplicantId);

	    		$submitted=false;
	    		$currentstatus=$request->Status;

	    		if ($request->Status=="Final Approved")
	    		{

	    			array_push($emaillist,$request->ApplicantId);
	    			array_push($emaillist,$request->ApproverId);

	    			/**
	    			*	adjust replacement leave
	    			*/
	    			if ($request->Request_type == 'Replacement Leave') {
	    				// the selected start date
	    				$date1 = new DateTime($request->Start_Date);
	    				// the selected end date
	    				$date2 = new DateTime($request->End_Date);
	    				// the selected period
	    				// $periods = $request->Leave_Period;

	    				// calculate the days difference between dates and add one day
	    				$days = $date2->diff($date1)->format("%a") + 1;

	    				$year = date("Y",strtotime($request->Start_Date));


	    				$updated = DB::table('leaveadjustments')
	    				            ->where('UserId', $request->ApplicantId)
	    				            ->where('Adjustment_Year', $year)
	    				            ->where('Adjustment_Leave_Type', 'Replacement Leave')
	    				            ->update(['Adjustment_Value' => DB::raw("Adjustment_Value + " . $days)]);

	    				if (! $updated) {
	    				    DB::table('leaveadjustments')->insert([
	    				        'UserId' => $request->ApplicantId,
	    				        'Adjustment_Value' => $days,
	    				        'Adjustment_Leave_Type' => 'Replacement Leave',
	    				        'Adjustment_Year' => $year
	    				    ]);

	    				}

	    				DB::table('leaveadjustmentshistory')->insert([
	    				    'UserId' => $request->ApplicantId,
	    				    'ApproverId' => $me->UserId,
	    				    'Adjustment_Value' => $days,
	    				    'Adjustment_Leave_Type' => 'Replacement Leave',
	    				    'Adjustment_Year' => $year,
	    				    'Remarks' => '[Requested Replacement Leave ' . $request->Start_Date . '-' . $request->End_Date . '] ' . $request->Remarks,
	    				]);
	    			}

	    		}

	    		if ((strpos($request->Status, 'Rejected') === false) && (strpos($request->Status, 'Not Approve') === false) && $request->Status!="Final Approved")
	    		{

	    			foreach ($approver as $user) {

	    					if (!empty($user->Id) && $request->ApproverId != $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($request->Status, FILTER_SANITIZE_NUMBER_INT))
	    					{

	    						DB::table('requeststatuses')->insert(
	    							['RequestId' => $request->RequestId,
	    							 'UserId' => $user->Id,
	    							 'Request_status' => "Pending Approval"
	    							]
	    						);
	    						$submitted=true;
	    						array_push($emaillist,$user->Id);
	    						array_push($emaillist,$request->ApplicantId);

	    						break;
	    					}
	    					elseif (!empty($user->Id) && $request->ApproverId == $user->Id  && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($request->Status, FILTER_SANITIZE_NUMBER_INT))
	    					{
	    						# code...
	    							$submitted=true;
	    							array_push($emaillist,$user->Id);
	    							array_push($emaillist,$request->ApplicantId);
	    					}
	    					elseif (!empty($user->Id) && $request->ApproverId == $user->Id && $user->Level=="Final Approval")
	    					{

	    						DB::table('requeststatuses')->insert(
	    							['RequestId' => $request->RequestId,
	    							 'UserId' => $user->Id,
	    							 'Request_status' => "Pending Approval"
	    							]
	    						);
	    						$submitted=true;
	    						array_push($emaillist,$user->Id);
	    						array_push($emaillist,$request->ApplicantId);

	    					}
	    					elseif (!empty($user->Id) && $request->ApproverId != $user->Id && $user->Level=="Final Approval")
	    					{

	    						DB::table('requeststatuses')->insert(
	    							['RequestId' => $request->RequestId,
	    							 'UserId' => $user->Id,
	    							 'Request_status' => "Pending Approval"
	    							]
	    						);
	    						$submitted=true;
	    						array_push($emaillist,$user->Id);
	    						array_push($emaillist,$request->ApplicantId);

	    						break;
	    					}
	    				}

	    		}
	    		elseif ((strpos($request->Status, 'Rejected') !== false))
	    		{

	    			array_push($emaillist,$request->ApplicantId);
	    		}
	    		elseif ((strpos($request->Status, 'Not Approve') !== false))
	    		{

	    			array_push($emaillist,$request->ApplicantId);
	    		}
	    		elseif ($request->Status=="Final Approved" || $request->Request_status=="Final Rejected")
	    		{
	    			$final=true;
	    			array_push($emaillist,$request->ApplicantId);
	    		}

	    		//notification
	    		if (count($emaillist)>1)
	    		{

	    			$notify = DB::table('users')
	    			->whereIn('Id', $emaillist)
	    			->get();

	    			if($final)
	    			{
	    				$subscribers = DB::table('notificationtype')
	    				->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
	    				->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
	    				// ->where('notificationtype.Id','=',39)
	    				->where('notificationtype.Id','=',64)
	    				->get();
	    			}
	    			else {
	    				$subscribers = DB::table('notificationtype')
	    				->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
	    				->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
	    				// ->where('notificationtype.Id','=',28)
	    				->where('notificationtype.Id','=',63)
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

	    			// array_push($emails,"latifah@pronetwork.com.my");

	    			$requestdetail = DB::table('request')
	    			->leftJoin( DB::raw('(select Max(Id) as maxid,RequestId from requeststatuses Group By RequestId) as max'), 'max.RequestId', '=', 'request.Id')
	    			->leftJoin('requeststatuses', 'requeststatuses.Id', '=', DB::raw('max.`maxid`'))
	    			->leftJoin('users as applicant', 'request.UserId', '=', 'applicant.Id')
	    			->leftJoin('users as approver', 'requeststatuses.UserId', '=', 'approver.Id')
	    			// ->leftJoin('projects', 'request.ProjectId', '=', 'projects.Id')
	    			// ->select('requeststatuses.Id','request.Id as RequestId','request.UserId as ApplicantId','applicant.Name','request.Leave_Type','request.Leave_Term','request.Start_Date','request.End_Date','request.No_of_Days','request.Reason','request.created_at as Application_Date','projects.Project_Name','approver.Id as ApproverId','approver.Name as Approver','requeststatuses.Request_status as Status','requeststatuses.Comment','requeststatuses.updated_at as Review_Date')
	    			// ->select('request.Id','requeststatuses.Id as StatusId','request.Approver as Approver_Id','request.Request_type','request.Others', 'request.Remarks','request.created_at as Application_Date','approver.Name as Approver','requeststatuses.Request_status as Status','requeststatuses.updated_at as Review_Date','requeststatuses.Comment', 'applicant.Name')
	    			->select('request.Request_type as Type','request.Others', 'request.Remarks','request.created_at as Application_Date','approver.Name as Approver','requeststatuses.Request_status as Status','requeststatuses.updated_at as Review_Date','requeststatuses.Comment', 'applicant.Name')
	    			->orderBy('request.Id','desc')
	    			->where('request.Id','=', $request->RequestId)
	    			->first();

	    			Mail::send('emails.requeststatus', ['me' => $me,'requestdetail' => $requestdetail], function($message) use ($me,$emails,$requestdetail,$NotificationSubject)
	    			{
	    					$emails = array_filter($emails);

	    					array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
	    					$message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');

	    			});

	    		}
	    		else {
	    			return 0;
	    		}

	    	}

	    	return 1;

	    }

	    public function redirectrequest(Request $request)
	    {

	    		$me=(new CommonController)->get_current_user();

	    		$arrRequestId = array();

	    		$input = $request->all();

	    		$Ids = explode(",", $input["Ids"]);

	    		$requestdetail = DB::table('request')
	    		->leftJoin( DB::raw('(select Max(Id) as maxid,RequestId from requeststatuses Group By RequestId) as max'), 'max.RequestId', '=', 'request.Id')
	    		->leftJoin('requeststatuses', 'requeststatuses.Id', '=', DB::raw('max.`maxid`'))
	    		->leftJoin( DB::raw('(select Max(Id) as maxid,TargetId from files where Type="Request" Group By Type,TargetId) as max2'), 'max2.TargetId', '=', 'request.Id')
	    		// ->leftJoin('files', 'files.Id', '=', DB::raw('max2.`maxid` and files.`Type`="Request"'))
	    		->leftJoin('users as applicant', 'request.UserId', '=', 'applicant.Id')
	    		->leftJoin('users as approver', 'requeststatuses.UserId', '=', 'approver.Id')
	    		//->leftJoin('projects', 'leaves.ProjectId', '=', 'projects.Id')
	    		->select('request.Id','requeststatuses.Id as StatusId','request.Approver as Approver_Id','request.Request_type','request.Others', 'request.Remarks','request.created_at as Application_Date','approver.Name as Approver','requeststatuses.Request_status','requeststatuses.updated_at as Review_Date','requeststatuses.Comment')
	    		->orderBy('requeststatuses.Id','desc')
	    		->whereIn('request.Id', $Ids)
	    		->get();


	    		$id=0;

	    		foreach ($requestdetail as $item) {

	    			# code...
	    			if(str_contains($item->Request_status,"Final Approved")==false)
	    			{
	    				$id=DB::table('requeststatuses')->insertGetId(
	    					['RequestId' => $item->Id,
	    					 'UserId' => $input["Approver"],
	    					 'Request_status' => "Pending Approval"
	    					]
	    				);

	    				array_push($arrRequestId,$item->Id);
	    			}

	    		}


	    		if ($id>0)
	    		{

	    			$requestdetail = DB::table('request')
	    			->leftJoin('requeststatuses', 'request.Id', '=', 'requeststatuses.RequestId')
	    			->leftJoin('users as applicant', 'request.UserId', '=', 'applicant.Id')
	    			->leftJoin('users as approver', 'requeststatuses.UserId', '=', 'approver.Id')
	    			// ->select('applicant.Name','leaves.Leave_Type','leaves.Leave_Term','leaves.Start_Date','leaves.End_Date','leaves.No_of_Days','leaves.Reason','leaves.created_at as Application_Date','approver.Name as Approver')
	    			// ->select('request.Id','requeststatuses.Id as StatusId','request.Approver as Approver_Id','request.Request_type','request.Others', 'request.Remarks','request.created_at as Application_Date','approver.Name as Approver','requeststatuses.Request_status as Status','requeststatuses.updated_at as Review_Date','requeststatuses.Comment','applicant.Name')
	    			->select('request.Request_type as Type','request.Others', 'request.Remarks','request.created_at as Application_Date','approver.Name as Approver','requeststatuses.Request_status as Status','requeststatuses.updated_at as Review_Date','requeststatuses.Comment','applicant.Name')
	    			->orderBy('requeststatuses.Id','desc')
	    			->whereIn('request.Id', $Ids)
	    			->first();

	    			$subscribers = DB::table('notificationtype')
	    			->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
	    			->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
	    			->where('notificationtype.Id','=',66)
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

	    			$notify = DB::table('users')
	    			->whereIn('Id', [$me->UserId, $input["Approver"]])
	    			->get();

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

	    			//array_push($emails,"latifah@pronetwork.com.my");

	    			Mail::send('emails.requestredirected', ['requestdetail' => $requestdetail,'from'=>$me->Name], function($message) use ($emails,$requestdetail,$NotificationSubject)
	    			{
	    					array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
	    					$emails = array_filter($emails);
	    					$message->to($emails)->subject($NotificationSubject.' ['.$requestdetail->Name.']');
	    			});

	    			return 1;
	    		}
	    		else {
	    			return 0;
	    		}

	    }

	    public function exportrequest($id)
	    {
	    	$me = (new CommonController)->get_current_user();

	    	$request = DB::table('request')
	    		->leftJoin( DB::raw('(select Max(Id) as maxid,RequestId from requeststatuses Group By RequestId) as max'), 'max.RequestId', '=', 'request.Id')
	    		->leftJoin('requeststatuses', 'requeststatuses.Id', '=', DB::raw('max.`maxid`'))
	    		->leftJoin('users as applicant', 'request.UserId', '=', 'applicant.Id')
	    		->leftJoin('users as approver', 'requeststatuses.UserId', '=', 'approver.Id')
	    		->leftJoin('projects', 'projects.Id', '=', 'request.ProjectId')
	    		->select('request.Id','applicant.Name','requeststatuses.Id as StatusId','request.Approver as Approver_Id','request.Request_type','request.Others', 'request.Remarks','request.Start_Date','request.End_Date','request.created_at','projects.Project_Name','approver.Name as Approver','requeststatuses.Request_status','requeststatuses.updated_at as Review_Date','requeststatuses.Comment')
	    		->where('request.Id', '=', $id)
	    		->orderBy('request.Id','desc')
	    		->first();

	    	$approver=DB::table('requeststatuses')
	    	->leftJoin('users', 'requeststatuses.UserId', '=', 'users.Id')
	    		->where('requeststatuses.RequestId', '=', $id)
	    		->orderBy('requeststatuses.Id','DESC')
	    		->select('requeststatuses.updated_at','requeststatuses.Request_status','users.Name as Approver')
	    		->get();

	    	$html= view('exportrequest', ['me' => $me, 'request' => $request, 'approver' => $approver]);
	    	(new ExportPDFController)->Export($html);

	    }

		public function advancemanagement($start=null,$end=null)
		{
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

			$all = DB::table('advances')
			->select('advances.Id','advancestatuses.Status','users.Name','advances.Purpose','advances.Destination','advances.Start_Date','advances.End_Date','advances.Mode_Of_Transport','advances.Car_No','approver.Name as Approver')
			->leftJoin('users','users.Id','=','advances.UserId')
			->leftJoin( DB::raw('(select Max(Id) as maxid,AdvanceId from advancestatuses Group By AdvanceId) as max'), 'max.AdvanceId', '=', 'advances.Id')
			->leftJoin('advancestatuses', 'advancestatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver','approver.Id','=','advancestatuses.UserId')
			->where(DB::raw('str_to_date(advances.Start_Date,"%d-%M-%Y")'), '>=', DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
			->where(DB::raw('str_to_date(advances.End_Date,"%d-%M-%Y")'), '<=', DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
			->get();

			$advances = DB::table('advances')
			->select('advances.Id','advancestatuses.Status','users.Name','advances.Purpose','advances.Destination','advances.Start_Date','advances.End_Date','advances.Mode_Of_Transport','advances.Car_No','approver.Name as Approver')
			->leftJoin('users','users.Id','=','advances.UserId')
			->leftJoin( DB::raw('(select Max(Id) as maxid,AdvanceId from advancestatuses Group By AdvanceId) as max'), 'max.AdvanceId', '=', 'advances.Id')
			->leftJoin('advancestatuses', 'advancestatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver','approver.Id','=','advancestatuses.UserId')
			->where('advancestatuses.UserId', '=', $me->UserId)
			->get();

			$allfinal = DB::table('advances')
			->select('advances.Id','advancestatuses.Status','users.Name','advances.Purpose','advances.Destination','advances.Start_Date','advances.End_Date','advances.Mode_Of_Transport','advances.Car_No','approver.Name as Approver')
			->leftJoin('users','users.Id','=','advances.UserId')
			->leftJoin( DB::raw('(select Max(Id) as maxid,AdvanceId from advancestatuses Group By AdvanceId) as max'), 'max.AdvanceId', '=', 'advances.Id')
			->leftJoin('advancestatuses', 'advancestatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver','approver.Id','=','advancestatuses.UserId')
			->where('advancestatuses.Status','=','Final Approved')
			->get();

			return view('advancemanagement',['me'=>$me, 'start'=>$start, 'end'=>$end,'advances'=>$advances,'all'=>$all, 'allfinal'=>$allfinal]);
		}

		public function advancedetail($advanceid)
		{
			$me = (new CommonController)->get_current_user();

			$advance = DB::table('advances')
			->select('advancestatuses.Status','advances.Id','users.Name','users.Bank_Account_No','users.Position','projects.Project_Name','advances.Purpose','advances.Destination','advances.Start_Date','advances.End_Date','advances.Mode_Of_Transport','advances.Car_No','approver.Name as Approver',DB::raw('Format((advances.Total_Requested),2) as Total_Requested'))
			->leftJoin('users','users.Id','=','advances.UserId')
			->leftJoin('projects','projects.Id','=','advances.ProjectId')
			->leftJoin('advancestatuses','advances.Id','=','advancestatuses.AdvanceId')
			->leftJoin('users as approver','approver.Id','=','advancestatuses.UserId')
			->where('advances.Id','=',$advanceid)
			->get();

			$advancedetails = DB::table('advancedetails')
			->select('Type','Days',DB::raw('Format((Allowance),2) as Allowance'),DB::raw('Format((Total),2) as Total'))
			->where('AdvanceId','=',$advanceid)
			->get();

			$approver = DB::table('users')
			->leftJoin('accesscontroltemplates', 'users.AccessControlTemplateId', '=', 'accesscontroltemplates.Id')
			->select('users.Id','users.Name')
			->where('accesscontroltemplates.Approve_Claim', '=', 1)
			->get();

			$mylevel = DB::table('approvalsettings')
			->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
			->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
			->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
			->where('approvalsettings.Type', '=', 'Claim')
			->where('approvalsettings.UserId', '=', $me->UserId)
			->orderBy('approvalsettings.Country','asc')
			->orderBy('projects.Project_Name','asc')
			// ->orderBy('approvalsettings.Level','Final Approval","5th Approval","4th Approval","3rd Approval","2nd Approval","1st Approval")
			->orderByRaw("FIELD(approvalsettings.Level , 'Final Approval', '5th Approval', '4th Approval','3rd Approval','2nd Approval','1st Approval') ASC")
			->first();

			// dd($advancedetails);

			return view('advancedetails',['advance'=>$advance,'me'=>$me, 'advancedetails'=>$advancedetails, 'advanceid'=>$advanceid, 'approver'=>$approver, 'mylevel'=>$mylevel]);

		}

		public function approveadvance(Request $request)
		{
				$me=(new CommonController)->get_current_user();

				$input = $request->all();

				$emaillist=array();
				array_push($emaillist,$me->UserId);

				$rules = array(
					'Total_Approved' => 'Required'
					);

					$messages = array(
						'Total_Approved.required' => 'The Total Approved amount field is required'
						);


							$validator = Validator::make($input, $rules,$messages);

							if ($validator->passes())
							{

								DB::table('advances')
											->where('Id', $input["AdvanceId"])
											->update(array(
											'Total_Approved' =>  $input["Total_Approved"]
										));

								DB::table('advancestatuses')
											->where('AdvanceId', $input["AdvanceId"])
											->update(array(
											'Status' =>  $input["Status"]
										));

										$approver = DB::table('advancestatuses')
										->select('advancestatuses.UserId')
										->where('advancestatuses.AdvanceId','=',$input["AdvanceId"])
										->get();

										foreach ($approver as $approverid)
										{

											array_push($emaillist,$approverid->UserId);

										}


										if (count($emaillist)>1)
										{

											$notify = DB::table('users')
											->whereIn('Id', $emaillist)
											->get();

											$subscribers = DB::table('notificationtype')
											->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
											->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
											->where('notificationtype.Id','=',47)
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

											$advance = DB::table('advances')
											->select('advancestatuses.Status','advances.Id','users.Name','users.Bank_Account_No','users.Position','projects.Project_Name','advances.Purpose','advances.Destination','advances.Start_Date','advances.End_Date','advances.Mode_Of_Transport','advances.Car_No','approver.Name as Approver',DB::raw('Format((advances.Total_Requested),2) as Total_Requested'))
											->leftJoin('users','users.Id','=','advances.UserId')
											->leftJoin('projects','projects.Id','=','advances.ProjectId')
											->leftJoin('advancestatuses','advances.Id','=','advancestatuses.AdvanceId')
											->leftJoin('users as approver','approver.Id','=','advancestatuses.UserId')
											->where('advances.Id','=',$input["AdvanceId"])
											->get();


											Mail::send('emails.advanceapproved', ['me' => $me,'advance' => $advance], function($message) use ($emails,$me,$NotificationSubject)
											{
													$emails = array_filter($emails);
													array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
													$message->to($emails)->subject($NotificationSubject.' ['.$advance->Name.']');

											});

											return 1;
										}


									return 1;
							}

				else {

					return json_encode($validator->errors()->toArray());
				}



		}

		public function rejectadvance(Request $request)
		{
				$me=(new CommonController)->get_current_user();

				$input = $request->all();


				$id = DB::table('advancestatuses')
							->where('AdvanceId', $input["AdvanceId"])
							->update(array(
							'Status' =>  'Rejected'
						));

				return 1;



		}


		public function redirectadvance(Request $request)
		{

			$me = (new CommonController)->get_current_user();

			$input = $request->all();


			$advances = DB::table('advances')
			->select('advancestatuses.Status','advances.Id as advanceid','users.Name','users.Bank_Account_No','users.Position','projects.Project_Name','advances.Purpose','advances.Destination','advances.Start_Date','advances.End_Date','advances.Mode_Of_Transport','advances.Car_No','approver.Name as Approver',DB::raw('Format((advances.Total_Requested),2) as Total_Requested'))
			->leftJoin('users','users.Id','=','advances.UserId')
			->leftJoin('projects','projects.Id','=','advances.ProjectId')
			->leftJoin('advancestatuses','advances.Id','=','advancestatuses.AdvanceId')
			->leftJoin('users as approver','approver.Id','=','advancestatuses.UserId')
			->where('advances.Id','=',$input["AdvanceId"])
			->get();

			$advancedetails = DB::table('advancedetails')
			->select('Type','Days',DB::raw('Format((Allowance),2) as Allowance'),DB::raw('Format((Total),2) as Total'))
			->where('AdvanceId','=',$input["AdvanceId"])
			->get();

			foreach ($advances as $advance) {

				# code...
				$id=DB::table('advancestatuses')->insertGetId(
					['AdvanceId' => $advance->advanceid,
					 'UserId' => $input["Approver"],
					 'Status' => "Pending Approval"
					]
				);
			}

			if ($id>0)
			{

				$advances = DB::table('advances')
				->select('advancestatuses.Status','advances.Id as advanceid','users.Name','users.Bank_Account_No','users.Position','projects.Project_Name','advances.Purpose','advances.Destination','advances.Start_Date','advances.End_Date','advances.Mode_Of_Transport','advances.Car_No','approver.Name as Approver',DB::raw('Format((advances.Total_Requested),2) as Total_Requested'))
				->leftJoin('users','users.Id','=','advances.UserId')
				->leftJoin('projects','projects.Id','=','advances.ProjectId')
				->leftJoin('advancestatuses','advances.Id','=','advancestatuses.AdvanceId')
				->leftJoin('users as approver','approver.Id','=','advancestatuses.UserId')
				->where('advances.Id','=',$input["AdvanceId"])
				->get();

				$advancedetails = DB::table('advancedetails')
				->select('Type','Days',DB::raw('Format((Allowance),2) as Allowance'),DB::raw('Format((Total),2) as Total'))
				->where('AdvanceId','=',$input["AdvanceId"])
				->get();

				$notify = DB::table('users')
				->whereIn('Id', [$me->UserId, $input["Approver"]])
				->get();

				$subscribers = DB::table('notificationtype')
				->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
				->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
				->where('notificationtype.Id','=',54)
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

				Mail::send('emails.advanceredirected', ['me'=>$me,'advances' => $advances], function($message) use ($emails,$me,$NotificationSubject)
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

		public function submitadvance(Request $request)
		{

			$me = (new CommonController)->get_current_user();

			$emaillist=array();
			array_push($emaillist,$me->UserId);

			$input = $request->all();

			$advances = DB::table('advances')
			->select('advancestatuses.Status','advances.Id as advanceid','advancestatuses.UserId','users.Name','users.Bank_Account_No','users.Position','projects.Project_Name','advances.Purpose','advances.Destination','advances.Start_Date','advances.End_Date','advances.Mode_Of_Transport','advances.Car_No','approver.Name as Approver',DB::raw('Format((advances.Total_Requested),2) as Total_Requested'))
			->leftJoin('users','users.Id','=','advances.UserId')
			->leftJoin('projects','projects.Id','=','advances.ProjectId')
			->leftJoin('advancestatuses','advances.Id','=','advancestatuses.AdvanceId')
			->leftJoin('users as approver','approver.Id','=','advancestatuses.UserId')
			->where('advances.Id','=',$input["AdvanceId"])
			->get();

			$approver = DB::table('approvalsettings')
			->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
			->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
			->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
			->where('approvalsettings.Type', '=', 'Claim')
			->where('approvalsettings.ProjectId', '<>', '0')
			->orderBy('approvalsettings.Country','asc')
			->orderBy('projects.Project_Name','asc')
			->orderByRaw("FIELD(approvalsettings.Level , '1st Approval','2nd Approval','3rd Approval','4th Approval','5th Approval','Final Approval') ASC")
			->get();


			$submittedfornextapproval=false;
			$final=false;

			foreach ($advances as $advance) {
				# code...
				$submitted=false;

				if ((strpos($advance->Status, 'Rejected') === false) && $advance->Status!="Final Approved")
				{

					foreach ($approver as $user) {

							if (!empty($user->Id) && $user->Project_Name==$advance->Project_Name && $advance->UserId != $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($advance->Status, FILTER_SANITIZE_NUMBER_INT))
							{

								DB::table('advancestatuses')->insert(
									['AdvanceId' => $advance->Id,
									 'UserId' => $user->Id,
									 'Status' => "Pending Approval"
									]
								);
								$submitted=true;
								$submittedfornextapproval=true;
								array_push($emaillist,$user->Id);
								array_push($emaillist,$advance->UserId);

								break;
							}
							elseif (!empty($user->Id) && $user->Project_Name==$advance->Project_Name && $advance->UserId == $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($advance->Status, FILTER_SANITIZE_NUMBER_INT))
							{
								# code...
									$submitted=true;
									array_push($emaillist,$user->Id);
							}
							elseif (!empty($user->Id) && $user->Project_Name==$advance->Project_Name && $advance->UserId == $user->Id && $advance->Status=="Recalled")
							{

								DB::table('advancestatuses')->insert(
									['AdvanceId' => $advance->Id,
									 'UserId' => $user->Id,
									 'Status' => "Pending Approval"
									]
								);
								$submitted=true;
								$submittedfornextapproval=true;
								array_push($emaillist,$user->Id);

								break;
							}
							elseif (!empty($user->Id) && $user->Project_Name==$advance->Project_Name && $advance->UserId == $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($advance->Status, FILTER_SANITIZE_NUMBER_INT))
							{
								# code...
									$submitted=true;
									$submittedfornextapproval=true;
									array_push($emaillist,$user->Id);
									array_push($emaillist,$advance->UserId);
							}
							elseif (!empty($user->Id) && $user->Project_Name==$advance->Project_Name && $advance->UserId != $user->Id && $user->Level=="Final Approval")
							{

								DB::table('advancestatuses')->insert(
									['AdvanceId' => $advance->Id,
									 'UserId' => $user->Id,
									 'Status' => "Pending Approval"
									]
								);
								$submitted=true;
								$submittedfornextapproval=true;
								array_push($emaillist,$user->Id);
								array_push($emaillist,$advance->UserId);

								break;
							}
							else {

							}

						}


					array_push($emaillist,$advance->UserId);
				}
				elseif ($advance->Status=="Final Approved" ||$advance->Status=="Final Approved with Special Attention" || $advance->Status=="Final Rejected")
				{
					$final=true;
					array_push($emaillist,$advance->UserId);
				}

			}

			if($final)
			{

				DB::table('advancestatuses')
							->where('Id', $Id)
							->update(array(
							'Status' =>  $advance->Status
						));

						array_push($emaillist,$advance->UserId);

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
					->where('notificationtype.Id','=',47)
					->get();

				}
				else {
					# code...

					$subscribers = DB::table('notificationtype')
					->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
					->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
					->where('notificationtype.Id','=',55)
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


				$advances = DB::table('advances')
				->select('advancestatuses.Status','advances.Id as advanceid','advancestatuses.UserId','users.Name','users.Bank_Account_No','users.Position','projects.Project_Name','advances.Purpose','advances.Destination','advances.Start_Date','advances.End_Date','advances.Mode_Of_Transport','advances.Car_No','approver.Name as Approver',DB::raw('Format((advances.Total_Requested),2) as Total_Requested'))
				->leftJoin('users','users.Id','=','advances.UserId')
				->leftJoin('projects','projects.Id','=','advances.ProjectId')
				->leftJoin('advancestatuses','advances.Id','=','advancestatuses.AdvanceId')
				->leftJoin('users as approver','approver.Id','=','advancestatuses.UserId')
				->where('advances.Id','=',$input["AdvanceId"])
				->get();

				Mail::send('emails.advanceapproval2', ['me' => $me,'advances' => $advances], function($message) use ($emails,$advances,$NotificationSubject)
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

		public function schedule()
		{
			$me = (new CommonController)->get_current_user();


			$schedules = DB::table('schedules')
			->select('schedules.Id','schedules.Event','schedules.Venue','schedules.Start_Date','schedules.End_Date','schedules.Time','schedules.Remarks','users.Name','Assign.Name as AssignedBy')
			->leftJoin('schedulecandidates', 'schedulecandidates.ScheduleId', '=', 'schedules.Id')
			->leftJoin('users', 'users.Id', '=', 'schedulecandidates.UserId')
			->leftJoin('users as Assign', 'Assign.Id', '=', 'schedules.Assigned_By')
			->get();

			$users = DB::table('users')
			->get();

			// dd($schedules);

			return view('schedule',['me'=>$me,'schedules'=>$schedules,'users'=>$users]);

		}

		public function schedulelist()
		{
			$me = (new CommonController)->get_current_user();

			$today = date('d-M-Y', strtotime('today'));
			$oneweek = date('d-M-Y', strtotime('today +7 days'));

			$schedules = DB::table('schedules')
			->select('schedules.Id','schedules.Event','schedules.Venue','schedules.Start_Date','schedules.End_Date','schedules.Time','schedules.Remarks','users.Name','Assign.Name as AssignedBy')
			->leftJoin('schedulecandidates', 'schedulecandidates.ScheduleId', '=', 'schedules.Id')
			->leftJoin('users', 'users.Id', '=', 'schedulecandidates.UserId')
			->leftJoin('users as Assign', 'Assign.Id', '=', 'schedules.Assigned_By')
			->whereRaw('(str_to_date(schedules.Start_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y") OR str_to_date(schedules.End_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y"))')->get();

			return json_encode($schedules);


		}

		public function myschedulelist()
		{
			$me = (new CommonController)->get_current_user();

			$today = date('d-M-Y', strtotime('today'));
			$oneweek = date('d-M-Y', strtotime('today +7 days'));

			$schedules = DB::table('schedules')
			->select('schedules.Id','schedules.Event','schedules.Venue','schedules.Start_Date','schedules.End_Date','schedules.Time','schedules.Remarks','users.Name','Assign.Name as AssignedBy')
			->leftJoin('schedulecandidates', 'schedulecandidates.ScheduleId', '=', 'schedules.Id')
			->leftJoin('users', 'users.Id', '=', 'schedulecandidates.UserId')
			->leftJoin('users as Assign', 'Assign.Id', '=', 'schedules.Assigned_By')
			->whereRaw('(str_to_date(schedules.Start_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y") OR str_to_date(schedules.End_Date,"%d-%M-%Y") >= str_to_date("'.$today.'","%d-%M-%Y"))')
			->where('schedulecandidates.UserId','=',$me->UserId)
			->get();

			return json_encode($schedules);

		}

		public function userdetailpdf(Request $request,$id)
		{
			$input = $request->all();

			$me = (new CommonController)->get_current_user();

			$family = DB::table('family')
			->select('family.Name','family.Age','family.Relationship','family.Occupation','family.Company_School_Name')
			->where('family.UserId', '=', $id)
			->orderBy('family.Id','desc')
			->get();

			$html=view('userdetailpdf', ['input'=>$input, 'family'=>$family]);

			// return $html;
			(new ExportPDFController)->Export($html);

		}

		public function deleteresume(Request $request)
		{
			$input = $request->all();

			return DB::table('files')
			->where('Id', '=', $input["Id"])
			->delete();

		}


		public function myloan()
		{
			$me = (new CommonController)->get_current_user();

			$date=date('d-M-Y', strtotime('first day of last month'));
			$date = date('d-M-Y', strtotime($date . " +20 days"));

			$user = DB::table('users')
			->select('Id','Name','Bank_Account_No','Position')
			->where('users.Id','=',$me->UserId)
			->first();

			$projects = DB::table('projects')
		     ->get();

			$options= DB::table('options')
			->whereIn('Table', ["users"])
			->orderBy('Table','asc')
			->orderBy('Option','asc')
			->get();

			$myloan = DB::table('staffloans')
			->select('staffloans.Id','staffloanstatuses.Status','staffloans.Type','staffloans.Date',DB::raw('Format((staffloans.Total_Requested),2) as Total_Requested'),DB::raw('Format((staffloans.Total_Approved),2) as Total_Approved'),'projects.Project_Name','approver.Name as Approver')
			->leftJoin('users','users.Id','=','staffloans.UserId')
			->leftJoin('projects','projects.Id','=','staffloans.ProjectId')
			->leftJoin( DB::raw('(select Max(Id) as maxid,StaffLoanId from staffloanstatuses Group By StaffLoanId) as max'), 'max.StaffLoanId', '=', 'staffloans.Id')
			->leftJoin('staffloanstatuses', 'staffloanstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver','approver.Id','=','staffloanstatuses.UserId')
			->where('staffloans.UserId', '=', $me->UserId)
			->get();

			return view('myloanrequest',['loan'=> [],'me'=>$me,'type'=>'personal','user'=>$user, 'projects'=>$projects,'options'=>$options,'myloan'=>$myloan]);

		}

		public function cancelloan(Request $request)				{

				$me=(new CommonController)->get_current_user();

				$input = $request->all();

				$staffloandetail = DB::table('staffloans')
				->leftJoin('staffloanstatuses', 'staffloans.Id', '=', 'staffloanstatuses.StaffLoanId')
				->leftJoin('users as applicant', 'staffloans.UserId', '=', 'applicant.Id')
				->leftJoin('users as approver', 'staffloanstatuses.UserId', '=', 'approver.Id')
				->select('applicant.Name','staffloanstatuses.UserId as ApproverId','staffloans.Type','approver.Name as Approver', 'staffloans.Purpose as Remarks')
				->orderBy('staffloanstatuses.Id','desc')
				->where('staffloans.Id', '=',$input["Id"])
				->first();

				$id=DB::table('staffloanstatuses')->insertGetId(
					['StaffLoanId' => $input["Id"],
					 'UserId' => 0,
					 'Status' => "Cancelled"
					]
				);

				$subscribers = DB::table('notificationtype')
				->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
				->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
				->where('notificationtype.Id','=',57)
				->get();

				if ($id>0)
				{

					$notify = DB::table('users')
					->whereIn('Id', [$me->UserId, $staffloandetail->ApproverId])
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

					$staffloandetail = DB::table('staffloans')
					->leftJoin('staffloanstatuses', 'staffloans.Id', '=', 'staffloanstatuses.StaffLoanId')
					->leftJoin('users as applicant', 'staffloans.UserId', '=', 'applicant.Id')
					->leftJoin('users as approver', 'staffloanstatuses.UserId', '=', 'approver.Id')
					->leftJoin('projects', 'staffloans.ProjectId', '=', 'projects.Id')
					->select('applicant.Name', 'projects.Project_Name as Project_Name' ,'staffloans.Purpose')
					->orderBy('staffloanstatuses.Id','desc')
					->where('staffloans.Id', '=',$input["Id"])
					->first();

					Mail::send('emails.staffloancancel', ['staffloandetail' => $staffloandetail], function($message) use ($emails,$me,$NotificationSubject)
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

		public function applyloan(Request $request)
		{
				$me = (new CommonController)->get_current_user();

				$today = date('d-M-Y', strtotime('today'));

				$emaillist=array();
				array_push($emaillist,$me->UserId);

				$input = $request->all();


				$rules = array(
					'ProjectId' => 'Required',
					'Bank_Account_No' => 'Required',
					'Purpose' => 'Required',
					);

				$messages = array(
					'ProjectId.required' => 'The Project field is required',
					'Bank_Account_No.required' => 'The Bank Account No field is required',
					'Purpose.required' => 'The Purpose field is required'

					);


				$approver = DB::table('approvalsettings')
				->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
				->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
				->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
				->where('approvalsettings.Type', '=', 'Loan')
				->where('approvalsettings.ProjectId', '=', $input["ProjectId"])
				->orderBy('approvalsettings.Country','asc')
				->orderBy('projects.Project_Name','asc')
				->orderByRaw("FIELD(approvalsettings.Level , '1st Approval', '2nd Approval', '3rd Approval','4th Approval','5th Approval','Final Approval') ASC")
				->first();

				$mylevel = DB::table('approvalsettings')
				->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
				->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
				->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
				->where('approvalsettings.Type', '=', 'Loan')
				->where('approvalsettings.UserId', '=', $me->UserId)
				->orderBy('approvalsettings.Country','asc')
				->orderBy('projects.Project_Name','asc')
				->orderByRaw("FIELD(approvalsettings.Level , 'Final Approval', '5th Approval', '4th Approval','3rd Approval','2nd Approval','1st Approval') ASC")
				->get();

					$validator = Validator::make($input, $rules,$messages);

					if ($validator->passes())
					{

							$id=DB::table('staffloans')->insertGetId(
								['UserId' => $me->UserId,
								 'ProjectId' => $input["ProjectId"],
								 'Date' => $today,
								 'Type' => $input["Type"],
								 'Total_Requested' => $input["Total_Requested"],
								 // 'Installment' => $input["Installment"],
								 'Bank_Account_No' => $input['Bank_Account_No'],
								 'Purpose' => $input['Purpose'],
								 // 'SiteName' => $input['SiteId']
								 'Repayment' => $input['Repayment']
							 	]
							);



							DB::table('staffloanstatuses')->insertGetId(
								['UserId' => $approver->Id,
								 'StaffLoanId' => $id,
								 'Status' => "Pending Approval"
							 	]
							);


							DB::table('staffloandetails')->insertGetId(
								['StaffLoanId'=>$id,
								'Type' =>  $input["Purpose"],
								'Total' => $input["Sum7"],
							 	]

							);

							array_push($emaillist,$approver->Id);

							if (count($emaillist)>1)
							{

								$notify = DB::table('users')
								->whereIn('Id', $emaillist)
								->get();

								$subscribers = DB::table('notificationtype')
								->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
								->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
								->where('notificationtype.Id','=',58)
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

								$staffloan = DB::table('staffloans')
								->select('staffloanstatuses.Status','staffloans.Id','users.Name','staffloans.Bank_Account_No','users.Position','projects.Project_Name','staffloans.Date','approver.Name as Approver',DB::raw('Format((staffloans.Total_Requested),2) as Total_Requested'))
								->leftJoin('users','users.Id','=','staffloans.UserId')
								->leftJoin('projects','projects.Id','=','staffloans.ProjectId')
								->leftJoin('staffloanstatuses','staffloans.Id','=','staffloanstatuses.StaffLoanId')
								->leftJoin('users as approver','approver.Id','=','staffloanstatuses.UserId')
								->where('staffloans.Id','=',$id)
								->get();


								Mail::send('emails.staffloanrequest', ['me' => $me,'staffloan' => $staffloan], function($message) use ($emails,$me,$NotificationSubject)
								{
										array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
										$emails = array_filter($emails);
										$message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');

								});

								return 1;
							}


					}

				else {

					return json_encode($validator->errors()->toArray());
				}

				if (count($emaillist)>1)
				{

					$notify = DB::table('users')
					->whereIn('Id', $emaillist)
					->get();

					$subscribers = DB::table('notificationtype')
					->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
					->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
					->where('notificationtype.Id','=',46)
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

					$staffloan = DB::table('staffloans')
					->select('staffloanstatuses.Status','staffloans.Id','users.Name','users.Bank_Account_No','users.Position','projects.Project_Name','staffloans.Destination','staffloans.Date','staffloans.Mode_Of_Transport','staffloans.Car_No','approver.Name as Approver',DB::raw('Format((staffloans.Total_Requested),2) as Total_Requested'))
					->leftJoin('users','users.Id','=','staffloans.UserId')
					->leftJoin('projects','projects.Id','=','staffloans.ProjectId')
					->leftJoin('staffloanstatuses','staffloans.Id','=','staffloanstatuses.StaffLoanId')
					->leftJoin('users as approver','approver.Id','=','staffloanstatuses.UserId')
					->where('staffloans.Id','=',$staffloanid)
					->get();

					return 1;

					// Mail::send('emails.staffloan', ['me' => $me,'staffloan' => $staffloan], function($message) use ($emails,$me,$NotificationSubject)
					// {
					// 		$emails = array_filter($emails);
					// 		array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
					// 		$message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');
					//
					// });
				}

		}

		public function staffloanmanagement($start=null,$end=null)
		{
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
			->whereRaw("str_to_date(staffloans.Date,'%d-%M-%Y') between str_to_date('".$start."','%d-%M-%Y') AND str_to_date('".$end."','%d-%M-%Y')")
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
			->select('staffloans.Id','staffloanstatuses.Status','users.Name','staffloans.Date',DB::raw('Format((staffloans.Total_Requested),2) as Total_Requested'),DB::raw('Format((staffloans.Total_Approved),2) as Total_Approved'),'approver.Name as Approver','staffloans.Status as PaidStatus')
			->leftJoin('users','users.Id','=','staffloans.UserId')
			->leftJoin( DB::raw('(select Max(Id) as maxid,StaffLoanId from staffloanstatuses Group By StaffLoanId) as max'), 'max.StaffLoanId', '=', 'staffloans.Id')
			->leftJoin('staffloanstatuses', 'staffloanstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver','approver.Id','=','staffloanstatuses.UserId')
			->where('staffloanstatuses.Status','=','Final Approved')
			->get();



			$users = DB::table('users')->where('active', 1)->get();
			$users_depts = collect($users)->groupBy('Department')->all();
			return view('staffloanmanagement',['me'=>$me, 'start'=>$start, 'end'=>$end,'staffloans'=>$staffloans,'all'=>$all, 'allfinal'=>$allfinal]);
		}

		public function staffloandetail($staffloanid)
		{
			$me = (new CommonController)->get_current_user();

			$staffloan = DB::table('staffloans')
			->select('staffloanstatuses.Status','staffloans.Id','users.Name','users.Bank_Account_No','users.Position','staffloans.ProjectId','projects.Project_Name','staffloans.Purpose','staffloans.Date','approver.Name as Approver',DB::raw('Format((staffloans.Total_Requested),2) as Total_Requested'),'staffloans.Total_Approved','staffloans.Repayment','staffloans.Repayment_1','staffloans.Repayment_2','staffloans.Repayment_3','staffloans.Repayment_4','approver.Id as approverId','users.Acc_Holder_Name','users.Bank_Name','users.Department','staffloans.Status as PaidStatus')
			->leftJoin('users','users.Id','=','staffloans.UserId')
			->leftJoin( DB::raw('(select Max(Id) as maxid,StaffLoanId from staffloanstatuses Group By StaffLoanId) as max'), 'max.StaffLoanId', '=', 'staffloans.Id')
			->leftJoin('projects','projects.Id','=','staffloans.ProjectId')
			->leftJoin('staffloanstatuses', 'staffloanstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver','approver.Id','=','staffloanstatuses.UserId')
			->where('staffloans.Id','=',$staffloanid)
			->get();



			$cond = 1;
			if( strpos($staffloan[0]->Status,'1st') !== false )
			{
				$cond = "approvalsettings.Level NOT LIKE '%1st%' ";
			}

			$installments = DB::table('staffloaninstallments')
			->select('staffloaninstallments.Id','staffloaninstallments.StaffLoanId','staffloaninstallments.Payment_Date','staffloaninstallments.Amount','staffloaninstallments.Paid','staffloaninstallments.created_at','staffloaninstallments.updated_at')
			->where('staffloaninstallments.StaffLoanId', '=',$staffloanid)
			->orderBy('staffloaninstallments.Payment_Date')
			->get();

			$myattachment = DB::table('files')
			->where('TargetId', '=', $staffloanid)
			->where('Type', '=', 'Staff Loan')
			->get();

			$staffloandetails = DB::table('staffloandetails')
			->select('Type','Days',DB::raw('Format((Allowance),2) as Allowance'),DB::raw('Format((Total),2) as Total'))
			->where('StaffLoanId','=',$staffloanid)
			->get();

			$approver = DB::table('users')
			->leftJoin('accesscontroltemplates', 'users.AccessControlTemplateId', '=', 'accesscontroltemplates.Id')
			->leftJoin('approvalsettings', 'users.Id', '=', 'approvalsettings.UserId')
			->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
			->select('users.Id','users.Name','approvalsettings.Type','approvalsettings.Level','projects.Project_Name')
			->where('accesscontroltemplates.Approve_Staff_Loan', '=', 1)
			->where('approvalsettings.Type','=','Loan')
			->whereRaw($cond)
			// ->where('projects.Project_Name', '=', $me->Department )
			->get();

			// dd($approver);
			$mylevel = DB::table('approvalsettings')
			->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
			->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
			->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
			->where('approvalsettings.Type', '=', 'Loan')
			->where('approvalsettings.UserId', '=', $me->UserId)
			// ->where('approvalsettings.ProjectId', '=', $staffloan[0]->ProjectId)
			->orderBy('approvalsettings.Country','asc')
			->orderBy('projects.Project_Name','asc')
			// ->orderBy('approvalsettings.Level','Final Approval","5th Approval","4th Approval","3rd Approval","2nd Approval","1st Approval")
			->orderByRaw("FIELD(approvalsettings.Level , 'Final Approval', '5th Approval', '4th Approval','3rd Approval','2nd Approval','1st Approval') ASC")
			->first();

			// dd($staffloandetails);

			return view('staffloandetail',['staffloan'=>$staffloan,'me'=>$me, 'staffloandetails'=>$staffloandetails, 'staffloanid'=>$staffloanid, 'approver'=>$approver, 'mylevel'=>$mylevel,'myattachment'=>$myattachment, 'installments' => $installments,'staffloanid'=>$staffloanid]);

		}

		public function updateBankIn(Request $request)
		{
			$me=(new CommonController)->get_current_user();
			$input = $request->all();
			// dd($input)

			$updateBank=DB::table('staffloans')
			->where('Id',$input["StaffLoanId"])
			->update(array(
				'Status' => $input['Status']
			));

			return 1;

		}

		public function approvestaffloan(Request $request)
		{
				$me=(new CommonController)->get_current_user();

				$input = $request->all();
				$rules = array(
					'Total_Approved' => 'Required'
					);

					$messages = array(
						'Total_Approved.required' => 'The Total Approved amount field is required'
						);


							$validator = Validator::make($input, $rules,$messages);

							if ($validator->passes())
							{

								DB::table('staffloans')
											->where('Id', $input["StaffLoanId"])
											->update(array(
											'Total_Approved' =>  $input["Total_Approved"],
											"Repayment_1" => $input["Repayment_1"],
											"Repayment_2" => $input["Repayment_2"],
											"Repayment_3" => $input["Repayment_3"],
											"Repayment_4" => $input["Repayment_4"],
											"Approver" => $input["Approver"]
										));

								// DB::table('staffloanstatuses')
								// 			->where('StaffLoanId', $input["StaffLoanId"])
								// 			->update(array(
								// 			'Status' =>  $input["Status"]
								// 		));
									if( strpos($input['Status'],"Final Approved") === false)
									{
										DB::table('staffloanstatuses')
											->insert([
											'UserId' => $me->UserId,
											'StaffLoanId' => $input["StaffLoanId"],
											'Status' =>  $input["Status"],
											'update_at' => DB::raw('NOW()')
										]);

										DB::table('staffloanstatuses')
											->insert([
											'UserId' => $me->UserId,
											'StaffLoanId' => $input["StaffLoanId"],
											'Status' =>  "Pending Final Approval",
											'update_at' => DB::raw('NOW()')
										]);

									}
									else
									{
										DB::table('staffloanstatuses')
											->where('StaffLoanId', $input["StaffLoanId"])
											->insert([
											'UserId' => $me->UserId,
											'StaffLoanId' => $input["StaffLoanId"],
											'Status' =>  $input["Status"],
											'update_at' => DB::raw('NOW()')
										]);
									}

										if(str_contains($input["Status"],"Approved"))
										{

											$staffloan = DB::table('staffloans')
											->where('Id','=',$input["StaffLoanId"])
											->first();

											$dayth            = date('d');
											$start            = strtotime('today');

											if($dayth>20)
											{
												// 19 1st month
												$firstMonthStart  = strtotime("+18 day", strtotime("first day of next month", $start));
												// 19 2nd month
												$secondMonthStart = strtotime("+1 month", $firstMonthStart);
												// 19 3nd month
												$thirdMonthStart = strtotime("+1 month", $secondMonthStart);
												// 19 4nd month
												$fourthMonthStart = strtotime("+1 month", $thirdMonthStart);

											}
											else {
												// 19 1st month
												$firstMonthStart  = strtotime("+18 day", strtotime("first day of this month", $start));
												// 19 2nd month
												$secondMonthStart = strtotime("+1 month", $firstMonthStart);
												// 19 3nd month
												$thirdMonthStart = strtotime("+1 month", $secondMonthStart);
												// 19 4nd month
												$fourthMonthStart = strtotime("+1 month", $thirdMonthStart);

											}

												//clear all previous staffloaninstallments and staffdeductions
												DB::table('staffloaninstallments')
												->where('StaffLoanId', '=', $input["StaffLoanId"])
												->delete();

												DB::table('staffdeductions')
												->whereRaw('TableRowId not in (select Id from staffloaninstallments)')
												->where('Type', '=', 'STAFF LOAN')
												->delete();

												if($input["Repayment_1"]>0)
												{

													$id=DB::table('staffloaninstallments')->insertGetId(
														['StaffLoanId' => $input["StaffLoanId"],
														 'Payment_Date' => date('d-M-Y',$firstMonthStart),
														 'Amount' => $input["Repayment_1"]
														]
													);

													$insertid=DB::table('staffdeductions')->insertGetId(
														['UserId' => $staffloan->UserId,
														 'Type' => "STAFF LOAN",
														 'Month' => date('M Y',$firstMonthStart),
														 'Date' => date('d-M-Y',$firstMonthStart),
														 'Amount' => $input["Repayment_1"],
														 'FinalAmount' => $input["Repayment_1"],
														 'Description' => "[FROM LOAN RECORD]",
														 'TableRowId' => $id,
														 'created_at' =>date('Y-m-d H:i:s'),
														 'created_by' =>$me->UserId

														]
													);
												}

												if($input["Repayment_2"]>0)
												{
													$id=DB::table('staffloaninstallments')->insertGetId(
														['StaffLoanId' => $input["StaffLoanId"],
														 'Payment_Date' => date('d-M-Y',$secondMonthStart),
														 'Amount' => $input["Repayment_2"]
														]
													);

													$insertid=DB::table('staffdeductions')->insertGetId(
														['UserId' => $staffloan->UserId,
														 'Type' => "STAFF LOAN",
														 'Month' => date('M Y',$secondMonthStart),
														 'Date' => date('d-M-Y',$secondMonthStart),
														 'Amount' => $input["Repayment_2"],
														 'FinalAmount' => $input["Repayment_2"],
														 'Description' => "[FROM LOAN RECORD]",
														 'TableRowId' => $id,
														 'created_at' =>date('Y-m-d H:i:s'),
														 'created_by' =>$me->UserId

														]
													);
												}

												if($input["Repayment_3"]>0)
												{
													$id=DB::table('staffloaninstallments')->insertGetId(
														['StaffLoanId' => $input["StaffLoanId"],
														 'Payment_Date' => date('d-M-Y',$thirdMonthStart),
														 'Amount' => $input["Repayment_3"]
														]
													);

													$insertid=DB::table('staffdeductions')->insertGetId(
														['UserId' => $staffloan->UserId,
														 'Type' => "STAFF LOAN",
														 'Month' => date('M Y',$thirdMonthStart),
														 'Date' => date('d-M-Y',$thirdMonthStart),
														 'Amount' => $input["Repayment_3"],
														 'FinalAmount' => $input["Repayment_3"],
														 'Description' => "[FROM LOAN RECORD]",
														 'TableRowId' => $id,
														 'created_at' =>date('Y-m-d H:i:s'),
														 'created_by' =>$me->UserId

														]
													);
												}

												if($input["Repayment_4"]>0)
												{
													$id=DB::table('staffloaninstallments')->insertGetId(
														['StaffLoanId' => $input["StaffLoanId"],
														 'Payment_Date' => date('d-M-Y',$fourthMonthStart),
														 'Amount' => $input["Repayment_4"]
														]
													);

													$insertid=DB::table('staffdeductions')->insertGetId(
														['UserId' => $staffloan->UserId,
														 'Type' => "STAFF LOAN",
														 'Month' => date('M Y',$fourthMonthStart),
														 'Date' => date('d-M-Y',$fourthMonthStart),
														 'Amount' => $input["Repayment_4"],
														 'FinalAmount' => $input["Repayment_4"],
														 'Description' => "[FROM LOAN RECORD]",
														 'TableRowId' => $id,
														 'created_at' =>date('Y-m-d H:i:s'),
														 'created_by' =>$me->UserId

														]
													);
												}

										}
										else {
											// do nothing
										}

										$input = $request->all();

										$staffloans = DB::table('staffloans')
										->select('staffloanstatuses.Status','staffloans.Id','staffloanstatuses.Id as StatusId','staffloanstatuses.UserId','users.Name','staffloans.Bank_Account_No','users.Position','projects.Project_Name','staffloans.Purpose','staffloans.Date','approver.Name as Approver',DB::raw('Format((staffloans.Total_Requested),2) as Total_Requested'))
										->leftJoin('users','users.Id','=','staffloans.UserId')
										->leftJoin('projects','projects.Id','=','staffloans.ProjectId')
										->leftJoin('staffloanstatuses','staffloans.Id','=','staffloanstatuses.StaffLoanId')
										->leftJoin('users as approver','approver.Id','=','staffloanstatuses.UserId')
										->where('staffloans.Id','=',$input["StaffLoanId"])
										->get();

										$approver = DB::table('approvalsettings')
										->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
										->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
										->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
										->where('approvalsettings.Type', '=', 'StaffLoan')
										->where('approvalsettings.ProjectId', '<>', '0')
										->orderBy('approvalsettings.Country','asc')
										->orderBy('projects.Project_Name','asc')
										->orderByRaw("FIELD(approvalsettings.Level , '1st Approval','2nd Approval','3rd Approval','4th Approval','5th Approval','Final Approval') ASC")
										->get();

										$submittedfornextapproval=false;
										$final=false;

										$emaillist=array();
										array_push($emaillist,$me->UserId);

										foreach ($staffloans as $staffloan) {
											# code...
											$submitted=false;

											if ((strpos($staffloan->Status, 'Rejected') === false) && $staffloan->Status!="Final Approved")
											{

												foreach ($approver as $user) {

														if (!empty($user->Id) && $user->Project_Name==$staffloan->Project_Name && $staffloan->UserId != $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($staffloan->Status, FILTER_SANITIZE_NUMBER_INT))
														{

															DB::table('staffloanstatuses')->insert(
																['StaffLoanId' => $staffloan->Id,
																 'UserId' => $user->Id,
																 'Status' => "Pending Approval"
																]
															);
															$submitted=true;
															$submittedfornextapproval=true;
															array_push($emaillist,$user->Id);
															array_push($emaillist,$staffloan->UserId);

															break;
														}
														elseif (!empty($user->Id) && $user->Project_Name==$staffloan->Project_Name && $staffloan->UserId == $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($staffloan->Status, FILTER_SANITIZE_NUMBER_INT))
														{
															# code...
																$submitted=true;
																array_push($emaillist,$user->Id);

																break;
														}
														elseif (!empty($user->Id) && $user->Project_Name==$staffloan->Project_Name && $staffloan->UserId == $user->Id && $staffloan->Status=="Recalled")
														{

															DB::table('staffloanstatuses')->insert(
																['StaffLoanId' => $staffloan->Id,
																 'UserId' => $user->Id,
																 'Status' => "Pending Approval"
																]
															);
															$submitted=true;
															$submittedfornextapproval=true;
															array_push($emaillist,$user->Id);

															break;
														}
														elseif (!empty($user->Id) && $user->Project_Name==$staffloan->Project_Name && $staffloan->UserId == $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($staffloan->Status, FILTER_SANITIZE_NUMBER_INT))
														{
															# code...
																$submitted=true;
																$submittedfornextapproval=true;
																array_push($emaillist,$user->Id);
																array_push($emaillist,$staffloan->UserId);

																break;
														}
														elseif (!empty($user->Id) && $user->Project_Name==$staffloan->Project_Name && $staffloan->UserId != $user->Id && $user->Level=="Final Approval")
														{

															DB::table('staffloanstatuses')->insert(
																['StaffLoanId' => $staffloan->Id,
																 'UserId' => $user->Id,
																 'Status' => "Pending Approval"
																]
															);
															$submitted=true;
															$submittedfornextapproval=true;
															array_push($emaillist,$user->Id);
															array_push($emaillist,$staffloan->UserId);

															break;
														}
														else {

														}

													}

											}
											elseif ($staffloan->Status=="Final Approved" ||$staffloan->Status=="Final Approved with Special Attention" || $staffloan->Status=="Final Rejected")
											{
												$final=true;
												array_push($emaillist,$staffloan->UserId);

												break;
											}

										}

										if($final)
										{

											DB::table('staffloanstatuses')
														->where('Id', $staffloan->StatusId)
														->update(array(
														'Status' =>  $staffloan->Status
													));

													array_push($emaillist,$staffloan->UserId);

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
												->where('notificationtype.Id','=',61)
												->get();

											}
											else {
												# code...

												$subscribers = DB::table('notificationtype')
												->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
												->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
												->where('notificationtype.Id','=',59)
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


											$staffloans = DB::table('staffloans')
											->select('staffloanstatuses.Status','staffloans.Id as staffloanid','staffloanstatuses.UserId','users.Name','staffloans.Bank_Account_No','users.Position','projects.Project_Name','staffloans.Purpose','staffloans.Date','approver.Name as Approver',DB::raw('Format((staffloans.Total_Requested),2) as Total_Requested'))
											->leftJoin('users','users.Id','=','staffloans.UserId')
											->leftJoin('projects','projects.Id','=','staffloans.ProjectId')
											->leftJoin('staffloanstatuses','staffloans.Id','=','staffloanstatuses.StaffLoanId')
											->leftJoin('users as approver','approver.Id','=','staffloanstatuses.UserId')
											->where('staffloans.Id','=',$input["StaffLoanId"])
											->get();

											Mail::send('emails.staffloanapproval2', ['me' => $me,'staffloans' => $staffloans], function($message) use ($emails,$staffloans,$NotificationSubject)
											{
													array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
													$emails = array_filter($emails);
													$message->to($emails)->subject($NotificationSubject);

											});

											return 1;
										}
										else {
											return 0;
										}
							}

				else {

					return json_encode($validator->errors()->toArray());
				}



		}

		public function rejectstaffloan(Request $request)
		{
				$me=(new CommonController)->get_current_user();

				$input = $request->all();

				$id = DB::table('staffloanstatuses')
							->where('StaffLoanId', $input["StaffLoanId"])
							->update(array(
							'Status' =>  'Rejected'
						));

						$emaillist=array();
						array_push($emaillist,$me->UserId);

						$input = $request->all();

						$staffloans = DB::table('staffloans')
						->select('staffloanstatuses.Status','staffloans.Id','staffloanstatuses.Id as StatusId','staffloanstatuses.UserId','users.Name','staffloans.Bank_Account_No','users.Position','projects.Project_Name','staffloans.Purpose','staffloans.Date','approver.Name as Approver',DB::raw('Format((staffloans.Total_Requested),2) as Total_Requested'))
						->leftJoin('users','users.Id','=','staffloans.UserId')
						->leftJoin('projects','projects.Id','=','staffloans.ProjectId')
						->leftJoin('staffloanstatuses','staffloans.Id','=','staffloanstatuses.StaffLoanId')
						->leftJoin('users as approver','approver.Id','=','staffloanstatuses.UserId')
						->where('staffloans.Id','=',$input["StaffLoanId"])
						->get();

						$approver = DB::table('approvalsettings')
						->leftJoin('users', 'users.Id', '=', 'approvalsettings.UserId')
						->leftJoin('projects', 'projects.Id', '=', 'approvalsettings.ProjectId')
						->select('users.Id','users.Name','approvalsettings.Level','approvalsettings.Country','projects.Project_Name')
						->where('approvalsettings.Type', '=', 'StaffLoan')
						->where('approvalsettings.ProjectId', '<>', '0')
						->orderBy('approvalsettings.Country','asc')
						->orderBy('projects.Project_Name','asc')
						->orderByRaw("FIELD(approvalsettings.Level , '1st Approval','2nd Approval','3rd Approval','4th Approval','5th Approval','Final Approval') ASC")
						->get();


						$submittedfornextapproval=false;
						$final=false;

						foreach ($staffloans as $staffloan) {
							# code...
							$submitted=false;

							if ((strpos($staffloan->Status, 'Rejected') === false) && $staffloan->Status!="Final Approved")
							{

								foreach ($approver as $user) {

										if (!empty($user->Id) && $user->Project_Name==$staffloan->Project_Name && $staffloan->UserId != $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($staffloan->Status, FILTER_SANITIZE_NUMBER_INT))
										{

											DB::table('staffloanstatuses')->insert(
												['StaffLoanId' => $staffloan->Id,
												 'UserId' => $user->Id,
												 'Status' => "Pending Approval"
												]
											);
											$submitted=true;
											$submittedfornextapproval=true;
											array_push($emaillist,$user->Id);
											array_push($emaillist,$staffloan->UserId);

											break;
										}
										elseif (!empty($user->Id) && $user->Project_Name==$staffloan->Project_Name && $staffloan->UserId == $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($staffloan->Status, FILTER_SANITIZE_NUMBER_INT))
										{
											# code...
												$submitted=true;
												array_push($emaillist,$user->Id);
										}
										elseif (!empty($user->Id) && $user->Project_Name==$staffloan->Project_Name && $staffloan->UserId == $user->Id && $staffloan->Status=="Recalled")
										{

											DB::table('staffloanstatuses')->insert(
												['StaffLoanId' => $staffloan->Id,
												 'UserId' => $user->Id,
												 'Status' => "Pending Approval"
												]
											);
											$submitted=true;
											$submittedfornextapproval=true;
											array_push($emaillist,$user->Id);

											break;
										}
										elseif (!empty($user->Id) && $user->Project_Name==$staffloan->Project_Name && $staffloan->UserId == $user->Id && filter_var($user->Level, FILTER_SANITIZE_NUMBER_INT)>filter_var($staffloan->Status, FILTER_SANITIZE_NUMBER_INT))
										{
											# code...
												$submitted=true;
												$submittedfornextapproval=true;
												array_push($emaillist,$user->Id);
												array_push($emaillist,$staffloan->UserId);
										}
										elseif (!empty($user->Id) && $user->Project_Name==$staffloan->Project_Name && $staffloan->UserId != $user->Id && $user->Level=="Final Approval")
										{

											DB::table('staffloanstatuses')->insert(
												['StaffLoanId' => $staffloan->Id,
												 'UserId' => $user->Id,
												 'Status' => "Pending Approval"
												]
											);
											$submitted=true;
											$submittedfornextapproval=true;
											array_push($emaillist,$user->Id);
											array_push($emaillist,$staffloan->UserId);

											break;
										}
										else {

										}

									}


								array_push($emaillist,$staffloan->UserId);
							}
							elseif ($staffloan->Status=="Final Approved" ||$staffloan->Status=="Final Approved with Special Attention" || $staffloan->Status=="Final Rejected")
							{
								$final=true;
								array_push($emaillist,$staffloan->UserId);
							}

						}

						if($final)
						{

							DB::table('staffloanstatuses')
										->where('Id', $staffloan->StatusId)
										->update(array(
										'Status' =>  $staffloan->Status
									));

									array_push($emaillist,$staffloan->UserId);

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
								->where('notificationtype.Id','=',61)
								->get();

							}
							else {
								# code...

								$subscribers = DB::table('notificationtype')
								->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
								->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
								->where('notificationtype.Id','=',59)
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


							$staffloans = DB::table('staffloans')
							->select('staffloanstatuses.Status','staffloans.Id as staffloanid','staffloanstatuses.UserId','users.Name','staffloans.Bank_Account_No','users.Position','projects.Project_Name','staffloans.Purpose','staffloans.Date','approver.Name as Approver',DB::raw('Format((staffloans.Total_Requested),2) as Total_Requested'))
							->leftJoin('users','users.Id','=','staffloans.UserId')
							->leftJoin('projects','projects.Id','=','staffloans.ProjectId')
							->leftJoin('staffloanstatuses','staffloans.Id','=','staffloanstatuses.StaffLoanId')
							->leftJoin('users as approver','approver.Id','=','staffloanstatuses.UserId')
							->where('staffloans.Id','=',$input["StaffLoanId"])
							->get();

							Mail::send('emails.staffloanapproval2', ['me' => $me,'staffloans' => $staffloans], function($message) use ($emails,$staffloans,$NotificationSubject)
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


		public function redirectstaffloan(Request $request)
		{

			$me = (new CommonController)->get_current_user();

			$input = $request->all();


			$staffloans = DB::table('staffloans')
			->select('staffloanstatuses.Status','staffloans.Id as staffloanid','users.Name','staffloans.Bank_Account_No','users.Position','projects.Project_Name','staffloans.Purpose','staffloans.Date','approver.Name as Approver',DB::raw('Format((staffloans.Total_Requested),2) as Total_Requested'))
			->leftJoin('users','users.Id','=','staffloans.UserId')
			->leftJoin('projects','projects.Id','=','staffloans.ProjectId')
			->leftJoin('staffloanstatuses','staffloans.Id','=','staffloanstatuses.StaffLoanId')
			->leftJoin('users as approver','approver.Id','=','staffloanstatuses.UserId')
			->where('staffloans.Id','=',$input["StaffLoanId"])
			->get();

			$staffloandetails = DB::table('staffloandetails')
			->select('Type','Days',DB::raw('Format((Allowance),2) as Allowance'),DB::raw('Format((Total),2) as Total'))
			->where('StaffLoanId','=',$input["StaffLoanId"])
			->get();

			foreach ($staffloans as $staffloan) {

				# code...
				$id=DB::table('staffloanstatuses')->insertGetId(
					['StaffLoanId' => $staffloan->staffloanid,
					 'UserId' => $input["Approver"],
					 'Status' => "Pending Approval"
					]
				);
			}

			if ($id>0)
			{

				$staffloans = DB::table('staffloans')
				->select('staffloanstatuses.Status','staffloans.Id as staffloanid','users.Name','staffloans.Bank_Account_No','users.Position','projects.Project_Name','staffloans.Purpose','staffloans.Date','approver.Name as Approver',DB::raw('Format((staffloans.Total_Requested),2) as Total_Requested'))
				->leftJoin('users','users.Id','=','staffloans.UserId')
				->leftJoin('projects','projects.Id','=','staffloans.ProjectId')
				->leftJoin('staffloanstatuses','staffloans.Id','=','staffloanstatuses.StaffLoanId')
				->leftJoin('users as approver','approver.Id','=','staffloanstatuses.UserId')
				->where('staffloans.Id','=',$input["StaffLoanId"])
				->get();

				$staffloandetails = DB::table('staffloandetails')
				->select('Type','Days',DB::raw('Format((Allowance),2) as Allowance'),DB::raw('Format((Total),2) as Total'))
				->where('StaffLoanId','=',$input["StaffLoanId"])
				->get();

				$notify = DB::table('users')
				->whereIn('Id', [$me->UserId, $input["Approver"]])
				->get();

				$subscribers = DB::table('notificationtype')
				->leftJoin('notificationsubscriber','notificationtype.Id','=','notificationsubscriber.NotificationTypeId')
				->leftJoin('users','users.Id','=','notificationsubscriber.UserId')
				->where('notificationtype.Id','=',60)
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

				Mail::send('emails.staffloanredirected', ['me'=>$me,'staffloans' => $staffloans], function($message) use ($emails,$me,$NotificationSubject)
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

		public function staffloanuploadreceipt(Request $request)
		{
			$filenames="";
			$input = $request->all();
			$insertid=$input["StaffLoanId"];
			$type="Staff Loan";
			$uploadcount=count($request->file('receipt'));



			if ($request->hasFile('receipt')) {
				for($i=0; $i<$uploadcount; $i++) {

						# code...
						$file = $request->file('receipt')[$i];
						$destinationPath=public_path()."/private/upload/Staff Loan";
						$extension = $file->getClientOriginalExtension();
						$originalName=$file->getClientOriginalName();
						$fileSize=$file->getSize();
						$fileName=time()."_".$i."_".$uploadcount.".".$extension;
						$upload_success = $file->move($destinationPath, $fileName);
						$insert=DB::table('files')->insertGetId(
							['Type' => $type,
							 'TargetId' => $insertid,
							 'File_Name' => $originalName,
							 'File_Size' => $fileSize,
							 'Web_Path' => '/private/upload/Staff Loan/'.$fileName
							]
						);

						if ($i == ($uploadcount-1)) {
							$filenames.= $insert."|".url('/private/upload/Staff Loan/'.$fileName)."|" .$originalName;
						} else {
							$filenames.= $insert."|".url('/private/upload/Staff Loan/'.$fileName)."|" .$originalName.",";
						}

				}
				return $filenames;
					//return '/private/upload/'.$fileName;
			}
			else {
				return 0;
			}

		}

		public function staffloandeletereceipt(Request $request)
		{
			$input = $request->all();

			return DB::table('files')
			->where('Id', '=', $input["Id"])
			->delete();

		}


		public function myloandetail($staffloanid)
		{
			$me = (new CommonController)->get_current_user();

			$staffloan = DB::table('staffloans')
			->select('staffloanstatuses.Status','staffloans.Id','users.Name','staffloans.Bank_Account_No','users.Position','projects.Project_Name','staffloans.Purpose','approver.Name as Approver',DB::raw('Format((staffloans.Total_Requested),2) as Total_Requested'),'staffloans.Total_Approved')
			->leftJoin('users','users.Id','=','staffloans.UserId')
			->leftJoin( DB::raw('(select Max(Id) as maxid,StaffLoanId from staffloanstatuses Group By StaffLoanId) as max'), 'max.StaffLoanId', '=', 'staffloans.Id')
			->leftJoin('projects','projects.Id','=','staffloans.ProjectId')
			->leftJoin('staffloanstatuses', 'staffloanstatuses.Id', '=', DB::raw('max.`maxid`'))
			->leftJoin('users as approver','approver.Id','=','staffloanstatuses.UserId')
			->where('staffloans.Id','=',$staffloanid)
			->get();

			$myattachment = DB::table('files')
			->where('TargetId', '=', $staffloanid)
			->where('Type', '=', 'Staff Loan')
			->get();

			$staffloandetails = DB::table('staffloandetails')
			->select('Type','Days',DB::raw('Format((Allowance),2) as Allowance'),DB::raw('Format((Total),2) as Total'))
			->where('StaffLoanId','=',$staffloanid)
			->get();

			return view('myloandetail',['staffloan'=>$staffloan,'me'=>$me, 'staffloandetails'=>$staffloandetails, 'staffloanid'=>$staffloanid, 'myattachment'=>$myattachment]);

		}


		public function mypayslip()
		{

			$me = (new CommonController)->get_current_user();

			return view('mypayslip', ['me' => $me]);
		}

		public function downloadpayslip(Request $request)
		{
			$me = (new CommonController)->get_current_user();

			// if (Hash::check($request->Payslip_Password,$me->Payslip_Password)) {
			if ($request->Payslip_Password == $me->Payslip_Password) {
				$month = $request->Payslip_Month;
				$year = $request->Payslip_Year;
				$staffid = $me->Staff_ID;

				$exist = Storage::has("payslips/$year/$month/$staffid.pdf");

				if ($exist) {
					$file = storage_path("app/payslips/$year/$month/$staffid.pdf");



					$headers = [
		              'Content-Type' => 'application/pdf',
		            ];

					return response()->download($file, 'Payslip_'. $staffid . '_' . $month . '_' . $year . '.pdf', $headers);

				}

				//file not found
				abort(404);
			}

			return view('errors.denied', ['me' => $me]);

		}

		public function ewallet($start=null, $end=null,$includeResigned=null)
		{
			$me = (new CommonController)->get_current_user();
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


		$expenses = DB::table('ewallet')
			->select(DB::raw('Sum(ewallet.Amount) as total'),DB::raw('IF(ewallet.Expenses_Type != "",ewallet.Expenses_Type,"EMPTY") as Expenses_Type'))
			->where('Type','=','Expenses')
			->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
      		->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
			->groupBy('ewallet.Expenses_Type')
			->get();
		$fion=DB::table('fionrecord')
		->select(
			DB::Raw('SUM(IF(fionrecord.Type = "Starting Balance",fionrecord.Amount,0)) as cash_on_hand'),
			DB::Raw('SUM(IF(fionrecord.Type= "Reimburse",fionrecord.Amount,0)) as reimburse'),
			DB::Raw('SUM(IF(fionrecord.Type= "Submission Acct",fionrecord.Amount,0)) as submission_acct')
		)
		->whereRaw('str_to_date("'.$end.'","%d-%M-%Y")')
		->get();

		$fiontopup = DB::table('ewallet')
		->select(
		DB::raw('SUM(Amount) as `Total_TopUp`'))
		->where('Type','=','Top-up')
		->get();

		$ewalletData=DB::Table('ewallet')
		->select(
			DB::Raw('SUM(IF(ewallet.verified_by = 0 AND ewallet.Type = "Expenses",ewallet.Amount,0)) as not_verified'),
			DB::Raw('SUM(IF(ewallet.verified_by <> 0 AND ewallet.Type = "Expenses",ewallet.Amount,0)) as verified')
		)
		->whereRaw('str_to_date(ewallet.Date,"%d-%M-%Y") between str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y")')

		->get();

		$verified=DB::Table('ewallet')
		->select(
			DB::Raw('SUM(Amount) as verified')
		)
		->where('ewallet.verified_by','>','0')
		->whereRaw('str_to_date(ewallet.Date,"%d-%M-%Y") <= str_to_date("'.$end.'","%d-%M-%Y")')
		->first();

		// $expenses = DB::table('options')
		// ->leftJoin('ewallet','ewallet.Expenses_Type','=','options.Option')
		// ->select(DB::raw('Sum(ewallet.Amount) as total'),'options.Option','ewallet.Date')
		// ->where('Table','=','ewallet')
		// ->where('Field','=','Expenses_Type')
		// ->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
  //       ->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		// ->groupBy('options.Option')
		// ->get();

		// Requested by Afiqin to remove restriction 18 Feb 2020
			// if($me->UserId == 656)
			// {
			// 	$ewallet = DB::table('users')
			// 	->select('users.Id','users.StaffId','users.Company','users.Name','users.Department','users.Position',
			// 	DB::raw('(select SUM(Amount) FROM ewallet Where Type="Top-up" and ewallet.UserId=users.Id) as `Total_TopUp`'),
			// 	DB::raw('(select SUM(Amount) FROM ewallet Where Type!="Top-up" and ewallet.UserId=users.Id) as `Total_Expenses`'),
			// 	DB::raw('(select SUM(Amount) FROM ewallet Where Type="Top-up" and ewallet.UserId=users.Id and str_to_date(ewallet.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") and str_to_date(ewallet.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")) as `Current_TopUp`'),
			// 	DB::raw('(select SUM(Amount) FROM ewallet Where Type!="Top-up" and ewallet.UserId=users.Id and str_to_date(ewallet.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") and str_to_date(ewallet.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")) as `Current_Expenses`'),
			// 	DB::raw('(select SUM(Amount) FROM ewallet Where Type="Top-up" and ewallet.UserId=users.Id)-(select SUM(Amount) FROM ewallet Where Type!="Top-up" and ewallet.UserId=users.Id) as Balance'))
			// 	->orderBy('users.Name')
			// 	->where('users.Department','=','MY_Department_LOG')
			// 	->get();
			// }
			// else
			// {
			$filter="1";

			if($includeResigned && $includeResigned!="false")
			{
				$filter='1';
			}
			else {
				$filter='users.Resignation_Date = ""';
			}

			$ewallet = DB::table('users')
			->select('users.Id','users.StaffId','users.Company','users.Name','users.Department','users.Position',
			DB::raw('(select SUM(Amount) FROM ewallet Where Type="Top-up" and ewallet.UserId=users.Id) as `Total_TopUp`'),
			DB::raw('(select SUM(Amount) FROM ewallet Where Type!="Top-up" and ewallet.UserId=users.Id and ewallet.Expenses_Type!="Shell Card") as `Total_Expenses`'),
			DB::raw('(select SUM(Amount) FROM ewallet Where Type="Top-up" and ewallet.UserId=users.Id and str_to_date(ewallet.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") and str_to_date(ewallet.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")) as `Current_TopUp`'),
			DB::raw('(select SUM(Amount) FROM ewallet Where Type!="Top-up" and ewallet.UserId=users.Id and str_to_date(ewallet.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") and str_to_date(ewallet.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y") and ewallet.Expenses_Type!="Shell Card" ) as `Current_Expenses`'),
			DB::raw('(select SUM(Amount) FROM ewallet Where Type="Top-up" and ewallet.UserId=users.Id)-(select SUM(Amount) FROM ewallet Where Type!="Top-up" and ewallet.UserId=users.Id AND Expenses_Type!="Shell Card") as Balance'))
			->whereRaw($filter)
			->whereRaw('(select SUM(Amount) FROM ewallet Where Type!="Top-up" and ewallet.UserId=users.Id and ewallet.Expenses_Type!="Shell Card")>0')
			->orderBy('users.Name')
			->get();

			$ewallet2 = DB::table('users')
			->select('users.Id','users.StaffId','users.Company','users.Name','users.Department','users.Position',
			DB::raw('(select SUM(Amount) FROM ewallet Where Type="Top-up" and ewallet.UserId=users.Id) as `Total_TopUp`'),
			DB::raw('(select SUM(Amount) FROM ewallet Where Type!="Top-up" and ewallet.UserId=users.Id and ewallet.Expenses_Type!="Shell Card") as `Total_Expenses`'),
			DB::raw('(select SUM(Amount) FROM ewallet Where Type="Top-up" and ewallet.UserId=users.Id and str_to_date(ewallet.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") and str_to_date(ewallet.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")) as `Current_TopUp`'),
			DB::raw('(select SUM(Amount) FROM ewallet Where Type!="Top-up" and ewallet.UserId=users.Id and str_to_date(ewallet.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") and str_to_date(ewallet.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y") and ewallet.Expenses_Type!="Shell Card" ) as `Current_Expenses`'),
			DB::raw('(select SUM(Amount) FROM ewallet Where Type="Top-up" and ewallet.UserId=users.Id)-(select SUM(Amount) FROM ewallet Where Type!="Top-up" and ewallet.UserId=users.Id AND Expenses_Type!="Shell Card") as Balance'))
			// ->whereRaw($filter)
			// ->whereRaw('(select SUM(Amount) FROM ewallet Where Type!="Top-up" and ewallet.UserId=users.Id and ewallet.Expenses_Type!="Shell Card")>0')
			->orderBy('users.Name')
			->get();



			// }
			$collection=collect($ewallet2);

			return view('ewallet', ['me' => $me,'ewallet' => $ewallet,'start'=>$start,'end'=>$end,'expenses'=>$expenses,'fion'=>$fion,'collection'=>$collection,'ewalletData'=>$ewalletData,'fiontopup'=>$fiontopup,'verified'=>$verified,'includeResigned'=>$includeResigned]);

		}

		public function ewalletdetails($type,$start,$end,$userid=null)
		{
			$me = (new CommonController)->get_current_user();

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

			if($type == "EMPTY")
			{
				$type = "";
			}

			if($userid == null)
			{

				$ewalletrecord = DB::table('ewallet')
				->select('ewallet.Id','ewallet.Date','projects.Project_Name','tracker.Project_Code','ewallet.Amount','ewallet.Remarks','ewallet.created_at','ewallet.updated_at','creator.Name as Created_By','creator.Company')
				->leftJoin('projects','ewallet.ProjectId','=','projects.Id')
				->leftJoin('tracker','ewallet.TrackerId','=','tracker.Id')
				->leftJoin('users as creator','ewallet.created_by','=','creator.Id')
				->where('ewallet.Expenses_Type','=',$type)
				->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		  		->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
				->orderByRaw('str_to_date(ewallet.Date,"%d-%M-%Y") DESC')
				->get();
			}
			else
			{
				$ewalletrecord = DB::table('ewallet')
				->select('ewallet.Id','ewallet.Date','projects.Project_Name','tracker.Project_Code','ewallet.Amount','ewallet.Remarks','ewallet.created_at','ewallet.updated_at','creator.Name as Created_By','creator.Company')
				->leftJoin('projects','ewallet.ProjectId','=','projects.Id')
				->leftJoin('tracker','ewallet.TrackerId','=','tracker.Id')
				->leftJoin('users as creator','ewallet.created_by','=','creator.Id')
				->where('ewallet.Expenses_Type','=',$type)
				->where('ewallet.UserId','=',$userid)
				->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		  		->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
				->orderByRaw('str_to_date(ewallet.Date,"%d-%M-%Y") DESC')
				->get();

			}

			$files = DB::table('files')->select('Id', 'TargetId', 'Web_Path')->where('Type', 'fionrecord')->get();
					$filesByGroup = collect($files)->groupBy('TargetId')->toArray();

			return view('ewalletdetails', ['me' => $me,'ewalletrecord'=>$ewalletrecord,'type'=>$type,'start'=>$start,'end'=>$end,'filesByGroup'=>$filesByGroup,'userid'=>$userid]);

		}

		public function ewalletsummarybreakdown($start,$end,$type)
		{
			$me = (new CommonController)->get_current_user();

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

			switch ($type) {
				case 'Top Up to Fion':
					// code...
					$ewalletrecord = DB::table('fionrecord')
					->select('fionrecord.Id','fionrecord.Date','fionrecord.Type','fionrecord.Amount','fionrecord.Remarks','fionrecord.created_at','fionrecord.updated_at','creator.Name as Created_By','creator.Company')
					->leftJoin('users as creator','fionrecord.created_by','=','creator.Id')
					->where(DB::raw('str_to_date(fionrecord.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
					->where('Type','=','Starting Balance')
					->orderByRaw('str_to_date(fionrecord.Date,"%d-%M-%Y") DESC')
					->get();

					break;

				case 'Fion Top Up to Staff':
					// code...
					$ewalletrecord = DB::table('ewallet')
					->select('ewallet.Id','ewallet.Date','projects.Project_Name','tracker.Project_Code','ewallet.Amount','ewallet.Remarks','ewallet.created_at','ewallet.updated_at','creator.Name as Created_By','creator.Company')
					->leftJoin('projects','ewallet.ProjectId','=','projects.Id')
					->leftJoin('tracker','ewallet.TrackerId','=','tracker.Id')
					->leftJoin('users as creator','ewallet.created_by','=','creator.Id')

			  	->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
					->where('ewallet.Type','=','Top-up')
					->orderByRaw('str_to_date(ewallet.Date,"%d-%M-%Y") DESC')
					->get();
					break;

				case 'Staff Resits':
					// code...
					$ewalletrecord = DB::table('ewallet')
					->select('ewallet.Id','ewallet.Date','projects.Project_Name','tracker.Project_Code','ewallet.Amount','ewallet.Remarks','ewallet.created_at','ewallet.updated_at','creator.Name as Created_By','creator.Company')
					->leftJoin('projects','ewallet.ProjectId','=','projects.Id')
					->leftJoin('tracker','ewallet.TrackerId','=','tracker.Id')
					->leftJoin('users as creator','ewallet.created_by','=','creator.Id')
					->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
			  	->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
					->where('ewallet.Type','=','Expenses')
					->orderByRaw('str_to_date(ewallet.Date,"%d-%M-%Y") DESC')
					->get();

					break;

				case 'Not Verified':
					// code...
					$ewalletrecord = DB::table('ewallet')
					->select('ewallet.Id','ewallet.Date','projects.Project_Name','tracker.Project_Code','ewallet.Amount','ewallet.Remarks','ewallet.created_at','ewallet.updated_at','creator.Name as Created_By','creator.Company')
					->leftJoin('projects','ewallet.ProjectId','=','projects.Id')
					->leftJoin('tracker','ewallet.TrackerId','=','tracker.Id')
					->leftJoin('users as creator','ewallet.created_by','=','creator.Id')
					->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
			  	->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
					->where('ewallet.Type','=','Expenses')
					->where('verified_at','=','0000-00-00 00:00:00')
					->orderByRaw('str_to_date(ewallet.Date,"%d-%M-%Y") DESC')
					->get();
					break;

				case 'Verified':
					// code...
					$ewalletrecord = DB::table('ewallet')
					->select('ewallet.Id','ewallet.Date','projects.Project_Name','tracker.Project_Code','ewallet.Amount','ewallet.Remarks','ewallet.created_at','ewallet.updated_at','creator.Name as Created_By','creator.Company')
					->leftJoin('projects','ewallet.ProjectId','=','projects.Id')
					->leftJoin('tracker','ewallet.TrackerId','=','tracker.Id')
					->leftJoin('users as creator','ewallet.created_by','=','creator.Id')
					->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
			  	->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
					->where('ewallet.Type','=','Expenses')
					->where('ewallet.verified_by','>','0')
					->orderByRaw('str_to_date(ewallet.Date,"%d-%M-%Y") DESC')
					->get();

					break;

				case 'Reimbursed to Fion':
						// code...
						$ewalletrecord = DB::table('fionrecord')
						->select('fionrecord.Id','fionrecord.Date','fionrecord.Type','fionrecord.Amount','fionrecord.Remarks','fionrecord.created_at','fionrecord.updated_at','creator.Name as Created_By','creator.Company')
						->leftJoin('users as creator','fionrecord.created_by','=','creator.Id')
				  	->where(DB::raw('str_to_date(fionrecord.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
						->where('Type','=','Reimburse')
						->orderByRaw('str_to_date(fionrecord.Date,"%d-%M-%Y") DESC')
						->get();
						break;

				case 'Submission Acct':
						// code...
						$ewalletrecord = DB::table('fionrecord')
						->select('fionrecord.Id','fionrecord.Date','fionrecord.Type','fionrecord.Amount','fionrecord.Remarks','fionrecord.created_at','fionrecord.updated_at','creator.Name as Created_By','creator.Company')
						->leftJoin('users as creator','fionrecord.created_by','=','creator.Id')
				  	->where(DB::raw('str_to_date(fionrecord.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
						->where('Type','=','Submission Acct')
						->orderByRaw('str_to_date(fionrecord.Date,"%d-%M-%Y") DESC')
						->get();
						break;

					case 'Total':
							// code...
							$ewalletrecord = DB::table('fionrecord')
							->select('fionrecord.Id','fionrecord.Date','fionrecord.Type','fionrecord.Amount','fionrecord.Remarks','fionrecord.created_at','fionrecord.updated_at','creator.Name as Created_By','creator.Company')
							->leftJoin('users as creator','fionrecord.created_by','=','creator.Id')
					  	->where(DB::raw('str_to_date(fionrecord.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
							->where('Type','=','Starting Balance')
							->orderByRaw('str_to_date(fionrecord.Date,"%d-%M-%Y") DESC')
							->get();
							break;
			}

			return view('ewalletsummarybreakdown', ['me' => $me,'ewalletrecord'=>$ewalletrecord,'type'=>$type,'start'=>$start,'end'=>$end]);

		}

		public function fionrecord($start=null, $end=null)
		{
			$me = (new CommonController)->get_current_user();

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

			$fionrecord = DB::table('fionrecord')
			->select('fionrecord.Id','fionrecord.Date','fionrecord.Type','fionrecord.Amount','fionrecord.Remarks','fionrecord.created_at','fionrecord.updated_at','creator.Name as Created_By')
			->leftJoin('users as creator','fionrecord.created_by','=','creator.Id')
			->where(DB::raw('str_to_date(fionrecord.Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
	  	->where(DB::raw('str_to_date(fionrecord.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
			->orderByRaw('str_to_date(fionrecord.Date,"%d-%M-%Y") DESC')
			->get();

			$files = DB::table('files')->select('Id', 'TargetId', 'Web_Path')->where('Type', 'fionrecord')->get();
					$filesByGroup = collect($files)->groupBy('TargetId')->toArray();

			return view('fionrecord', ['me' => $me,'fionrecord' => $fionrecord,'start'=>$start,'end'=>$end,'filesByGroup'=>$filesByGroup]);

		}

		public function ewalletrecord($userid,$start=null, $end=null)
		{
			$me = (new CommonController)->get_current_user();

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

			$ewallet = DB::table('users')
			->select('users.Id','users.StaffId as Staff_ID','users.Name','users.Department','users.Position',
			DB::raw('(select IF(SUM(Amount),SUM(Amount),0) FROM ewallet Where Type="Top-up" and ewallet.UserId=users.Id) as `Total_Top-Up`'),
			DB::raw('(select IF(SUM(Amount),SUM(Amount),0) FROM ewallet Where Type!="Top-up" and ewallet.UserId=users.Id AND str_to_date(ewallet.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") and ewallet.Expenses_Type!="Shell Card" and str_to_date(ewallet.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")) as `Total_Expenses`'),
			DB::raw('(select IF(SUM(Amount),SUM(Amount),0) FROM ewallet Where Type="Top-up" and ewallet.UserId=users.Id)-(select IF(SUM(Amount),SUM(Amount),0) FROM ewallet Where Type!="Top-up" and ewallet.UserId=users.Id AND Expenses_Type!="Shell Card") as Balance'))
			->where('users.Id', '=',$userid)
			->orderBy('users.Name')
			->first();

		// $expenses = DB::table('options')
		// ->leftJoin('ewallet','ewallet.Expenses_Type','=','options.Option')
		// ->select(DB::raw('Sum(ewallet.Amount) as total'),'options.Option','ewallet.Date')
		// ->where('Table','=','ewallet')
		// ->where('Field','=','Expenses_Type')
		// ->where('ewallet.UserId', '=',$userid)
		// ->groupBy('options.Option')
		// ->get();

			$expenses = DB::table('ewallet')
			->select(DB::raw('Sum(ewallet.Amount) as total'),'ewallet.Expenses_Type')
			->where('ewallet.UserId', '=',$userid)
			->whereRaw('str_to_date(ewallet.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") and str_to_date(ewallet.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y")')
			->where('Type','=','Expenses')
			->where('Expenses_Type','<>',"Shell Card")
			->groupBy('ewallet.Expenses_Type')
			->get();

			$projects = DB::table('projects')
			->where('projects.Project_Name','not like','%department%')
			->get();

			$sitelist = DB::table('tracker')
			->select('Id','ProjectId','Project_Code',DB::raw('`Site ID` as site_id'),DB::raw('`Site LRD` as site_lrd'),DB::raw('`Site Name` as site_name'))
			->get();

			$ewalletrecord = DB::table('ewallet')
			->select('ewallet.Id','ewallet.Date','projects.Project_Name','tracker.Project_Code','ewallet.Type','ewallet.Expenses_Type','ewallet.Amount','ewallet.Remarks','ewallet.created_at','ewallet.updated_at','creator.Name as Created_By')
			->leftJoin('projects','ewallet.ProjectId','=','projects.Id')
			->leftJoin('tracker','ewallet.TrackerId','=','tracker.Id')
			->leftJoin('users as creator','ewallet.created_by','=','creator.Id')
			->where('ewallet.UserId', '=',$userid)
			->where('ewallet.Expenses_Type', '!=','Shell Card')
			->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
	  	->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
			->orderByRaw('str_to_date(ewallet.Date,"%d-%M-%Y") DESC')
			->get();

			$options= DB::table('options')
			->whereIn('Table', ["ewallet"])
			->orderBy('Table','asc')
			->orderBy('Option','asc')
			->get();

			$files = DB::table('files')->select('Id', 'TargetId', 'Web_Path')->where('Type', 'eWallet')->get();
        	$filesByGroup = collect($files)->groupBy('TargetId')->toArray();

			return view('ewalletrecord', ['me' => $me,'ewallet'=>$ewallet,'ewalletrecord' => $ewalletrecord,'userid'=>$userid,'projects'=>$projects,'sitelist'=>$sitelist,'options'=>$options,'filesByGroup' => $filesByGroup,'expenses'=>$expenses,'userid'=>$userid,'start'=>$start,'end'=>$end]);

		}

		public function siteewalletrecord($trackerid)
		{
			$me = (new CommonController)->get_current_user();

			$ewallet = DB::table('tracker')
			->select(DB::raw('(select IF(SUM(Amount),SUM(Amount),0) FROM ewallet Where Type="Top-up" and ewallet.TrackerId=tracker.Id) as `Total_Top-Up`'),
			DB::raw('(select IF(SUM(Amount),SUM(Amount),0) FROM ewallet Where Type!="Top-up" and ewallet.TrackerId=tracker.Id) as `Total_Expenses`'),
			DB::raw('(select IF(SUM(Amount),SUM(Amount),0) FROM ewallet Where Type="Top-up" and ewallet.TrackerId=tracker.Id)-(select IF(SUM(Amount),SUM(Amount),0) FROM ewallet Where Type!="Top-up" and ewallet.TrackerId=tracker.Id) as Balance'))
			->where('tracker.Id', '=',$trackerid)
			->first();

			$ewalletrecord = DB::table('ewallet')
			->select('ewallet.Id','ewallet.Date','projects.Project_Name','tracker.Project_Code','ewallet.Type','ewallet.Expenses_Type','ewallet.Amount','ewallet.Remarks','ewallet.created_at','ewallet.updated_at','creator.Name as Created_By')
			->leftJoin('projects','ewallet.ProjectId','=','projects.Id')
			->leftJoin('tracker','ewallet.TrackerId','=','tracker.Id')
			->leftJoin('users as creator','ewallet.created_by','=','creator.Id')
			->where('ewallet.TrackerId', '=',$trackerid)
			->orderByRaw('str_to_date(ewallet.Date,"%d-%M-%Y") DESC')
			->get();

			$projects = DB::table('projects')
			->where('projects.Project_Name','not like','%department%')
			->get();

			$sitelist = DB::table('tracker')
			->select('Id','ProjectId','Project_Code','Site ID','Site LRD','Site Name')
			->get();

			$siteinfo= DB::table('tracker')
			->where('Id', '=',$trackerid)
			->first();

			$ewalletrecord = DB::table('ewallet')
			->select('ewallet.Id','ewallet.Date','projects.Project_Name','tracker.Project_Code','ewallet.Type','ewallet.Expenses_Type','ewallet.Amount','ewallet.Remarks','ewallet.created_at','ewallet.updated_at','creator.Name as Created_By')
			->leftJoin('projects','ewallet.ProjectId','=','projects.Id')
			->leftJoin('tracker','ewallet.TrackerId','=','tracker.Id')
			->leftJoin('users as creator','ewallet.created_by','=','creator.Id')
			->where('ewallet.TrackerId', '=',$trackerid)
			->orderByRaw('str_to_date(ewallet.Date,"%d-%M-%Y") DESC')
			->get();

			$options= DB::table('options')
			->whereIn('Table', ["ewallet"])
			->orderBy('Table','asc')
			->orderBy('Option','asc')
			->get();

			$files = DB::table('files')->select('Id', 'TargetId', 'Web_Path')->where('Type', 'eWallet')->get();
        	$filesByGroup = collect($files)->groupBy('TargetId')->toArray();

			return view('siteewalletrecord', ['me' => $me,'siteinfo'=>$siteinfo,'ewallet'=>$ewallet,'ewalletrecord' => $ewalletrecord,'projects'=>$projects,'sitelist'=>$sitelist,'options'=>$options,'filesByGroup' => $filesByGroup]);

		}

		public function upload(Request $request)
    {
        $me = (new CommonController)->get_current_user();
        $input = $request->all();
        $time = Carbon::now();
        $project = DB::table('projects')
        ->select('projects.Id','projects.Project_Name')
        ->leftJoin('ewallet','ewallet.ProjectId','=','projects.Id')
          ->where('ewallet.Id','=',$input['ewalletId'])
    			->first();
    	if($project != null)
    	{
        $tracker = DB::table('tracker')
        ->select('tracker.Project_Code')
        ->where('tracker.ProjectID','=',$project->Id)
    			->first();
    	}

        $filenames="";
        $attachmentUrl = null;
        if($project != null && $tracker != null)
        {
        $destinationPath="private/upload/Site Document/".$project->Project_Name."/".$tracker->Project_Code."/eWallet/";
    	}
    	else
    	{
    	$destinationPath="private/upload/Site Document/topup/eWallet/";
    	}
        $type="eWallet";
        $uploadcount=count($request->file('uploadfile'));
        if ($request->hasFile('uploadfile')) {

            for ($i=0; $i <$uploadcount ; $i++) {
                # code...
                $file = $request->file('uploadfile')[$i];
                // $destinationPath=public_path()."/private/upload/Delivery";
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize=$file->getSize();
                $fileName=time()."_".$i.".".$extension;
                $upload_success = $file->move($destinationPath,$fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => $type,
                     'TargetId' => $input['ewalletId'],
                     'File_Name' => $originalName.$time,
                     'File_Size' => $fileSize,
                     'Web_Path' => $destinationPath.$fileName
                    ]
                );
                $attachmentUrl = url($destinationPath.$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
            }

            $filenames=substr($filenames, 0, strlen($filenames)-1);

            return $filenames;
        }
        return 0;
    }

        public function removeupload(Request $request)
    {
        $input = $request->all();
        return DB::table('files')
        ->where('Id', '=', $input["Id"])
        ->delete();

    }

		public function fionupload(Request $request)
    {
        $me = (new CommonController)->get_current_user();
        $input = $request->all();
        $time = Carbon::now();


        $filenames="";
        $attachmentUrl = null;

        $destinationPath="private/upload/fionupload/";

        $type="fionrecord";
        $uploadcount=count($request->file('uploadfile'));
        if ($request->hasFile('uploadfile')) {

            for ($i=0; $i <$uploadcount ; $i++) {
                # code...
                $file = $request->file('uploadfile')[$i];
                // $destinationPath=public_path()."/private/upload/Delivery";
                $extension = $file->getClientOriginalExtension();
                $originalName=$file->getClientOriginalName();
                $fileSize=$file->getSize();
                $fileName=time()."_".$i.".".$extension;
                $upload_success = $file->move($destinationPath,$fileName);
                $insert=DB::table('files')->insertGetId(
                    ['Type' => $type,
                     'TargetId' => $input['fionrecordId'],
                     'File_Name' => $originalName.$time,
                     'File_Size' => $fileSize,
                     'Web_Path' => $destinationPath.$fileName
                    ]
                );
                $attachmentUrl = url($destinationPath.$fileName);
                $filenames.= $insert."|".$attachmentUrl."|" .$originalName.",";
            }

            $filenames=substr($filenames, 0, strlen($filenames)-1);

            return $filenames;
        }
        return 0;
    }

        public function fionremoveupload(Request $request)
    {
        $input = $request->all();
        return DB::table('files')
        ->where('Id', '=', $input["Id"])
        ->delete();

    }

		public function ewalletfinanceupdate($start=null, $end=null,$company=null)
		{
			$me = (new CommonController)->get_current_user();

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

			$cond="1";

			if($company && $company!="false")
			{
				$cond.=" AND users.Company='".$company."'";
			}

			$ewalletrecord = DB::table('ewallet')
			->select(
				'ewallet.Id',
				'users.StaffId',
				'users.Name',
				'ewallet.Date',
				'projects.Project_Name',
				'tracker.Project_Code',
				'ewallet.DocNo',
				'ewallet.DealWith',
				'ewallet.Expenses_Type',
				'ewallet.Amount',
				'ewallet.Remarks',
				'creator.Name as Created_By')
			->leftJoin('projects','ewallet.ProjectId','=','projects.Id')
			->leftJoin('tracker','ewallet.TrackerId','=','tracker.Id')
			->leftJoin('users','ewallet.UserId','=','users.Id')
			->leftJoin('users as creator','ewallet.created_by','=','creator.Id')
			->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
	    ->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
			->whereRaw($cond)
			->orderByRaw('str_to_date(ewallet.Date,"%d-%M-%Y") ASC')
			->get();

			$companies= DB::table('options')
			->whereIn('Table', ["users"])
			->where('Field','=','Company')
			->orderBy('Table','asc')
			->orderBy('Option','asc')
			->get();

			$options= DB::table('options')
			->whereIn('Table', ["ewallet"])
			->orderBy('Table','asc')
			->orderBy('Option','asc')
			->get();

			$files = DB::table('files')->select('Id', 'TargetId', 'Web_Path')->where('Type', 'eWallet')->get();
      $filesByGroup = collect($files)->groupBy('TargetId')->toArray();

			return view('ewalletfinanceupdate', ['me' => $me,'ewalletrecord' => $ewalletrecord,'options'=>$options,'filesByGroup' => $filesByGroup,'start' => $start, 'end' => $end,'companies'=>$companies,'company'=>$company]);

		}
	function verify(Request $request){
		$me=(new CommonController)->get_current_user();
		$update=DB::Table('ewallet')->where('Id',$request->id)->update([
			'verified_by'=>$me->UserId,
			'verified_at'=>DB::Raw('now()')
		]);
		return $update;
	}

	function verifytick(Request $request){
		$me=(new CommonController)->get_current_user();

		$input = $request->all();

		$recordIds = explode(",", $input["Ids"]);

		foreach ($recordIds as $id) {
			// code...
			$update=DB::Table('ewallet')->where('Id',$id)->update([
				'verified_by'=>$me->UserId,
				'verified_at'=>DB::Raw('now()')
			]);
		}

		return $update;
	}

	public function ewalletsummary($start=null, $end=null,$projectid=null,$trackerid=null)
	{
		$me = (new CommonController)->get_current_user();

		$projectids = explode("|",$me->ProjectIds);

		$projects= DB::table('projects')
		->whereIn('Id', $projectids)
		->get();

		if($projectid==null)
		{
					$projectid=31;
		}

		$site= DB::table('tracker')
		->select('tracker.Id','tracker.Project_Code','tracker.Site Name','tracker.Unique ID')
		->where('ProjectId','=', $projectid)
		->get();

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

		$cond="1 ";

		if($projectid && $projectid!="0")
		{
			$cond.=" and tracker.ProjectId=".$projectid;
		}


		if($trackerid && $trackerid!=0)
		{
			$cond.=" and tracker.Id=".$trackerid;
		}

		$summary = DB::table('ewallet')
		->select(
			'ewallet.Expenses_Type',
			DB::raw('SUM(ewallet.Amount) as Total'))
		->leftJoin('projects','ewallet.ProjectId','=','projects.Id')
		->leftJoin('tracker','ewallet.TrackerId','=','tracker.Id')
		->leftJoin('users','ewallet.UserId','=','users.Id')
		->leftJoin('users as creator','ewallet.created_by','=','creator.Id')
		->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->whereRaw($cond)
		->groupBy('ewallet.Expenses_Type')
		->orderByRaw('ewallet.Expenses_Type ASC')
		->get();

		$total = DB::table('ewallet')
		->select(
			'projects.Project_Name',
			'tracker.Unique ID',
			'ewallet.Expenses_Type',
				DB::raw('SUM(ewallet.Amount) as Total'))
		->leftJoin('projects','ewallet.ProjectId','=','projects.Id')
		->leftJoin('tracker','ewallet.TrackerId','=','tracker.Id')
		->leftJoin('users','ewallet.UserId','=','users.Id')
		->leftJoin('users as creator','ewallet.created_by','=','creator.Id')
		->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),">=",DB::raw('str_to_date("'.$start.'","%d-%M-%Y")'))
		->where(DB::raw('str_to_date(ewallet.Date,"%d-%M-%Y")'),"<=",DB::raw('str_to_date("'.$end.'","%d-%M-%Y")'))
		->whereRaw($cond)
		->groupBy('projects.Project_Name')
		->groupBy('tracker.Unique ID')
		->groupBy('ewallet.Expenses_Type')
		->orderByRaw('projects.Project_Name ASC,tracker.`Unique ID` asc,ewallet.Expenses_Type asc')
		->get();

		if ($summary==null){
			$data = "";
			$title = "";
		}
		else {
			$data = "";
			$title = "";
			foreach($summary as $key => $quote){
				$ret[]=$quote->Total;
				$data .= $quote->Total.",";
			}
		}
		foreach($summary as $key => $quote){
			$title .= $quote->Expenses_Type.",";
		}
		$data=substr($data,0,strlen($data)-1);
		$title=substr($title,0,strlen($title)-1);


		return view("ewalletsummary", ['me' => $me, 'start' => $start,'end' =>$end, 'summary' => $summary,'total' => $total,'data' => $data, 'title' => $title,'projects'=>$projects,'projectid'=>$projectid,'trackerid'=>$trackerid,'site'=>$site]);
	}

	public function staffdashboard($start = null, $end = null, $userid = null, $year = null, $kpiresult = null, $cmeresult = null){

		$me = (new CommonController)->get_current_user();
		$today = Carbon::now()->format('d-M-Y');

		if($start == null)
		{
			$start = date('d-M-Y',strtotime('today'));
			$firstday = date('d-M-Y',strtotime('first day of'.$today));
		}

		if($end == null)
		{
			$end = date('d-M-Y',strtotime('today'));
		}

		if($year == null)
		{
			$year = Date('Y');
		}

		$startmonth = date('d-M-Y',strtotime($start));
		$firstday = date('d-M-Y',strtotime('first day of'.$startmonth));
		$year = date('Y',strtotime($firstday));

		if(!$userid)
		{
			$userid = "All";
		}
		if($userid == "All")
		{
			$leave = null;

            $notimein = null;

            $staffdeductions = null;

		}
		else
		{
			$leave = DB::table('leaves')
	            ->leftJoin(DB::raw('(SELECT Max(Id) as maxid,LeaveId from leavestatuses  GROUP BY LeaveId) as max'),'max.LeaveId', '=', 'leaves.Id')
	            ->leftJoin('leavestatuses','leavestatuses.Id','=','max.maxid')
	            ->select(DB::raw('(SELECT COUNT(leavestatuses.Id)) as count'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "1 Hour Time Off") as 1_Hour_Time_Off'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "2 Hours Time Off") as 2_Hours_Time_Off'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "3 Hours Time Off") as 3_Hours_Time_Off'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Annual Leave") as Annual_Leave'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Compassionate Leave") as Compassionate_Leave'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Emergency Leave") as Emergency_Leave'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Hospitalization Leave") as Hospitalization_Leave'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Marriage Leave") as Marriage_Leave'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Maternity Leave") as Maternity_Leave'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Medical Leave") as Medical_Leave'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Paternity Leave") as Paternity_Leave'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Replacement Leave") as Replacement_Leave'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Sick Leave") as Sick_Leave'),DB::raw('(SELECT COUNT(leavestatuses.Id) WHERE leaves.Leave_Type = "Unpaid Leave") as Unpaid_Leave'))
				->where('leavestatuses.Leave_Status', 'like','%Approved%')
	            ->whereRaw('leaves.UserId = "'.$userid.'" AND (str_to_date(leaves.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
	            ->first();

            $notimein = DB::table('timesheets')
            ->select(DB::raw('(SELECT COUNT(timesheets.UserId)) as count'))
            ->where('timesheets.Check_In_Type','like','%On Duty%')
            ->where('timesheets.Time_In','=','')
            ->whereRaw('timesheets.UserId = "'.$userid.'" AND (str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $staffdeductions = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->whereRaw('staffdeductions.UserId = "'.$userid.'" AND (str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            //*********************************************Calculation of KPI *********************************************
            $attend = DB::table('timesheets')
            ->select(DB::raw('(SELECT DISTINCT(timesheets.Date))'),'timesheets.UserId')
            ->where('timesheets.Time_In','!=','')
            ->whereRaw('timesheets.UserId = "'.$userid.'" AND (str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->groupBy('Date')
            ->get();

            $totaldays = DB::table('timesheets')
            ->select('timesheets.Date','timesheets.UserId')
            ->whereRaw('timesheets.UserId = "'.$userid.'" AND (str_to_date(timesheets.Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(timesheets.Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->groupBy('timesheets.Date')
            ->get();

            $total = Carbon::parse($start)->diffInDays(Carbon::parse($end));

            $holiday = DB::table('holidayterritorydays')
            ->leftJoin('holidayterritories','holidayterritories.Id','=','holidayterritorydays.HolidayTerritoryId')
            ->select('Id')
            ->whereRaw('(str_to_date(holidayterritorydays.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(holidayterritorydays.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->count();

            $leaves = DB::table('leaves')
            ->leftJoin(DB::raw('(SELECT Max(Id) as maxid,LeaveId from leavestatuses  GROUP BY LeaveId) as max'),'max.LeaveId', '=', 'leaves.Id')
	        ->leftJoin('leavestatuses','leavestatuses.Id','=','max.maxid')
	        ->select('leaves.Id')
	        ->whereRaw('leaves.UserId = "'.$userid.'" AND (str_to_date(leaves.Start_Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(leaves.End_Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
	        ->count();

	        $workingdays = DB::table('users')
	        ->select('Working_Days')
	        ->whereRaw('Id = "'.$userid.'"')
	        ->first();

       		foreach($totaldays as $k => $v)
       		{
    			$day = date("N", strtotime($v->Date));
       			if($workingdays->Working_Days == 5)
       			{
       				if ( $day >= 6 )
	        		{
	        			$holiday ++;
	        		}
       			}
       			else
       			{
       				if ( $day == 7 )
	        		{
	        			$holiday ++;
	        		}
       			}
       		}

       		$total = $total + 1;

       		$totalday = $total - $holiday - $leaves;

            $kpiresult = (count($attend) / $totalday) * 100;

            $kpiresult = number_format($kpiresult, 2, '.', ',');

            //*********************************************Calculation of CME *********************************************
       		$cmetotal = DB::table('tasks')
       		->leftJoin(DB::raw('(SELECT Max(Id) as maxid, TaskId From taskstatuses GROUP BY TaskId) as max'),'max.TaskId','=','tasks.Id')
			->leftJoin('taskstatuses','taskstatuses.Id','=',DB::raw('max.maxid'))
			->select('tasks.Id')
       		->where('tasks.UserId',$userid)
	       	->where('taskstatuses.Status','=','Completed')
	       	->whereRaw('(str_to_date(tasks.created_at,"%Y-%m-%d")>=str_to_date("'.$firstday.'","%d-%M-%Y") AND str_to_date(tasks.created_at,"%Y-%m-%d")<=str_to_date("'.$start.'","%d-%M-%Y"))')
	        ->count();

       		$cmeoverdue = DB::table('tasks')
       		->leftJoin(DB::raw('(SELECT Max(Id) as maxid, TaskId From taskstatuses GROUP BY TaskId) as max'),'max.TaskId','=','tasks.Id')
			->leftJoin('taskstatuses','taskstatuses.Id','=',DB::raw('max.maxid'))
			->select('tasks.Id')
       		->where('tasks.UserId',$userid)
       		->where('taskstatuses.Status','=','Completed')
       		->whereRaw('tasks.complete_date !="" AND (str_to_date(concat(complete_date," ",complete_time),"%d-%M-%Y %H:%i:%s") > str_to_date(concat(target_date," ",target_time),"%d-%M-%Y %H:%i:%s"))')
       		->whereRaw('(str_to_date(tasks.created_at,"%Y-%m-%d")>=str_to_date("'.$firstday.'","%d-%M-%Y") AND str_to_date(tasks.created_at,"%Y-%m-%d")<=str_to_date("'.$start.'","%d-%M-%Y"))')
       		->count();

       		$cmeontime = $cmetotal - $cmeoverdue;

       		if($cmetotal == 0)
       		{
       			$cmeresult = null;
       		}
       		else
       		{
       			$cmeresult = ($cmeontime / $cmetotal) * 100;
       			$cmeresult = number_format($cmeresult, 2, '.', ',');
       		}
        }

		$user = DB::table('users')
		->select('Id','Name')
		->get();

		return view('staffdashboard',['me'=> $me , 'leave' => $leave , 'notimein'=>$notimein , 'staffdeductions'=>$staffdeductions ,'user'=>$user , 'userid'=> $userid, 'start'=>$start, 'end'=>$end, 'today'=>$today, 'firstday'=>$firstday , 'year'=>$year ,'kpiresult'=>$kpiresult, 'cmeresult'=>$cmeresult]);
	}

	public function staffdeductionsdashboard($start = null, $end = null, $userid = null)
	{
		$me = (new CommonController)->get_current_user();
		$today = Carbon::now()->format('d-M-Y');

		if($start == null)
		{
			$start = date('d-M-Y',strtotime('today'));

		}
		if($end == null)
		{
			$end = date('d-M-Y',strtotime('today'));
		}

		if(!$userid)
		{
			$userid = "All";
		}

		if($userid == "All")
		{
			$summons = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%SUMMONS%')
            ->whereRaw('(str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $late = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%Late%')
            ->whereRaw('(str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $loan = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%LOAN%')
            ->whereRaw('(str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $accident = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%ACCIDENT%')
            ->whereRaw('(str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $presaving = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%PRE-SAVING SCHEME%')
            ->whereRaw('(str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $notinradius = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%Not In Radius%')
            ->whereRaw('(str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $advancesalary = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%ADVANCE SALARY DEDUCTION%')
            ->whereRaw('(str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $drivinglicense = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%DRIVING LICENSE DEDUCTION%')
            ->whereRaw('(str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $lossofequipment = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%LOSS OF EQUIPMENT%')
            ->whereRaw('(str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $paybacktoeric = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%PAY BACK TO ERIC%')
            ->whereRaw('(str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $pettycashfion = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%PETTY CASH FION%')
            ->whereRaw('(str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $shellcard = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%SHELL CARD%')
            ->whereRaw('(str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $touchngo = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%TOUCH N GO%')
            ->whereRaw('(str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();
		}
		else
		{
			$summons = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%SUMMONS%')
            ->whereRaw('staffdeductions.UserId = "'.$userid.'" AND (str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $late = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%Late%')
            ->whereRaw('staffdeductions.UserId = "'.$userid.'" AND (str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $loan = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%LOAN%')
            ->whereRaw('staffdeductions.UserId = "'.$userid.'" AND (str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $accident = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%ACCIDENT%')
            ->whereRaw('staffdeductions.UserId = "'.$userid.'" AND (str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $presaving = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%PRE-SAVING SCHEME%')
            ->whereRaw('staffdeductions.UserId = "'.$userid.'" AND (str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $notinradius = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%Not In Radius%')
            ->whereRaw('staffdeductions.UserId = "'.$userid.'" AND (str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $advancesalary = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%ADVANCE SALARY DEDUCTION%')
            ->whereRaw('staffdeductions.UserId = "'.$userid.'" AND (str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $drivinglicense = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%DRIVING LICENSE DEDUCTION%')
            ->whereRaw('staffdeductions.UserId = "'.$userid.'" AND (str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $lossofequipment = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%LOSS OF EQUIPMENT%')
            ->whereRaw('staffdeductions.UserId = "'.$userid.'" AND (str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $paybacktoeric = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%PAY BACK TO ERIC%')
            ->whereRaw('staffdeductions.UserId = "'.$userid.'" AND (str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $pettycashfion = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%PETTY CASH FION%')
            ->whereRaw('staffdeductions.UserId = "'.$userid.'" AND (str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $shellcard = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%SHELL CARD%')
            ->whereRaw('(str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();

            $touchngo = DB::table('staffdeductions')
            ->select(DB::raw('(SELECT SUM(staffdeductions.FinalAmount)) as sum'))
            ->where('staffdeductions.Type','like','%TOUCH N GO%')
            ->whereRaw('staffdeductions.UserId = "'.$userid.'" AND (str_to_date(Date,"%d-%M-%Y")>=str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date(Date,"%d-%M-%Y")<=str_to_date("'.$end.'","%d-%M-%Y"))')
            ->first();
		}

		$user = DB::table('users')
		->select('Id','Name')
		->get();

		return view('staffdeductionsdashboard',['me'=> $me, 'start'=>$start, 'end'=>$end, 'today'=>$today, 'userid'=>$userid, 'user'=>$user, 'summons'=>$summons, 'late'=>$late, 'loan'=>$loan, 'accident'=>$accident, 'presaving'=>$presaving, 'notinradius'=>$notinradius, 'advancesalary'=>$advancesalary, 'drivinglicense'=>$drivinglicense , 'lossofequipment'=>$lossofequipment, 'paybacktoeric'=>$paybacktoeric, 'pettycashfion'=>$pettycashfion, 'shellcard'=>$shellcard, 'touchngo'=>$touchngo]);
	}

	public function staffdeductionsdetail($start = null,$end = null, $userid = null, $type = null)
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

		if(!$userid)
		{
			$userid = "All";
		}

		$year=date('Y');

		$resignedlastmonthsameday=date('d-M-Y');
		$datestring=$resignedlastmonthsameday. 'first day of last month';
		$dt=date_create($datestring);
		$resignedlastmonthsameday=date('d')."-".$dt->format('M-Y'); //2011-02

		if($userid == "All")
		{
			$users = DB::table('users')
			->leftJoin('staffdeductions','staffdeductions.UserId','=','users.Id')
			->select(
				'users.Id','users.StaffId as Staff_ID','users.Name','users.Department','users.Position','users.Joining_Date','users.Nationality',
				DB::raw('(
					SELECT SUM(FinalAmount) FROM staffdeductions a
					WHERE a.UserId=users.Id
					AND str_to_date(Date,"%d-%M-%Y") >= str_to_date("'.$start.'","%d-%M-%Y")
					AND str_to_date(Date,"%d-%M-%Y") <= str_to_date("'.$end.'","%d-%M-%Y"))
				AS Total_Deductions')
			)
			->whereRaw('(users.Resignation_Date="" or str_to_date(users.Resignation_Date,"%d-%M-%Y")>=str_to_date("'.$resignedlastmonthsameday.'","%d-%M-%Y")) AND (str_to_date(staffdeductions.Date,"%d-%M-%Y") BETWEEN str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y")) AND (staffdeductions.Type = "'.$type.'")')
			->orderBy('users.Name')
			->get();
		}
		else
		{
			$users = DB::table('users')
			->leftJoin('staffdeductions','staffdeductions.UserId','=','users.Id')
			->select(
				'users.Id','users.StaffId as Staff_ID','users.Name','users.Department','users.Position','users.Joining_Date','users.Nationality',
				DB::raw('(
					SELECT SUM(FinalAmount) FROM staffdeductions a
					WHERE a.UserId=users.Id
					AND str_to_date(Date,"%d-%M-%Y") >= str_to_date("'.$start.'","%d-%M-%Y")
					AND str_to_date(Date,"%d-%M-%Y") <= str_to_date("'.$end.'","%d-%M-%Y"))
				AS Total_Deductions')
			)
			->whereRaw('(users.Resignation_Date="" or str_to_date(users.Resignation_Date,"%d-%M-%Y")>=str_to_date("'.$resignedlastmonthsameday.'","%d-%M-%Y")) AND (str_to_date(staffdeductions.Date,"%d-%M-%Y") BETWEEN str_to_date("'.$start.'","%d-%M-%Y") AND str_to_date("'.$end.'","%d-%M-%Y")) AND (staffdeductions.Type = "'.$type.'") AND (staffdeductions.UserId = "'.$userid.'")')
			->orderBy('users.Name')
			->get();
		}


    	return view('staffdeductionsdetail', ['me' => $me,'users' => $users,'year'=>$year, 'start' => $start, 'end' => $end, 'type'=>$type]);

	}

	public function kpiresult($start = null, $end = null, $userid = null, $year = null, $kpiresult = null)
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

		if(!$userid)
		{
			$userid = "All";
		}

		if ($year==null)
		{
			$year=Date("Y");
		}

			# code...
			$attend = DB::table("timesheets")
			->select(DB::raw('RIGHT(timesheets.Date,8) as Work_Month'),DB::raw('COUNT(DISTINCT timesheets.Date) AS Day_Attend'))
			->where('timesheets.Time_In','!=','')
			->where(DB::raw('RIGHT(timesheets.Date,4)'), '=',$year)
			->whereRaw('timesheets.UserId = "'.$userid.'"')
			->groupBy(DB::raw('RIGHT(timesheets.Date,8)'))
			->orderByRaw(DB::raw('LEFT(str_to_date(timesheets.Date,"%d-%M-%Y"),7)'))
			->get();

		$attends = "";
		$amount="";
		$months=array();

		foreach($attend as $key => $quote){
			$g[]=$quote->Work_Month;
			$attends = implode(',', $g);

			$h[]=$quote->Day_Attend;
			$amount = implode(',', $h);
		}

		$user = DB::table('users')
		->select('Id','Name')
		->get();

		return view('kpiresult',['me'=>$me, 'start'=>$start , 'end'=>$end , 'userid'=>$userid , 'user'=>$user ,'year'=>$year, 'months'=>$months ,'attend'=>$attend,'attends'=>$attends,'amount'=>$amount]);
	}

	public function cmeresult($start = null, $end = null, $userid = null, $year = null, $cmeresult = null)
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

		if(!$userid)
		{
			$userid = "All";
		}

		if ($year==null)
		{
			$year=Date("Y");
		}

			# code...
			$task = DB::table("tasks")
			->leftJoin(DB::raw('(SELECT Max(Id) as maxid, TaskId From taskstatuses GROUP BY TaskId) as max'),'max.TaskId','=','tasks.Id')
			->leftJoin('taskstatuses','taskstatuses.Id','=',DB::raw('max.maxid'))
			->select(DB::raw('LEFT(tasks.created_at,7) as Task_Month'),DB::raw('COUNT(tasks.created_at) AS Total_Tasks'))
			->where('taskstatuses.Status','=','Completed')
			->where(DB::raw('LEFT(tasks.created_at,4)'), '=',$year)
			->where('tasks.UserId',$userid)
			->groupBy(DB::raw('LEFT(tasks.created_at,8)'))
			->orderByRaw(DB::raw('LEFT(str_to_date(tasks.created_at,"%Y-%m-%d"),7)'))
			->get();

		$tasks = "";
		$amount="";
		$months=array();

		foreach($task as $key => $quote){
			$g[]=$quote->Task_Month;
			$tasks = implode(',', $g);

			$h[]=$quote->Total_Tasks;
			$amount = implode(',', $h);
		}

		$user = DB::table('users')
		->select('Id','Name')
		->get();

		return view('cmeresult',['me'=>$me, 'start'=>$start , 'end'=>$end , 'userid'=>$userid , 'user'=>$user ,'year'=>$year, 'months'=>$months ,'task'=>$task,'tasks'=>$tasks,'amount'=>$amount]);
	}

}
