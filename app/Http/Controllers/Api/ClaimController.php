<?php namespace App\Http\Controllers\Api;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;

use Input;

class ClaimController extends Controller {

	public function newclaim(Request $request)
	{

		$me = JWTAuth::parseToken()->authenticate();
		$input = $request->all();

		$insertid=DB::table('claims')->insertGetId([
			'ClaimSheetId' => $input["ClaimSheetId"],
			'Date' => $input["Date"],
			'ProjectId' => $input["ProjectId"],
			'Site_Name' => $input["Site_Name"],
			'State' => $input["State"],
			'Work_Description' => $input["Work_Description"],
			'Next_Person' => $input["Next_Person"],
			'Car_No' => $input["Car_No"],
			'Mileage' => $input["Mileage"],
			'Expenses_Type' => $input["Expenses_Type"],
			'Total_Expenses' => $input["Total_Expenses"],
			'Petrol_SmartPay' => $input["Petrol_SmartPay"],
			'Advance' => $input["Advance"],
			'Total_Amount' => $input["Total_Amount"],
			'GST_Amount' => $input["GST_Amount"],
			'Total_Without_GST' => $input["Total_Amount"]-$input["GST_Amount"],
			'Receipt_No' => $input["Receipt_No"],
			'Company_Name' => $input["Company_Name"],
			'GST_No' => $input["GST_No"],
			'Remarks' => $input["Remarks"]
		]);

		return $insertid;
	}


	public function myrequest(Request $request)
	{

		$auth = JWTAuth::parseToken()->authenticate();

		$me = (new AuthController)->get_current_user($auth->Id);

		$input = $request->all();

		$token = $input["token"];

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

		return view('myrequest2',['advance'=>$advance,'me'=>$me,'users'=>$users, 'projects'=>$projects,'options'=>$options,'token'=>$token]);
	}


	public function applyadvance(Request $request)
	{
		$me = JWTAuth::parseToken()->authenticate();

		$emaillist=array();
		array_push($emaillist,$me->Id);

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
		->where('approvalsettings.UserId', '=', $me->Id)
		->orderBy('approvalsettings.Country','asc')
		->orderBy('projects.Project_Name','asc')
		->orderByRaw("FIELD(approvalsettings.Level , 'Final Approval', '5th Approval', '4th Approval','3rd Approval','2nd Approval','1st Approval') ASC")
		->get();

		foreach ($approver as $approverid) {

			$validator = Validator::make($input, $rules,$messages);

				if ($validator->passes())
				{

					$id=DB::table('advances')->insertGetId([
						'UserId' => $me->Id,
						'ProjectId' => $input["ProjectId"],
						'Purpose' => $input["Purpose"],
						'Start_Date' => $input["Start_Date"],
						'End_Date' => $input["End_Date"],
						'Destination' => $input["Destination"],
						'Mode_Of_Transport' => $input["Mode_Of_Transport"],
						'Car_No' => $input["Car_No"],
						'Total_Requested' => $input["Total_Requested"],
					]);

					DB::table('advancestatuses')->insertGetId([
						'UserId' => $approverid->Id,
						'AdvanceId' => $id,
						'Status' => "Pending Approval"
					]);

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

					if (count($emaillist)>1){

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

						// Mail::send('emails.advancerequest', ['me' => $me,'advance' => $advance], function($message) use ($emails,$me,$NotificationSubject)
						// {
						// 	$emails = array_filter($emails);
						// 	array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
						// 	$message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');

						// });

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

				// Mail::send('emails.advance', ['me' => $me,'advance' => $advance], function($message) use ($emails,$me,$NotificationSubject)
				// {
				// 	$emails = array_filter($emails);
				// 	array_push($emails,env('MAIL_DEFAULT_RECIPIENT'));
				// 	$message->to($emails)->subject($NotificationSubject.' ['.$me->Name.']');
				// });
			}
		}
	}

	public function newclaimsheet(Request $request)
	{

		$me = JWTAuth::parseToken()->authenticate();
		$input = $request->all();

		$insertid=DB::table('claimsheets')->insertGetId(
			['UserId' => $input["UserId"],
			 'Claim_Sheet_Name' => $input["Claim_Sheet_Name"],
			 'Status' => "Pending Submission",
			 'Remarks' => $input["Remarks"]
		 	]
		);

		return $insertid;
	}

  public function getclaimsheet()
	{

		$me = JWTAuth::parseToken()->authenticate();

		$claims = DB::table('claimsheets')
		->where('UserId', '=', $me->Id)
		->orderBy('claimsheets.Id','desc')
		->get();

		return json_encode($claims);
	}

	public function getclaims(Request $request)
	{

		$me = JWTAuth::parseToken()->authenticate();

		$input = $request->all();

		$claims = DB::table('claims')
		->orderBy('Date','Asc')
		->get();

		return json_encode($claims);
	}

	public function getoptions()
	{

		$me = JWTAuth::parseToken()->authenticate();

		$options= DB::table('options')
		->whereIn('Table', ["users","claims"])
		->orderBy('Table','asc')
		->orderBy('Option','asc')
		->get();

		return json_encode($options);
	}
	
}
